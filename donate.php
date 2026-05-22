<?php
require_once 'config/db.php';

// Ensure donations table exists
$conn->query("CREATE TABLE IF NOT EXISTS donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    reference VARCHAR(200) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$msg     = "";
$msgType = "";

// Process manual donation confirmation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name      = $conn->real_escape_string($_POST['name']);
    $amount    = floatval($_POST['amount']);
    $reference = $conn->real_escape_string($_POST['reference']);

    $conn->query("INSERT INTO donations (name, amount, reference) VALUES ('$name', $amount, '$reference')");

    $msg     = "Thank you, " . htmlspecialchars($name) . "! Your donation confirmation of " . number_format($amount, 0) . " Birr has been received. Once we verify the transfer (Ref: " . htmlspecialchars($reference) . "), we will put it to great use! ❤️";
    $msgType = "success";
}

require_once 'includes/header.php'; 
?>

<!-- Hero Section -->
<section class="about-hero" style="padding: 4rem 5%; margin-bottom: 2rem;">
    <div class="about-hero-content">
        <h1 style="color: var(--white); font-size: 3.5rem; margin-bottom: 1.5rem;">Help Us Save Lives</h1>
        <p style="font-size: 1.2rem; max-width: 800px; margin: 0 auto; color: var(--white);">Your donation goes directly to medical care, food, and shelter for animals in Hawassa and beyond. Every single Birr makes a difference.</p>
    </div>
</section>

<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 5% 4rem;">
    
    <?php if(!empty($msg)): ?>
        <div class="alert alert-<?php echo $msgType; ?>" style="text-align: center; font-size: 1.2rem; margin-bottom: 3rem; padding: 2rem;"><?php echo $msg; ?></div>
    <?php endif; ?>

    <div class="contact-grid">
        <!-- Account Details -->
        <div class="contact-info-section">
            <h2 style="margin-bottom: 1.5rem; color: var(--secondary);">How to Donate</h2>
            <p style="margin-bottom: 2rem; color: var(--text-main); line-height: 1.6;">Please send your donation using any of our official accounts below. Once you have sent the money, please fill out the confirmation form so we can verify and send you a thank you note!</p>

            <div class="contact-card" style="margin-bottom: 1.5rem;">
                <h3><i class="fas fa-mobile-alt" style="color: var(--primary);"></i> Telebirr</h3>
                <p style="margin-left: 2.2rem; font-size: 1.2rem; font-weight: bold; color: var(--text-main);">0911 23 45 67</p>
                <p style="margin-left: 2.2rem; font-size: 0.9rem;">Account Name: Paws and Hearts Rescue</p>
            </div>

            <div class="contact-card">
                <h3><i class="fas fa-university" style="color: var(--primary);"></i> Commercial Bank of Ethiopia (CBE)</h3>
                <p style="margin-left: 2.2rem; font-size: 1.2rem; font-weight: bold; color: var(--text-main);">1000 1234 5678 9</p>
                <p style="margin-left: 2.2rem; font-size: 0.9rem;">Account Name: Paws and Hearts Rescue Hawassa</p>
            </div>
        </div>

        <!-- Confirmation Form -->
        <div class="contact-form-section">
            <div class="form-container" style="margin: 0; max-width: 100%;">
                <h2 style="margin-bottom: 1.5rem; color: var(--secondary);">I Just Donated!</h2>
                <p style="margin-bottom: 2rem;">Fill this out after you complete your transfer.</p>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label>Your Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Abebe Kebede">
                    </div>
                    <div class="form-group">
                        <label>Amount Sent (Birr)</label>
                        <input type="number" name="amount" min="1" class="form-control" required placeholder="e.g. 500">
                    </div>
                    <div class="form-group">
                        <label>Transaction Reference / Phone Number</label>
                        <input type="text" name="reference" class="form-control" required placeholder="Transaction ID or the number you sent from">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fas fa-check-circle"></i> Confirm Donation</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
