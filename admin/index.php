<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Get counts for dashboard
$animalCount  = $conn->query("SELECT COUNT(*) as c FROM animals")->fetch_assoc()['c'];
$requestCount = $conn->query("SELECT COUNT(*) as c FROM adoptions")->fetch_assoc()['c'];
$pendingCount = $conn->query("SELECT COUNT(*) as c FROM adoptions WHERE status='Pending'")->fetch_assoc()['c'];
$msgCount     = 0;
$msgRes = $conn->query("SELECT COUNT(*) as c FROM contact_messages");
if ($msgRes) $msgCount = $msgRes->fetch_assoc()['c'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Paws & Hearts</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <h2><i class="fas fa-paw"></i> Admin Panel</h2>
            <ul class="admin-nav">
                <li><a href="index.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="manage_animals.php"><i class="fas fa-dog"></i> Manage Animals</a></li>
                <li><a href="manage_requests.php"><i class="fas fa-clipboard-list"></i> Adoption Requests</a></li>
                <li><a href="manage_messages.php"><i class="fas fa-envelope"></i> Messages</a></li>
                <li><a href="manage_settings.php"><i class="fas fa-cog"></i> Site Settings</a></li>
                <li><a href="change_password.php"><i class="fas fa-key"></i> Change Password</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <li><a href="../index.php" target="_blank" style="margin-top: 2rem;"><i class="fas fa-external-link-alt"></i> View Site</a></li>
            </ul>
        </aside>
        
        <main class="admin-main">
            <h1 style="color: var(--secondary); margin-bottom: 2rem;">Dashboard Overview</h1>
            <p>Welcome back, <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong>!</p>
            
            <div class="dashboard-cards" style="margin-top: 2rem;">
                <div class="stat-card">
                    <h3>Total Animals</h3>
                    <div class="number"><?php echo $animalCount; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Total Requests</h3>
                    <div class="number"><?php echo $requestCount; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Pending Requests</h3>
                    <div class="number" style="color: var(--danger);"><?php echo $pendingCount; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Messages</h3>
                    <div class="number" style="color: var(--accent);"><?php echo $msgCount; ?></div>
                    <a href="manage_messages.php" style="font-size:0.85rem; color:var(--primary); font-weight:600;">View all &rarr;</a>
                </div>
            </div>
            
            <div class="table-wrapper" style="margin-top: 3rem;">
                <h3 style="margin-bottom: 1rem; color: var(--secondary);">Recent Adoption Requests</h3>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM adoptions ORDER BY created_at DESC LIMIT 5";
                        $res = $conn->query($sql);
                        if($res && $res->num_rows > 0) {
                            while($row = $res->fetch_assoc()) {
                                $statusClass = strtolower($row['status']);
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td><span class='status-badge status-{$statusClass}'>" . htmlspecialchars($row['status']) . "</span></td>";
                                echo "<td>" . date('M j, Y', strtotime($row['created_at'])) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No requests found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
