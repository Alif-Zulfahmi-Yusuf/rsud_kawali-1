<?php

namespace App\Http\Controllers;

use App\Models\Atasan;
use App\Models\Pangkat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\AtasanRequest;
use App\Http\Services\AtasanService;

class AtasanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(private AtasanService $atasanService)
    {

        $this->middleware('permission:atasan-list|atasan-create|atasan-edit|atasan-delete', ['only' => ['index', 'store']]);
    }

    public function index()
    {
        $atasans = Atasan::all();
        return view('backend/atasans.index', compact('atasans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pangkats = Pangkat::all();

        return view('backend.atasans.create', compact('pangkats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AtasanRequest $request)
    {
        $data = $request->validated();

        try {
            $this->atasanService->create($data);

            return redirect()->route('atasans.index')->with('status', [
                'message' => 'Data berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create Atasan: ' . $e->getMessage());

            return redirect()->route('atasans.create')->withInput()->withErrors([
                'error' => 'Data gagal disimpan. Silakan coba lagi.',
            ]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Atasan $atasan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        $atasan = $this->atasanService->selectFirstById('uuid', $uuid);
        $pangkats = Pangkat::all();

        return view('backend.atasans.edit', compact('atasan', 'pangkats'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AtasanRequest $request, string $uuid)
    {
        $data = $request->validated();

        try {
            $this->atasanService->update($data, $uuid);

            return redirect()->route('atasans.index')->with('status', [
                'message' => 'Data berhasil di edit.',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update Atasan: ' . $e->getMessage());

            return redirect()->route('atasans.edit', $uuid)->withInput()->withErrors([
                'error' => 'Data gagal di edit. Silakan coba lagi.',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $result = $this->atasanService->delete($uuid);

        if ($result) {
            return response()->json(['message' => 'Item successfully deleted.']);
        }

        return response()->json(['message' => 'Failed to delete the item.'], 500);
    }
}