<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'nip',
        'pangkat_id',
        'jabatan',
        'unit_kerja',
        'tmt_jabatan',
        'atasan_id',
        'jabatan',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Handle updating the profile image.
     *
     * @param  \Illuminate\Http\UploadedFile  $image
     * @return void
     */
    public function updateProfileImage($image)
    {
        // Tentukan directory penyimpanan
        $directory = 'images/profiles';

        // Cek apakah folder sudah ada, jika belum, buat folder
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        // Coba simpan gambar ke dalam folder
        try {
            $path = $image->store($directory, 'public');
            if ($path) {
                $this->image = $path;
                $this->save();
            } else {
                throw new \Exception("Failed to store image path");
            }
        } catch (\Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage());
            throw new \Exception("Image upload failed: " . $e->getMessage());
        }
    }

    /**
     * Relasi ke atasan (User lain yang menjadi atasan)
     */
    public function atasan()
    {
        return $this->belongsTo(Atasan::class, 'atasan_id', 'id');
    }

    // Relasi Atasan ke Pegawai
    public function pegawai()
    {
        return $this->hasMany(User::class, 'atasan_id');
    }

    public function skpAtasan()
    {
        return $this->hasMany(SkpAtasan::class, 'user_id', 'atasan_id');
    }
    /**
     * Relasi ke pangkat (Pangkat dari pengguna)
     */
    public function pangkat()
    {
        return $this->belongsTo(Pangkat::class, 'pangkat_id');
    }

    // Relasi ke Rencana Hasil Kinerja
    public function rencanaHasilKinerja()
    {
        return $this->hasMany(RencanaHasilKinerja::class);
    }

    // Relasi ke Rencana Hasil Kinerja Pegawai
    public function rencanaHasilKinerjaPegawai()
    {
        return $this->hasMany(RencanaHasilKinerjaPegawai::class);
    }

    // Relasi ke Indikator Kinerja
    public function indikatorKinerja()
    {
        return $this->hasMany(IndikatorKinerja::class);
    }
}