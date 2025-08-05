<?php require 'block/header.php'; ?>
<body>
    <div class="imghp">
        <picture>
            <source media="(max-width: 768px)" srcset="images/hp/homemobile.webp">
            <source media="(min-width: 769px)" srcset="images/hp/home.webp">
            <img src="images/hp/home.webp" alt="Accueil">
        </picture>
    </div>
    <div class="content-wrapper">
        <a href="https://www.facebook.com/people/Jacques-Page-Photographie/61556424717777" target="_blank" class="fb-circle">
            <span class="fb-logo">
                f
            </span>
        </a>
        <?php include_once 'block/footer.php';?>
    </div>
</body>