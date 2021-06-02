<?php


class ImageCompressor
{
    private const UPLOADS_PATH = 'uploads/';

    /**
     * Represents $_POST
     * @var array
     */
    private array $post;
    /**
     * Represents $_FILES
     * @var array
     */
    private array $files;
    /**
     * Compressed image quality
     * @var string|mixed
     */
    private string $quality;
    /**
     * Source image path and name (in the temporary directory)
     * @var string
     */
    private string $sourceFile;
    /**
     * Source image name
     * @var string
     */
    private string $sourceFileName;
    /**
     * Compressed image path
     * @var string
     */
    private string $compressedFilePath;
    /**
     * Compressed image path and name
     * @var string
     */
    private string $compressedFile;
    /**
     * Compressed image name
     * @var string
     */
    private string $compressedFileName;

    public function __construct(array $post, array $files, string $compressedFilePath)
    {
        $this->post = $post;
        $this->files = $files;
        $this->compressedFilePath = $compressedFilePath;
        $this->quality = $this->post['quality'];
    }

    /**
     * Take all images in $_FILES and compress them
     * @return string
     */
    public function compressImage(): ?string
    {
        $this->checkDirectories();

        $html = '<div class="completed" id="completed">Traitement terminé</div>';

        $fileCount = count($this->files['image_file']['name']);


        for ($i = 0; $i < $fileCount; $i++) {

            $this->sourceFileName = $this->files["image_file"]["name"][$i];
            $this->sourceFile = $this->files["image_file"]["tmp_name"][$i];
            $fileType = $this->files["image_file"]["type"][$i];
            $error = $this->files["image_file"]["error"][$i];

            if (!$this->sourceFile) {
                return '<div class="error">ERREUR: Veuillez sélectionner un ou plusieurs fichiers</div>';
            }
            if ($error > 0) {
                $html .= $error;
            } else if ($fileType === "image/jpeg" || $fileType === "image/webp" || $fileType === "image/png") {
                $html .= $this->createImage();
            } else {
                $html .= '<span class="danger">ERREUR : Format d\'image incorrect, les formats supportés '
                    . 'sont PNG, JPG et WEBP</span>';
            }
        }
        return $html;
    }

    /**
     * Generate image and the corresponding html
     * @return ?string
     */
    private function createImage(): ?string
    {
        $this->compressedFileName = $this->getCompressedFileName($this->sourceFileName);
        $this->compressedFile = $this->compressedFilePath . $this->compressedFileName;

        $info = getimagesize($this->sourceFile);

        $sourceWeight = filesize($this->sourceFile);

        if ($info['mime'] === 'image/jpeg') {
            $image = imagecreatefromjpeg($this->sourceFile);
            imagejpeg($image, $this->compressedFile, $this->quality);
        } else if ($info['mime'] === 'image/webp') {
            $image = imagecreatefromwebp($this->sourceFile);
            imagewebp($image, $this->compressedFile, $this->quality);
        } elseif ($info['mime'] === 'image/png') {
            $pngQuality = 9 - round(0.09 * $this->quality);
            $image = imagecreatefrompng($this->sourceFile);
            imagealphablending($image, false);
            imagesavealpha($image, true);
            imagepng($image, $this->compressedFile, $pngQuality, PNG_ALL_FILTERS);
//            imagejpeg($image, $this->compressedFile, $this->quality);
        }

        $compressedWeight = filesize($this->compressedFile);

//        var_dump($onlyCompressed, $sourceWeight, $compressedWeight);
//        exit();

        if ($this->post['only-compressed'] === 'on') {
            if ($compressedWeight < $sourceWeight) {
                copy($this->sourceFile, self::UPLOADS_PATH . $this->sourceFileName);
                copy($this->compressedFile, self::UPLOADS_PATH . $this->compressedFileName);

                return $this->generateHtml();
            } else {
                unlink($this->compressedFile);
                return null;
            }
        } else {
            copy($this->sourceFile, self::UPLOADS_PATH . $this->sourceFileName);
            copy($this->compressedFile, self::UPLOADS_PATH . $this->compressedFileName);

            return $this->generateHtml();
        }

    }

