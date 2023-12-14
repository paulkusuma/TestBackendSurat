<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Permohonan;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PermohonanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     //
    // }


    public function permohonan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:255',
            'nomorTelepon' => 'required',
            'pilihanProdi' => 'required',
            'pilihanSurat' => 'required',
            'isiSurat' => 'required',
            'semester' => '',
            'cuti' => 'string',
            'status' => '',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // $user = User::find('name', $request->name)->firstOrFail();

        $permohonan = Permohonan::create([

            'name' => $request->name,
            'nim' => $request->nim,
            'nomorTelepon' => $request->nomorTelepon,
            'pilihanProdi' => $request->pilihanProdi,
            'pilihanSurat' => $request->pilihanSurat,
            'semester' => $request->semester,
            'cuti' => $request->cuti,
            'isiSurat' => $request->isiSurat,
            'status' => $request->status
        ]);



        if ($permohonan) {
            return response()->json([
                'message' => 'Sukses',
                'data' => $permohonan,
            ], 201);
        } else {
            return response()->json([
                'message' => 'Gagal mengirim permohonan. Silakan coba lagi.'
            ], 500);
        }
    }

    // GET
    public function getAllPermohonan()
    {
        // Get all permohonan records
        $permohonans = Permohonan::all();

        // Return the data as JSON
        return response()->json([
            'data' => $permohonans,
        ], 200);
    }

    /**
     * Retrieve a specific permohonan record by ID.
     *
     * @param int $id
     */
    public function getPermohonanById($id)
    {
        // Find permohonan record by ID
        $permohonan = Permohonan::find($id);

        if (!$permohonan) {
            return response()->json([
                'message' => 'Permohonan not found',
            ], 404);
        }

        // Return the data as JSON
        return response()->json([
            'data' => $permohonan,
        ], 200);
    }

    public function approvPermohonan(Request $request)
    {

        $permohonan = Permohonan::find($request->only('id'))->first();
        if (!$permohonan) {
            return response()->json([
                'message' => 'Permohonan not found',
            ], 404);
        }
        $permohonan->status = 'Berhasil';
        $permohonan->save();
        self::kirimPesanWA($permohonan['nomorTelepon'], $permohonan['name'], $permohonan['status'], $permohonan['pilihanSurat']);
        $document = self::generate($request);

        return response()->json([
            'data' => $permohonan,
        ], 200);

    }

    public function rejectPermohonan(Request $request)
    {

        $permohonan = Permohonan::find($request->only('id'))->first();
        if (!$permohonan) {
            return response()->json([
                'message' => 'Permohonan not found',
            ], 404);
        }
        $permohonan->status = 'Ditolak';
        $permohonan->save();
        self::kirimPesanWA($permohonan['nomorTelepon'], $permohonan['name'], $permohonan['status'], $permohonan['pilihanSurat']);


        return response()->json([
            'data' => $permohonan,
        ], 200);

    }

    public function generate(Request $request)
    {
        $permohonan = Permohonan::find($request->only('id'))->first();


        if ($permohonan['pilihanSurat'] == 'Surat Berhenti Studi') {
            $file = Storage::disk('local')->get('public/SuratRTF/SuratBerhentiStudi.rtf');
            $document = str_replace("#NAMA", $permohonan['name'], $file);
            $document = str_replace("#NIM", $permohonan['nim'], $document);
            $document = str_replace("#PROSTU", $permohonan['pilihanProdi'], $document);
            $document = str_replace("#SEM", $permohonan['semester'], $document);
        } elseif ($permohonan['pilihanSurat'] == 'Surat Cuti') {
            $file = Storage::disk('local')->get('public/SuratRTF/SuratCuti.rtf');
            $document = str_replace("#NAMA", $permohonan['name'], $file);
            $document = str_replace("#NIM", $permohonan['nim'], $document);
            $document = str_replace("#PROGSTU", $permohonan['pilihanProdi'], $document);
            $document = str_replace("#SEMES", $permohonan['semester'], $document);
            $document = str_replace("#CUTI", $permohonan['cuti'], $document);
            $document = str_replace("#ISI", $permohonan['isiSurat'], $document);
        } elseif ($permohonan['pilihanSurat'] == 'Surat Aktif Studi') {
            $file = Storage::disk('local')->get('public/SuratRTF/SuratAktifStudi.rtf');
            $document = str_replace("#NAMA", $permohonan['name'], $file);
            $document = str_replace("#NIM", $permohonan['nim'], $document);
            $document = str_replace("#PROGRAMST", $permohonan['pilihanProdi'], $document);
            $document = str_replace("#SEMEST", $permohonan['semester'], $document);
        }

        $document = str_replace("#ISI", $permohonan['isi'], $document);
        $headers = [
            "Content-type" => "application/msword",
            "Content-length" => strlen($document),
        ];
        if ($permohonan['pilihan'] == 'Surat Berhenti Studi') {
            $headers["Content-disposition"] = "inline; filename=suratBerhentiStudi.doc";
        } elseif ($permohonan['pilihan'] == 'Surat Cuti') {
            $headers["Content-disposition"] = "inline; filename=suratCuti.doc";
        } elseif ($permohonan['pilihan'] == 'Surat Aktif Studi') {
            $headers["Content-disposition"] = "inline; filename=suratAktif.doc";
        }
        $file = 'assets/' . $permohonan['name'] . $permohonan['pilihanSurat'] . Str::random(5) . '.doc';
        Storage::disk('google')->put($file, $document);
        return response($document, headers: $headers);

    }

    function kirimPesanWA($nomor, $nama, $status, $jenis)
    {
        // Mengirim nomor telepon ke API setelah menghasilkan surat
        $curl = curl_init();

        // Pesan default
        $pesan = 'Halo ' . $nama . ', Surat sedang diurus. Status: ' . $status;

        // Menyesuaikan pesan berdasarkan jenis
        if ($jenis === 'disetujui') {
            $pesan = 'Halo ' . $nama . ', Surat Anda telah disetujui. Status: ' . $status;
        } elseif ($jenis === 'ditolak') {
            $pesan = 'Halo ' . $nama . ', Mohon maaf, surat Anda ditolak. Status: ' . $status;
        }
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $nomor,
                'message' => $pesan
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: #!sUwuAe_f7DfW+XBsp2'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
    }

}
