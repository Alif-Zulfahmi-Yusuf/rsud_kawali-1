<?php

namespace App\Http\Controllers;

use App\Models\Skp;
use App\Models\Atasan;
use App\Models\SkpAtasan;
use Illuminate\Http\Request;
use App\Models\KegiatanHarian;
use App\Models\EvaluasiPegawai;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\EvaluasiService;

class EvaluasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $evaluasiService;

    public function __construct(EvaluasiService $evaluasiService)
    {

        $this->evaluasiService = $evaluasiService;
    }

    public function index()
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Ambil data evaluasi pegawai berdasarkan user ID
        $evaluasiPegawai = EvaluasiPegawai::where('user_id', $user->id)
            ->where('status', '!=', 'nonaktif')
            ->with(['skp']) // Pastikan relasi dengan tabel SKP tersedia di model EvaluasiPegawai
            ->get()
            ->map(function ($evaluasi) {
                // Pastikan data laporan berupa array
                $laporanArray = is_array($evaluasi->laporan) ? $evaluasi->laporan : (is_string($evaluasi->laporan) ? json_decode($evaluasi->laporan, true) : []);
                $totalAda = collect($laporanArray)->filter(fn($item) => $item === 'ada')->count();
                $evaluasi->capaian_qty = count($laporanArray) > 0
                    ? round(($totalAda / count($laporanArray)) * 100, 2) . '%'
                    : '-';

                // Pastikan data kualitas berupa array
                $kualitasArray = is_array($evaluasi->kualitas) ? $evaluasi->kualitas : (is_string($evaluasi->kualitas) ? json_decode($evaluasi->kualitas, true) : []);
                $nilaiMap = [
                    'sangat_kurang' => 20,
                    'kurang' => 40,
                    'butuh_perbaikan' => 60,
                    'baik' => 80,
                    'sangat_baik' => 100,
                ];
                $mappedValues = collect($kualitasArray)->map(fn($item) => $nilaiMap[$item] ?? 0);
                $evaluasi->capaian_qlty = $mappedValues->isNotEmpty()
                    ? round($mappedValues->avg(), 2)
                    : '-';

                // Perhitungan total waktu
                $totalWaktu = DB::table('kegiatan_harians')
                    ->where('user_id', $evaluasi->user_id)
                    ->whereMonth('tanggal', now()->month)
                    ->whereYear('tanggal', now()->year)
                    ->get()
                    ->reduce(function ($carry, $item) {
                        if (isset($item->waktu_mulai, $item->waktu_selesai)) {
                            $carry += \Carbon\Carbon::parse($item->waktu_mulai)
                                ->diffInHours(\Carbon\Carbon::parse($item->waktu_selesai));
                        }
                        return $carry;
                    }, 0);

                // Menghitung jam
                $jam = floor($totalWaktu);

                // Menghitung menit
                $menit = ($totalWaktu - $jam) * 60;

                // Membulatkan menit ke bilangan bulat
                $menit = round($menit);
                $evaluasi->capaian_wkt = $jam . ' Jam ' . $menit . ' Menit';


                // Pastikan data nilai berupa array
                $nilaiArray = is_array($evaluasi->nilai) ? $evaluasi->nilai : (is_string($evaluasi->nilai) ? json_decode($evaluasi->nilai, true) : []);
                foreach ($nilaiArray as &$value) {
                    switch ($value) {
                        case 'dibawah_ekspektasi':
                            $value = 1;
                            break;
                        case 'sesuai_ekspektasi':
                            $value = 2;
                            break;
                        case 'diatas_ekspektasi':
                            $value = 3;
                            break;
                    }
                }
                unset($value);
                $total = array_sum($nilaiArray); // Menjumlahkan semua elemen
                $count = count($nilaiArray); // Menghitung jumlah elemen
                $rataRata = $count > 0 ? $total / $count : 0;

                if ($rataRata < 1.5) {
                    $evaluasi->perilaku_kerja = "Di Bawah Ekspektasi";
                    $evaluasi->nilai = "Di Bawah Ekspektasi";
                } elseif ($rataRata <= 2.5) {
                    $evaluasi->perilaku_kerja = "Sesuai Ekspektasi";
                    $evaluasi->nilai = "Sesuai Ekspektasi";
                } else {
                    $evaluasi->perilaku_kerja = "Di Atas Ekspektasi";
                    $evaluasi->nilai = "Di Atas Ekspektasi";
                }

                return $evaluasi;
            });

        // Kirim data ke view
        return view('backend.evaluasi-pegawai.index', compact('evaluasiPegawai'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'bulan' => 'required|date_format:Y-m', // Validasi format bulan
        ]);

        // Tambahkan hari default agar format sesuai dengan tipe DATE di MySQL
        $bulanDenganTanggal = $request->bulan . '-01';
        try {

            // Ambil user yang sedang login
            $user = Auth::user();

            // Pastikan user memiliki atasan_id yang valid
            if (!$user->atasan_id) {
                throw new \Exception('User belum memilih atasan.');
            }

            // Ambil data atasan berdasarkan atasan_id di tabel users (mengacu ke id di tabel atasans)
            $atasan = Atasan::find($user->atasan_id);

            // Jika data atasan tidak ditemukan, tampilkan error
            if (!$atasan) {
                Log::warning('Atasan tidak ditemukan', [
                    'atasan_id' => $user->atasan_id
                ]);
                throw new \Exception('Atasan dengan ID ' . $user->atasan_id . ' tidak ditemukan.');
            }


            // Cari SKP Atasan berdasarkan ID atasan dan tahun
            $skpAtasan = SkpAtasan::where('user_id', $atasan->user_id)->first();

            // Log ID SKP Atasan yang ditemukan
            Log::info('ID SKP Atasan', [
                'skp_atasan_id' => $skpAtasan ? $skpAtasan->id : null // Pastikan id-nya ada atau null
            ]);

            // Jika SKP Atasan tidak ditemukan, tampilkan pesan error
            if (!$skpAtasan) {
                Log::warning('SKP Atasan tidak ditemukan', [
                    'atasan_id' => $atasan->user_id,
                ]);
                throw new \Exception('Atasan belum membuat Skp.');
            }

            // Ambil SKP terkait (jika ada logika spesifik untuk mencari SKP, tambahkan di sini)
            $skp = Skp::where('user_id', $user->id)
                ->where('is_active', 1)
                ->where('status', 'approve')
                ->latest()
                ->first();

            if (!$skp) {
                return back()->with('error', 'SKP tidak ditemukan untuk user ini.');
            }

            $bulan = date('Y-m', strtotime($bulanDenganTanggal));

            $evaluasi = EvaluasiPegawai::where('user_id', $user->id)
                ->where('bulan', 'LIKE', "$bulan%")
                ->first();

            if (!$evaluasi) {
                // Simpan data evaluasi
                EvaluasiPegawai::create([
                    'user_id' => $user->id,
                    'skp_id' => $skp->id,
                    'skp_atasan_id' => $skpAtasan->id,
                    'bulan' => $bulanDenganTanggal,
                ]);
            } else {

                $kegiatanHarian = KegiatanHarian::where('id', $evaluasi->kegiatan_harian_id)->first();

                $evaluasi->update([
                    'skp_id' => $skp->id,
                    'skp_atasan_id' => $skpAtasan->id,
                    'rencana_pegawai_id' => $kegiatanHarian->rencana_pegawai_id,
                    'status' => 'review',

                ]);
            }


            return back()->with('success', 'Bulan evaluasi berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Tangani error dan log
            Log::error('Gagal menyimpan bulan evaluasi', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($uuid, EvaluasiService $evaluasiService)
    {
        try {
            // Ambil data evaluasi (Realisasi Rencana Aksi dan Evaluasi Kinerja Tahunan)
            $evaluasiData = $evaluasiService->getEvaluasiData($uuid);
            $evaluasi = EvaluasiPegawai::where('uuid', $uuid)->firstOrFail();
            $dataRencanaAksi = $evaluasiData['dataRencanaAksi'];
            $groupedDataEvaluasi = $evaluasiData['groupedDataEvaluasi'];

            // Kirim data ke view
            return view('backend.evaluasi-pegawai.edit', compact('evaluasi', 'dataRencanaAksi', 'groupedDataEvaluasi'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function generatePdf($uuid)
    {
        try {
            // Fetch evaluation data using service
            $evaluasiData = $this->evaluasiService->getEvaluasiData($uuid);
            $evaluasi = EvaluasiPegawai::where('uuid', $uuid)->firstOrFail();
            $dataRencanaAksi = $evaluasiData['dataRencanaAksi'];
            $groupedDataEvaluasi = $evaluasiData['groupedDataEvaluasi'];
            $filteredKegiatanHarian = $evaluasiData['filteredKegiatanHarian'];
            $totalWaktuKeseluruhan = $evaluasiData['totalWaktuKeseluruhan'];
            $totalWaktuKeseluruhanSisaMenit = $evaluasiData['totalWaktuKeseluruhanSisaMenit'];

            // Load view with data
            $pdf = Pdf::loadView('backend.evaluasi-pegawai.pdf', compact('evaluasi', 'dataRencanaAksi', 'groupedDataEvaluasi', 'filteredKegiatanHarian', 'totalWaktuKeseluruhan', 'totalWaktuKeseluruhanSisaMenit'))
                ->setPaper('A4', 'portrait');

            // Stream or Download
            return $pdf->stream("Laporan_Kinerja_{$evaluasi->bulan}.pdf");
        } catch (\Exception $e) {
            Log::error('Gagal membuat PDF', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat membuat PDF.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $evaluasi = EvaluasiPegawai::where('uuid', $id)->firstOrFail();

            $bulan = \Carbon\Carbon::parse($evaluasi->bulan)->format('m');
            $tahun = \Carbon\Carbon::parse($evaluasi->bulan)->format('Y');
            $kegiatan = KegiatanHarian::where('user_id', $evaluasi->user_id)
                ->whereMonth('tanggal', $bulan) // Filter bulan
                ->whereYear('tanggal', $tahun) // Filter tahun
                ->get();

            $kuantitas = [];
            $laporan = [];
            $kualitas = [];

            foreach ($kegiatan as $item) {
                $kuantitas[] = $request->kuantitas_output[$item->rencana_pegawai_id];
                $laporan[] = $request->laporan[$item->rencana_pegawai_id];
                $kualitas[] = $request->kualitas[$item->rencana_pegawai_id];
            }

            // Update data evaluasi
            $evaluasi->update([
                'tanggal_capaian' => $request->tanggal_capai,
                'kuantitas_output' => $kuantitas,
                'permasalahan' => $request->permasalahan,
                'jumlah_periode' => $request->jumlah_periode,
                'laporan' => $laporan,
                'kualitas' => $kualitas,
                'realisasi' => $request->realisasi,
                'is_submit' => $request->action === 'submit' ? true : $evaluasi->is_submit, // Ubah `is_submit` jika tombol "Ajukan" ditekan
            ]);

            return back()->with('success', 'Evaluasi berhasil disimpan' . ($request->action === 'submit' ? ' dan diajukan.' : '.'));
        } catch (\Exception $e) {
            Log::error('Gagal mengupdate data evaluasi', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat mengupdate data.');
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $result = $this->evaluasiService->delete($uuid);

        if ($result) {
            return response()->json(['message' => 'Item successfully deleted.']);
        }

        return response()->json(['message' => 'Failed to delete the item.'], 500);
    }
}
