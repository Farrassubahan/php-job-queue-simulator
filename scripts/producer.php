<?php

require_once __DIR__ . '/../app/QueueManager.php';

function generateRandomJob(): array
{
    $isHeavy = rand(1, 100) <= 40;

    if ($isHeavy) {
        return [
            'type' => 'heavy_task',
            'expected_weight' => 'heavy',
            'payload' => [
                'rows'  => rand(3000, 10000),
                'sleep' => rand(3, 6)
            ]
        ];
    }

    return [
        'type' => 'light_task',
        'expected_weight' => 'light',
        'payload' => [
            'sleep' => rand(0, 1)
        ]
    ];
}

$totalJobs = 20;

for ($i = 1; $i <= $totalJobs; $i++) {
    $job = generateRandomJob();

    QueueManager::push(
        $job['type'],
        $job['payload'],
        $job['expected_weight'],
        3
    );

    echo "[PUSH] {$job['type']} ({$job['expected_weight']})\n";
}

echo "Selesai push {$totalJobs} job\n";
