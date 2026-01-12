<?php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Job.php';

class QueueManager
{
    public static function push(string $type, array $payload, int $maxAttempts = 3): void
    {
        $db = Database::connect();

        $stmt = $db->prepare("
            INSERT INTO jobs (type, payload, status, attempts, max_attempts)
            VALUES (?, ?, 'pending', 0, ?)
        ");

        $stmt->execute([
            $type,
            json_encode($payload),
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
            SET status = 'processing'
            WHERE id = ?
        ");
        $update->execute([$data['id']]);

        $db->commit();

        return new Job($data);
    }

    public static function markSuccess(Job $job): void
    {
        $db = Database::connect();

        $stmt = $db->prepare("
            UPDATE jobs
            SET status = 'success'
            WHERE id = ?
        ");

        $stmt->execute([$job->id]);
    }

    public static function handleFailure(Job $job): void
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
                SET status = 'dead', attempts = ?
                WHERE id = ?
            ");
            $update->execute([$attempts, $job->id]);
        } else {

            $update = $db->prepare("
                UPDATE jobs
                SET status = 'pending', attempts = ?
                WHERE id = ?
            ");
            $update->execute([$attempts, $job->id]);
        }
    }
}
