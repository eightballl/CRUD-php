<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Zoom & Drag</title>
    <style>
        #hubble-container {
            width: 50%;
            height: auto;
            font-size: 0;
            border: 1px solid #111;
            overflow: hidden;
            margin: 0 auto;
            margin-top: 2rem;
            position: relative;
            cursor: grab;
        }

        #hubblepic {
            width: 100%;
            transition: transform 0.1s ease-out;
            transform-origin: center center;
            user-select: none;
        }

        #zoomer {
            display: block;
            width: 50%;
            margin: 2rem auto;
        }

        @media all and (max-width: 500px) {

            #zoomer,
            #hubble-container {
                width: 85%;
            }
        }
    </style>
</head>

<body>
    <base href="https://s3-us-west-2.amazonaws.com/s.cdpn.io/4273/">

    <div id="hubble-container">
        <img src="hubble-extreme-deep-field.jpg" id="hubblepic">
    </div>

    <input type="range" min="1" max="4" value="1" step="0.1" id="zoomer" oninput="deepdive()">

    <script>
        const zoomer = document.getElementById('zoomer');
        const hubblepic = document.getElementById('hubblepic');
        const container = document.getElementById('hubble-container');

        let zoomLevel = 1;
        let isDragging = false;
        let startX, startY, moveX = 0,
            moveY = 0;

        function deepdive() {
            zoomLevel = zoomer.valueAsNumber;
            updateTransform();
        }

        function updateTransform() {
            hubblepic.style.transform = `scale(${zoomLevel}) translate(${moveX / zoomLevel}px, ${moveY / zoomLevel}px)`;
        }

        container.addEventListener('mousedown', e => {
            if (zoomLevel <= 1) return; // tidak perlu drag kalau belum di-zoom
            isDragging = true;
            container.style.cursor = 'grabbing';
            startX = e.clientX - moveX;
            startY = e.clientY - moveY;
        });

        container.addEventListener('mouseup', () => {
            isDragging = false;
            container.style.cursor = 'grab';
        });

        container.addEventListener('mouseleave', () => {
            isDragging = false;
            container.style.cursor = 'grab';
        });

        container.addEventListener('mousemove', e => {
            if (!isDragging) return;
            moveX = e.clientX - startX;
            moveY = e.clientY - startY;
            updateTransform();
        });

        // Support sentuhan di HP
        container.addEventListener('touchstart', e => {
            if (zoomLevel <= 1) return;
            isDragging = true;
            startX = e.touches[0].clientX - moveX;
            startY = e.touches[0].clientY - moveY;
        });

        container.addEventListener('touchmove', e => {
            if (!isDragging) return;
            moveX = e.touches[0].clientX - startX;
            moveY = e.touches[0].clientY - startY;
            updateTransform();
        });

        container.addEventListener('touchend', () => {
            isDragging = false;
        });
    </script>
</body>

</html>