<?php 
require_once 'config/db.php';
require_once 'includes/header.php'; 

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='alert alert-danger'>No animal selected! <a href='animals.php'>Go back</a></div>";
    require_once 'includes/footer.php';
    exit;
}

$id = intval($_GET['id']);

// Get animal info
$sql = "SELECT name, status FROM animals WHERE id = $id LIMIT 1";
$result = $conn->query($sql);
if (!$result || $result->num_rows == 0) {
    echo "<div class='alert alert-danger'>Animal not found!</div>";
    require_once 'includes/footer.php';
    exit;
}
$animal = $result->fetch_assoc();

if ($animal['status'] != 'Available') {
    echo "<div class='alert alert-danger'>Sorry, this animal is no longer available for adoption.</div>";
    require_once 'includes/footer.php';
    exit;
}

// Process Form
$msg     = "";
$msgType = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name    = $conn->real_escape_string($_POST['name']);
    $email   = $conn->real_escape_string($_POST['email']);
    $phone   = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);

    // Check for duplicate request (same animal + same email)
    $dupCheck = $conn->query("SELECT id FROM adoptions WHERE animal_id=$id AND email='$email'");
    if ($dupCheck && $dupCheck->num_rows > 0) {
        $msg     = "You have already submitted an adoption request for this animal. We will contact you soon!";
        $msgType = "danger";
    } else {
        $insertSql = "INSERT INTO adoptions (animal_id, name, email, phone, address)
                      VALUES ('$id', '$name', '$email', '$phone', '$address')";
        if ($conn->query($insertSql) === TRUE) {
            $msg     = "Adoption request submitted successfully! We will contact you soon. 🐾";
            $msgType = "success";
        } else {
            $msg     = "Error submitting request: " . $conn->error;
            $msgType = "danger";
        }
    }
}
?>

<div class="form-container">
    <h2 style="text-align: center; color: var(--primary);">Adopt <?php echo htmlspecialchars($animal['name']); ?></h2>
    <p style="text-align: center; margin-bottom: 2rem;">Please fill out the form below to submit your adoption request.</p>

    <?php if(!empty($msg)): ?>
        <div class="alert alert-<?php echo $msgType; ?>"><?php echo $msg; ?></div>
    <?php endif; ?>

    <?php if($msgType !== "success"): ?>
    <form method="POST" action="">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Address</label>
            <textarea name="address" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%;">Submit Request</button>
    </form>
    <?php else: ?>
        <div style="text-align: center;">
            <a href="animals.php" class="btn btn-secondary">Back to Animals</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
