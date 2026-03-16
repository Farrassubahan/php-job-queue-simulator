<?php

require_once __DIR__ . '/../Job.php';

class GenerateReportJob
{
    public function handle(Job $job): void
    {
        $payload = $job->payload;

        $rows  = $payload['rows'] ?? 0;
        $sleep = $payload['sleep'] ?? 3;

        echo "Generating report ({$rows} rows)...\n";

        sleep($sleep);

        if (rand(1, 4) === 1) {
            throw new Exception("Report generation failed");
        }

        echo "Report successfully generated ({$rows} rows)\n";
    }
}
