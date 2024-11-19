<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fotokopi KTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .ktp-container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }
        .ktp-container img {
            width: 45%;
            height: auto;
            border: 1px solid #000;
        }
        .ktp-title {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="ktp-title">
        <h1>Fotokopi KTP</h1>
    </div>

    <div class="ktp-container">
        <!-- Foto Depan KTP -->
        <div>
            <h3>Foto Depan</h3>
            <img src="{{ $frontPhotoPath }}" alt="Foto Depan KTP">
        </div>
        <!-- Foto Belakang KTP -->
        <div>
            <h3>Foto Belakang</h3>
            <img src="{{ $backPhotoPath }}" alt="Foto Belakang KTP">
        </div>
    </div>

</body>
</html>
