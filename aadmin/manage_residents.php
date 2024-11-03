<?php
session_start();
include '../connections.php'; 

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
                        window.history.back(); // Redirects back to the previous page
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



// Function to validate email format
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Function to check if email exists
function emailExists($email) {
    global $connections;
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($connections, $query);
    return mysqli_num_rows($result) > 0;
}

// Function to handle user addition
function addUser($data) {
    global $connections;

    // Validate email format
    if (!isValidEmail($data['email'])) {
        return "Invalid email format.";
    }

    // Check if email already exists
    if (emailExists($data['email'])) {
        return "Email already registered.";
    }

    $first_name = $data['firstname'];
    $middle_name = $data['middlename'];
    $last_name = $data['lastname'];
    $suffix = $data['suffix'];
    $purok = $data['purok'];
    $contact_number = $data['contact'];
    $email = $data['email'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $account_type = 3;
    $created_at = date('Y-m-d H:i:s');

    $query_user = "INSERT INTO users (firstname, middlename, lastname, suffix, purok, contact, email, password, account_type, created_at) 
                   VALUES ('$first_name', '$middle_name', '$last_name', '$suffix','$purok', '$contact_number', '$email', '$password', $account_type, '$created_at')";
    
    return mysqli_query($connections, $query_user);
}

// Function to handle user update
function updateUser($data) {
    global $connections;

    // Validate email format
    if (!isValidEmail($data['email'])) {
        return "Invalid email format.";
    }

    // Check if email already exists
    // If it's a different email from the one being updated
    $existingEmailCheck = "SELECT * FROM users WHERE email = '{$data['email']}' AND id != {$data['id']}";
    $result = mysqli_query($connections, $existingEmailCheck);
    if (mysqli_num_rows($result) > 0) {
        return "Email already registered.";
    }

    $id = $data['id'];
    $first_name = $data['firstname'];
    $middle_name = $data['middlename'];
    $last_name = $data['lastname'];
    $suffix = $data['suffix'];
    $purok = $data['purok'];
    $contact_number = $data['contact'];
    $email = $data['email'];

    $query = "UPDATE users SET firstname='$first_name', middlename='$middle_name', lastname='$last_name', suffix='$suffix', purok='$purok', contact='$contact_number', email='$email' WHERE id=$id";
    
    return mysqli_query($connections, $query);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        // Validate required fields
        if (empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['purok']) || empty($_POST['contact']) || empty($_POST['email']) || empty($_POST['password'])) {
            $_SESSION['message'] = "Please fill out all required fields.";
            header("Location: manage_residents.php");
            exit();
        }

        // Attempt to add user
        $addUserResult = addUser($_POST);
        if ($addUserResult === true) {
            $_SESSION['message'] = "User added successfully.";
        } else {
            $_SESSION['message'] = $addUserResult; // Set error message
        }
        header("Location: manage_residents.php");
        exit();
    } elseif (isset($_POST['update'])) {
        // Validate required fields
        if (empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['purok']) || empty($_POST['contact']) || empty($_POST['email'])) {
            $_SESSION['message'] = "Please fill out all required fields.";
            header("Location: manage_residents.php");
            exit();
        }

        // Attempt to update user
        $updateUserResult = updateUser($_POST);
        if ($updateUserResult === true) {
            $_SESSION['message'] = "User updated successfully.";
        } else {
            $_SESSION['message'] = $updateUserResult; // Set error message
        }
        header("Location: manage_residents.php");
        exit();
    }
}

// Delete User
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($connections, "DELETE FROM blotter_report WHERE user_id=$id");
    mysqli_query($connections, "DELETE FROM users WHERE id=$id");
    $_SESSION['message'] = "User deleted successfully.";
    header("Location: manage_residents.php");
    exit();
}

