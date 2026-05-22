<?php 
require_once 'config/db.php';
require_once 'includes/header.php'; 

// Fetch real statistics from database
$stats = [
    'available' => 0,
    'adopted' => 0,
    'requests' => 0
];

$res1 = $conn->query("SELECT COUNT(*) as count FROM animals WHERE status = 'Available'");
if ($res1) $stats['available'] = $res1->fetch_assoc()['count'];

$res2 = $conn->query("SELECT COUNT(*) as count FROM animals WHERE status = 'Adopted'");
if ($res2) $stats['adopted'] = $res2->fetch_assoc()['count'];

$res3 = $conn->query("SELECT COUNT(*) as count FROM adoptions");
if ($res3) $stats['requests'] = $res3->fetch_assoc()['count'];
?>

<!-- Hero / Mission Section -->
<section class="about-hero">
    <div class="about-hero-content">
        <h1><?php echo s($settings, 'about_mission_title', 'Our Mission'); ?></h1>
        <p><?php echo s($settings, 'about_mission_text', 'At Paws &amp; Hearts, we believe every animal deserves a second chance at happiness.'); ?></p>
    </div>
</section>

<!-- Statistics Section -->
<section class="about-stats-section">
    <h2 style="text-align: center; margin-bottom: 3rem;">Our Impact</h2>
    <div class="stats-grid">
        <div class="stat-box">
            <i class="fas fa-paw"></i>
            <h3 data-count="<?php echo $stats['available']; ?>"><?php echo number_format($stats['available']); ?></h3>
            <p>Animals Waiting for a Home</p>
        </div>
        <div class="stat-box">
            <i class="fas fa-home"></i>
            <h3 data-count="<?php echo $stats['adopted']; ?>" data-suffix="+"><?php echo number_format($stats['adopted']); ?>+</h3>
            <p>Happy Adoptions</p>
        </div>
        <div class="stat-box">
            <i class="fas fa-envelope-open-text"></i>
            <h3 data-count="<?php echo $stats['requests']; ?>"><?php echo number_format($stats['requests']); ?></h3>
            <p>Adoption Requests Processed</p>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="about-team-section">
    <h2 style="text-align: center; margin-bottom: 3rem;">Meet Our Team</h2>
    <div class="team-grid">
        <div class="team-card">
            <img src="assets/images/person1.jpg" alt="Sarah Johnson" class="team-img">
            <h3>Sarah Johnson</h3>
            <p class="team-role">Founder & Director</p>
            <p class="team-bio">Sarah started Paws & Hearts to give a voice to the voiceless.</p>
        </div>
        <div class="team-card">
            <img src="assets/images/person2.jpg" alt="Michael Lee" class="team-img">
            <h3>Michael Lee</h3>
            <p class="team-role">Head Veterinarian</p>
            <p class="team-bio">Ensuring every animal is healthy, happy, and ready for adoption.</p>
        </div>
        <div class="team-card">
            <img src="assets/images/person3.jpg" alt="Emily Davis" class="team-img">
            <h3>Emily Davis</h3>
            <p class="team-role">Adoption Coordinator</p>
            <p class="team-bio">Emily finds the perfect match between pets and families.</p>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="about-testimonials-section">
    <h2 style="text-align: center; margin-bottom: 3rem;">Happy Tails</h2>
    <div class="testimonials-grid">
        <div class="testimonial-card">
            <div class="stars">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <p class="quote">"Adopting Bella was the best decision of our lives. The team was so supportive throughout the entire process!"</p>
            <p class="author">- The Smith Family</p>
        </div>
        <div class="testimonial-card">
            <div class="stars">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <p class="quote">"Professional, caring, and truly dedicated. They matched me with the perfect companion for my lifestyle."</p>
            <p class="author">- John D.</p>
        </div>
        <div class="testimonial-card">
            <div class="stars">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
            </div>
            <p class="quote">"I love what they do for the community. The facilities are clean and the animals are incredibly well-loved."</p>
            <p class="author">- Maria G.</p>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
