<?php

class Job
{
    public ?int $id = null;

    public string $type;
    public array $payload;

    public string $expectedWeight;

    public string $status = 'pending';

    public int $attempts = 0;
    public int $maxAttempts = 3;

    public int $priority = 0;

    public ?string $runAt = null;

    public ?string $startedAt = null;
    public ?string $finishedAt = null;

    public ?int $executionMs = null;

    public function __construct(
        string $type,
        array $payload,
        string $expectedWeight,
        int $maxAttempts = 3
    ) {
        $this->type = $type;
        $this->payload = $payload;
        $this->expectedWeight = $expectedWeight;
        $this->maxAttempts = $maxAttempts;
    }

    public static function fromDatabase(array $data): self
    {
        $job = new self(
            $data['type'],
            json_decode($data['payload'], true),
            $data['expected_weight'],
            $data['max_attempts']
        );

        $job->id = (int) $data['id'];
        $job->status = $data['status'];
        $job->attempts = (int) $data['attempts'];
        $job->priority = (int) ($data['priority'] ?? 0);
        $job->runAt = $data['run_at'];

        $job->startedAt = $data['started_at'];
        $job->finishedAt = $data['finished_at'];
        $job->executionMs = $data['execution_ms'];

        return $job;
    }

    public function canRetry(): bool
    {
        return $this->attempts < $this->maxAttempts;
    }

    public function isHeavy(): bool
    {
        return $this->expectedWeight === 'heavy';
    }

    public function isLight(): bool
    {
        return $this->expectedWeight === 'light';
    }
}
