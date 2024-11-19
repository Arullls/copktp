<form action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label>Nama:</label>
    <input type="text" name="name" required>
    <label>Email:</label>
    <input type="email" name="email" required>
    <label>Upload Foto Depan KTP:</label>
    <input type="file" name="front_photo" accept="image/*" required>
    <button type="submit">Submit</button>
</form>
