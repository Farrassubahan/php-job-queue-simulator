<?php

class Job
{
    public int $id;
    public string $type;
    public array $payload;
    public string $expectedWeight;
    public string $status;
    public int $attempts;
    public int $maxAttempts;
    public ?string $startedAt;
    public ?string $finishedAt;
    public ?int $executionMs;

    public function __construct(array $data)
    {
        $this->id             = (int) $data['id'];
        $this->type           = $data['type'];
        $this->payload        = json_decode($data['payload'], true);
        $this->expectedWeight = $data['expected_weight'];
        $this->status         = $data['status'];
        $this->attempts       = (int) $data['attempts'];
        $this->maxAttempts    = (int) $data['max_attempts'];
        $this->startedAt      = $data['started_at'] ?? null;
        $this->finishedAt     = $data['finished_at'] ?? null;
        $this->executionMs    = isset($data['execution_ms'])
            ? (int) $data['execution_ms']
            : null;
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
