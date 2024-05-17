<style>
    #dropArea {
        border: 2px dashed #007bff;
        border-radius: 5px;
        padding: 20px;
        text-align: center;
        color: #007bff;
        margin: 10px;
        cursor: pointer;
    }

    #preview {
        margin-top: 20px;
    }

    .file-preview {
        border: 1px solid #ddd;
        margin: 10px;
        padding: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: #f4f4f4;
        border-radius: 4px;
    }

    .file-icon {
        width: 30px;
        height: 30px;
        background-color: #999;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        color: white;
    }

    .thumbnail {
        max-width: 100px;
        max-height: 100px;
    }

    .delete-btn {
        margin-left: auto;
        cursor: pointer;
        color: red;
        border: none;
        background: none;
    }
</style>


<div id="dropArea">Haz clic o arrastra un archivo aquí</div>
<input type="file" id="fileInput" onchange="handleFile(this.files[0])" wire:model='fileI' accept="image/*,.pdf">
{{-- <button onclick="uploadFile()">Subir Archivo</button> --}}
<div id="preview"></div>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    let file;

    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('fileInput');
    const previewContainer = document.getElementById('preview');

    dropArea.addEventListener('click', function() {
        fileInput.click();
    });

    dropArea.addEventListener('dragover', (event) => {
        event.stopPropagation();
        event.preventDefault();
        event.dataTransfer.dropEffect = 'copy';
    });

    dropArea.addEventListener('drop', (event) => {
        event.stopPropagation();
        event.preventDefault();
        file = event.dataTransfer.files[0];
        fileInput.file = file;


        var formData = new FormData();
        formData.append('file', file); // 'file' es el nombre del campo en tu servidor
        fetch('/uploadfileinput', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content') // Asegúrate de tener un meta tag con el CSRF token
                },
                data: formData,
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                // Si es necesario, emite un evento a Livewire después de la carga
                Livewire.emit('fileUploaded', data.fileId);
            })
            .catch(error => console.error('Error:', error));

        handleFile(file);
    });

    // function handleFile(selectedFile) {
    //     file = selectedFile;
    //     previewFile();
    // }

    function handleFile(selectedFile) {
        if (!selectedFile.type.startsWith('image/') && selectedFile.type !== 'application/pdf') {
            // Agrega más tipos de archivos compatibles según sea necesario
            Livewire.emit('error', 'Archivo no soportado.');
            return;
        }

        file = selectedFile;
        console.log(file);
        previewFile();
    }

    function previewFile() {
        if (!file) {

            return;
        }

        const preview = document.createElement('div');
        preview.classList.add('file-preview');
        const icon = document.createElement('div');
        icon.classList.add('file-icon');
        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.classList.add('thumbnail');
            img.src = URL.createObjectURL(file);
            preview.appendChild(img);
        } else {
            icon.innerText = '📄';
            preview.appendChild(icon);
        }
        const fileName = document.createElement('p');
        fileName.textContent = file.name;
        const deleteBtn = document.createElement('button');
        deleteBtn.classList.add('delete-btn');
        deleteBtn.innerText = 'X';
        deleteBtn.onclick = function() {
            removePreview(preview);
        };
        preview.appendChild(fileName);
        preview.appendChild(deleteBtn);

        // Borra la previsualización anterior (si existe)
        const existingPreview = previewContainer.querySelector('.file-preview');
        if (existingPreview) {
            existingPreview.remove();
        }

        previewContainer.appendChild(preview);
    }

    function removePreview(previewElement) {
        Livewire.emit('resetFile');
        previewElement.remove();
        file = null; // Elimina el archivo seleccionado
    }

    function uploadFile() {
        if (!file) {
            alert('Por favor, selecciona un archivo.');
            return;
        }

        const formData = new FormData();
        formData.append('file', file);

        fetch('upload.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                alert('Archivo subido correctamente.');
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
</script>
