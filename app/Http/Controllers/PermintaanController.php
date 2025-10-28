<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permintaan;
use App\Models\Warkah;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;

class PermintaanController extends Controller
{
    /**
     * Tampilkan semua data permintaan
     */
    public function index(Request $request)
    {
        $query = \App\Models\Permintaan::query();

        // 🔍 Filter berdasarkan nama pemohon
        if ($request->filled('nama')) {
            $query->where('nama_pemohon', 'like', '%' . $request->nama . '%');
        }

        // 🔍 Filter berdasarkan uraian arsip (relasi ke tabel warkah) dengan normalisasi
        if ($request->filled('uraian')) {
            $uraian = $request->uraian;
            // ✨ Normalisasi keyword dengan menghapus spasi
            $normalizedUraian = preg_replace('/\s+/', '', $uraian);
            
            $query->whereHas('warkah', function ($q) use ($normalizedUraian) {
                $q->whereRaw("REPLACE(REPLACE(uraian_informasi_arsip, ' ', ''), '\n', '') LIKE ?", ["%{$normalizedUraian}%"])
                  ->orWhereRaw("REPLACE(REPLACE(kode_klasifikasi, ' ', ''), '\n', '') LIKE ?", ["%{$normalizedUraian}%"])
                  ->orWhereRaw("REPLACE(REPLACE(nomor_item_arsip, ' ', ''), '\n', '') LIKE ?", ["%{$normalizedUraian}%"]);
            });
        }

        // 🔍 Filter berdasarkan tanggal permintaan
        if ($request->filled('tanggal_permintaan')) {
            $query->whereDate('tanggal_permintaan', $request->tanggal_permintaan);
        }

        // Ambil data + relasi ke warkah
        $permintaan = $query
            ->with('warkah')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->appends($request->all()); // agar pagination tetap membawa query filter

        return view('permintaan.index', compact('permintaan'));
    }


    /**
     * Form tambah permintaan baru
     */
    public function create()
    {
        $warkah = Warkah::orderBy('uraian_informasi_arsip', 'asc')->get();
        return view('permintaan.create', compact('warkah'));
    }

    /**
     * Simpan permintaan baru
     */
    public function store(Request $request)
    {
        // ✅ Validasi input dengan dukungan MIME tambahan
        $data = $request->validate([
            'id_warkah' => 'required|exists:master_warkah,id',
            'nama_pemohon' => 'required|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'nomor_identitas' => 'nullable|string|max:100',
            'alamat_lengkap' => 'nullable|string',
            'nomor_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'tanggal_permintaan' => 'required|date',
            'jumlah_salinan' => 'required|integer|min:1',
            'catatan_tambahan' => 'nullable|string',
            'nota_dinas_masuk_no' => 'nullable|string|max:100',

            'nota_dinas_masuk_file' => [
                'nullable',
                'file',
                'max:4096',
                'mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/octet-stream,application/zip', 
            ],

            'nomor_surat_disposisi' => 'nullable|string|max:100',

            'file_disposisi' => [
                'nullable',
                'file',
                'max:4096',
                'mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/octet-stream,application/zip',
            ],

            'status' => 'required|in:Diajukan,Diterima,Disposisi,Disalin,Selesai',
        ]);

        // ✅ Fallback tambahan jika PHP salah deteksi MIME (file .docx terbaca .bin)
        $allowedExt = ['pdf', 'doc', 'docx'];

        foreach (['nota_dinas_masuk_file', 'file_disposisi'] as $field) {
            if ($request->hasFile($field)) {
                $ext = strtolower($request->file($field)->getClientOriginalExtension());
                if (!in_array($ext, $allowedExt)) {
                    return back()->withErrors([$field => 'File harus berupa PDF, DOC, atau DOCX.'])->withInput();
                }
            }
        }

        // ✅ Simpan file nota dinas
        $fileNota = null;
        if ($request->hasFile('nota_dinas_masuk_file')) {
            $file = $request->file('nota_dinas_masuk_file');
            $originalName = $file->getClientOriginalName();
            $filename = uniqid('nota_') . '_' . $originalName;
            $fileNota = $file->storeAs('nota_dinas', $filename, 'public');
        }

        // ✅ Simpan file disposisi
        $fileDisposisi = null;
        if ($request->hasFile('file_disposisi')) {
            $file = $request->file('file_disposisi');
            $originalName = $file->getClientOriginalName();
            $filename = uniqid('disposisi_') . '_' . $originalName;
            $fileDisposisi = $file->storeAs('disposisi', $filename, 'public');
        }

        // ✅ Simpan ke database
        Permintaan::create([
            'id_warkah' => $data['id_warkah'],
            'nama_pemohon' => $data['nama_pemohon'],
            'instansi' => $data['instansi'] ?? null,
            'nomor_identitas' => $data['nomor_identitas'] ?? null,
            'alamat_lengkap' => $data['alamat_lengkap'] ?? null,
            'nomor_telepon' => $data['nomor_telepon'] ?? null,
            'email' => $data['email'] ?? null,
            'tanggal_permintaan' => $data['tanggal_permintaan'],
            'jumlah_salinan' => $data['jumlah_salinan'],
            'catatan_tambahan' => $data['catatan_tambahan'] ?? null,
            'nota_dinas_masuk_no' => $data['nota_dinas_masuk_no'] ?? null,
            'nota_dinas_masuk_file' => $fileNota,
            'nomor_surat_disposisi' => $data['nomor_surat_disposisi'] ?? null,
            'file_disposisi' => $fileDisposisi,
            'status_permintaan' => $data['status'] ?? 'Diajukan',
        ]);

        return redirect()->route('permintaan.index')
            ->with('success', 'Permintaan salinan warkah berhasil disimpan.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_permintaan' => 'required|in:Diajukan,Diterima,Disposisi,Disalin,Selesai'
        ]);

