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
$sql = "SELECT * FROM animals WHERE id = $id LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $animal = $result->fetch_assoc();
    
    $imgPath = "assets/images/" . $animal['image'];
    if(empty($animal['image']) || !file_exists($imgPath)) {
        $imgPath = "https://via.placeholder.com/600x400?text=No+Image";
    }
} else {
    echo "<div class='alert alert-danger'>Animal not found! <a href='animals.php'>Go back</a></div>";
    require_once 'includes/footer.php';
    exit;
}
?>

<div class="details-wrapper">
    <div class="details-img">
        <img src="<?php echo htmlspecialchars($imgPath); ?>" alt="<?php echo htmlspecialchars($animal['name']); ?>">
    </div>
    
    <div class="details-content">
        <h1>Meet <?php echo htmlspecialchars($animal['name']); ?>!</h1>
        
        <div class="details-meta">
            <div class="meta-item">
                <span class="meta-label">Type:</span>
                <span><?php echo htmlspecialchars($animal['type']); ?></span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Age:</span>
                <span><?php echo htmlspecialchars($animal['age']); ?></span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Health:</span>
                <span><?php echo htmlspecialchars($animal['health_status']); ?></span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Status:</span>
                <span style="font-weight: bold; color: <?php echo $animal['status'] == 'Available' ? 'var(--success)' : 'var(--danger)'; ?>">
                    <?php echo htmlspecialchars($animal['status']); ?>
                </span>
            </div>
        </div>

        <h3 style="margin-top: 2rem;">About <?php echo htmlspecialchars($animal['name']); ?></h3>
        <p style="margin-bottom: 2rem; white-space: pre-line;"><?php echo htmlspecialchars($animal['description']); ?></p>

        <?php if ($animal['status'] == 'Available'): ?>
            <a href="adopt.php?id=<?php echo $animal['id']; ?>" class="btn btn-primary" style="font-size: 1.2rem; padding: 1rem 2rem;">
                <i class="fas fa-heart"></i> Adopt Me
            </a>
        <?php else: ?>
            <button class="btn btn-secondary" disabled>Already Adopted</button>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
