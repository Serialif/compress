<?php

require '../src/ImageCompressor.php';

const COMPRESSED_IMAGE_PATH = 'C:/Users/fif34/Downloads/compressed/';

$html = '';

if (isset($_POST['compress'])) {
    $html = (new ImageCompressor($_POST, $_FILES, COMPRESSED_IMAGE_PATH))->compressImage();
}

require '../templates/main.php';




