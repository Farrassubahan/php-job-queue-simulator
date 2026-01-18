<?php
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Job.php';
class QueueManager
{
    public static function push(
        string $type,
        array $payload,
        string $expectedWeight,
        int $maxAttempts = 3
    ): void {
        $db = Database::connect();

        $stmt = $db->prepare("
            INSERT INTO jobs (
                type,
                payload,
                expected_weight,
                status,
                attempts,
                max_attempts,
                created_at
            )
            VALUES (?, ?, ?, 'pending', 0, ?, NOW())
        ");

        $stmt->execute([
            $type,
            json_encode($payload),
            $expectedWeight,
            $maxAttempts
        ]);
    }

    public static function pop(): ?Job
    {
        $db = Database::connect();
        $db->beginTransaction();

        $stmt = $db->query("
            SELECT * FROM jobs
            WHERE status = 'pending'
            ORDER BY created_at ASC
            LIMIT 1
            FOR UPDATE
        ");

        $data = $stmt->fetch();

        if (!$data) {
            $db->commit();
            return null;
        }

        $update = $db->prepare("
            UPDATE jobs
            SET status = 'processing',
                started_at = NOW()
            WHERE id = ?
        ");
        $update->execute([$data['id']]);

        $db->commit();

        return new Job($data);
    }

    public static function markSuccess(Job $job, int $executionMs): void
    {
        $db = Database::connect();

        $stmt = $db->prepare("
            UPDATE jobs
            SET status = 'success',
                finished_at = NOW(),
                execution_ms = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $executionMs,
            $job->id
        ]);
    }

    public static function handleFailure(Job $job, int $executionMs): void
    {
        $db = Database::connect();
        $attempts = $job->attempts + 1;

        if ($attempts >= $job->maxAttempts) {

            $stmt = $db->prepare("
                INSERT INTO dead_jobs (job_id, type, payload, attempts)
                VALUES (?, ?, ?, ?)
            ");

            $stmt->execute([
                $job->id,
                $job->type,
                json_encode($job->payload),
                $attempts
            ]);

            $update = $db->prepare("
                UPDATE jobs
                SET status = 'dead',
                    attempts = ?,
                    finished_at = NOW(),
                    execution_ms = ?
                WHERE id = ?
            ");
            $update->execute([$attempts, $executionMs, $job->id]);
        } else {

            $update = $db->prepare("
                UPDATE jobs
                SET status = 'pending',
                    attempts = ?
                WHERE id = ?
            ");
            $update->execute([$attempts, $job->id]);
        }
    }
}
