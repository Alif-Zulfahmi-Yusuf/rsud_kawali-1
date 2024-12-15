<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KegiatanHarian;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Services\KegiatanService;
use App\Models\RencanaHasilKinerjaPegawai;
use App\Http\Requests\HarianPegawaiRequest;

class KegiatanHarianController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $kegiatanService;

    public function __construct(KegiatanService $kegiatanService)
    {
        $this->kegiatanService = $kegiatanService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $rencanaKerjaPegawai = RencanaHasilKinerjaPegawai::where('user_id', $user->id)->get();
        $kegiatanHarian = KegiatanHarian::where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        // Kembalikan data ke view
        return view('backend.harian.index', compact('rencanaKerjaPegawai', 'kegiatanHarian'));
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
    public function store(HarianPegawaiRequest $request): RedirectResponse
    {
        try {
            // Cek tombol yang diklik untuk menentukan draft atau review
            $isDraft = $request->has('is_draft');

            // Panggil service untuk menyimpan data
            $this->kegiatanService->saveKegiatanHarian($request->all(), $isDraft);

            return redirect()->back()->with('success', 'Kegiatan harian berhasil disimpan!');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan kegiatan harian', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Gagal menyimpan kegiatan harian. Silakan coba lagi!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
