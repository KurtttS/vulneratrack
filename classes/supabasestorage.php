<?php

require_once __DIR__ . '/../bootstrap.php';

class SupabaseStorage
{
    private string $baseUrl;
    private string $bucket;
    private string $serviceKey;

    public function __construct()
    {
        $this->baseUrl = rtrim((string)appEnvironment('SUPABASE_URL'), '/');
        $this->bucket = (string)appEnvironment('SUPABASE_REPORT_BUCKET', 'report-uploads');
        $this->serviceKey = (string)appEnvironment('SUPABASE_SERVICE_ROLE_KEY');

        if ($this->baseUrl === '' || $this->serviceKey === '') {
            throw new RuntimeException('Set SUPABASE_URL and SUPABASE_SERVICE_ROLE_KEY for file storage.');
        }
    }

    public function uploadReportFile(string $temporaryPath, string $mimeType): string
    {
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            'application/pdf' => 'pdf',
        ];

        if (!isset($extensions[$mimeType])) {
            throw new RuntimeException('Report attachments must be JPEG, PNG, WebP, GIF, or PDF files.');
        }

        $contents = file_get_contents($temporaryPath);
        if ($contents === false) {
            throw new RuntimeException('Unable to read an uploaded report attachment.');
        }

        $path = 'reports/' . bin2hex(random_bytes(16)) . '.' . $extensions[$mimeType];
        $this->request(
            'POST',
            '/object/' . rawurlencode($this->bucket) . '/' . $this->encodeObjectPath($path),
            $contents,
            $mimeType,
            ['x-upsert: false']
        );

        return $path;
    }

    public function createSignedUrl(string $path, int $expiresIn = 3600): string
    {
        $response = $this->request(
            'POST',
            '/object/sign/' . rawurlencode($this->bucket) . '/' . $this->encodeObjectPath($path),
            json_encode(['expiresIn' => $expiresIn], JSON_THROW_ON_ERROR),
            'application/json'
        );

        $result = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        $signedPath = $result['signedURL'] ?? null;
        if (!is_string($signedPath) || $signedPath === '') {
            throw new RuntimeException('Supabase Storage did not return a signed URL.');
        }

        if (str_starts_with($signedPath, 'https://') || str_starts_with($signedPath, 'http://')) {
            return $signedPath;
        }

        if (str_starts_with($signedPath, '/storage/v1/')) {
            return $this->baseUrl . $signedPath;
        }

        return $this->baseUrl . '/storage/v1/' . ltrim($signedPath, '/');
    }

    private function request(
        string $method,
        string $path,
        string $body,
        string $contentType,
        array $extraHeaders = []
    ): string {
        $curl = curl_init($this->baseUrl . '/storage/v1' . $path);
        if ($curl === false) {
            throw new RuntimeException('Unable to initialize the Supabase Storage request.');
        }

        curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array_merge([
                'apikey: ' . $this->serviceKey,
                'Authorization: Bearer ' . $this->serviceKey,
                'Content-Type: ' . $contentType,
            ], $extraHeaders),
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 60,
        ]);

        $response = curl_exec($curl);
        $status = (int)curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $failed = $response === false;
        curl_close($curl);

        if ($failed || $status < 200 || $status >= 300) {
            throw new RuntimeException('Supabase Storage request failed with HTTP status ' . $status . '.');
        }

        return $response;
    }

    private function encodeObjectPath(string $path): string
    {
        return implode('/', array_map('rawurlencode', explode('/', ltrim($path, '/'))));
    }
}
