<head>
    <meta charset="utf-8">
    <title>Image grid</title>
    <link rel="stylesheet" href="/styles/grid.css">
    <link rel="stylesheet" href="/styles/slider.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
    <div class="gallery">
        <?php
                $grid_num = str_replace('grid-', '', $grid_class);
                $thumbs_dir = $_SERVER['DOCUMENT_ROOT'] . "/images/grids/grid-{$grid_num}/thumbs/";
                $hd_dir = "/images/grids/grid-{$grid_num}/hd/";

                if (is_dir($thumbs_dir)) {
                    $thumbnails = glob($thumbs_dir . "*.webp");
                    sort($thumbnails);

                    foreach ($thumbnails as $thumb) {
                        $filename = basename($thumb);
                        $full_image = $hd_dir . $filename;
                        echo '<img src="/images/grids/grid-' . $grid_num . '/thumbs/' . $filename . '" data-full="' . $full_image . '" alt="' . $filename . '" draggable="false" class="grid-img lightbox-trigger">';
                    }
                }
                ?>
    </div>

    <div class="lightbox">
        <button class="lightbox-close">&times;</button>
        <i class="fa-solid fa-angle-left lightbox-prev"></i>
        <img src="" alt="">
        <i class="fa-solid fa-angle-right lightbox-next"></i>
    </div>

    <script src="/script/slider.js" defer></script>
</body>