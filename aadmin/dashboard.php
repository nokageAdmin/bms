<?php
session_start(); 

// Access for Admin Account only
if (!isset($_SESSION["user_id"]) || $_SESSION["account_type"] != "1") {
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Access Denied</title>
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css'>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Access Denied',
                    text: 'Admin lang ang may access dito',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.history.back();
                    }
                });
            });
        </script>
    </head>
    <body>
    </body>
    </html>";
    exit();
}

// Include database connection
include '../connections.php';

// Queries
$total_residents_query = "SELECT COUNT(id) as total FROM users WHERE account_type != '1'";
$total_residents_result = $connections->query($total_residents_query);
$total_residents = $total_residents_result->fetch_assoc()['total'];

$pending_reports_query = "SELECT COUNT(*) as pending FROM blotter_report WHERE status = 'pending'";
$pending_reports_result = $connections->query($pending_reports_query);
$pending_reports = $pending_reports_result->fetch_assoc()['pending'];

$scheduled_meetings_query = "SELECT COUNT(*) as finished FROM blotter_report WHERE status = 'finished'";
$scheduled_meetings_result = $connections->query($scheduled_meetings_query);
$scheduled_meetings = $scheduled_meetings_result->fetch_assoc()['finished'];

// Fetch data for the pie chart
$purok_counts_query = "SELECT purok, COUNT(*) as count FROM users WHERE account_type != '1' GROUP BY purok ORDER BY purok ASC";
$purok_counts_result = $connections->query($purok_counts_query);

$purok_data = [];
$total_count = 0;

// Initialize counts for purok 1 to 7
for ($i = 1; $i <= 7; $i++) {
    $purok_data[$i] = 0;
}

while ($row = $purok_counts_result->fetch_assoc()) {
    $purok = (int)$row['purok'];
    $purok_data[$purok] = (int)$row['count'];
    $total_count += $row['count'];
}

$connections->close();
?>

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
        body {
            background-color: #f4f7fa;
            font-family: 'Inter', sans-serif;
        }
        .card {
            border-radius: 12px;
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid #e0e0e0;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.1);
        }
        .card-header, .card-footer {
            background-color: #fff;
            padding: 1.5rem;
            border-bottom: 1px solid #e0e0e0;
        }
        .icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6e7ff3, #4a5eea);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.5rem;
        }
        .text-capitalize {
            text-transform: capitalize;
        }
        .text-success {
            color: #28a745;
        }
        .text-danger {
            color: #dc3545;
        }
        .dashboard-metrics {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        #pie-chart {
            max-width: 600px;
            margin: 20px auto;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
    </style>
</head>
<body class="g-sidenav-show bg-gray-100">
<?php include '../includes/sidebar.php'; ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <?php include '../includes/navbar.php'; ?>

    <div class="container-fluid py-2">
        <h1>Dashboard</h1>
        <hr>

        <div class="dashboard-metrics">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-capitalize">Total Residents</p>
                            <h4 class="mb-0"><?php echo $total_residents; ?></h4>
                        </div>
                        <div class="icon">
                            <i class="material-symbols-rounded"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+55% </span>than last week</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-capitalize">Pending Blotter Reports</p>
                            <h4 class="mb-0"><?php echo $pending_reports; ?></h4>
                        </div>
                        <div class="icon">
                            <i class="material-symbols-rounded"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+3% </span>than last month</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-capitalize">Scheduled Meetings</p>
                            <h4 class="mb-0"><?php echo $scheduled_meetings; ?></h4>
                        </div>
                        <div class="icon">
                            <i class="material-symbols-rounded"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <p class="mb-0 text-sm"><span class="text-danger font-weight-bolder">-2% </span>than yesterday</p>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div id="pie-chart">
            <canvas id="purokPieChart"></canvas>
        </div>

        <script src="../assets/js/core/popper.min.js"></script>
        <script src="../assets/js/core/bootstrap.min.js"></script>
        <script src="../assets/js/plugins/chartjs.min.js"></script>

        <script>
            const ctx = document.getElementById('purokPieChart').getContext('2d');
            const totalResidents = <?php echo $total_count; ?>;
            const purokCounts = [<?php echo implode(',', $purok_data); ?>];
            const purokLabels = ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4', 'Purok 5', 'Purok 6', 'Purok 7'];

            const pieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: purokLabels,
                    datasets: [{
                        data: purokCounts,
                        backgroundColor: [
                            '#B0C4DE', '#ADD8E6', '#87CEFA', '#4682B4', '#6495ED', '#5F9EA0', '#6A5ACD'
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const count = tooltipItem.raw;
                                    const percentage = totalResidents ? ((count / totalResidents) * 100).toFixed(2) : 0;
                                    return `${tooltipItem.label}: ${count} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        </script>
    </div>
</main>
</body>
</html>
