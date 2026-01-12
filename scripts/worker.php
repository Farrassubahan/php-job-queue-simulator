<?php

require_once __DIR__ . '/../app/QueueManager.php';
require_once __DIR__ . '/../app/JobProcessor/SendEmailJob.php';
require_once __DIR__ . '/../app/JobProcessor/GenerateReportJob.php';

echo "Worker started...\n";

while (true) {

    /** @var Job|null $job */
    $job = QueueManager::pop();

    if (!$job) {
        sleep(2);
        continue;
    }

    echo "Processing job ID {$job->id} (attempt {$job->attempts})\n";

    try {
        switch ($job->type) {
            case 'send_email':
                SendEmailJob::handle($job->payload);
                break;

            case 'generate_report':
                GenerateReportJob::handle($job->payload);
                break;

            default:
                throw new Exception("Unknown job type: {$job->type}");
        }

        QueueManager::markSuccess($job);
        echo "Job {$job->id} SUCCESS\n";

    } catch (Exception $e) {

        echo "Job {$job->id} FAILED: {$e->getMessage()}\n";
        QueueManager::handleFailure($job);
    }

    sleep(1);
}
