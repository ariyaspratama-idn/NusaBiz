<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

trait OptimasiGambar
{
    /**
     * Simpan gambar Base64 dan optimalkan (Kompresi GD).
     */
    protected function saveAndOptimizeBase64($base64String, $folder, $disk = 'public')
    {
        if (empty($base64String)) return null;

        try {
            if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $type)) {
                $base64String = substr($base64String, strpos($base64String, ',') + 1);
                $extension = strtolower($type[1]);
                
                if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) return null;

                $imageData = base64_decode(str_replace(' ', '+', $base64String));
                if ($imageData === false) return null;

                // Optimasi via GD
                $imageData = $this->optimizeWithGD($imageData);
                
                $fileName = uniqid() . '_' . time() . '.jpg';
                $path = 'uploads/' . $folder . '/' . $fileName;

                Storage::disk($disk)->put($path, $imageData);
                return $path;
            }
        } catch (\Exception $e) {
            \Log::error('OptimasiGambar Error: ' . $e->getMessage());
        }
        return null;
    }

    protected function optimizeWithGD($imageData)
    {
        try {
            if (!extension_loaded('gd')) return $imageData;

            $src = imagecreatefromstring($imageData);
            if (!$src) return $imageData;

            $width = imagesx($src);
            $height = imagesy($src);
            $maxWidth = 1000;

            if ($width > $maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = floor($height * ($maxWidth / $width));
                $tmp = imagecreatetruecolor($newWidth, $newHeight);
                imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                $src = $tmp;
            }

            ob_start();
            imagejpeg($src, null, 60); // Kualitas 60 untuk hemat TiDB
            $optimizedData = ob_get_contents();
            ob_end_clean();

            imagedestroy($src);
            return $optimizedData ?: $imageData;
        } catch (\Exception $e) {
            return $imageData;
        }
    }
}
