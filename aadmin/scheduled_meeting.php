<?php
include '../connections.php';
session_start();

// Access for Admin Account only
if (!isset($_SESSION["user_id"]) || $_SESSION["account_type"] != "1") {
    header("Location: access_denied.php");
    exit();
}

// Fetch all meetings, including canceled, ordered by created_at for latest reports first
$scheduled_query = "
    SELECT br.*, u.firstname, u.lastname 
    FROM blotter_report br 
    JOIN users u ON br.user_id = u.id 
    ORDER BY br.created_at DESC
";
$scheduled_result = mysqli_query($connections, $scheduled_query);

// Function to format the date
function formatDate($date) {
    return date('F j, Y', strtotime($date)); // e.g., August 21, 2024
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduled Meetings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        #content {
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: 20px;
        }
        h2 {
            margin-bottom: 20px;
            color: #343a40;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
        }
        .table th {
            background-color: #343a40;
            color: #fff;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .table tbody tr:hover {
            background-color: #e9ecef;
        }
    </style>
</head>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
    <style>
        /* Custom styles can go here */
    </style>
</head>
<body class="g-sidenav-show bg-gray-100">
    <?php include '../includes/sidebar.php'; ?>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <?php include '../includes/navbar.php'; ?>

        <div class="container-fluid py-2">
            <h2 class="mb-4">Scheduled Meetings</h2>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Report</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Meeting Date</th>
                            <th>Meeting Time</th>
                            <th>Date Reported</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($scheduled_result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['firstname']) ?></td>
                            <td><?= htmlspecialchars($row['lastname']) ?></td>
                            <td><?= htmlspecialchars($row['report_content']) ?></td>
                            <td><?= htmlspecialchars($row['reason']) ?></td>
                            <td><?= ucfirst(htmlspecialchars($row['status'])) ?></td>
                            <td><?= $row['meeting_date'] ? formatDate($row['meeting_date']) : 'N/A' ?></td>
                            <td><?= $row['meeting_time'] ? date('h:i A', strtotime($row['meeting_time'])) : 'N/A' ?></td>
                            <td>
                                <?= formatDate($row['created_at']) ?><br>
                                <?= date('h:i A', strtotime($row['created_at'])) ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <script src="../assets/js/core/popper.min.js"></script>
            <script src="../assets/js/core/bootstrap.min.js"></script>
            <script src="../assets/js/plugins/chartjs.min.js"></script>
        </div>
    </main>
</body>
</html>

</html>
