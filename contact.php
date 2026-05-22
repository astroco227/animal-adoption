<?php
require_once 'config/db.php';

// Auto-create table if it doesn't exist (just for safety since we are adding this feature late)
$conn->query("CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$msg = "";
$msgType = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['message']);

    if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($subject) && !empty($message)) {
        $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";
        if ($conn->query($sql) === TRUE) {
            $msg = "Thank you! Your message has been sent successfully. We will get back to you soon.";
            $msgType = "success";
        } else {
            $msg = "Error sending message. Please try again later.";
            $msgType = "danger";
        }
    } else {
        $msg = "Please fill out all fields correctly.";
        $msgType = "danger";
    }
}

require_once 'includes/header.php'; 
?>

<!-- Hero Section -->
<section class="about-hero" style="padding: 4rem 5%; margin-bottom: 2rem;">
    <div class="about-hero-content">
        <h1>Contact Us</h1>
        <p>We'd love to hear from you! Whether you have a question about adoption, volunteering, or just want to say hi, drop us a line below.</p>
    </div>
</section>

<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 5%;">
    <div class="contact-grid">
        <!-- Contact Info & Map -->
        <div class="contact-info-section">
            <div class="contact-card">
                <h3><i class="fas fa-map-marker-alt" style="color: var(--primary);"></i> Our Location</h3>
                <p><?php echo nl2br(s($settings, 'site_address', 'Piazza, Hawassa, Ethiopia')); ?></p>
                
                <h3 style="margin-top: 1.5rem;"><i class="fas fa-phone-alt" style="color: var(--primary);"></i> Phone</h3>
                <p><?php echo s($settings, 'site_phone', '(555) 123-4567'); ?></p>

                <h3 style="margin-top: 1.5rem;"><i class="fas fa-envelope" style="color: var(--primary);"></i> Email</h3>
                <p><?php echo s($settings, 'site_email', 'hello@pawsandhearts.org'); ?></p>
            </div>
            
            <div class="map-container" style="margin-top: 2rem; border-radius: var(--border-radius); overflow: hidden; box-shadow: var(--shadow);">
                <iframe width="100%" height="300" style="border:0;" loading="lazy" allowfullscreen src="https://maps.google.com/maps?q=Piazza,%20Hawassa,%20Ethiopia&t=&z=15&ie=UTF8&iwloc=&output=embed"></iframe>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="contact-form-section">
            <div class="form-container" style="margin: 0; max-width: 100%;">
                <h2 style="margin-bottom: 1.5rem; color: var(--secondary);">Send us a Message</h2>
                
                <?php if(!empty($msg)): ?>
                    <div class="alert alert-<?php echo $msgType; ?>"><?php echo $msg; ?></div>
                <?php endif; ?>

                <?php if($msgType !== "success"): ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label>Your Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" name="subject" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="message" class="form-control" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fas fa-paper-plane"></i> Send Message</button>
                </form>
                <?php else: ?>
                    <div style="text-align: center; margin-top: 2rem;">
                        <a href="contact.php" class="btn btn-secondary">Send Another Message</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="faq-section" style="margin: 4rem 0;">
        <h2 style="text-align: center; margin-bottom: 2rem;">Frequently Asked Questions</h2>
        <div class="faq-container">
            <div class="faq-item">
                <button class="faq-question">What is the adoption process? <i class="fas fa-chevron-down"></i></button>
                <div class="faq-answer">
                    <p>Our process starts with an online application. Once approved, we will schedule a meet-and-greet with the animal. If it's a match, you'll sign the adoption paperwork and take your new best friend home!</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">Are the animals vaccinated and neutered/spayed? <i class="fas fa-chevron-down"></i></button>
                <div class="faq-answer">
                    <p>Yes! All our animals receive a full medical examination, are up-to-date on necessary vaccinations, and are spayed or neutered prior to adoption.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">What are the adoption fees? <i class="fas fa-chevron-down"></i></button>
                <div class="faq-answer">
                    <p>Adoption fees vary depending on the animal's age, species, and medical needs. The fee covers vaccinations, microchipping, and spay/neuter surgery. Please check the individual animal's profile or contact us for specific fees.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">Can I volunteer at the shelter? <i class="fas fa-chevron-down"></i></button>
                <div class="faq-answer">
                    <p>Absolutely! We are always looking for passionate volunteers to help with dog walking, socializing cats, cleaning, and events. Send us a message using the form above to get started!</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
