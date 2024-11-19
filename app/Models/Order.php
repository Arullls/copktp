<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Menambahkan kolom-kolom yang dapat diisi secara massal
    protected $fillable = [
        'name',         // Nama pemesan
        'email',        // Email pemesan
        'front_photo',  // Foto depan KTP
    ];

    // Jika kamu membutuhkan pengaturan lain, kamu bisa menambahkannya di sini
}
