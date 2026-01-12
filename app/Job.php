<?php

class Job
{
    public int $id;
    public string $type;
    public array $payload;
    public string $status;
    public int $attempts;
    public int $maxAttempts;

    public function __construct(array $data)
    {
        $this->id          = (int) $data['id'];
        $this->type        = $data['type'];
        $this->payload     = json_decode($data['payload'], true);
        $this->status      = $data['status'];
        $this->attempts    = (int) $data['attempts'];
        $this->maxAttempts = (int) $data['max_attempts'];
    }

    public function canRetry(): bool
    {
        return $this->attempts < $this->maxAttempts;
    }
}
