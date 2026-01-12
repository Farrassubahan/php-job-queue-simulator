<?php

require_once __DIR__ . '/../app/QueueManager.php';
$payload = [
    'email' => 'test@example.com',
    'message' => 'Hallo Ini Pesan default dari queue'
];

QueueManager::push('send_email', $payload, 3);

echo "Job berhasil dimasukkan ke queue\n";