// Read Data
$result = mysqli_query($connections, "SELECT * FROM users WHERE account_type = 3");
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Export function
if (isset($_GET['export'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="residents.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['First Name', 'Middle Name', 'Last Name', 'Suffix', 'Purok', 'Contact Number', 'Email']); // Column headers

    foreach ($users as $user) {
        fputcsv($output, [
            $user['firstname'],
            $user['middlename'],
            $user['lastname'],
            $user['suffix'],
            $user['purok'],
            $user['contact'],
            $user['email']
        ]);
    }

    fclose($output);
    exit();
}

?>

<?php
include '../connections.php'; 

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
    <body></body>
    </html>";
    exit();
}

// ... [other functions and form handling code remain unchanged]

// Read Data
$result = mysqli_query($connections, "SELECT * FROM users WHERE account_type = 3");
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="script.js"></script>
</head>

<body class="g-sidenav-show bg-gray-100">
    <?php include '../includes/sidebar.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <?php include '../includes/navbar.php'; ?>

        <div class="container-fluid py-2">
            <h1>Manage Residents</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add User</button>

           
            <div class="table-responsive">
                <table class="table mt-4" id="userTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Purok</th>
                            <th>Contact</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= ucfirst(strtolower($user['lastname'])) . ', ' . ucfirst(strtolower($user['firstname'])) . ' ' . ucfirst(substr(strtolower($user['middlename']), 0, 1)) . '.' ?></td>
                            <td><?= $user['email'] ?></td>
                            <td><?= $user['purok'] ?></td>
                            <td><?= $user['contact'] ?></td>
                            <td>
                                <button class="btn btn-warning" onclick='openUpdateModal(<?= json_encode($user) ?>)'>Update</button>
                                <button class="btn btn-danger" onclick="confirmDeleteUser(<?= $user['id'] ?>)">Delete</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="manage_residents.php?export=1" class="btn btn-success">Export to CSV</a>
            </div>

            <!-- Add Residents Modal -->
            <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="addUserForm" method="POST" onsubmit="return validateAddUserForm()">
                            <input type="hidden" name="create" value="1">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addModalLabel">Add New User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="addFirstName" class="form-label">First Name</label>
                                        <input type="text" name="firstname" id="addFirstName" class="form-control" placeholder="First Name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="addMiddleName" class="form-label">Middle Name</label>
                                        <input type="text" name="middlename" id="addMiddleName" class="form-control" placeholder="Middle Name">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="addLastName" class="form-label">Last Name</label>
                                        <input type="text" name="lastname" id="addLastName" class="form-control" placeholder="Last Name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="addSuffix" class="form-label">Suffix</label>
                                        <input type="text" name="suffix" id="addSuffix" class="form-control" placeholder="Suffix (if any)">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="addPurok" class="form-label">Purok</label>
                                        <input type="text" name="purok" id="addPurok" class="form-control" placeholder="Purok" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="addContact" class="form-label">Contact Number</label>
                                        <input type="text" name="contact" id="addContact" class="form-control" placeholder="Contact Number" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="addEmail" class="form-label">Email</label>
                                        <input type="email" name="email" id="addEmail" class="form-control" placeholder="Email" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="addPassword" class="form-label">Password</label>
                                        <input type="password" name="password" id="addPassword" class="form-control" placeholder="Password" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Add User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Update Residents Modal -->
            <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="POST" onsubmit="return validateUpdateUserForm()">
                            <input type="hidden" name="update" value="1">
                            <input type="hidden" name="id" id="updateId">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateModalLabel">Update User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="updateFirstName" class="form-label">First Name</label>
                                        <input type="text" name="firstname" id="updateFirstName" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="updateMiddleName" class="form-label">Middle Name</label>
                                        <input type="text" name="middlename" id="updateMiddleName" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="updateLastName" class="form-label">Last Name</label>
                                        <input type="text" name="lastname" id="updateLastName" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="updateSuffix" class="form-label">Suffix</label>
                                        <input type="text" name="suffix" id="updateSuffix" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="updatePurok" class="form-label">Purok</label>
                                        <input type="text" name="purok" id="updatePurok" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="updateContact" class="form-label">Contact Number</label>
                                        <input type="text" name="contact" id="updateContact" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="updateEmail" class="form-label">Email</label>
                                        <input type="email" name="email" id="updateEmail" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="updatePassword" class="form-label">Password</label>
                                        <input type="password" name="password" id="updatePassword" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </main>
    
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>
    
    <script>
        function filterTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('userTable');
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                const td = tr[i].getElementsByTagName('td');
                let found = false;

                for (let j = 0; j < td.length - 1; j++) { // Exclude the Actions column
                    if (td[j] && td[j].textContent.toLowerCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
                tr[i].style.display = found ? "" : "none";
            }
        }

        function validateAddUserForm() {
            const email = document.getElementById('addEmail').value;
            const contact = document.getElementById('addContact').value;

            if (!validateEmail(email)) {
                alert("Please enter a valid email address.");
                return false;
            }
            if (!validateContact(contact)) {
                alert("Please enter a valid contact number.");
                return false;
            }
            return true;
        }

        function validateUpdateUserForm() {
            const email = document.getElementById('updateEmail').value;
            const contact = document.getElementById('updateContact').value;

            if (!validateEmail(email)) {
                alert("Please enter a valid email address.");
                return false;
            }
            if (!validateContact(contact)) {
                alert("Please enter a valid contact number.");
                return false;
            }
            return true;
        }

        function validateEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }

        function validateContact(contact) {
            const regex = /^\d{10}$/; // Example regex for 10-digit contact numbers
            return regex.test(contact);
        }

        function confirmDeleteUser(userId) {
            if (confirm("Are you sure you want to delete this user?")) {
                // Add your delete logic here (e.g., AJAX call)
            }
        }

        function openUpdateModal(user) {
            document.getElementById('updateId').value = user.id;
            document.getElementById('updateFirstName').value = user.firstname;
            document.getElementById('updateMiddleName').value = user.middlename;
            document.getElementById('updateLastName').value = user.lastname;
            document.getElementById('updateSuffix').value = user.suffix;
            document.getElementById('updatePurok').value = user.purok;
            document.getElementById('updateContact').value = user.contact;
            document.getElementById('updateEmail').value = user.email;
            var updateModal = new bootstrap.Modal(document.getElementById('updateModal'));
            updateModal.show();
        }
    </script>
</body>

</html>
