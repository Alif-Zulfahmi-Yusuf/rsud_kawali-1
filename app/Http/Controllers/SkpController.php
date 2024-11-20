<?php

namespace App\Http\Controllers;

use App\Models\Skp;
use App\Models\User;
use App\Models\Atasan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Requests\SkpRequest;
use App\Http\Services\SkpService;
use Illuminate\Support\Facades\Auth;

class SkpController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $skpService;

    public function __construct(SkpService $skpService)
    {
        $this->skpService = $skpService;
    }
    public function index()
    {
        $skps = Skp::with(['user', 'atasan'])->get();
        return view('backend.skp.index', compact('skps'));
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
    public function store(SkpRequest $request)
    {
        try {
            $this->skpService->store($request->validated(), Auth::user());
            return redirect()->back()->with('status', 'Data SKP berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('status', 'Data SKP gagal disimpan. Silakan coba lagi.' . $e->getMessage());
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
    public function update(Request $request, string $uuid)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $result = $this->skpService->delete($uuid);

        if ($result) {
            return response()->json(['message' => 'Item successfully deleted.']);
        }

        return response()->json(['message' => 'Failed to delete the item.'], 500);
    }
}