<?php
// File: app/Http/Controllers/WarkahController.php

namespace App\Http\Controllers;

use App\Models\Warkah;
use Illuminate\Http\Request;

class WarkahController extends Controller
{
    // Index - Dashboard Master Data Warkah
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        $filters = $request->only(['tahun', 'lokasi', 'kode_klasifikasi']);
        $showDeleted = $request->get('show_deleted', false);

        $query = Warkah::query();

        // Tampilkan data yang dihapus jika diminta
        if ($showDeleted) {
            $query->onlyTrashed();
        }

        if ($keyword) {
            $query->search($keyword);
        }

        if (count(array_filter($filters))) {
            $query->filter($filters);
        }

        $warkah = $query->orderBy('created_at', 'DESC')->paginate(15);
        
        $tahunList = Warkah::distinct('tahun')->pluck('tahun')->sort()->reverse();
        $lokasiList = Warkah::distinct('lokasi')->pluck('lokasi')->sort();
        $klasifikasiList = Warkah::distinct('kode_klasifikasi')->pluck('kode_klasifikasi')->sort();

        return view('warkah.index', compact('warkah', 'keyword', 'filters', 'tahunList', 'lokasiList', 'klasifikasiList', 'showDeleted'));
    }

    // Create - Tampilkan form tambah
    public function create()
    {
        // Generate preview nomor warkah untuk tahun ini
        $tahunSekarang = date('Y');
        $latestNo = Warkah::where('tahun', $tahunSekarang)
            ->orderBy('id', 'DESC')
            ->first();
        
        $number = $latestNo ? (int)substr($latestNo->no_warkah, -4) + 1 : 1;
        $noWarkah = 'WRK-' . $tahunSekarang . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        
        return view('warkah.create', compact('noWarkah'));
    }

    // Store - Simpan data baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|numeric|min:1900|max:' . date('Y'),
            'no_sk' => 'required|string|max:100',
            'nama' => 'required|string|max:255',
            'lokasi' => 'required|string|max:100',
            'kode_klasifikasi' => 'required|string|max:50',
            'jenis_arsip_vital' => 'required|string|max:100',
            'uraian_informasi_arsip' => 'required|string',
            'jumlah' => 'required|numeric|min:1',
            'tingkat_perkembangan' => 'required|string|max:100',
            'ruang_penyimpanan_rak' => 'required|string|max:100',
            'no_boks_definitif' => 'required|string|max:50',
            'no_folder' => 'required|string|max:50',
            'metode_perlindungan' => 'required|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        try {
            // Generate no_warkah otomatis berdasarkan tahun input user
            $tahunInput = $validated['tahun'];
            
            // Cari nomor terakhir di tahun yang sama
            $latestNo = Warkah::where('tahun', $tahunInput)
                ->orderBy('no_warkah', 'DESC')
                ->lockForUpdate() // Prevent race condition
                ->first();
            
            if ($latestNo && preg_match('/WRK-\d{4}-(\d{4})/', $latestNo->no_warkah, $matches)) {
                $number = (int)$matches[1] + 1;
            } else {
                $number = 1;
            }
            
            $validated['no_warkah'] = 'WRK-' . $tahunInput . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
            
            // Set default status jika kolom status ada di database
            if (in_array('status', (new Warkah)->getFillable())) {
                $validated['status'] = 'Tersedia';
            }
            
            // Set user yang create (hanya jika sudah login)
            if (auth()->check()) {
                $validated['created_by'] = auth()->id();
            }

            $warkah = Warkah::create($validated);

            return redirect()->route('warkah.index')->with('success', 'Data warkah berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Log::error('Error saat simpan warkah: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    // Show - Lihat detail
    public function show(Warkah $warkah)
    {
        return view('warkah.show', compact('warkah'));
    }

    // Edit - Tampilkan form edit
    public function edit(Warkah $warkah)
    {
        return view('warkah.edit', compact('warkah'));
    }

    // Update - Simpan perubahan
    public function update(Request $request, Warkah $warkah)
    {
        $validated = $request->validate([
            'tahun' => 'required|numeric|min:1900|max:' . date('Y'),
            'no_sk' => 'required|string|max:100',
            'nama' => 'required|string|max:255',
            'lokasi' => 'required|string|max:100',
            'kode_klasifikasi' => 'required|string|max:50',
            'jenis_arsip_vital' => 'required|string|max:100',
            'uraian_informasi_arsip' => 'required|string',
            'jumlah' => 'required|numeric|min:1',
            'tingkat_perkembangan' => 'required|string|max:100',
            'ruang_penyimpanan_rak' => 'required|string|max:100',
            'no_boks_definitif' => 'required|string|max:50',
            'no_folder' => 'required|string|max:50',
            'metode_perlindungan' => 'required|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        try {
            // Set user yang update (hanya jika sudah login)
            if (auth()->check()) {
                $validated['updated_by'] = auth()->id();
            }
            
            $warkah->update($validated);

            return redirect()->route('warkah.index')->with('success', 'Data warkah berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }
}