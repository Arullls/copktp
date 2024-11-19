<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Foto Copy KTP</title>
</head>
<body>
    <h1>Upload Foto Depan KTP</h1>
    <form action="{{ route('ktp.process') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label>Upload Foto Depan KTP:</label>
        <input type="file" name="ktp_front" required>
        <button type="submit">Proses KTP</button>
    </form>
</body>
</html>
