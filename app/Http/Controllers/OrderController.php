<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class OrderController extends Controller
{
    // Menampilkan form upload
    public function create()
    {
        return view('order.create');
    }

    // Menyimpan data pesanan
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'front_photo' => 'required|image|mimes:jpeg,png|max:2048',
        ]);

        // Ambil foto depan yang di-upload
        $frontPhoto = $request->file('front_photo');

        // Tentukan path file foto depan
        $frontPhotoPath = $frontPhoto->store('ktp_photos', 'public');

        // Menggunakan Intervention Image untuk memanipulasi gambar
        $image = Image::make(storage_path('app/public/' . $frontPhotoPath));

        // Rotasi otomatis berdasarkan EXIF metadata
        $image->orientate(); // Menyesuaikan rotasi otomatis sesuai metadata EXIF

        // Jika gambar lebih tinggi dari lebar (portrait), putar 90 derajat
        if ($image->height() > $image->width()) {
            $image->rotate(-90); // Sesuaikan sudut rotasi jika diperlukan
        }

        // Simpan gambar yang telah diperbaiki
        $image->save(storage_path('app/public/' . $frontPhotoPath));

        // Simpan data pesanan ke database
        $order = Order::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'front_photo' => $frontPhotoPath,
        ]);

        return redirect()->route('order.preview', ['order' => $order->id]);
    }

    // Menampilkan pratinjau hasil cetakan
    public function preview(Order $order)
    {
        $backPhotoPath = '/images/ktp_back_template.jpg'; // Lokasi foto belakang default
        return view('order.preview', compact('order', 'backPhotoPath'));
    }

    // Generate PDF untuk cetak
    public function generatePDF(Request $request, Order $order)
    {
        $backPhotoPath = public_path('images/ktp_back_template.jpg'); // Lokasi foto belakang
        $frontPhotoPath = storage_path('app/public/' . $order->front_photo);

        // Buat salinan sementara untuk memproses gambar depan
        $tempFrontPath = storage_path('app/public/temp_front_photo.jpg');
        $image = Image::make($frontPhotoPath);

        // Terapkan rotasi
        $rotation = $request->input('front_rotation', 0);
        if ($rotation) {
            $image->rotate(-$rotation); // Rotate counterclockwise sesuai input
        }

        // Terapkan flip
        $flip = $request->input('front_flip', 'none');
        if ($flip === 'horizontal') {
            $image->flip('h');
        } elseif ($flip === 'vertical') {
            $image->flip('v');
        }

        // Simpan hasil transformasi sementara
        $image->save($tempFrontPath);

        // Gunakan path sementara untuk PDF
        $frontPhotoPath = $tempFrontPath;

        $pdf = app('dompdf.wrapper');

        // Menambahkan layout untuk dua gambar KTP (depan dan belakang)
        $pdf->loadView('order.pdf', compact('frontPhotoPath', 'backPhotoPath'));

        // Menggunakan stream() untuk menampilkan PDF langsung di browser
        return $pdf->stream('fotokopi_ktp_' . $order->name . '.pdf');
    }
}
