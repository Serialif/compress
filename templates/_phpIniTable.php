<?php

?>

<div class="table">
    <table>
        <thead>
            <tr>
                <th colspan="3">Limitations du fichier php.ini<sup>*</sup></th>
            </tr>
            <tr>
                <th>Paramètre</th>
                <th>Description</th>
                <th>Valeur</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td>Chemin du fichier php.ini</td>
                <td><?= php_ini_loaded_file() ?></td>
            </tr>
            <tr>
                <td>Extension GD<sup>**</sup></td>
                <td>Pour générer directement des images</td>
                <td id="gd_extension" data-value="<?= extension_loaded('gd') ? 'y' : 'n' ?>">
                    <?= extension_loaded('gd')
                        ? '<span class="success">Activée</span>'
                        : '<span class="danger">Désactivée</span>' ?></td>
            </tr>
            <tr>
                <td>file_uploads<sup>***</sup></td>
                <td>Autorise ou non le chargement de fichiers par HTTP</td>
                <td id="file_uploads" data-value="<?= ini_get('file_uploads') ? 'y' : 'n' ?>">
                    <?= ini_get('file_uploads')
                        ? '<span class="notranslate success">On</span>'
                        : '<span class="notranslate danger">Off</span>' ?></td>
            </tr>
            <tr>
                <td>max_file_uploads</td>
                <td>Nombre maximum de fichiers pouvant être envoyés simultanément</td>
                <td id="max_file_uploads" data-value="<?= ini_get('max_file_uploads') ?>">
                    <?= ini_get('max_file_uploads') ?></td>
            </tr>
            <tr>
                <td>post_max_size</td>
                <td>Taille maximale des données reçues par la méthode POST</td>
                <td id="post_max_size" data-value="<?= ini_get('post_max_size') ?>">
                    <?= ini_get('post_max_size') ?></td>
            </tr>
            <tr>
                <td>upload_max_filesize</td>
                <td>Taille maximale d'un fichier à charger</td>
                <td id="upload_max_filesize" data-value="<?= ini_get('upload_max_filesize') ?>">
                    <?= ini_get('upload_max_filesize') ?></td>
            </tr>
        </tbody>
    </table>
    <p><sup>*</sup> vous pouvez modifier ces valeurs, directement dans le fichier, selon vos besoins</p>
    <p><sup>**</sup> l'extension GD (gd2) doit obligatoirement être activée</p>
    <p><sup>***</sup> file_uploads doit obligatoirement être à : On</p>
</div>