<?php

class SendEmailJob
{
    public static function handle(array $payload): void
    {
        $sleep = $payload['sleep'] ?? 1;

        sleep($sleep);

        if (rand(1, 3) === 1) {
            throw new Exception("Simulated email failure");
        }

        echo "Email task completed\n";
    }
}
