<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryPerilaku;
use Illuminate\Support\Facades\Log;
use App\Http\Services\CategoryService;

class CategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {

        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = CategoryPerilaku::all();

        return view('backend.categori.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.categori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        try {
            CategoryPerilaku::create($validated);

            return redirect()->route('category.index')->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan Category Perilaku: ' . $e->getMessage());

            return redirect()->route('category.create')->with('error', 'Data gagal disimpan. Silakan coba lagi.');
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid, categoryService $categoryService)
    {
        $categories = $categoryService->selectFirstById('uuid', $uuid);

        return view('backend.categori.edit', compact('categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        try {
            CategoryPerilaku::where('uuid', $uuid)->update($validated);

            return redirect()->route('category.index')->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui Category Perilaku: ' . $e->getMessage());

            return redirect()->route('category.edit', $uuid)->with('error', 'Data gagal disimpan. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $result = $this->categoryService->delete($uuid);

        if ($result) {
            return response()->json(['message' => 'Item successfully deleted.']);
        }

        // Jika UUID tidak ditemukan
        return response()->json(['message' => 'Data not found.'], 404);
    }
}