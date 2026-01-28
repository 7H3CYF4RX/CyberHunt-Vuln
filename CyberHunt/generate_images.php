<?php
/**
 * Generate placeholder product images
 */

$products = [
    ['name' => 'headphones', 'color' => '667eea'],
    ['name' => 'webcam', 'color' => '764ba2'],
    ['name' => 'mouse', 'color' => 'f093fb'],
    ['name' => 'keyboard', 'color' => '4facfe'],
    ['name' => 'smartwatch', 'color' => '00f2fe'],
    ['name' => 'usb_hub', 'color' => '43e97b'],
    ['name' => 'laptop_stand', 'color' => 'fa709a'],
    ['name' => 'charger', 'color' => 'fee140'],
    ['name' => 'phone_case', 'color' => '30cfd0'],
    ['name' => 'mouse_pad', 'color' => 'a8edea'],
    ['name' => 'ssd', 'color' => 'ff0844'],
    ['name' => 'flash_drive', 'color' => 'ffb199'],
    ['name' => 'memory_card', 'color' => '667eea'],
    ['name' => 'hard_drive', 'color' => '764ba2'],
    ['name' => 'office_chair', 'color' => 'f093fb'],
    ['name' => 'desk_lamp', 'color' => '4facfe'],
    ['name' => 'monitor', 'color' => '00f2fe'],
    ['name' => 'speaker', 'color' => '43e97b'],
    ['name' => 'microphone', 'color' => 'fa709a'],
    ['name' => 'tablet', 'color' => 'fee140'],
];

$dir = __DIR__ . '/assets/images/products/';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

echo "Generating product images...\n";

foreach ($products as $product) {
    $filename = $dir . $product['name'] . '.svg';
    $svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="300" height="220" viewBox="0 0 300 220">
  <defs>
    <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#' . $product['color'] . ';stop-opacity:1" />
      <stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" />
    </linearGradient>
  </defs>
  <rect width="300" height="220" fill="url(#grad)" rx="10"/>
  <text x="150" y="110" font-family="Arial, sans-serif" font-size="18" fill="white" text-anchor="middle" dominant-baseline="middle">' . ucwords(str_replace('_', ' ', $product['name'])) . '</text>
  <rect x="100" y="140" width="100" height="30" fill="rgba(255,255,255,0.2)" rx="5"/>
  <text x="150" y="157" font-family="Arial, sans-serif" font-size="12" fill="white" text-anchor="middle">CyberHunt</text>
</svg>';
    
    file_put_contents($filename, $svg);
    echo "Created: {$product['name']}.svg\n";
}

echo "\nDone! Created " . count($products) . " product images.\n";
?>
