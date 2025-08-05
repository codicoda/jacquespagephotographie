<?php
function getImageInfo($path) {
    $filename = basename($path, '.webp');
    // Format attendu: "TITRE§§image.webp"
    $parts = explode('§§', $filename);
    
    return [
        'path' => $path,
        'date' => filemtime($path),
        'title' => count($parts) > 1 ? $parts[0] : '' // Titre par défaut si pas de séparateur
    ];
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 5;
$start = ($page - 1) * $per_page;

$images = [];
$hd_dir = $_SERVER['DOCUMENT_ROOT'] . "/images/blog/hd/";

if (!is_dir($hd_dir)) {
    header('Content-Type: application/json');
    die(json_encode(['error' => 'Directory not found: ' . $hd_dir]));
}

$files = glob($hd_dir . "*.webp");
if (empty($files)) {
    header('Content-Type: application/json');
    die(json_encode(['error' => 'No images found in directory']));
}

foreach ($files as $file) {
    $images[] = getImageInfo($file);
}

usort($images, function($a, $b) {
    return $b['date'] - $a['date'];
});

$paginated_images = array_slice($images, $start, $per_page);

$response = [];
foreach ($paginated_images as $image) {
    $response[] = [
        'path' => str_replace($_SERVER['DOCUMENT_ROOT'], '', $image['path']),
        'date' => date('d/m/Y', $image['date']),
        'title' => $image['title']
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
exit;
?>

