<?php
require_once dirname(__FILE__) . '/../includes/settings.php';
$settings = load_settings($conn);
// Detect current page for active nav highlighting
$currentPage = basename($_SERVER['PHP_SELF']);
function navActive($page) {
    global $currentPage;
    return $currentPage === $page ? 'active-nav' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo s($settings, 'site_name', 'Paws & Hearts'); ?> - Animal Adoption</title>
    <meta name="description" content="Adopt a loving pet from Paws &amp; Hearts. Browse available dogs, cats, birds and more in Hawassa, Ethiopia.">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<nav class="navbar">
    <a href="index.php" class="logo">
        <i class="fas fa-paw"></i> <?php echo s($settings, 'site_name', 'Paws &amp; Hearts'); ?>
    </a>

    <button class="hamburger" id="hamburger-btn" aria-label="Open menu" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <ul class="nav-links">
        <li><a href="index.php"   class="<?php echo navActive('index.php'); ?>">Home</a></li>
        <li><a href="about.php"   class="<?php echo navActive('about.php'); ?>">About Us</a></li>
        <li><a href="contact.php" class="<?php echo navActive('contact.php'); ?>">Contact Us</a></li>
        <li><a href="animals.php" class="<?php echo navActive('animals.php'); ?>">Find a Friend</a></li>
        <li><a href="donate.php"      class="btn btn-primary <?php echo navActive('donate.php'); ?>" style="padding:0.5rem 1rem;">Donate</a></li>
        <li><a href="admin/login.php" class="btn btn-admin" style="padding:0.5rem 1rem;"><i class="fas fa-lock"></i> Admin Login</a></li>
    </ul>
</nav>

<main class="container">
