<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yaza\LaravelGoogleDriveStorage\Gdrive;

class GDriveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function upload()
    {
        $filepath = public_path() . '/' . 'file.jpg';
        $filename = 'assets/file.jpg';
        Storage::disk('google')->put($filename, File::get($filepath));

        // // Dapatkan URL penyimpanan dari Google Drive
        // // $url = Storage::disk('google')->url($filename);
        // // return response()->json(['url' => $url, 'Success' => true]);
        // return response()->json(['Succes' => true]);


        // // // Kirim variabel $url ke tampilan qrcode.blade.php
        // // return view('qrcode', ['url' => $url]);
        // $request->merge(['url' => $url]);
        // return view('qrcode');
    }

    public function getFile(Request $request)
    {
        $data = Gdrive::get('assets/file.jpg');
        $url = $data->file; // Simpan URL dalam variabel $url
        return response($url);

    }
}
