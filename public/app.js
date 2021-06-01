document.getElementById("quality").addEventListener("input", function (event) {
    document.getElementById("quality-value").innerHTML = document.getElementById("quality").value + '%'
});

const submitButton = document.getElementById('submit-button')
submitButton.disabled = true
submitButton.style.background = '#EEEEEE'
submitButton.style.color = '#aaa'
submitButton.style.cursor = 'no-drop'


const imageFile = document.getElementById('image_file')
const maxFileUploads = document.getElementById('max_file_uploads')
const postMaxSize = document.getElementById('post_max_size')
const uploadMaxFilesize = document.getElementById('upload_max_filesize')

window.addEventListener('load', () => {
    let valid = true
    if (document.getElementById('file_uploads').dataset.value === 'n') {
        const text = 'L\'option [file-uploads] doit être à "On" (voir fichier php.ini)'
        setConfigError(text)
        imageFile.disabled = true;
        imageFile.style.cursor = 'no-drop';
    }

    if (document.getElementById('gd_extension').dataset.value === 'n') {
        const text = 'L\'extension GD doit être activée (voir fichier php.ini)'
        setConfigError(text)
        imageFile.disabled = true;
        imageFile.style.cursor = 'no-drop';
    }


    imageFile.addEventListener('change', function (e) {
        document.getElementById('config-error').style.display = 'none'
        valid = true

        let totalSize = 0;
        for (let i = 0; i < e.target.files.length; i++) {
            const size = e.target.files[i].size
            if (size > convertSizeToBytes(uploadMaxFilesize.dataset.value)) {
                const text = 'Le poids du fichier "' + e.target.files[i].name
                    + '" (' + convertBytesToSize(size) + ') est supérieure au poids maximum autorisé dans le fichier ' +
                    'php.ini : ' + uploadMaxFilesize.dataset.value + ' (upload_max_filesize)'
                setConfigError(text)
            }
            totalSize += size
        }

        if (totalSize > convertSizeToBytes(postMaxSize.dataset.value)) {
            const text = 'Le poids total des fichiers (' + convertBytesToSize(totalSize) + ') est supérieure au poids ' +
                'maximum autorisé dans le fichier php.ini : ' + postMaxSize.dataset.value + ' (post_max_size)'
            setConfigError(text)
        }

        if ((e.target.files.length > maxFileUploads.dataset.value)) {
            const text = 'Il y a ' + e.target.files.length + ' fichiers sélectionnés' +
                ', alors que le nombre de fichiers maximum autorisés est ' + maxFileUploads.dataset.value
                + ' dans le fichier php.ini (max_file_uploads)'
            setConfigError(text)
        }

        if (valid) {
            submitButton.disabled = false
            submitButton.style.background = '#0e4c00dd'
            submitButton.style.color = '#fff'
            submitButton.style.cursor = 'pointer'
        }

        document.getElementById('completed').style.display = 'none'

        function convertSizeToBytes(size) {
            const lastChar = size.substr(size.length - 1)
            size = parseInt(size, 10)
            if (lastChar === 'k' || lastChar === 'K') {
                return size * 1024
            } else if (lastChar === 'm' || lastChar === 'M') {
                return size * 1024 * 1024
            } else if (lastChar === 'g' || lastChar === 'G') {
                return size * 1024 * 1024 * 1024
            } else {
                return size
            }
        }

        function convertBytesToSize(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            let k = 1024,
                // dm = decimals || 2,
                sizes = ['Bytes', 'K', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y'],
                i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(decimals)) + ' ' + sizes[i];
        }
    })

    function setConfigError(text) {
        const configError = document.getElementById('config-error')
        configError.style.background = '#e30000'
        configError.style.color = '#fff'
        configError.innerHTML = 'ERREUR<br>' + text
        configError.style.display = 'block'
        valid = false
    }
})

