<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$msg = "";
$msgType = "";

// Handle Delete Message
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM contact_messages WHERE id = $id");
    header("Location: manage_messages.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Messages - Admin Panel</title>
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
                <li><a href="manage_messages.php" class="active"><i class="fas fa-envelope"></i> Messages</a></li>
                <li><a href="change_password.php"><i class="fas fa-key"></i> Change Password</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <li><a href="../index.php" target="_blank" style="margin-top: 2rem;"><i class="fas fa-external-link-alt"></i> View Site</a></li>
            </ul>
        </aside>
        
        <main class="admin-main">
            <h1 style="color: var(--secondary); margin-bottom: 2rem;">Contact Messages</h1>
            
            <?php if(!empty($msg)): ?>
                <div class="alert alert-<?php echo $msgType; ?>"><?php echo $msg; ?></div>
            <?php endif; ?>

            <div class="table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Create table if it doesn't exist yet just to prevent SQL errors
                        $conn->query("CREATE TABLE IF NOT EXISTS contact_messages (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            name VARCHAR(100) NOT NULL,
                            email VARCHAR(100) NOT NULL,
                            subject VARCHAR(150) NOT NULL,
                            message TEXT NOT NULL,
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                        )");

                        $sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
                        $res = $conn->query($sql);
                        if($res && $res->num_rows > 0) {
                            while($row = $res->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . date('M j, Y g:i A', strtotime($row['created_at'])) . "</td>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td><a href='mailto:" . htmlspecialchars($row['email']) . "' style='color: var(--primary);'>" . htmlspecialchars($row['email']) . "</a></td>";
                                echo "<td><strong>" . htmlspecialchars($row['subject']) . "</strong></td>";
                                echo "<td style='max-width: 300px; white-space: normal;'>" . nl2br(htmlspecialchars($row['message'])) . "</td>";
                                echo "<td>
                                    <a href='?delete=" . $row['id'] . "' class='btn btn-secondary' style='padding: 0.3rem 0.8rem; font-size: 0.8rem; background: var(--danger);' onclick='return confirm(\"Are you sure you want to delete this message?\");'>Delete</a>
                                </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align: center; padding: 2rem;'>No messages found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
