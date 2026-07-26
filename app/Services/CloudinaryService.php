<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    /**
     * Upload file ke Cloudinary
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @return string|null (Full HTTPS URL dari Cloudinary)
     */
    public static function upload($file, string $folder = 'posters'): ?string
    {
        $cloudinaryUrl = env('CLOUDINARY_URL');

        if (!$cloudinaryUrl) {
            Log::error('Cloudinary Upload Failed: CLOUDINARY_URL is missing in environment variables.');
            return null;
        }

        try {
            $parsed = parse_url($cloudinaryUrl);
            $apiKey = $parsed['user'] ?? '';
            $apiSecret = $parsed['pass'] ?? '';
            $cloudName = $parsed['host'] ?? '';

            if (empty($cloudName) || empty($apiKey) || empty($apiSecret)) {
                Log::error('Cloudinary Upload Failed: Invalid CLOUDINARY_URL format.');
                return null;
            }

            $timestamp = time();
            $params = [
                'folder' => $folder,
                'timestamp' => $timestamp,
            ];
            ksort($params);

            $signatureStr = '';
            foreach ($params as $key => $value) {
                $signatureStr .= "{$key}={$value}&";
            }
            $signatureStr = rtrim($signatureStr, '&') . $apiSecret;
            $signature = sha1($signatureStr);

            $response = Http::attach(
                'file',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )->post("https://api.cloudinary.com/v1_1/{$cloudName}/image/upload", [
                'api_key' => $apiKey,
                'timestamp' => $timestamp,
                'folder' => $folder,
                'signature' => $signature,
            ]);

            if ($response->successful() && isset($response['secure_url'])) {
                return $response['secure_url'];
            }

            Log::error('Cloudinary Upload Unsuccessful: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Cloudinary Upload Exception: ' . $e->getMessage());
            return null;
        }
    }
}