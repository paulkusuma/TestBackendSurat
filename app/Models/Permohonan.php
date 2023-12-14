<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Permohonan extends Model
{
    use HasFactory;
    protected $table = 'permohonans';
    protected $fillable = [

        'name',
        'nim',
        'semester',
        'isiSurat',
        'pilihanSurat',
        'pilihanProdi',
        'nomorTelepon',
    ];
    protected $attributes = [
        'status' => 'Menunggu Tanda Tangan',
    ];
}
