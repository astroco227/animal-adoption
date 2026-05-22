<?php 
require_once 'config/db.php';
require_once 'includes/header.php';

// Quick stats for hero section
$heroAvailable = 0; $heroAdopted = 0; $heroRequests = 0;
$r1 = $conn->query("SELECT COUNT(*) as c FROM animals WHERE status='Available'");
if ($r1) $heroAvailable = $r1->fetch_assoc()['c'];
$r2 = $conn->query("SELECT COUNT(*) as c FROM animals WHERE status='Adopted'");
if ($r2) $heroAdopted   = $r2->fetch_assoc()['c'];
$r3 = $conn->query("SELECT COUNT(*) as c FROM adoptions");
if ($r3) $heroRequests  = $r3->fetch_assoc()['c'];
?>

<section class="hero">
    <h1><?php echo s($settings, 'home_hero_title', 'Welcome to Paws &amp; Hearts 🐾'); ?></h1>
    <p><?php echo s($settings, 'home_hero_subtitle', 'Every animal deserves a loving home.'); ?></p>
    <a href="animals.php" class="btn btn-primary">View All Animals</a>

    <div class="hero-stats">
        <div class="hero-stat">
            <span class="big-num" data-count="<?php echo $heroAvailable; ?>"><?php echo $heroAvailable; ?></span>
            <span class="label">Available Now</span>
        </div>
        <div class="hero-stat">
            <span class="big-num" data-count="<?php echo $heroAdopted; ?>" data-suffix="+"><?php echo $heroAdopted; ?>+</span>
            <span class="label">Happy Adoptions</span>
        </div>
        <div class="hero-stat">
            <span class="big-num" data-count="<?php echo $heroRequests; ?>"><?php echo $heroRequests; ?></span>
            <span class="label">Applications Processed</span>
        </div>
    </div>
</section>

<h2 style="text-align: center; margin-bottom: 0.5rem;"><?php echo s($settings, 'home_featured_title', 'Featured Friends'); ?></h2>
<p style="text-align:center; color:var(--text-muted); margin-bottom:2.5rem;"><?php echo s($settings, 'home_featured_sub', 'A few animals looking for their forever home right now.'); ?></p>

<div class="animal-grid">
    <?php
    $sql    = "SELECT * FROM animals WHERE status = 'Available' ORDER BY created_at DESC LIMIT 3";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $imgPath = "assets/images/" . $row['image'];
            if (empty($row['image']) || !file_exists($imgPath)) {
                $imgPath = "https://via.placeholder.com/300x250?text=No+Image";
            }
            echo '<div class="animal-card">';
            echo '  <div class="animal-img-wrapper">';
            echo '      <img src="' . htmlspecialchars($imgPath) . '" alt="' . htmlspecialchars($row['name']) . '" class="animal-img">';
            echo '  </div>';
            echo '  <div class="animal-info">';
            echo '      <h3>' . htmlspecialchars($row['name']) . '</h3>';
            echo '      <div class="animal-tags">';
            echo '          <span class="tag">' . htmlspecialchars($row['type']) . '</span>';
            echo '          <span class="tag">' . htmlspecialchars($row['age'])  . '</span>';
            echo '      </div>';
            echo '      <p>' . htmlspecialchars(substr($row['description'], 0, 80)) . '...</p>';
            echo '      <a href="details.php?id=' . $row['id'] . '" class="btn btn-secondary">View Details</a>';
            echo '  </div>';
            echo '</div>';
        }
    } else {
        echo '<p style="text-align:center;grid-column:1/-1;color:var(--text-muted);">No animals available at the moment. Please check back later!</p>';
    }
    ?>
</div>

<!-- How It Works Section -->
<section class="how-it-works">
    <h2>How It Works</h2>
    <p class="section-subtitle">Finding your perfect companion is easy, fast, and rewarding.</p>
    <div class="how-steps">
        <div class="how-step">
            <div class="step-number">1</div>
            <h3>Browse Animals</h3>
            <p>Explore our gallery of lovable animals looking for their forever homes. Filter by type or search by name.</p>
        </div>
        <div class="how-step">
            <div class="step-number">2</div>
            <h3>Submit a Request</h3>
            <p>Found your match? Fill out our simple adoption form. We review every application with care and love.</p>
        </div>
        <div class="how-step">
            <div class="step-number">3</div>
            <h3>Welcome Home! 🐾</h3>
            <p>Once approved, come meet your new family member and take them home. A beautiful friendship begins!</p>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>