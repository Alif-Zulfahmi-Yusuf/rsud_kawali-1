<?php

namespace App\Http\Controllers;

use App\Models\Pangkat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PangkatRequest;
use App\Http\Services\PangkatService;
use Illuminate\Http\RedirectResponse;

class PangkatController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct(private PangkatService $pangkatService) {}

    public function index()
    {
        $pangkats = Pangkat::all();
        return view('backend/pangkat.index', compact('pangkats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('backend/pangkat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PangkatRequest $request): RedirectResponse
    {
        $data = $request->validated();

        try {
            $this->pangkatService->create($data);

            return redirect()->route('pangkat.index')->with('status', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('Failed to store Pangkat: ' . $e->getMessage());
            return redirect()->route('pangkat.index')->with('status', 'Data gagal disimpan. Silakan coba lagi.');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
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
    public function destroy(string $uuid)
    {
        //
    }
}