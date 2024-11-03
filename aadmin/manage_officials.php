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

// Fetch barangay officials
$result = $connections->query("SELECT name, position FROM barangay_officials");

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
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body class="g-sidenav-show bg-gray-100">
<?php include '../includes/sidebar.php'; ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <?php include '../includes/navbar.php'; ?>

    <div class="container-fluid py-2">
        <h1>Barangay Officials</h1>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['position']); ?></td>
                        <td><button class="btn btn-primary" onclick="openEditModal('<?php echo htmlspecialchars($row['name']); ?>')">Edit</button></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Official</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="officialEditForm" method="POST" onsubmit="confirmUpdate(event)">
                            <input type="hidden" id="oldName" name="oldName" required>
                            <div class="mb-3">
                                <label for="newName" class="form-label">New Name:</label>
                                <input type="text" class="form-control" id="newName" name="newName" required>
                            </div>
                            <button type="submit" class="btn btn-warning">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
        <script>
            function openEditModal(name) {
                document.getElementById('oldName').value = name;
                document.getElementById('newName').value = name; // Pre-fill with current name
                var modal = new bootstrap.Modal(document.getElementById('editModal'));
                modal.show();
            }

            async function confirmUpdate(event) {
                event.preventDefault(); // Prevent the form from submitting immediately
                const newName = document.getElementById('newName').value;

                const result = await Swal.fire({
                    title: 'Update Official',
                    text: "Are you sure you want to update this official's name to " + newName + "?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, update it!'
                });

                if (result.isConfirmed) {
                    document.getElementById('officialEditForm').submit(); // Submit the form if confirmed
                }
            }

            // Show SweetAlert notifications based on session message
            document.addEventListener('DOMContentLoaded', function() {
                <?php if (isset($_SESSION['message'])): ?>
                    const message = <?= json_encode($_SESSION['message']); ?>;
                    let title = 'Notification';
                    let icon = 'success';
                    
                    if (message.includes('updated')) {
                        title = 'Official updated';
                    } else {
                        icon = 'error';
                    }

                    Swal.fire({
                        icon: icon,
                        title: title,
                        text: message,
                    });
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>
            });
        </script>
    </div>
</main>
</body>
</html>
