<?php

class SendEmailJob
{
    public static function handle(array $payload): void
    {
        sleep(2);
        if (rand(1, 3) === 1) {
            throw new Exception("Simulated email failure");
        }

        echo "Email sent to {$payload['email']}\n";
    }
}