        $permintaan = Permintaan::findOrFail($id);
        $permintaan->status_permintaan = $request->status_permintaan;
        $permintaan->save();

        return redirect()->back()->with('success', 'Status berhasil diperbarui!');
    }

    public function show($id)
    {
        $permintaan = Permintaan::with('warkah')->findOrFail($id);
        $qrCode = QrCode::size(200)->generate(
            'Permintaan #' . $permintaan->id . ' - ' . ($permintaan->warkah->uraian_informasi_arsip ?? 'Data Arsip')
        );

        return view('permintaan.show', compact('permintaan', 'qrCode'));
    }

    /**
     * Lihat file (PDF langsung, DOC/DOCX via Google Docs)
     */
    public function lihatFile($id, $type)
    {
        $permintaan = Permintaan::findOrFail($id);

        if ($type === 'nota') {
            $filePath = $permintaan->nota_dinas_masuk_file;
        } elseif ($type === 'disposisi') {
            $filePath = $permintaan->file_disposisi;
        } else {
            abort(404, 'Tipe file tidak valid');
        }

        if (empty($filePath)) {
            abort(404, 'File tidak ditemukan di database');
        }

        $path = storage_path('app/public/' . $filePath);
        
        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan di server');
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        // Jika PDF, tampilkan langsung inline
        if ($extension === 'pdf') {
            return response()->file($path, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
            ]);
        }

        // Jika DOC/DOCX, convert ke HTML atau gunakan viewer library
        if (in_array($extension, ['doc', 'docx'])) {
            $downloadUrl = route('permintaan.downloadFile', ['id' => $id, 'type' => $type]);
            $backUrl = route('permintaan.show', $id);
            $filePath = $path;
            $fileName = basename($path);
            // PENTING: Kirim variable $type ke view
            $fileType = $type;

            return view('permintaan.viewer', compact('filePath', 'fileName', 'downloadUrl', 'backUrl', 'id', 'fileType'));
        }

        return response()->file($path);
    }

    public function downloadFile($id, $type)
    {
        $permintaan = Permintaan::findOrFail($id);
        
        // Tentukan file mana yang akan didownload
        if ($type === 'nota') {
            $fileColumn = $permintaan->nota_dinas_masuk_file;
        } elseif ($type === 'disposisi') {
            $fileColumn = $permintaan->file_disposisi;
        } else {
            abort(404, 'Tipe file tidak ditemukan');
        }
        
        // Cek apakah file ada di database
        if (empty($fileColumn)) {
            return back()->with('error', 'File tidak ditemukan untuk permintaan ini');
        }
        
        // Cek apakah file ada di storage
        if (!Storage::disk('public')->exists($fileColumn)) {
            return back()->with('error', 'File tidak ditemukan di server');
        }
        
        $filePath = Storage::disk('public')->path($fileColumn);
        $fileName = basename($fileColumn);
        
        // Force download dengan headers
        return response()->download($filePath, $fileName, [
            'Content-Type' => Storage::disk('public')->mimeType($fileColumn),
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
    
    public function destroy($id)
    {
        $permintaan = Permintaan::findOrFail($id);
        $permintaan->delete();

        return back()->with('success', 'Data permintaan berhasil dihapus.');
    }

    public function cetakPDF($id)
    {
        // Hanya load relasi warkah
        $permintaan = Permintaan::with(['warkah'])->findOrFail($id);
        
        return view('permintaan.cetak', compact('permintaan'));
    }

    /**
     * 🔹 Ambil data warkah untuk dropdown Select2 (dengan normalisasi pencarian)
     */
    public function getAvailableWarkah(Request $request)
    {
        $search = $request->get('search', '');

        // Ambil semua warkah (tidak ada filter status khusus untuk permintaan salinan)
        $query = Warkah::query();

        // ✨ Tambahkan filter pencarian dengan normalisasi jika ada input
        if ($search) {
            // Normalisasi keyword dengan menghapus spasi
            $normalizedSearch = preg_replace('/\s+/', '', $search);
            
            $query->where(function ($q) use ($normalizedSearch) {
                $q->whereRaw("REPLACE(REPLACE(kode_klasifikasi, ' ', ''), '\n', '') LIKE ?", ["%{$normalizedSearch}%"])
                    ->orWhereRaw("REPLACE(REPLACE(uraian_informasi_arsip, ' ', ''), '\n', '') LIKE ?", ["%{$normalizedSearch}%"])
                    ->orWhereRaw("REPLACE(REPLACE(nomor_item_arsip, ' ', ''), '\n', '') LIKE ?", ["%{$normalizedSearch}%"])
                    ->orWhereRaw("REPLACE(REPLACE(ruang_penyimpanan_rak, ' ', ''), '\n', '') LIKE ?", ["%{$normalizedSearch}%"])
                    ->orWhereRaw("REPLACE(REPLACE(kurun_waktu_berkas, ' ', ''), '\n', '') LIKE ?", ["%{$normalizedSearch}%"])
                    ->orWhereRaw("REPLACE(REPLACE(jenis_arsip_vital, ' ', ''), '\n', '') LIKE ?", ["%{$normalizedSearch}%"]);
            });
        }

        $warkah = $query->select(
            'id',
            'kode_klasifikasi',
            'nomor_item_arsip',
            'uraian_informasi_arsip',
            'ruang_penyimpanan_rak',
            'kurun_waktu_berkas',
            'jenis_arsip_vital',
            'status'
        )
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json($warkah);
    }
}