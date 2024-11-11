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
    public function edit(string $uuid, PangkatService $pangkatService)
    {
        $pangkat = $pangkatService->selectFirstById('uuid', $uuid);

        return view('backend.pangkat.edit', compact('pangkat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PangkatRequest $request, string $uuid)
    {
        try {
            $this->pangkatService->update($request->validated(), $uuid);

            return redirect()->route('pangkat.index')->with('status', 'Data berhasil di edit.');
        } catch (\Exception $e) {
            Log::error('Failed to update Pangkat: ' . $e->getMessage());
            return redirect()->route('pangkat.index')->with('status', 'Data gagal di edit. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $result = $this->pangkatService->delete($uuid);

        if ($result) {
            return response()->json(['message' => 'Item successfully deleted.']);
        }

        return response()->json(['message' => 'Failed to delete the item.'], 500);
    }
}