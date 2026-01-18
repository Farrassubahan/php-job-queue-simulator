<?php

class GenerateReportJob
{
    public static function handle(array $payload): void
    {
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
