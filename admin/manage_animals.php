<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$msg     = "";
$msgType = "";

// ── Handle Delete ──────────────────────────────────────────────
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM animals WHERE id = $id");
    header("Location: manage_animals.php?deleted=1");
    exit;
}

// ── Handle Add / Edit (POST) ───────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name        = $conn->real_escape_string($_POST['name']);
    $age         = $conn->real_escape_string($_POST['age']);
    $type        = $conn->real_escape_string($_POST['type']);
    $description = $conn->real_escape_string($_POST['description']);
    $health      = $conn->real_escape_string($_POST['health_status']);
    $status      = $conn->real_escape_string($_POST['status']);

    // Image Upload
    $imageName = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $ext       = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/" . $imageName);
    }

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // ── Update existing animal
        $id = intval($_POST['id']);
        if ($imageName) {
            $sql = "UPDATE animals SET name='$name', age='$age', type='$type', description='$description', health_status='$health', status='$status', image='$imageName' WHERE id=$id";
        } else {
            $sql = "UPDATE animals SET name='$name', age='$age', type='$type', description='$description', health_status='$health', status='$status' WHERE id=$id";
        }
        $conn->query($sql);
        $msg     = "Animal updated successfully!";
        $msgType = "success";
    } else {
        // ── Add new animal
        if (empty($imageName)) $imageName = "default.jpg";
        $sql = "INSERT INTO animals (name, age, type, description, health_status, image, status)
                VALUES ('$name', '$age', '$type', '$description', '$health', '$imageName', '$status')";
        $conn->query($sql);
        $msg     = "Animal added successfully!";
        $msgType = "success";
    }
}

if (isset($_GET['deleted'])) { $msg = "Animal deleted."; $msgType = "danger"; }

// ── Load animal for editing ────────────────────────────────────
$editAnimal = null;
if (isset($_GET['edit'])) {
    $editId  = intval($_GET['edit']);
    $editRes = $conn->query("SELECT * FROM animals WHERE id = $editId");
    if ($editRes && $editRes->num_rows > 0) {
        $editAnimal = $editRes->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Animals - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <h2><i class="fas fa-paw"></i> Admin Panel</h2>
            <ul class="admin-nav">
                <li><a href="index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="manage_animals.php" class="active"><i class="fas fa-dog"></i> Manage Animals</a></li>
                <li><a href="manage_requests.php"><i class="fas fa-clipboard-list"></i> Adoption Requests</a></li>
                <li><a href="manage_messages.php"><i class="fas fa-envelope"></i> Messages</a></li>
                <li><a href="manage_settings.php"><i class="fas fa-cog"></i> Site Settings</a></li>
                <li><a href="change_password.php"><i class="fas fa-key"></i> Change Password</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <li><a href="../index.php" target="_blank" style="margin-top: 2rem;"><i class="fas fa-external-link-alt"></i> View Site</a></li>
            </ul>
        </aside>

        <main class="admin-main">
            <h1 style="color: var(--secondary); margin-bottom: 2rem;">Manage Animals</h1>

            <?php if (!empty($msg)): ?>
                <div class="alert alert-<?php echo $msgType; ?>"><?php echo $msg; ?></div>
            <?php endif; ?>

            <!-- ── Add / Edit Form ── -->
            <div class="table-wrapper" style="margin-bottom: 3rem;" id="animal-form">
                <h3 style="margin-bottom: 1.5rem;">
                    <?php echo $editAnimal ? '<i class="fas fa-edit"></i> Edit Animal' : '<i class="fas fa-plus-circle"></i> Add New Animal'; ?>
                </h3>

                <form method="POST" action="manage_animals.php" enctype="multipart/form-data"
                      style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">

                    <?php if ($editAnimal): ?>
                        <input type="hidden" name="id" value="<?php echo $editAnimal['id']; ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required
                               value="<?php echo $editAnimal ? htmlspecialchars($editAnimal['name']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Age</label>
                        <input type="text" name="age" class="form-control" placeholder="e.g. 2 months, 3 years" required
                               value="<?php echo $editAnimal ? htmlspecialchars($editAnimal['age']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Type / Breed</label>
                        <input type="text" name="type" class="form-control" required
                               value="<?php echo $editAnimal ? htmlspecialchars($editAnimal['type']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Health Status</label>
                        <input type="text" name="health_status" class="form-control" required
                               value="<?php echo $editAnimal ? htmlspecialchars($editAnimal['health_status']) : ''; ?>">
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>Description</label>
                        <textarea name="description" class="form-control" style="min-height: 80px;" required><?php
                            echo $editAnimal ? htmlspecialchars($editAnimal['description']) : '';
                        ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="Available" <?php echo ($editAnimal && $editAnimal['status'] === 'Available') ? 'selected' : ''; ?>>Available</option>
                            <option value="Adopted"   <?php echo ($editAnimal && $editAnimal['status'] === 'Adopted')   ? 'selected' : ''; ?>>Adopted</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>
                            Image Upload
                            <?php if ($editAnimal && $editAnimal['image']): ?>
                                <small style="color: var(--text-muted); font-weight: 400;">(leave blank to keep current)</small>
                            <?php endif; ?>
                        </label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1; display: flex; gap: 1rem;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo $editAnimal ? 'Update Animal' : 'Save Animal'; ?>
                        </button>
                        <?php if ($editAnimal): ?>
                            <a href="manage_animals.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel Edit
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- ── Animals Table ── -->
            <div class="table-wrapper">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h3 style="margin: 0;">Current Animals</h3>
                    <form method="GET" action="manage_animals.php" style="display: flex; gap: 0.5rem;">
                        <input type="text" name="search" class="form-control" placeholder="Search name or type..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                    </form>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Age</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $searchQuery = "";
                        if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
                            $search = $conn->real_escape_string(trim($_GET['search']));
                            $searchQuery = " WHERE name LIKE '%$search%' OR type LIKE '%$search%'";
                        }
                        $sql = "SELECT * FROM animals" . $searchQuery . " ORDER BY id DESC";
                        $res = $conn->query($sql);
                        if ($res && $res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $statusClass = strtolower($row['status']);
                                $imgPath     = "../assets/images/" . $row['image'];
                                if (empty($row['image']) || !file_exists($imgPath)) {
                                    $imgPath = "https://via.placeholder.com/50";
                                }
                                echo "<tr>";
                                echo "<td><img src='$imgPath' style='width:50px;height:50px;object-fit:cover;border-radius:8px;'></td>";
                                echo "<td><strong>" . htmlspecialchars($row['name']) . "</strong></td>";
                                echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['age'])  . "</td>";
                                echo "<td><span class='status-badge status-{$statusClass}'>" . htmlspecialchars($row['status']) . "</span></td>";
                                echo "<td style='display:flex;gap:6px;flex-wrap:wrap;'>";
                                echo "<a href='manage_animals.php?edit=" . $row['id'] . "#animal-form' class='btn btn-edit' style='padding:0.3rem 0.8rem;font-size:0.8rem;'><i class='fas fa-edit'></i> Edit</a>";
                                echo "<a href='manage_animals.php?delete=" . $row['id'] . "' class='btn btn-secondary' style='padding:0.3rem 0.8rem;font-size:0.8rem;background:var(--danger);' onclick='return confirm(\"Delete this animal? This cannot be undone.\");'><i class='fas fa-trash'></i> Delete</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align:center;padding:2rem;'>No animals found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
