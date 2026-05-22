<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$msg = "";
$msgType = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = $_SESSION['admin_id'];
    $new_username = trim($_POST['new_username']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch current admin record
    $result = $conn->query("SELECT * FROM admins WHERE id = " . (int)$admin_id);
    if ($result && $result->num_rows == 1) {
        $admin = $result->fetch_assoc();

        // --- Update Username ---
        if (!empty($new_username) && $new_username !== $admin['username']) {
            $safeUsername = $conn->real_escape_string($new_username);
            // Check if username already taken
            $checkRes = $conn->query("SELECT id FROM admins WHERE username='$safeUsername' AND id != " . (int)$admin_id);
            if ($checkRes && $checkRes->num_rows > 0) {
                $msg = "That username is already taken. Please choose another.";
                $msgType = "danger";
            } else {
                $conn->query("UPDATE admins SET username='$safeUsername' WHERE id=" . (int)$admin_id);
                $_SESSION['admin_username'] = $new_username;
                $msg = "Username updated successfully.";
                $msgType = "success";
            }
        }

        // --- Update Password (only if fields are filled) ---
        if (!empty($new_password) || !empty($current_password)) {
            if ($new_password !== $confirm_password) {
                $msg = "New password and confirm password do not match.";
                $msgType = "danger";
            } else {
                $storedHash = $admin['password'];
                $isValid = false;

                if (substr($storedHash, 0, 4) === '$2y$') {
                    $isValid = password_verify($current_password, $storedHash);
                } else {
                    if ($storedHash === md5($current_password)) {
                        $isValid = true;
                    }
                }

                if ($isValid) {
                    $newHash = $conn->real_escape_string(password_hash($new_password, PASSWORD_DEFAULT));
                    if ($conn->query("UPDATE admins SET password='$newHash' WHERE id=" . (int)$admin_id)) {
                        $msg = (!empty($msg) ? "Username &amp; " : "") . "Password updated successfully.";
                        $msgType = "success";
                    } else {
                        $msg = "Failed to update password.";
                        $msgType = "danger";
                    }
                } else {
                    $msg = "Current password is incorrect.";
                    $msgType = "danger";
                }
            }
        }
    } else {
        $msg = "Admin not found.";
        $msgType = "danger";
    }
}

// Get counts for dashboard sidebar if needed
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <h2><i class="fas fa-paw"></i> Admin Panel</h2>
            <ul class="admin-nav">
                <li><a href="index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="manage_animals.php"><i class="fas fa-dog"></i> Manage Animals</a></li>
                <li><a href="manage_requests.php"><i class="fas fa-clipboard-list"></i> Adoption Requests</a></li>
                <li><a href="manage_messages.php"><i class="fas fa-envelope"></i> Messages</a></li>
                <li><a href="change_password.php" class="active"><i class="fas fa-key"></i> Change Password</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <li><a href="../index.php" target="_blank" style="margin-top: 2rem;"><i class="fas fa-external-link-alt"></i> View Site</a></li>
            </ul>
        </aside>
        
        <main class="admin-main">
            <h1 style="color: var(--secondary); margin-bottom: 2rem;">Account Settings</h1>
            
            <?php if (!empty($msg)): ?>
                <div class="alert alert-<?php echo $msgType; ?>">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <div class="form-container" style="max-width: 500px; margin: 0;">
                <form method="POST" action="">

                    <h3 style="margin-bottom: 1rem; color: var(--secondary);"><i class="fas fa-user"></i> Change Username</h3>
                    <div class="form-group">
                        <label>Current Username</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($_SESSION['admin_username']); ?>" disabled style="opacity:0.6;">
                    </div>
                    <div class="form-group">
                        <label>New Username</label>
                        <input type="text" name="new_username" class="form-control" placeholder="Leave blank to keep current">
                    </div>

                    <hr style="margin: 2rem 0; border-color: rgba(0,0,0,0.1);">

                    <h3 style="margin-bottom: 1rem; color: var(--secondary);"><i class="fas fa-key"></i> Change Password</h3>
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" name="current_password" class="form-control" placeholder="Required only if changing password">
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_password" class="form-control" placeholder="Leave blank to keep current">
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Leave blank to keep current">
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
