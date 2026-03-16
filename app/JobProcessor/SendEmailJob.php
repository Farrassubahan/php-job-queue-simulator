<?php

require_once __DIR__ . '/../Job.php';

class SendEmailJob
{
    public function handle(Job $job): void
    {
        $payload = $job->payload;

        $sleep = $payload['sleep'] ?? 1;

        sleep($sleep);

        if (rand(1, 3) === 1) {
            throw new Exception("Simulated email failure");
        }

        echo "Email task completed\n";
    }
}
