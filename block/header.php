<?php
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();?>

<head>
    <title>Jacques Page - Photograhie</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="description" content="Découvrez diverses photographies à travers l'oeil de Jacques Page, un artiste photographe au regard singulier.">
    <link rel="stylesheet" type="text/css" href="/styles/styles.css">
    <script src="script/script.js" defer></script>
</head>
<header>
    <h1 class="title"><a href="/">Jacques Page Photographie</a></h1>
    <button class="burger-menu" aria-label="Menu">
        <span></span>
        <span></span>
        <span></span>
    </button>
    <nav class="nav-menu">
        <ul class="menu">
            <li>
                <a class="link" href="/pen-bron-a-pen-be">de PEN-BRON à PEN-BÉ</a>
            </li>
            <li>
                <a class="link" href="/trioplan">Trioplan</a>
            </li>
            <!--<li>
                <a class="link" href="/infra-rouge">Infra-rouge</a>
            </li>-->
            <li>
                <a href="/aout-2025">Expositions</a>
                <ul class="submenu">
                    <li>
                        <a href="/aout-2025">Août 2025</a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="link" href="/nouveautes">Nouveautés</a>
            </li>
            <li>
                <a class="link" href="/contact">Contact</a>
            </li>
            <!--<li>
                <a class="link" class="private" href="/private">Henri Fohanno</a>
            </li>-->
        </ul>
    </nav>
</header>
