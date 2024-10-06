<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Resources\BeritaResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreBeritaRequest;
use App\Http\Requests\UpdateBeritaRequest;

class BeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $berita = Berita::all();

        return BeritaResource::collection($berita);
    }
    public function getImage($id)
    {
        // $path = storage_path('app/public/gambar-berita/' . $request->input('gambar'));

        // if (!File::exists($path)) {
        //     return response()->json(['error' => 'File not found'], 404);
        // }

        // $file = File::get($path);
        // $type = File::mimeType($path);

        // return response($file, 200)->header("Content-Type", $type);
        try {
            // Cari berita berdasarkan ID
            $berita = Berita::findOrFail($id);

            // Ambil nama file gambar dari atribut 'gambar'
            $filename = $berita->gambar;
            $path = storage_path('app/public/gambar-berita/' . $filename);

            // Cek apakah file gambar ada
            if (!File::exists($path)) {
                return response()->json(['error' => 'File not found'], 404);
            }

            // Ambil file gambar dan MIME type
            $file = File::get($path);
            $type = File::mimeType($path);

            // Kembalikan file sebagai response dengan MIME type yang sesuai
            return response($file, 200)->header("Content-Type", $type);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil gambar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBeritaRequest $request)
    {
        try {
            $filename = null;
            if ($request->hasFile('gambar')) {
                $foto = $request->file('gambar');
                $filename = date('Y-m-d') . '-' . $foto->getClientOriginalName();
                $path = 'gambar-berita/' . $filename;
                Storage::disk('public')->put($path, file_get_contents($foto));
            }

            // Mengganti input 'gambar' dengan filename di request
            $data = $request->all();
            $data['gambar'] = $filename; // Set 'gambar' menjadi nama file

            $berita = Berita::create($data);

            return response()->json([
                'message' => 'Data Berhasil ditambahkan'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Terjadi Kesalahan: " . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // try {
        //     // Mencari berita berdasarkan ID
        //     $berita = Berita::findOrFail($id);

        //     // Mengembalikan resource dalam format json
        //     return new BeritaResource($berita);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'message' => 'Berita tidak ditemukan: ' . $e->getMessage()
        //     ], 404);
        // }


        try {
            // Mencari berita berdasarkan ID
            $berita = Berita::findOrFail($id);

            // Mengembalikan resource dalam format json, termasuk URL gambar
            return response()->json([
                'id' => $berita->id,
                'judul_berita' => $berita->judul,
                'isi_berita' => $berita->konten,
                'gambar' => $berita->gambar ? url('storage/gambar-berita/' . $berita->gambar) : null, // URL gambar publik
                'tanggal' => $berita->tanggal,
                'created_at' => $berita->created_at,
                'updated_at' => $berita->updated_at
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Berita tidak ditemukan: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Berita $berita) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBeritaRequest $request, Berita $berita)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Berita $berita)
    {
        //
    }
}
