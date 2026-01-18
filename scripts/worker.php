<?php

require_once __DIR__ . '/../app/QueueManager.php';
require_once __DIR__ . '/../app/Job.php';
require_once __DIR__ . '/../app/JobProcessor/SendEmailJob.php';
require_once __DIR__ . '/../app/JobProcessor/GenerateReportJob.php';

echo "Worker started...\n";

while (true) {

    $job = QueueManager::pop();

    if (!$job) {
        sleep(2);
        continue;
    }

    echo "Processing job ID {$job->id} ({$job->expectedWeight}) attempt {$job->attempts}\n";

    $start = microtime(true);

    try {

        switch ($job->type) {
            case 'light_task':
            case 'send_email':
                SendEmailJob::handle($job->payload);
                break;

            case 'heavy_task':
            case 'generate_report':
                GenerateReportJob::handle($job->payload);
                break;

            default:
                throw new Exception("Unknown job type: {$job->type}");
        }

        $executionMs = (int) ((microtime(true) - $start) * 1000);

        echo"---------------------------------------------\n";
        QueueManager::markSuccess($job, $executionMs);
        echo "Job {$job->id} SUCCESS ({$executionMs} ms)\n";
        echo"=============================================\n";
        } catch (Exception $e) {
            
            $executionMs = (int) ((microtime(true) - $start) * 1000);
            
        echo"--------------------------------------------\n";
        QueueManager::handleFailure($job, $executionMs);
        echo "Job {$job->id} FAILED ({$executionMs} ms): {$e->getMessage()}\n";
        echo"=============================================\n";
    }

    sleep(1);
}
