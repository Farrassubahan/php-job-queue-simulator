<?php

require_once __DIR__ . '/../app/Database.php';

$db = Database::connect();

function countJobs($db, $status)
{
    $stmt = $db->prepare("SELECT COUNT(*) FROM jobs WHERE status = ?");
    $stmt->execute([$status]);
    return $stmt->fetchColumn();
}

$pending = countJobs($db, 'pending');
$processing = countJobs($db, 'processing');
$success = countJobs($db, 'success');
$dead = countJobs($db, 'dead');

$recentJobs = $db->query("
    SELECT id, type, status, attempts, created_at
    FROM jobs
    ORDER BY created_at DESC
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Queue Dashboard</title>
    <style>
        body {
            font-family: Arial;
            margin: 40px;
        }

        .stats {
            display: flex;
            gap: 20px;
        }

        .card {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 150px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        th {
            background: #f5f5f5;
        }
    </style>
</head>

<body>

    <h1>Queue Dashboard</h1>

    <div class="stats">

        <div class="card">
            <h3>Pending</h3>
            <p id="pending-count"><?= $pending ?></p>
        </div>

        <div class="card">
            <h3>Processing</h3>
            <p id="processing-count"><?= $processing ?></p>
        </div>

        <div class="card">
            <h3>Success</h3>
            <p id="success-count"><?= $success ?></p>
        </div>

        <div class="card">
            <h3>Dead</h3>
            <p id="dead-count"><?= $dead ?></p>
        </div>

        <div class="card">
            <h3>Last Execution</h3>
            <p id="execution-ms">-</p>
        </div>

    </div>

    <h2>Recent Jobs</h2>

    <table id="jobs-table">

        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Status</th>
            <th>Attempts</th>
            <th>Created</th>
        </tr>

        <?php foreach ($recentJobs as $job): ?>

            <tr>
                <td><?= $job['id'] ?></td>
                <td><?= $job['type'] ?></td>
                <td><?= $job['status'] ?></td>
                <td><?= $job['attempts'] ?></td>
                <td><?= $job['created_at'] ?></td>
            </tr>

        <?php endforeach; ?>

    </table>




    <script>
        async function updateDashboard() {
            const response = await fetch('dashboard-data.php');
            const data = await response.json();

            document.getElementById('pending-count').innerText = data.pending;
            document.getElementById('processing-count').innerText = data.processing;
            document.getElementById('success-count').innerText = data.success;
            document.getElementById('dead-count').innerText = data.dead;

            document.getElementById('execution-ms').innerText =
                data.last_execution ? data.last_execution + " ms" : "-";

            const table = document.getElementById('jobs-table');

            table.innerHTML = '';

            data.jobs.forEach(job => {

                const row = `
        <tr>
            <td>${job.id}</td>
            <td>${job.type}</td>
            <td>${job.status}</td>
            <td>${job.attempts}</td>
            <td>${job.execution_ms ?? '-'}</td>
            <td>${job.created_at}</td>
        </tr>
        `;

                table.innerHTML += row;

            });
        }

        setInterval(updateDashboard, 2000);
    </script>

</body>


</html>