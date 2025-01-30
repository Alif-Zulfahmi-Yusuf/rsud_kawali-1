<?php

namespace App\Http\Controllers;

use App\Models\Skp;
use App\Models\Ekspetasi;
use Illuminate\Http\Request;
use App\Models\KegiatanHarian;
use App\Models\EvaluasiPegawai;
use App\Models\CategoryPerilaku;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\EvaluasiAtasanService;

class EvaluasiAtasanController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $evaluasiService;

    public function __construct(EvaluasiAtasanService $evaluasiService)
    {

        $this->evaluasiService = $evaluasiService;
    }

    public function index(Request $request)
    {
        Log::info('Memulai proses index validasi harian.', [
            'user_id' => Auth::id(),
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
        ]);

        DB::enableQueryLog();

        $bulan = $request->bulan;
        $tahun = $request->tahun;

        // Ambil data evaluasi atasan dengan kondisi tertentu
        $EvaluasiAtasan = EvaluasiPegawai::with(['user', 'user.pangkat'])
            ->whereIn('status', ['review', 'revisi'])
            ->where('is_submit', 1)
            ->whereHas('rencanaPegawai', function ($query) use ($bulan, $tahun) {
                $query->whereHas('rencanaAtasan', function ($queryAtasan) {
                    $queryAtasan->where('user_id', Auth::id());
                });

                if ($bulan) {
                    $query->whereMonth('bulan', $bulan);
                }

                if ($tahun) {
                    $query->whereYear('bulan', $tahun);
                }
            });

        if (!$bulan && !$tahun) {
            $EvaluasiAtasan = $EvaluasiAtasan->orWhereDoesntHave('rencanaPegawai');
        }

        // Map hasil dengan perhitungan tambahan
        $EvaluasiAtasan = $EvaluasiAtasan->get()->unique('user_id')->map(function ($evaluasi) use ($bulan, $tahun) {
            // Perhitungan rata-rata capaian kuantitas
            $laporanArray = is_array($evaluasi->laporan) ? $evaluasi->laporan : (is_string($evaluasi->laporan) ? json_decode($evaluasi->laporan, true) : []);
            $totalAda = collect($laporanArray)->filter(fn($item) => $item === 'ada')->count();
            $evaluasi->capaian_qty = count($laporanArray) > 0
                ? round(($totalAda / count($laporanArray)) * 100, 2) . '%'
                : '-';

            // Perhitungan rata-rata kualitas
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

            // Perhitungan total waktu dari kegiatan harian
            $totalWaktu = DB::table('kegiatan_harians')
                ->where('user_id', $evaluasi->user_id)
                ->whereMonth('tanggal', $bulan ?: now()->month)
                ->whereYear('tanggal', $tahun ?: now()->year)
                ->get()
                ->reduce(function ($carry, $item) {
                    if (isset($item->waktu_mulai, $item->waktu_selesai)) {
                        $carry += \Carbon\Carbon::parse($item->waktu_mulai)
                            ->diffInHours(\Carbon\Carbon::parse($item->waktu_selesai));
                    }
                    return $carry;
                }, 0);

            $totalMenit = $totalWaktu * 60;
            $evaluasi->capaian_wkt = $totalMenit . ' Menit';

            // Perhitungan perilaku kerja
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

            $total = array_sum($nilaiArray); // Total nilai
            $count = count($nilaiArray); // Jumlah elemen
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



            // Ambil langsung nilai dari kolom 'rating'
            $rating = $evaluasi->rating ?? 'dibawah_ekspektasi';
            $ratingMap = [
                'dibawah_ekspektasi' => 1,
                'sesuai_ekspektasi' => 2,
                'diatas_ekspektasi' => 3,
            ];


            $ratingValue = $ratingMap[$rating] ?? 1; // Default ke 'di_bawah_ekspektasi' jika rating tidak valid

            // Gabungkan rata-rata perilaku kerja dan rating untuk predikat
            $gabunganNilai = ($rataRata + $ratingValue) / 2;

            // PRIORITAS KONDISI UNTUK PREDIKAT
            if ($rataRata > 2.5) {
                $evaluasi->predikat = "Sangat Baik";
            } elseif ($rataRata <= 2.5 && $rataRata >= 1.5) {
                $evaluasi->predikat = "Baik";
            } elseif ($rataRata < 1.5 && $rataRata >= 1.25) {
                $evaluasi->predikat = "Kurang";
            } else {
                $evaluasi->predikat = "Sangat Kurang";
            }


            return $evaluasi;
        });

        Log::info('Query SQL:', DB::getQueryLog());
        DB::disableQueryLog();

        if ($EvaluasiAtasan->isEmpty()) {
            Log::warning('Tidak ada data ditemukan untuk validasi harian.', [
                'user_id' => Auth::id(),
                'bulan' => $bulan,
                'tahun' => $tahun,
            ]);
        }

        return view('backend.evaluasi-atasan.index', compact('EvaluasiAtasan'));
    }


    public function getByUser($userId)
    {
        try {
            $evaluasiAtasan = EvaluasiPegawai::where('user_id', $userId)
                ->where('status', 'review')
                ->where('is_submit', 1)
                ->get();

            if ($evaluasiAtasan->isEmpty()) {
                return redirect()->back()->with('warning', 'Tidak ada data ditemukan');
            }

            return response()->json($evaluasiAtasan);
        } catch (\Exception $e) {
            Log::error('Gagal mengambil data evaluasi kinerja untuk user_id ' . $userId, ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan');
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($uuid, Request $request, EvaluasiAtasanService $evaluasiAtasanService)
    {
        try {
            $pegawaiId = $request->query('pegawai_id'); // Ambil pegawai ID dari query parameter

            if (!$pegawaiId) {
                throw new \Exception('Pegawai ID tidak ditemukan.');
            }

            $evaluasiData = $evaluasiAtasanService->getEvaluasiData($uuid, $pegawaiId);
            $evaluasi = EvaluasiPegawai::where('uuid', $uuid)->firstOrFail();
            $dataRencanaAksi = $evaluasiData['dataRencanaAksi'];
            $groupedDataEvaluasi = $evaluasiData['groupedDataEvaluasi'];

            // Ambil kategori perilaku dan ekspektasi
            $categories = CategoryPerilaku::with('perilakus')
                ->whereHas('perilakus')
                ->get();

            $ekspektasis = Ekspetasi::where('skp_id', optional($evaluasi->skp)->id ?? null)->get();

            return view('backend.evaluasi-atasan.edit', compact(
                'evaluasi',
                'dataRencanaAksi',
                'groupedDataEvaluasi',
                'categories',
                'ekspektasis',
                'pegawaiId'
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $evaluasi = EvaluasiPegawai::where('uuid', $id)->firstOrFail();

            // Ambil data bulan dan tahun
            $bulan = \Carbon\Carbon::parse($evaluasi->bulan)->format('m');
            $tahun = \Carbon\Carbon::parse($evaluasi->bulan)->format('Y');
            $kegiatan = KegiatanHarian::where('user_id', $evaluasi->user_id)
                ->whereMonth('tanggal', $bulan) // Filter bulan
                ->whereYear('tanggal', $tahun) // Filter tahun
                ->get();

            $kuantitas = [];
            $laporan = [];
            $kualitas = [];
            $nilai = [];
            $umpanBalik = [];
            $umpanBalikBerkelanjutan = [];
            $realisasi = [];

            // Loop untuk mengisi data kegiatan
            foreach ($kegiatan as $item) {
                $kuantitas[] = $request->kuantitas_output[$item->rencana_pegawai_id] ?? null;
                $laporan[] = $request->laporan[$item->rencana_pegawai_id] ?? null;
                $kualitas[] = $request->kualitas[$item->rencana_pegawai_id] ?? null;
            }

            // Mengisi data umpan balik secara terpisah
            foreach ($request->umpan_balik as $index => $value) {
                $umpanBalik[] = $value ?? null;
            }

            // Mengisi data realisasi dan umpan balik secara terpisah
            foreach ($request->realisasi as $index => $value) {
                $realisasi[] = $value ?? null;
            }


            foreach ($request->umpan_balik_berkelanjutan as $index => $value) {
                $umpanBalikBerkelanjutan[] = $value ?? null;
            }

            foreach ($request->nilai as $index => $value) {
                $nilai[] = $value ?? null;
            }

            // Update data evaluasi
            $evaluasi->update([
                'tanggal_terbit' => $request->tanggal_terbit,
                'kuantitas_output' => $kuantitas,
                'permasalahan' => $request->permasalahan,
                'jumlah_periode' => $request->jumlah_periode,
                'rating' => $request->rating,
                'nilai' => $nilai,
                'laporan' => $laporan,
                'kualitas' => $kualitas,
                'status' => $request->status,
                'umpan_balik' => $umpanBalik,
                'umpan_balik_berkelanjutan' => $umpanBalikBerkelanjutan,
                'realisasi' => $realisasi,
                'is_submit' => $request->action === 'submit' ? true : $evaluasi->is_submit, // Update is_submit jika tombol "Ajukan" ditekan
            ]);

            return back()->with(
                'success',
                'Evaluasi berhasil disimpan' . ($request->action === 'submit' ? ' dan di Review.' : '.')
            );
        } catch (\Exception $e) {
            Log::error('Gagal mengupdate data evaluasi', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat mengupdate data.');
        }
    }
}