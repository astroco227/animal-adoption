<?php
session_start();
require_once '../config/db.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username    = $conn->real_escape_string($_POST['username']);
    $inputPass   = $_POST['password'];

    // Fetch user by username only (so we can inspect the stored hash)
    $sql    = "SELECT * FROM admins WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        $admin       = $result->fetch_assoc();
        $storedHash  = $admin['password'];
        $isValid     = false;

        // Detect legacy MD5 hash (does not start with $2y$)
        if (substr($storedHash, 0, 4) === '$2y$') {
            // Modern bcrypt hash
            $isValid = password_verify($inputPass, $storedHash);
        } else {
            // Legacy MD5 — verify and silently migrate
            if ($storedHash === md5($inputPass)) {
                $isValid    = true;
                $newHash    = $conn->real_escape_string(password_hash($inputPass, PASSWORD_DEFAULT));
                $conn->query("UPDATE admins SET password='$newHash' WHERE id=" . (int)$admin['id']);
            }
        }

        if ($isValid) {
            $_SESSION['admin_id']       = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid username or password!";
        }
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Paws & Hearts</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { display: flex; justify-content: center; align-items: center; background: var(--secondary); }
        .login-box { background: white; padding: 3rem; border-radius: var(--border-radius); width: 100%; max-width: 400px; box-shadow: var(--shadow); }
    </style>
</head>
<body>
    <div class="login-box">
        <h2 style="text-align: center; color: var(--primary); margin-bottom: 2rem;">Admin Login</h2>
        <?php if(!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
        </form>
        <p style="text-align: center; margin-top: 1rem;"><a href="../index.php">Back to Website</a></p>
    </div>
</body>
</html>
