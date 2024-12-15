<?php

namespace App\Http\Services;

use App\Models\KegiatanHarian;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;


class KegiatanService
{
    //

    public function saveKegiatanHarian(array $data, bool $isDraft = false): KegiatanHarian
    {
        // Tambahkan user_id dan skp_id secara otomatis
        $data['user_id'] = Auth::id(); // Ambil user yang sedang login
        $data['skp_id'] = $this->getSkpIdByUser(Auth::id()); // Fungsi untuk mengambil skp_id
        $data['is_draft'] = $isDraft ? 1 : 0; // Jika draft, is_draft = 1, else 0
        $data['status'] = 'pending'; // Default status

        // Tangani file evidence jika ada
        if (isset($data['evidence']) && $data['evidence'] instanceof UploadedFile) {
            $data['evidence'] = $this->uploadEvidence($data['evidence']);
        }

        // Simpan data ke database
        return KegiatanHarian::create($data);
    }

    protected function uploadEvidence(UploadedFile $file): string
    {
        return $file->store('evidence', 'public');
    }

    protected function getSkpIdByUser(int $userId): ?int
    {
        // Contoh logika: Ambil SKP aktif berdasarkan user_id
        return optional(Auth::user()->skpAktif)->id;
    }
}
