<?php

namespace App\Http\Controllers;

use App\Models\Sdn; // Gunakan model Sdn
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SdnController extends Controller
{
    // Menampilkan data SDN dengan pagination dan urutan terbaru
    public function index(Request $request)
    {
        $query = Sdn::query();

        // Pencarian berdasarkan nama
        if ($request->has('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Pagination dengan 10 item per halaman
        $sdn = $query->paginate(10)->withQueryString();

        return view('index', [
            'title' => 'Daftar Sekolah Dasar Negeri',
            'sdn' => $sdn,
            'search' => $request->search
        ]);
    }

    // Menyimpan data SDN baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            // Generate slug dari nama
            $slug = Str::slug($request->nama, '-');

            // Cek apakah slug sudah ada di database
            $count = Sdn::where('slug', $slug)->count();
            if ($count > 0) {
                // Jika slug sudah ada, tambahkan angka di belakang
                $slug = $slug . '-' . ($count + 1);
            }

            // Simpan file gambar dengan nama di-hash
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $hashedName = $image->hashName();
                $imagePath = $image->storeAs('images/sdn', $hashedName, 'public');
            }

            // Simpan data ke database
            $sdn = Sdn::create([
                'slug' => $slug,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'image' => $imagePath
            ]);

            // Redirect dengan pesan sukses
            return redirect()->route('admin.dashboard')->with('success', 'Data SDN berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Redirect dengan pesan error jika terjadi kesalahan
            return redirect()->back()->with('error', 'Gagal menambahkan data SDN. Silakan coba lagi. Error: ' . $e->getMessage())->withInput();
        }
    }

    // Mengupdate data SDN
    public function update(Request $request, $slug)
    {
        try {
            // Validasi input
            $request->validate([
                'nama' => 'sometimes|string|max:255', // Sesuaikan dengan field yang ada di model Sdn
                'latitude' => 'sometimes|string',
                'longitude' => 'sometimes|string',
                'image' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Cari data SDN berdasarkan slug
            $sdn = Sdn::where('slug', $slug)->firstOrFail();

            // Update nama dan slug jika nama diisi
            if ($request->has('nama')) {
                $newSlug = Str::slug($request->nama);
                $count = Sdn::where('slug', $newSlug)->where('id', '!=', $sdn->id)->count();
                if ($count > 0) {
                    $newSlug = $newSlug . '-' . ($count + 1);
                }
                $sdn->nama = $request->nama;
                $sdn->slug = $newSlug;
            }

            // Update latitude dan longitude jika diisi
            if ($request->has('latitude')) {
                $sdn->latitude = $request->latitude;
            }
            if ($request->has('longitude')) {
                $sdn->longitude = $request->longitude;
            }

            // Update gambar jika diunggah
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($sdn->image && Storage::disk('public')->exists($sdn->image)) {
                    Storage::disk('public')->delete($sdn->image);
                }
                // Simpan gambar baru
                $image = $request->file('image');
                $imagePath = $image->storeAs('images/sdn', $image->hashName(), 'public');
                $sdn->image = $imagePath;
            }

            // Simpan perubahan
            $sdn->save();

            // Redirect dengan pesan sukses
            return redirect()->route('admin.dashboard')->with('success', 'Data SDN berhasil diubah.');
        } catch (\Exception $e) {
            // Redirect dengan pesan error jika terjadi kesalahan
            return redirect()->back()->with('error', 'Gagal mengubah data SDN. Silakan coba lagi. Error: ' . $e->getMessage());
        }
    }

    // Menghapus data SDN
    public function destroy($slug)
    {
        try {
            $sdn = Sdn::where('slug', $slug)->firstOrFail();

            // Hapus gambar jika ada
            if ($sdn->image) {
                // Hapus file dari storage
                if (Storage::disk('public')->exists($sdn->image)) {
                    Storage::disk('public')->delete($sdn->image);
                }
            }

            // Hapus data
            $sdn->delete();

            // Kirim respons JSON untuk fetch API
            return response()->json(['message' => 'Data SDN berhasil dihapus.'], 200);
        } catch (\Exception $e) {
            // Tangkap error dan kirim respons JSON
            return response()->json(['error' => 'Gagal menghapus data SDN. Silakan coba lagi.'], 500);
        }
    }

    public function dashboard(Request $request)
    {
        $query = Sdn::query();

        // Pencarian berdasarkan nama
        if ($request->has('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Pengurutan berdasarkan waktu terbaru
        $query->latest();

        // Pagination dengan 10 item per halaman
        $sdn = $query->paginate(10)->withQueryString();

        return view('admin.dashboard', [
            'sdn' => $sdn,
        ]);
    }

    public function create()
    {
        return view('admin.sdn-form');
    }

    public function edit($slug)
    {
        $sdn = Sdn::where('slug', $slug)->firstOrFail();
        return view('admin.sdn-form', compact('sdn'));
    }
}
