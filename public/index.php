<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Queue Simulator</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
            margin-top: 100px;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background: #3490dc;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        a:hover {
            background: #2779bd;
        }
    </style>
</head>

<body>

    <h1>PHP Queue Simulator</h1>

    <p>Mini queue engine untuk simulasi worker, retry, dan job processing.</p>

    <a href="dashboard.php">Buka Queue Dashboard</a>

</body>

</html>