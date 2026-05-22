<?php 
require_once 'config/db.php';
require_once 'includes/header.php'; 

// Handle Search and Filter logic
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$type_filter = isset($_GET['type']) ? $conn->real_escape_string($_GET['type']) : '';

$whereClause = "status = 'Available'";
if (!empty($search)) {
    $whereClause .= " AND (name LIKE '%$search%' OR description LIKE '%$search%')";
}
if (!empty($type_filter)) {
    $whereClause .= " AND type = '$type_filter'";
}

$sql = "SELECT * FROM animals WHERE $whereClause ORDER BY created_at DESC";
$result = $conn->query($sql);

// Fetch all available types for the filter tags dynamically
$typesResult = $conn->query("SELECT DISTINCT type FROM animals WHERE status = 'Available' ORDER BY type ASC");
$available_types = [];
if ($typesResult) {
    while($row = $typesResult->fetch_assoc()) {
        $available_types[] = $row['type'];
    }
}
?>

<div style="text-align: center; margin-bottom: 2rem;">
    <h1 style="font-size: 2.5rem; color: var(--secondary);">Find Your Perfect Companion</h1>
    <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto;">Browse through our animals looking for their forever homes. Adopt, don't shop!</p>
</div>

<div class="search-filter-section">
    <form action="animals.php" method="GET" class="search-form">
        <div class="search-bar-wrapper">
            <input type="text" name="search" class="search-input" placeholder="Search by name or keyword..." value="<?php echo htmlspecialchars($search); ?>">
            <?php if (!empty($type_filter)): ?>
                <input type="hidden" name="type" value="<?php echo htmlspecialchars($type_filter); ?>">
            <?php endif; ?>
            <button type="submit" class="btn btn-primary search-btn"><i class="fas fa-search"></i> Search</button>
            <?php if (!empty($search) || !empty($type_filter)): ?>
                <a href="animals.php" class="btn btn-secondary search-btn" style="background: var(--text-muted);"><i class="fas fa-times"></i> Clear Filters</a>
            <?php endif; ?>
        </div>
        
        <div class="filter-tags-wrapper">
            <span class="filter-label">Categories:</span>
            <a href="animals.php<?php echo !empty($search) ? '?search=' . urlencode($search) : ''; ?>" class="filter-tag <?php echo empty($type_filter) ? 'active' : ''; ?>">All Animals</a>
            <?php foreach ($available_types as $t): ?>
                <?php 
                    $activeClass = ($type_filter === $t) ? 'active' : '';
                    $searchParam = !empty($search) ? '&search=' . urlencode($search) : '';
                    $filterUrl = "animals.php?type=" . urlencode($t) . $searchParam;
                ?>
                <a href="<?php echo $filterUrl; ?>" class="filter-tag <?php echo $activeClass; ?>"><?php echo htmlspecialchars($t); ?></a>
            <?php endforeach; ?>
        </div>
    </form>
</div>

<div class="animal-grid">
    <?php

    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $imgPath = "assets/images/" . $row['image'];
            if(empty($row['image']) || !file_exists($imgPath)) {
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
            echo '          <span class="tag">' . htmlspecialchars($row['age']) . '</span>';
            echo '      </div>';
            echo '      <p>' . htmlspecialchars(substr($row['description'], 0, 80)) . '...</p>';
            echo '      <a href="details.php?id=' . $row['id'] . '" class="btn btn-secondary">View Details</a>';
            echo '  </div>';
            echo '</div>';
        }
    } else {
        echo '<p style="text-align: center; grid-column: 1/-1; color: var(--text-muted); font-size: 1.2rem;">Currently, we have no animals available for adoption. Check back soon!</p>';
    }
    ?>
</div>

<?php require_once 'includes/footer.php'; ?>
