<?php
// Configuration des dossiers surveillés et de leurs destinations
$directories = [
    "www/jacquespagephotographie.fr/images/incoming/grids/grid-1/" => "www/jacquespagephotographie.fr/images/grids/grid-1/",
    "www/jacquespagephotographie.fr/images/incoming/grids/grid-2/" => "www/jacquespagephotographie.fr/images/grids/grid-2/",
    "www/jacquespagephotographie.fr/images/incoming/grids/grid-3/" => "www/jacquespagephotographie.fr/images/grids/grid-3/",
    "www/jacquespagephotographie.fr/images/incoming/grids/grid-4/" => "www/jacquespagephotographie.fr/images/grids/grid-4/",
    "www/jacquespagephotographie.fr/images/incoming/grids/grid-5/" => "www/jacquespagephotographie.fr/images/grids/grid-5/",
    "www/jacquespagephotographie.fr/images/incoming/grids/grid-6/" => "www/jacquespagephotographie.fr/images/grids/grid-6/",
    "www/jacquespagephotographie.fr/images/incoming/blog/" => "www/jacquespagephotographie.fr/images/blog/"
];

// Taille des images intermédiaires (HD) et miniatures
$hdWidth = 2048;
$thumbWidth = 1000;

// Crée les répertoires si besoin
foreach ($directories as $sourceDir => $destinationDir) {
    if ($sourceDir === "www/jacquespagephotographie.fr/images/incoming/blog/") {
        // Pour le blog, créer uniquement le répertoire HD
        if (!is_dir($destinationDir . "hd/")) {
            mkdir($destinationDir . "hd/", 0777, true);
            echo "Répertoire créé : " . $destinationDir . "hd/\n";
        }
    } else {
        // Pour les grids, créer tous les répertoires
        if (!is_dir($sourceDir)) {
            mkdir($sourceDir, 0777, true);
            echo "Répertoire $sourceDir créé !\n";
        }
        if (!is_dir($destinationDir . "hd/")) {
            mkdir($destinationDir . "hd/", 0777, true);
            echo "Répertoire créé : " . $destinationDir . "hd/\n";
        }
        if (!is_dir($destinationDir . "thumbs/")) {
            mkdir($destinationDir . "thumbs/", 0777, true);
            echo "Répertoire créé : " . $destinationDir . "thumbs/\n";
        }
    }
}

// Fonction pour redimensionner une image
function resizeImage($sourceImage, $originalWidth, $originalHeight, $newWidth) {
    $newHeight = intval(($newWidth / $originalWidth) * $originalHeight);
    $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
    return $resizedImage;
}

// Fonction de conversion et de création des différents formats
function convertAndGenerateImages($file, $destinationDir, $hdWidth, $thumbWidth, $isBlog = false) {
    $imageInfo = getimagesize($file);
    if (!$imageInfo) return;

    $mime = $imageInfo['mime'];
    $filename = pathinfo($file, PATHINFO_FILENAME);
    $originalWidth = $imageInfo[0];
    $originalHeight = $imageInfo[1];

    // Chargement selon format
    switch (strtolower($mime)) {
        case 'image/jpeg':
        case 'image/jpg':
            $sourceImage = imagecreatefromjpeg($file);
            break;
        case 'image/png':
            $sourceImage = imagecreatefrompng($file);
            imagealphablending($sourceImage, false);
            imagesavealpha($sourceImage, true);
            break;
        case 'image/gif':
            $sourceImage = imagecreatefromgif($file);
            break;
        default:
            echo "Type MIME non supporté pour le fichier: $file ($mime)\n";
            return;
    }

    if ($isBlog) {
        // Préserver le titre original dans le nom du fichier webp
        $filename = pathinfo($file, PATHINFO_FILENAME); // Garde le format "TITRE§§image"
    } else {
        // Pour les autres images, comportement normal
        $filename = basename($file, '.' . pathinfo($file, PATHINFO_EXTENSION));
    }

    // Création HD (2048px)
    $hdImage = resizeImage($sourceImage, $originalWidth, $originalHeight, min($hdWidth, $originalWidth));
    $hdPath = $destinationDir . "hd/" . $filename . ".webp";
    imagewebp($hdImage, $hdPath, 85);
    imagedestroy($hdImage);

    // Création thumbnail uniquement si ce n'est pas le blog
    if (!$isBlog) {
        $thumbImage = resizeImage($sourceImage, $originalWidth, $originalHeight, min($thumbWidth, $originalWidth));
        $thumbPath = $destinationDir . "thumbs/" . $filename . ".webp";
        imagewebp($thumbImage, $thumbPath, 85);
        imagedestroy($thumbImage);
    }

    // Nettoyage
    imagedestroy($sourceImage);
    unlink($file);

    echo "✅ $filename converti" . ($isBlog ? " en hd" : " en thumb + hd") . "\n";
}

// Traitement des images
foreach ($directories as $sourceDir => $destinationDir) {
    $files = glob($sourceDir . "*.{jpg,jpeg,JPG,JPEG,PNG,GIF,png,gif}", GLOB_BRACE | GLOB_NOSORT);
    echo "Recherche dans $sourceDir : " . count($files) . " fichiers trouvés\n";
    
    // Détermine si c'est le dossier blog
    $isBlog = ($sourceDir === "www/jacquespagephotographie.fr/images/incoming/blog/");
    
    foreach ($files as $file) {
        echo "Traitement du fichier : $file\n";
        $mime = mime_content_type($file);
        echo "Type MIME détecté : $mime\n";
        convertAndGenerateImages($file, $destinationDir, $hdWidth, $thumbWidth, $isBlog);
    }
}

echo "🚀 Conversion terminée pour tous les répertoires !\n";