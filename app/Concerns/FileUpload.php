<?php

namespace App\Concerns;

use Illuminate\Support\Facades\Storage;

trait FileUpload
{
    /**
     * Helper untuk upload file atau simpan base64 ke database (TiDB/Vercel legacy).
     */
    public function uploadFile($file, $path = 'uploads')
    {
        if (is_string($file) && str_starts_with($file, 'data:image')) {
            // Jika base64, bisa tetap disimpan di DB untuk Vercel
            return $file;
        }

        return $file->store($path, 'public');
    }
}
