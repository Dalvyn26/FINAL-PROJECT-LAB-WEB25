<?php
echo "<h1>PHP GD Extension Check</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";

if (extension_loaded('gd')) {
    echo "<p style='color: green;'><strong>✓ GD Extension: ENABLED</strong></p>";
    $gdInfo = gd_info();
    echo "<pre>";
    print_r($gdInfo);
    echo "</pre>";
} else {
    echo "<p style='color: red;'><strong>✗ GD Extension: DISABLED</strong></p>";
    echo "<p>Please enable GD extension in php.ini</p>";
    echo "<p>Loaded php.ini: " . php_ini_loaded_file() . "</p>";
}

echo "<h2>All Loaded Extensions:</h2>";
echo "<pre>";
print_r(get_loaded_extensions());
echo "</pre>";
?>

