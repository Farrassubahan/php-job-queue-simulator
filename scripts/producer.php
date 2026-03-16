<?php

require_once __DIR__ . '/../app/QueueManager.php';
require_once __DIR__ . '/../app/Job.php';

function generateRandomJob(): Job
{
    $isHeavy = rand(1, 100) <= 40;

    if ($isHeavy) {
        return new Job(
            'heavy_task',
            [
                'rows'  => rand(3000, 10000),
                'sleep' => rand(3, 6)
            ],
            'heavy',
            3
        );
    }

    return new Job(
        'light_task',
        [
            'sleep' => rand(0, 1)
        ],
        'light',
        3
    );
}

$totalJobs = 20;

for ($i = 1; $i <= $totalJobs; $i++) {

    $job = generateRandomJob();

    QueueManager::push($job);

    echo "[PUSH] {$job->type} ({$job->expectedWeight})\n";
}

echo "Selesai push {$totalJobs} job\n";
