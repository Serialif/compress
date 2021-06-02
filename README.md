<div align="center">
    <p align="center"><a href="https://serialif.com"><img width="80" height="80" src="https://serialif.com/images/serialif-white.png" alt="Serialif"></a>
<hr>
</div>

<h1 align="center">This is a web application to be used locally to compress images (png, jpeg and webp) for use on a site</h1>
<br>
<div align="center">
    <img src="https://serialif.com/images/compress.jpg" alt="Serialif">
</div>

## Installation
Just clone or download the project and run the index.php file.

## Configuration

### php.ini
You can and should modify some options in the php.ini file to make the best use of this app.
#### `GD extension`
This extension must be `enabled` to generate images.
#### `file_uploads`
Whether to allow HTTP file uploads, this option must be `On`.
#### `max_file_uploads`
The maximum number of files allowed to be uploaded simultaneously.
#### `post_max_size`
Sets max size of post data allowed.
#### `upload_max_filesize`
The maximum size of an uploaded file.

### Constants
#### `COMPRESSED_IMAGE_PATH`
You can choose the output directory for the compressed images. You have to put the path of the desired folder, in the contant in index.php.
#### `UPLOADS_PATH`
You can choose the image upload directory for preview images. You have to put the path of the desired folder, in the constant in the class src/ImageCompressor.php.


## Usage
Once the configuration is complete, you just have to select the images to compress, the desired quality and a preview option.

### Images selection
You can select multiple files. If you exceed the maximum number specified in the php.ini file, in the `max_file_uploads` option, no compression will be possible.
### Quality
You can choose the quality of the compressed images. The lower it is, the lighter the compressed file and, on the contrary, the larger it is, the heavier the file will be.

Depending on the compression of the selected images, the new compression will not have the same results.

The average quality which does not change the output size of the images is 75%, which is why, by default, the quality is 50%, so that there is compression without too much loss of quality.

You can also choose to keep only the images smaller than the original ones
### Preview
You can choose to preview the selected and compressed images to see the quality loss or thumbnails.

The side-by-side preview makes it easier to compare vertical images, and the preview one above the other is more for horizontal images.

## Translation
I add a Google Translate Button, it supports over 100 languages. You can therefore use this application whatever your language.

## License
MIT