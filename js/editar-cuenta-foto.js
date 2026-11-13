(() => {
    const form = document.getElementById('foto-form');
    const fileInput = document.getElementById('foto');
    const editor = document.getElementById('foto-editor');
    const canvas = document.getElementById('foto-canvas');
    const zoomControl = document.getElementById('foto-zoom');
    const status = document.getElementById('foto-status');
    const submitButton = form?.querySelector('button[type="submit"]');

    if (!form || !fileInput || !editor || !canvas || !zoomControl || !status || !submitButton) {
        return;
    }

    const context = canvas.getContext('2d');
    const maxFileSize = Number(fileInput.dataset.maxSize) || 2 * 1024 * 1024;
    const maxFileSizeLabel = fileInput.dataset.maxSizeLabel || '2 MB';
    if (!context) {
        status.textContent = 'No se pudo iniciar la previsualización de la foto.';
        return;
    }

    const image = new Image();
    let imageReady = false;
    let cropX = 0;
    let cropY = 0;
    let cropSize = 0;
    let objectUrl = '';
    let dragging = false;
    let lastPointerX = 0;
    let lastPointerY = 0;

    const clampCrop = () => {
        cropX = Math.max(0, Math.min(cropX, image.naturalWidth - cropSize));
        cropY = Math.max(0, Math.min(cropY, image.naturalHeight - cropSize));
    };

    const drawPreview = () => {
        if (!imageReady) {
            return;
        }

        context.clearRect(0, 0, canvas.width, canvas.height);
        context.drawImage(
            image,
            cropX,
            cropY,
            cropSize,
            cropSize,
            0,
            0,
            canvas.width,
            canvas.height
        );
    };

    const setZoom = () => {
        if (!imageReady) {
            return;
        }

        const centerX = cropX + cropSize / 2;
        const centerY = cropY + cropSize / 2;
        cropSize = Math.min(image.naturalWidth, image.naturalHeight) / Number(zoomControl.value);
        cropX = centerX - cropSize / 2;
        cropY = centerY - cropSize / 2;
        clampCrop();
        drawPreview();
    };

    fileInput.addEventListener('change', () => {
        const file = fileInput.files?.[0];
        imageReady = false;
        editor.hidden = true;
        status.textContent = '';

        if (!file) {
            return;
        }

        if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
            fileInput.value = '';
            status.textContent = 'Selecciona una imagen JPG, PNG o WEBP.';
            return;
        }

        if (file.size > maxFileSize) {
            fileInput.value = '';
            status.textContent = `La foto debe pesar como máximo ${maxFileSizeLabel}.`;
            return;
        }

        if (objectUrl) {
            URL.revokeObjectURL(objectUrl);
        }
        objectUrl = URL.createObjectURL(file);
        image.onload = () => {
            imageReady = true;
            zoomControl.value = '1';
            cropSize = Math.min(image.naturalWidth, image.naturalHeight);
            cropX = (image.naturalWidth - cropSize) / 2;
            cropY = (image.naturalHeight - cropSize) / 2;
            editor.hidden = false;
            status.textContent = 'Previsualiza y ajusta el recorte antes de guardar.';
            drawPreview();
        };
        image.onerror = () => {
            fileInput.value = '';
            status.textContent = 'No se pudo leer la imagen. Selecciona un archivo JPG, PNG o WEBP válido.';
        };
        image.src = objectUrl;
    });

    zoomControl.addEventListener('input', setZoom);

    canvas.addEventListener('pointerdown', event => {
        if (!imageReady) {
            return;
        }
        dragging = true;
        lastPointerX = event.clientX;
        lastPointerY = event.clientY;
        canvas.setPointerCapture(event.pointerId);
    });

    canvas.addEventListener('pointermove', event => {
        if (!dragging || !imageReady) {
            return;
        }
        const bounds = canvas.getBoundingClientRect();
        const sourcePixelsPerScreenPixel = cropSize / bounds.width;
        cropX -= (event.clientX - lastPointerX) * sourcePixelsPerScreenPixel;
        cropY -= (event.clientY - lastPointerY) * sourcePixelsPerScreenPixel;
        lastPointerX = event.clientX;
        lastPointerY = event.clientY;
        clampCrop();
        drawPreview();
    });

    const stopDragging = () => {
        dragging = false;
    };
    canvas.addEventListener('pointerup', stopDragging);
    canvas.addEventListener('pointercancel', stopDragging);

    form.addEventListener('submit', event => {
        if (!imageReady) {
            return;
        }

        event.preventDefault();
        submitButton.disabled = true;
        status.textContent = 'Preparando la foto...';

        const croppedCanvas = document.createElement('canvas');
        croppedCanvas.width = 512;
        croppedCanvas.height = 512;
        const croppedContext = croppedCanvas.getContext('2d');
        if (!croppedContext) {
            submitButton.disabled = false;
            status.textContent = 'No se pudo preparar la foto. Intenta nuevamente.';
            return;
        }

        croppedContext.drawImage(
            image,
            cropX,
            cropY,
            cropSize,
            cropSize,
            0,
            0,
            croppedCanvas.width,
            croppedCanvas.height
        );

        croppedCanvas.toBlob(blob => {
            if (!blob) {
                submitButton.disabled = false;
                status.textContent = 'No se pudo preparar la foto. Intenta nuevamente.';
                return;
            }

            try {
                const croppedFile = new File([blob], 'foto-perfil.jpg', { type: 'image/jpeg' });
                const transfer = new DataTransfer();
                transfer.items.add(croppedFile);
                fileInput.files = transfer.files;
                form.submit();
            } catch {
                submitButton.disabled = false;
                status.textContent = 'Tu navegador no permite recortar la foto. Intenta con otro navegador.';
            }
        }, 'image/jpeg', 0.92);
    });

    window.addEventListener('pagehide', () => {
        if (objectUrl) {
            URL.revokeObjectURL(objectUrl);
        }
    });
})();
