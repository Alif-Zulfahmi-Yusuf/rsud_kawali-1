<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\SkpPegawaiService;
use App\Models\SkpPegawai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SkpPegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $skpPegawaiService;

    public function __construct(SkpPegawaiService $skpPegawaiService)
    {
        $this->skpPegawaiService = $skpPegawaiService;
    }
    public function index()
    {
        $skps = SkpPegawai::with(['user.atasan']) // Relasi dengan user dan atasan
            ->where('user_id', Auth::id()) // Filter berdasarkan pengguna yang login
            ->get();
        return view('backend.skp_pegawai.index', compact('skps'));
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
    public function store(Request $request)
    {
        //
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