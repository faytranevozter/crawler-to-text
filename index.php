<?php
// Assuming you installed from Composer:
require "vendor/autoload.php";
use PHPHtmlParser\Dom;

if (!isset($_GET['url'])) {
    die('url not defined!');
}

try {
    $dom = new Dom;
    $dom->loadFromUrl($_GET['url']);
    echo strip_tags($dom->find('body')->innerHtml);
} catch (Exception $e) {
    die($e->getMessage());
}
