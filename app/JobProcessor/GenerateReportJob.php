<?php

class GenerateReportJob
{
    public static function handle(array $payload): void
    {
        echo "Generating report for user {$payload['user_id']}...\n";

        // simulasi proses berat
        sleep(5);

        // simulasi error sesekali
        if (rand(1, 4) === 1) {
            throw new Exception("Report generation failed");
        }

        echo "Report successfully generated for user {$payload['user_id']}\n";
    }
}
