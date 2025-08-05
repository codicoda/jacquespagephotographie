<head>
    <meta charset="utf-8">
    <title>Image Slider</title>
    <link rel="stylesheet" href="/styles/slider.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <script src="/script/slider.js" defer></script>
</head>
<body>
    <div class="wrapper <?php echo $slider_class; ?>">
        <i id="left" class="fa-solid fa-angle-left"></i>
        <div class="carousel">
            <?php
            $grid_num = str_replace('slider-', '', $slider_class);
            $thumbs_dir = $_SERVER['DOCUMENT_ROOT'] . "/images/grids/grid-{$grid_num}/thumbs/";
            $hd_dir = "/images/grids/grid-{$grid_num}/hd/";

            if (is_dir($thumbs_dir)) {
                $thumbnails = glob($thumbs_dir . "*.webp");
                sort($thumbnails);

                foreach ($thumbnails as $thumb) {
                    $filename = basename($thumb);
                    $full_image = $hd_dir . $filename;
                    echo '<img src="/images/grids/grid-' . $grid_num . '/thumbs/' . $filename . '" data-full="' . $full_image . '" alt="' . $filename . '" draggable="false" class="carousel-img lightbox-trigger">';
                }
            }
            ?>
        </div>
        <i id="right" class="fa-solid fa-angle-right"></i>
    </div>

    <div class="lightbox">
        <button class="lightbox-close">&times;</button>
        <i class="fa-solid fa-angle-left lightbox-prev"></i>
        <img src="" alt="">
        <i class="fa-solid fa-angle-right lightbox-next"></i>
    </div>
</body>
</html>
