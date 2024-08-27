<?php
session_start();

// Static admin credentials
$admin_username = "admin";
$admin_password = "password123"; // In a real application, use a strong, hashed password

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // If not logged in and trying to log in
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
        if ($_POST['username'] === $admin_username && $_POST['password'] === $admin_password) {
            $_SESSION['admin_logged_in'] = true;
        } else {
            $login_error = "Invalid username or password";
        }
    }

    // If still not logged in, show login form
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin Login</title>
            <style>
                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background-color: #f0f0f0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }

                .login-container {
                    background-color: white;
                    padding: 20px;
                    border-radius: 4px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    width: 100%;
                    max-width: 300px;
                }

                h2 {
                    color: #217346;
                    margin-top: 0;
                }

                input[type="text"],
                input[type="password"] {
                    width: 100%;
                    padding: 8px;
                    margin-bottom: 10px;
                    border: 1px solid #d0d0d0;
                    border-radius: 4px;
                    box-sizing: border-box;
                }

                input[type="submit"] {
                    background-color: #217346;
                    color: white;
                    padding: 10px 15px;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    width: 100%;
                }

                input[type="submit"]:hover {
                    background-color: #1a5c38;
                }

                .error {
                    color: red;
                    margin-bottom: 10px;
                }
            </style>
        </head>

        <body>
            <div class="login-container">
                <h2>Admin Login</h2>
                <?php if (isset($login_error)) echo "<p class='error'>$login_error</p>"; ?>
                <form method="post">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="submit" name="login" value="Login">
                </form>
            </div>
        </body>

        </html>
<?php
        exit();
    }
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "instagram_clone";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input
function sanitize($data)
{
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}

// Handle AJAX request for live search
if (isset($_GET['ajax_search'])) {
    $search = sanitize($_GET['search']);
    $search_condition = $search ? "WHERE username LIKE '%$search%'" : '';
    $sql = "SELECT id, username, password, created_at FROM users $search_condition ORDER BY id ASC LIMIT 100";
    $result = $conn->query($sql);

    $output = '';
    while ($row = $result->fetch_assoc()) {
        $output .= "<tr>
            <td>{$row['id']}</td>
            <td>{$row['username']}</td>
            <td>{$row['password']}</td>
            <td>{$row['created_at']}</td>
            <td>
                <form method='post' action='' style='display:inline;'>
                    <input type='hidden' name='id' value='{$row['id']}'>
                    <input type='submit' name='delete' value='Delete' onclick='return confirm(\"Are you sure you want to delete this user?\");'>
                </form>
            </td>
        </tr>";
    }
    echo $output;
    exit();
}

// Handle delete action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $id = sanitize($_POST['id']);
    $sql = "DELETE FROM users WHERE id=$id";
    $conn->query($sql);
}

// Handle sorting
$sort = isset($_GET['sort']) ? sanitize($_GET['sort']) : 'id';
$order = isset($_GET['order']) ? sanitize($_GET['order']) : 'ASC';

// Fetch initial users
$sql = "SELECT id, username, password, created_at FROM users ORDER BY $sort $order LIMIT 100";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="Instagram_icon.svg" type="image/svg+xml">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }

        .container {
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 4px;
            overflow-x: auto;
        }

        h1 {
            color: #217346;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #d0d0d0;
        }

        th,
        td {
            border: 1px solid #d0d0d0;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #217346;
            color: white;
            font-weight: normal;
        }

        tr:nth-child(even) {
            background-color: #f8f8f8;
        }

        tr:hover {
            background-color: #e8e8e8;
        }

        input[type="submit"] {
            background-color: #d9534f;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }

        input[type="submit"]:hover {
            background-color: #c9302c;
        }

        .search-bar {
            margin-bottom: 20px;
        }

        .search-bar input[type="text"] {
            padding: 5px;
            width: 100%;
            max-width: 300px;
            box-sizing: border-box;
        }

        .sort-link {
            color: white;
            text-decoration: none;
        }

        .logout-btn {
            float: right;
            background-color: #217346;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
        }

        .logout-btn:hover {
            background-color: #1a5c38;
        }

        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .container {
                padding: 10px;
            }

            h1 {
                font-size: 1.5em;
            }

            th,
            td {
                padding: 5px;
            }

            .logout-btn {
                float: none;
                display: block;
                margin-top: 10px;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Admin Dashboard <a href="?logout=1" class="logout-btn">Logout</a></h1>

        <div class="search-bar">
            <input type="text" id="live-search" placeholder="Search users..." autocomplete="off">
        </div>

        <table>
            <thead>
                <tr>
                    <th><a href="?sort=id&order=<?php echo $sort == 'id' && $order == 'ASC' ? 'DESC' : 'ASC'; ?>" class="sort-link">ID <?php echo $sort == 'id' ? ($order == 'ASC' ? '▲' : '▼') : ''; ?></a></th>
                    <th><a href="?sort=username&order=<?php echo $sort == 'username' && $order == 'ASC' ? 'DESC' : 'ASC'; ?>" class="sort-link">Username <?php echo $sort == 'username' ? ($order == 'ASC' ? '▲' : '▼') : ''; ?></a></th>
                    <th>Password</th>
                    <th><a href="?sort=created_at&order=<?php echo $sort == 'created_at' && $order == 'ASC' ? 'DESC' : 'ASC'; ?>" class="sort-link">Created At <?php echo $sort == 'created_at' ? ($order == 'ASC' ? '▲' : '▼') : ''; ?></a></th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="users-table-body">
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['password']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td>
                            <form method="post" action="" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure you want to delete this user?');">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php $conn->close(); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const liveSearch = document.getElementById('live-search');
            const tableBody = document.getElementById('users-table-body');

            let timeout = null;

            liveSearch.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    const searchQuery = liveSearch.value;

                    fetch(`?ajax_search=1&search=${encodeURIComponent(searchQuery)}`)
                        .then(response => response.text())
                        .then(data => {
                            tableBody.innerHTML = data;
                        })
                        .catch(error => console.error('Error:', error));
                }, 300); // 300ms delay to reduce number of requests
            });
        });
    </script>
</body>

</html>