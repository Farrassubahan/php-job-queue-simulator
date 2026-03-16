<?php

require_once __DIR__ . '/../app/QueueManager.php';
require_once __DIR__ . '/../app/ProcessorFactory.php';

echo "Worker started...\n";

while (true) {

    $job = QueueManager::pop();

    if (!$job) {
        echo "[IDLE] No job found...\n";
        sleep(2);
        continue;
    }

    echo "[PROCESSING] Job ID {$job->id} | Type: {$job->type}\n";

    $startTime = microtime(true);

    try {

        $processor = ProcessorFactory::make($job->type);

        $processor->handle($job);

        $executionMs = (int)((microtime(true) - $startTime) * 1000);

        QueueManager::markSuccess($job, $executionMs);

        echo "[SUCCESS] Job {$job->id} finished in {$executionMs} ms\n";
        echo "\n";
    } catch (Exception $e) {

        $executionMs = (int)((microtime(true) - $startTime) * 1000);

        QueueManager::handleFailure($job, $executionMs);

        echo "[FAILED] Job {$job->id} error: " . $e->getMessage() . "\n";
        echo "\n";
    }

    sleep(1);
}
