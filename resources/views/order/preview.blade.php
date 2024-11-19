<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pratinjau Cetakan</title>
    <style>
        /* Ukuran halaman cetak A4 */
        @page {
            size: A4;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f8f8;
            font-family: 'Arial', sans-serif;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            text-align: center;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
        }

        p {
            color: #777;
            font-size: 16px;
        }

        .a4 {
            width: 210mm;
            height: 296mm;
            background: white;
            position: relative;
            margin: 20px auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .ktp-container img {
            position: absolute;
            width: 80mm; /* Ukuran KTP */
            cursor: grab;
            transition: transform 0.2s ease; /* Smooth transition for rotation */
        }

        .controls {
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            margin: 5px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        .btn-primary {
            background-color: #3498db;
            color: white;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }

        .btn-secondary {
            background-color: #95a5a6;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
        }

        input[type="file"] {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-top: 10px;
        }

        .ktp-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            gap: 20px;
        }

        /* Hanya tampilkan elemen A4 saat cetak */
        @media print {
            body {
                background: none;
            }

            .container, .controls {
                display: none;
            }

            .a4 {
                margin: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pratinjau Cetakan</h1>
        <p>Pastikan gambar terlihat rapi sebelum mencetak.</p>

        <!-- Form untuk meng-upload foto depan -->
        <div>
            <label for="front-photo-upload">Pilih Foto Depan KTP:</label>
            <input type="file" id="front-photo-upload" accept="image/*">
            <button class="btn btn-danger" id="remove-front-photo">Hapus Foto Depan</button>
            <!-- Tombol Rotasi untuk Foto Depan -->
            <button class="btn btn-primary" id="rotate-front-photo">Putar Depan</button>
        </div>

        <!-- Form untuk meng-upload foto belakang -->
        <div>
            <label for="back-photo-upload">Pilih Foto Belakang KTP:</label>
            <input type="file" id="back-photo-upload" accept="image/*">
            <button class="btn btn-danger" id="remove-back-photo">Hapus Foto Belakang</button>
            <!-- Tombol Rotasi untuk Foto Belakang -->
            <button class="btn btn-primary" id="rotate-back-photo">Putar Belakang</button>
        </div>

        <div class="controls">
            <button class="btn btn-secondary" onclick="resetPosition()">Reset Posisi</button>
            <button class="btn btn-primary" onclick="window.print()">Cetak</button>
        </div>
    </div>

    <!-- Area untuk mencetak -->
    <div class="a4">
        <div class="ktp-container">
            <!-- Foto KTP depan -->
            <img id="front-photo" src="{{ asset('storage/' . $order->front_photo) }}" alt="Foto Depan" style="top: 50px; left: 200px;">
            <!-- Foto KTP belakang -->
            <img id="back-photo" src="{{ asset($backPhotoPath) }}" alt="" style="top: 300px; left: 200px;">
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
    <script>
        // Fungsi untuk membuat elemen bisa di-drag dan resize
        function makeDraggableAndResizable(element) {
            let offsetX = 0, offsetY = 0;

            element.addEventListener('mousedown', (e) => {
                offsetX = e.clientX - element.offsetLeft;
                offsetY = e.clientY - element.offsetTop;
                element.style.cursor = 'grabbing';

                document.addEventListener('mousemove', moveElement);
                document.addEventListener('mouseup', stopDrag);
            });

            const moveElement = (e) => {
                const canvasRect = document.querySelector('.a4').getBoundingClientRect();

                // Hitung posisi baru dengan batas halaman A4
                let newX = Math.min(Math.max(e.clientX - offsetX, 0), canvasRect.width - element.offsetWidth);
                let newY = Math.min(Math.max(e.clientY - offsetY, 0), canvasRect.height - element.offsetHeight);

                // Terapkan posisi ke elemen
                element.style.left = `${newX}px`;
                element.style.top = `${newY}px`;
            };

            const stopDrag = () => {
                document.removeEventListener('mousemove', moveElement);
                document.removeEventListener('mouseup', stopDrag);
                element.style.cursor = 'grab';
            };

            interact(element)
                .resizable({
                    edges: { left: true, right: true, bottom: true, top: true },
                    onmove(event) {
                        let { width, height } = event.rect;

                        // Menyesuaikan ukuran gambar sesuai dengan pergerakan resize
                        event.target.style.width = `${width}px`;
                        event.target.style.height = `${height}px`;
                    }
                })
                .draggable({
                    onmove(event) {
                        const target = event.target;
                        const x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
                        const y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

                        // Gabungkan transformasi translate dan rotasi
                        target.style.transform = `translate(${x}px, ${y}px) rotate(${target.getAttribute('data-angle')}deg)`;
                        target.setAttribute('data-x', x);
                        target.setAttribute('data-y', y);
                    }
                });
        }

        // Terapkan fungsi drag dan resize ke elemen gambar
        const frontPhoto = document.getElementById('front-photo');
        const backPhoto = document.getElementById('back-photo');
        makeDraggableAndResizable(frontPhoto);
        makeDraggableAndResizable(backPhoto);

        // Fungsi untuk rotasi menggunakan tombol
        let frontPhotoAngle = 0;
        let backPhotoAngle = 0;

        // Putar foto depan 90 derajat
        document.getElementById('rotate-front-photo').addEventListener('click', function() {
            frontPhotoAngle += 90;
            frontPhoto.style.transform = `rotate(${frontPhotoAngle}deg)`;
            frontPhoto.setAttribute('data-angle', frontPhotoAngle); // Menyimpan angle untuk interact.js
        });

        // Putar foto belakang 90 derajat
        document.getElementById('rotate-back-photo').addEventListener('click', function() {
            backPhotoAngle += 90;
            backPhoto.style.transform = `rotate(${backPhotoAngle}deg)`;
            backPhoto.setAttribute('data-angle', backPhotoAngle); // Menyimpan angle untuk interact.js
        });

        // Fungsi mengganti foto depan KTP
        document.getElementById('front-photo-upload').addEventListener('change', function(event) {
            const reader = new FileReader();
            reader.onload = function(e) {
                frontPhoto.src = e.target.result; // Ganti dengan foto yang di-upload
            }
            reader.readAsDataURL(event.target.files[0]);
        });

        // Fungsi mengganti foto belakang KTP
        document.getElementById('back-photo-upload').addEventListener('change', function(event) {
            const reader = new FileReader();
            reader.onload = function(e) {
                backPhoto.src = e.target.result; // Ganti dengan foto yang di-upload
            }
            reader.readAsDataURL(event.target.files[0]);
        });

        // Fungsi untuk menghapus foto depan
        document.getElementById('remove-front-photo').addEventListener('click', function() {
            frontPhoto.src = ""; // Hapus foto
        });

        // Fungsi untuk menghapus foto belakang
        document.getElementById('remove-back-photo').addEventListener('click', function() {
            backPhoto.src = ""; // Hapus foto
        });

        // Fungsi reset posisi
        function resetPosition() {
            frontPhoto.style.top = "50px";
            frontPhoto.style.left = "65px";
            backPhoto.style.top = "300px";
            backPhoto.style.left = "65px";
        }
    </script>
</body>
</html>
