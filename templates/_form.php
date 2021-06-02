<?php


if (isset($_POST['image_preview'])) {
    $halfChecked = $_POST['image_preview'] === 'half' ? 'checked' : '';
    $fullChecked = $_POST['image_preview'] === 'full' ? 'checked' : '';
    $noneChecked = $_POST['image_preview'] === 'none' ? 'checked' : '';
    $thumbnailChecked = $_POST['image_preview'] === 'thumbnail' ? 'checked' : '';
} else {
    $halfChecked = 'checked';
    $fullChecked = '';
    $noneChecked = '';
    $thumbnailChecked = '';
}

?>

<form method='post' action='#result' enctype='multipart/form-data' id="form">
    <div>
        <label for="output-path">Répertoire de destination des images compressées</label>
        <div>
            <input type="text" name="output-path" id="output-path" value="<?= COMPRESSED_IMAGE_PATH ?>">
        </div>
    </div>
    <div>
        <label for="image_file">Fichiers à compresser</label>
        <div class="help" id="help">Nombre de fichiers maximum : <?= ini_get('max_file_uploads') ?></div>
        <div>
            <input type="file" name="image_file[]" id="image_file" accept=".jpg, .jpeg, .png, .webp" multiple>
        </div>
    </div>
    <div>
        <label for="quality">Qualité des images : <span id="quality-value">50%</span></label>
        <div class="range">
            <input type="range" id="quality" name="quality" min="0" max="100" value="50" step="5">
            <div class="quality-info">
                <div class="min">
                    <div>Poids et qualité</div>
                    <div>faibles</div>
                </div>
                <div class="max">
                    <div>Poids et qualité</div>
                    <div>élevés</div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class=" label">Prévisualisation des images
        </div>
        <div class="preview">
            <div>
                <div class="radio"><input type="radio" name="image_preview" id="image_preview_none"
                                          value="none" <?= $noneChecked ?>></div>
                <div><label for="image_preview_none">Pas de prévisualisation</label></div>
            </div>
            <div>
                <div class="radio"><input type="radio" name="image_preview" id="image_preview_thumbnail"
                                          value="thumbnail" <?= $thumbnailChecked ?>></div>
                <div><label for="image_preview_thumbnail">Miniature</label></div>
            </div>
            <div>
                <div class="radio"><input type="radio" name="image_preview" id="image_preview_half"
                                          value="half" <?= $halfChecked ?>></div>
                <div><label for="image_preview_half">Images côte à côte (plus hautes)</label></div>
            </div>
            <div>
                <div class="radio"><input type="radio" name="image_preview" id="image_preview_full"
                                          value="full" <?= $fullChecked ?>></div>
                <div><label for="image_preview_full">Images l'une au dessus sur l'autre (plus large)</label></div>
            </div>
        </div>
    </div>
    <div class="config-error" id="config-error"></div>
    <div id="result">
        <input type='submit' name='compress' value='Compresser les images' id="submit-button">
    </div>
</form>