<?php
require_once 'config/db.php';

$imagesDir = __DIR__ . '/assets/images';
$images = scandir($imagesDir);

$names = ['Bella', 'Max', 'Luna', 'Charlie', 'Lucy', 'Cooper', 'Daisy', 'Milo', 'Zoe', 'Rocky', 'Lily', 'Bear', 'Stella', 'Tucker', 'Lola', 'Oliver', 'Molly', 'Duke', 'Sadie', 'Leo', 'Bailey', 'Zeus', 'Chloe', 'Bentley', 'Penny', 'Toby', 'Ruby', 'Riley', 'Rosie', 'Buster'];
$ages = ['2 Months', '6 Months', '1 Year', '2 Years', '3 Years', '5 Years', 'Senior'];
$healthStatuses = ['Vaccinated & Healthy', 'Requires Special Diet', 'Healthy', 'Neutered/Spayed & Healthy', 'Needs Dental Care', 'Healthy & Active'];

$count = 0;

foreach ($images as $image) {
    if ($image === '.' || $image === '..') continue;

    // Determine type from file name (e.g., bird1.jpg -> Bird)
    $type = 'Unknown';
    if (strpos($image, 'bird') !== false) $type = 'Bird';
    if (strpos($image, 'cat') !== false) $type = 'Cat';
    if (strpos($image, 'chicken') !== false) $type = 'Chicken';
    if (strpos($image, 'dog') !== false) $type = 'Dog';
    if (strpos($image, 'fish') !== false) $type = 'Fish';
    if (strpos($image, 'guinea') !== false) $type = 'Guinea Pig';
    if (strpos($image, 'hamster') !== false) $type = 'Hamster';
    if (strpos($image, 'parakeet') !== false) $type = 'Parakeet';
    if (strpos($image, 'pigeon') !== false) $type = 'Pigeon';
    if (strpos($image, 'rabbit') !== false) $type = 'Rabbit';

    $name = $names[array_rand($names)];
    $age = $ages[array_rand($ages)];
    $health = $healthStatuses[array_rand($healthStatuses)];
    
    // Create a description based on type
    $description = "Meet $name, a wonderful $type who is looking for a forever home. They are $age old and their health status is: $health. If you have room in your heart and home for $name, please consider adopting today!";

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO animals (name, age, type, description, health_status, image, status) VALUES (?, ?, ?, ?, ?, ?, 'Available')");
    $stmt->bind_param("ssssss", $name, $age, $type, $description, $health, $image);
    
    if ($stmt->execute()) {
        $count++;
    } else {
        echo "Error inserting $image: " . $stmt->error . "\n";
    }
    $stmt->close();
}

echo "Successfully inserted $count animals into the database!\n";

?>
