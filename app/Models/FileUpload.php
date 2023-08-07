<?php

namespace App\Models;

use Exception;
use App\Helpers\Str;

class FileUpload {
    private string $extension;
    private string $currentLocation;
    private string $generatedName;

    public function __construct(private array $file)
    {
        $this->extension = strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));
        $this->currentLocation = $this->file['tmp_name'];
        $this->generatedName = Str::token() . '.' . $this->extension;
    }

    public function saveIn(string $folder): void
    {
        $destination = "{$folder}/{$this->generatedName}";

        $uploadedSuccessfully = move_uploaded_file($this->currentLocation, $destination);

        if (!$uploadedSuccessfully) {
            throw new Exception('We encountered an error uploading the file.');
        }
    }

    public function getGeneratedName(): string
    {
        return $this->generatedName;
    }

    public static function delete(string $path): bool
    {
        return unlink(ltrim($path, '/'));
    }
}
