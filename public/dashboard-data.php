<?php

require_once __DIR__ . '/../app/Database.php';

header('Content-Type: application/json');

$db = Database::connect();

function countJobs($db, $status)
{
    $stmt = $db->prepare("SELECT COUNT(*) FROM jobs WHERE status = ?");
    $stmt->execute([$status]);
    return $stmt->fetchColumn();
}

$data = [
    'pending' => countJobs($db, 'pending'),
    'processing' => countJobs($db, 'processing'),
    'success' => countJobs($db, 'success'),
    'dead' => countJobs($db, 'dead'),
];

/* job terakhir yang selesai */
$lastExecution = $db->query("
    SELECT execution_ms
    FROM jobs
    WHERE execution_ms IS NOT NULL
    ORDER BY finished_at DESC
    LIMIT 1
")->fetch(PDO::FETCH_ASSOC);

$data['last_execution'] = $lastExecution['execution_ms'] ?? null;

/* jobs terbaru */
$recentJobs = $db->query("
    SELECT id, type, status, attempts, execution_ms, created_at
    FROM jobs
    ORDER BY created_at DESC
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

$data['jobs'] = $recentJobs;

echo json_encode($data);
