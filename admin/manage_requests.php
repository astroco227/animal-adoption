<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$msg = "";
$msgType = "";

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    
    // Get animal ID from this adoption request to update its status
    $reqRes = $conn->query("SELECT animal_id FROM adoptions WHERE id = $id");
    if ($reqRes && $reqRes->num_rows > 0) {
        $animal_id = $reqRes->fetch_assoc()['animal_id'];
        
        if ($action == 'approve') {
            $conn->query("UPDATE adoptions SET status = 'Approved' WHERE id = $id");
            $conn->query("UPDATE animals SET status = 'Adopted' WHERE id = $animal_id");
            $msg = "Request Approved. Animal status changed to Adopted.";
            $msgType = "success";
        } elseif ($action == 'reject') {
            $conn->query("UPDATE adoptions SET status = 'Rejected' WHERE id = $id");
            // Also revert animal status if it was previously approved
            $conn->query("UPDATE animals SET status = 'Available' WHERE id = $animal_id");
            $msg = "Request Rejected.";
            $msgType = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Adoption Requests - Admin Panel</title>
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
                <li><a href="manage_requests.php" class="active"><i class="fas fa-clipboard-list"></i> Adoption Requests</a></li>
                <li><a href="manage_messages.php"><i class="fas fa-envelope"></i> Messages</a></li>
                <li><a href="change_password.php"><i class="fas fa-key"></i> Change Password</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <li><a href="../index.php" target="_blank" style="margin-top: 2rem;"><i class="fas fa-external-link-alt"></i> View Site</a></li>
            </ul>
        </aside>
        
        <main class="admin-main">
            <h1 style="color: var(--secondary); margin-bottom: 2rem;">Adoption Requests</h1>
            
            <?php if(!empty($msg)): ?>
                <div class="alert alert-<?php echo $msgType; ?>"><?php echo $msg; ?></div>
            <?php endif; ?>

            <div class="table-wrapper">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h3 style="margin: 0;">Current Adoption Requests</h3>
                    <form method="GET" action="manage_requests.php" style="display: flex; gap: 0.5rem;">
                        <input type="text" name="search" class="form-control" placeholder="Search applicant..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                    </form>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Applicant</th>
                            <th>Contact Info</th>
                            <th>Animal ID</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $searchQuery = "";
                        if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
                            $search = $conn->real_escape_string(trim($_GET['search']));
                            $searchQuery = " WHERE a.name LIKE '%$search%' OR a.email LIKE '%$search%'";
                        }
                        $sql = "SELECT a.*, an.name as animal_name FROM adoptions a LEFT JOIN animals an ON a.animal_id = an.id" . $searchQuery . " ORDER BY a.created_at DESC";
                        $res = $conn->query($sql);
                        if($res && $res->num_rows > 0) {
                            while($row = $res->fetch_assoc()) {
                                $statusClass = strtolower($row['status']);
                                echo "<tr>";
                                echo "<td><strong>" . htmlspecialchars($row['name']) . "</strong><br><small>" . htmlspecialchars($row['address']) . "</small></td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "<br>" . htmlspecialchars($row['phone']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['animal_name'] ?? 'Unknown') . " (#" . $row['animal_id'] . ")</td>";
                                echo "<td>" . date('M j, Y', strtotime($row['created_at'])) . "</td>";
                                echo "<td><span class='status-badge status-{$statusClass}'>" . htmlspecialchars($row['status']) . "</span></td>";
                                
                                echo "<td>";
                                if($row['status'] == 'Pending') {
                                    echo "<a href='?action=approve&id=" . $row['id'] . "' class='btn btn-primary' style='padding: 0.3rem 0.8rem; font-size: 0.8rem; background: var(--success); margin-right: 5px;'>Approve</a>";
                                    echo "<a href='?action=reject&id=" . $row['id'] . "' class='btn btn-secondary' style='padding: 0.3rem 0.8rem; font-size: 0.8rem; background: var(--danger);'>Reject</a>";
                                } else {
                                    echo "<em>Done</em>";
                                }
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No adoption requests found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