    /**
     * Generate HTML for image preview
     * @return string
     */
    private function generateHtml(): string
    {
        $baseFileSizeInBytes = filesize($this->sourceFile);
        $compressedFileSizeInBytes = filesize($this->compressedFile);

        $rate = round(100 * ($baseFileSizeInBytes - $compressedFileSizeInBytes) / $baseFileSizeInBytes);

        $BaseFileSize = $this->getHumanReadableSize($baseFileSizeInBytes);

        $compressedFileSize = $this->formatAccordingToRate($rate,
            $this->getHumanReadableSize($compressedFileSizeInBytes));
        $percentRate = $this->formatAccordingToRate($rate, $rate . '%');

        $imagePreview = $this->post['image_preview'];
        if ($imagePreview === 'none') {
            $imagePreview = 'half';
            $imageClass = 'class="none"';
        } else {
            $imageClass = '';
        }

        $uploadsPath = self::UPLOADS_PATH;

        $figcaptionClass = $rate < 1 ? 'figcaption-danger' : 'figcaption-success';

        return <<<HTML
            <div class="imgs">
            <figure class="img $imagePreview">
                <figcaption>
                    <span class="image-title">Image originale</span><br>
                    $this->sourceFileName<br>
                    Taille : <span class='danger'>$BaseFileSize</span><br>
                    <br>
                </figcaption>
                <img src="$uploadsPath$this->sourceFileName" alt="$this->sourceFileName"$imageClass>
            </figure>
            <figure class="img $imagePreview">
                <figcaption class="$figcaptionClass">
                    <span class="image-title">Image compréssée</span><br>
                    $this->compressedFileName<br>
                    Taille : $compressedFileSize<br>
                    Compression : $percentRate
                </figcaption>
                <img src="$uploadsPath$this->compressedFileName" alt="$this->compressedFileName"$imageClass>
            </figure>
            </div>
            HTML;
    }

    /**
     * Converts bytes into human readable size
     * @param string $bytes
     * @return string
     */
    private function getHumanReadableSize(string $bytes): string
    {
        $bytes = floatval($bytes);
        $conversions = [
            0 => [
                'unit' => 'TB',
                'value' => pow(1024, 4)
            ],
            1 => [
                'unit' => 'GB',
                'value' => pow(1024, 3)
            ],
            2 => [
                'unit' => 'MB',
                'value' => pow(1024, 2)
            ],
            3 => [
                'unit' => 'KB',
                'value' => 1024
            ],
            4 => [
                'unit' => 'B',
                'value' => 1
            ],
        ];

        foreach ($conversions as $conversion) {
            if ($bytes >= $conversion['value']) {
                $result = $bytes / $conversion['value'];
                return str_replace('.', ',', strval(round($result, 2)))
                    . ' ' . $conversion['unit'];
            }
        }
        return 'Error';
    }

    /**
     * Add "_compressed_" and the quality at the end of the FileName
     * @param string $fileName
     * @return string
     */
    private function getCompressedFileName(string $fileName): string
    {
        $extension = strrchr($fileName, '.');
        return substr_replace($fileName, '_compressed_' . $this->quality . $extension,
            -(strlen($extension)));
    }

    /**
     * Format the CSS of a string according to rate
     * @param string $rate
     * @param string $html
     * @return string
     */
    private function formatAccordingToRate(string $rate, string $html): string
    {
        return $rate < 1
            ? "<span class='compressions-danger'>$html</span>"
            : "<span class='compressions-success'>$html</span>";
    }

    /**
     * Create and empty directories
     */
    private function checkDirectories(): void
    {
        if (!file_exists($this->compressedFilePath)) {
            mkdir($this->compressedFilePath, 0777, true);
        }
        if (!file_exists(self::UPLOADS_PATH)) {
            mkdir(self::UPLOADS_PATH, 0777, true);
        } else {
            $this->emptyUploadsDirectory();
        }
    }

    /**
     * Empty uploads directory
     */
    private function emptyUploadsDirectory(): void
    {
        array_map('unlink', glob(self::UPLOADS_PATH . '*'));
    }
}