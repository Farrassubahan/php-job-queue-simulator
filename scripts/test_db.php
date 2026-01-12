<?php

require_once __DIR__ . '/../app/Database.php';

$db = Database::connect();

echo "Database connected successfully\n";
