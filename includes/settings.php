<?php
/**
 * Load all site settings from the database into an associative array.
 * Call this once per page after $conn is available.
 */
function load_settings($conn) {
    $settings = [];
    // Auto-create table if it doesn't exist yet
    $conn->query("CREATE TABLE IF NOT EXISTS site_settings (
        setting_key   VARCHAR(100) NOT NULL PRIMARY KEY,
        setting_value TEXT,
        updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");

    // Insert default values (only if not already set)
    $defaults = [
        'site_name'            => 'Paws & Hearts',
        'site_tagline'         => 'Adopt a friend, save a life ❤️',
        'site_email'           => 'hello@pawsandhearts.org',
        'site_phone'           => '(555) 123-4567',
        'site_address'         => 'Piazza, Hawassa, Ethiopia',
        'home_hero_title'      => 'Welcome to Paws & Hearts 🐾',
        'home_hero_subtitle'   => 'Every animal deserves a loving home. Browse our available furry friends and take the first step towards finding your new best friend.',
        'home_featured_title'  => 'Featured Friends',
        'home_featured_sub'    => 'A few animals looking for their forever home right now.',
        'about_mission_title'  => 'Our Mission',
        'about_mission_text'   => 'At Paws & Hearts, we believe every animal deserves a second chance at happiness. Our dedicated team works tirelessly to rescue, rehabilitate, and rehome pets in need. We aren\'t just an adoption center; we are a community built on compassion and love for all living creatures.',
        'social_twitter'       => 'https://x.com/Andocry',
        'social_instagram'     => 'https://www.instagram.com/mini21_stars?igsh=cjl6NmxicjM2bnY3',
        'social_linkedin'      => 'https://www.linkedin.com/in/minister-mebratu-74314430a',
        'social_telegram'      => 'https://t.me/@ando888',
    ];

    foreach ($defaults as $key => $val) {
        $k = $conn->real_escape_string($key);
        $v = $conn->real_escape_string($val);
        $conn->query("INSERT IGNORE INTO site_settings (setting_key, setting_value) VALUES ('$k', '$v')");
    }

    // Load all settings into array
    $res = $conn->query("SELECT setting_key, setting_value FROM site_settings");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    }
    return $settings;
}

/**
 * Helper: get a setting value safely, with HTML escaping.
 * Usage: s($settings, 'key', 'fallback')
 */
function s($settings, $key, $default = '') {
    return htmlspecialchars(
        (isset($settings[$key]) && $settings[$key] !== '') ? $settings[$key] : $default
    );
}
?>
