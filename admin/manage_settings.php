<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../includes/settings.php';
$settings = load_settings($conn);

$msg = "";
$msgType = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fields = [
        'site_name', 'site_tagline', 'site_email', 'site_phone', 'site_address',
        'home_hero_title', 'home_hero_subtitle', 'home_featured_title', 'home_featured_sub',
        'about_mission_title', 'about_mission_text',
        'social_twitter', 'social_instagram', 'social_linkedin', 'social_telegram',
    ];

    foreach ($fields as $field) {
        $k = $conn->real_escape_string($field);
        $v = $conn->real_escape_string(trim($_POST[$field] ?? ''));
        $conn->query("INSERT INTO site_settings (setting_key, setting_value) VALUES ('$k','$v')
                      ON DUPLICATE KEY UPDATE setting_value='$v'");
    }

    // Reload settings
    $settings = load_settings($conn);
    $msg = "Settings saved successfully!";
    $msgType = "success";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Site Settings - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .settings-tabs { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 2rem; }
        .tab-btn {
            padding: 0.5rem 1.2rem; border: 2px solid var(--primary); background: transparent;
            color: var(--primary); border-radius: 50px; cursor: pointer; font-weight: 600;
            transition: all 0.2s;
        }
        .tab-btn.active, .tab-btn:hover { background: var(--primary); color: white; }
        .tab-section { display: none; }
        .tab-section.active { display: block; }
        .settings-group {
            background: white; border-radius: var(--border-radius);
            padding: 1.5rem; margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .settings-group h4 {
            color: var(--secondary); margin-bottom: 1rem;
            padding-bottom: 0.5rem; border-bottom: 2px solid var(--primary-light, #f0f0f0);
        }
    </style>
</head>
<body>
<div class="admin-layout">
    <aside class="admin-sidebar">
        <h2><i class="fas fa-paw"></i> Admin Panel</h2>
        <ul class="admin-nav">
            <li><a href="index.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="manage_animals.php"><i class="fas fa-dog"></i> Manage Animals</a></li>
            <li><a href="manage_requests.php"><i class="fas fa-clipboard-list"></i> Adoption Requests</a></li>
            <li><a href="manage_messages.php"><i class="fas fa-envelope"></i> Messages</a></li>
            <li><a href="manage_settings.php" class="active"><i class="fas fa-cog"></i> Site Settings</a></li>
            <li><a href="change_password.php"><i class="fas fa-key"></i> Change Password</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            <li><a href="../index.php" target="_blank" style="margin-top: 2rem;"><i class="fas fa-external-link-alt"></i> View Site</a></li>
        </ul>
    </aside>

    <main class="admin-main">
        <h1 style="color: var(--secondary); margin-bottom: 0.5rem;">Site Settings</h1>
        <p style="color: var(--text-muted); margin-bottom: 2rem;">Edit the text content shown on your website.</p>

        <?php if (!empty($msg)): ?>
            <div class="alert alert-<?php echo $msgType; ?>"><?php echo $msg; ?></div>
        <?php endif; ?>

        <!-- Tabs -->
        <div class="settings-tabs">
            <button class="tab-btn active" onclick="switchTab('general', this)"><i class="fas fa-globe"></i> General</button>
            <button class="tab-btn" onclick="switchTab('homepage', this)"><i class="fas fa-home"></i> Homepage</button>
            <button class="tab-btn" onclick="switchTab('about', this)"><i class="fas fa-info-circle"></i> About Page</button>
            <button class="tab-btn" onclick="switchTab('social', this)"><i class="fas fa-share-alt"></i> Social Media</button>
        </div>

        <form method="POST" action="">

            <!-- ─── General ─── -->
            <div class="tab-section active" id="tab-general">
                <div class="settings-group">
                    <h4><i class="fas fa-tag"></i> Site Identity</h4>
                    <div class="form-group">
                        <label>Site Name</label>
                        <input type="text" name="site_name" class="form-control" value="<?php echo s($settings, 'site_name'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Site Tagline <small>(shown in footer)</small></label>
                        <input type="text" name="site_tagline" class="form-control" value="<?php echo s($settings, 'site_tagline'); ?>">
                    </div>
                </div>
                <div class="settings-group">
                    <h4><i class="fas fa-phone"></i> Contact Information</h4>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="site_email" class="form-control" value="<?php echo s($settings, 'site_email'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="site_phone" class="form-control" value="<?php echo s($settings, 'site_phone'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="site_address" class="form-control" value="<?php echo s($settings, 'site_address'); ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Settings</button>
            </div>

            <!-- ─── Homepage ─── -->
            <div class="tab-section" id="tab-homepage">
                <div class="settings-group">
                    <h4><i class="fas fa-star"></i> Hero Section</h4>
                    <div class="form-group">
                        <label>Hero Title</label>
                        <input type="text" name="home_hero_title" class="form-control" value="<?php echo s($settings, 'home_hero_title'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Hero Subtitle</label>
                        <textarea name="home_hero_subtitle" class="form-control" style="min-height:80px;"><?php echo s($settings, 'home_hero_subtitle'); ?></textarea>
                    </div>
                </div>
                <div class="settings-group">
                    <h4><i class="fas fa-th"></i> Featured Animals Section</h4>
                    <div class="form-group">
                        <label>Section Title</label>
                        <input type="text" name="home_featured_title" class="form-control" value="<?php echo s($settings, 'home_featured_title'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Section Subtitle</label>
                        <input type="text" name="home_featured_sub" class="form-control" value="<?php echo s($settings, 'home_featured_sub'); ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Settings</button>
            </div>

            <!-- ─── About ─── -->
            <div class="tab-section" id="tab-about">
                <div class="settings-group">
                    <h4><i class="fas fa-heart"></i> Mission Section</h4>
                    <div class="form-group">
                        <label>Mission Title</label>
                        <input type="text" name="about_mission_title" class="form-control" value="<?php echo s($settings, 'about_mission_title'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Mission Text</label>
                        <textarea name="about_mission_text" class="form-control" style="min-height:120px;"><?php echo s($settings, 'about_mission_text'); ?></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Settings</button>
            </div>

            <!-- ─── Social ─── -->
            <div class="tab-section" id="tab-social">
                <div class="settings-group">
                    <h4><i class="fas fa-share-alt"></i> Social Media Links</h4>
                    <div class="form-group">
                        <label><i class="fab fa-twitter" style="color:#1da1f2;"></i> Twitter / X URL</label>
                        <input type="url" name="social_twitter" class="form-control" value="<?php echo s($settings, 'social_twitter'); ?>">
                    </div>
                    <div class="form-group">
                        <label><i class="fab fa-instagram" style="color:#e1306c;"></i> Instagram URL</label>
                        <input type="url" name="social_instagram" class="form-control" value="<?php echo s($settings, 'social_instagram'); ?>">
                    </div>
                    <div class="form-group">
                        <label><i class="fab fa-linkedin" style="color:#0077b5;"></i> LinkedIn URL</label>
                        <input type="url" name="social_linkedin" class="form-control" value="<?php echo s($settings, 'social_linkedin'); ?>">
                    </div>
                    <div class="form-group">
                        <label><i class="fab fa-telegram" style="color:#0088cc;"></i> Telegram URL</label>
                        <input type="url" name="social_telegram" class="form-control" value="<?php echo s($settings, 'social_telegram'); ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Settings</button>
            </div>

        </form>
    </main>
</div>

<script>
function switchTab(id, btn) {
    document.querySelectorAll('.tab-section').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + id).classList.add('active');
    btn.classList.add('active');
}
</script>
</body>
</html>
