<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

class GoogleDriveService
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function uploadFile($accessToken, $zipFileName)
    {
        $this->client->setAccessToken($accessToken);

        $drive = new Drive($this->client);
        $file = new DriveFile($this->client);

        $drive->files->create(
            $file,
            [
                'data' => file_get_contents($zipFileName),
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart'
            ]
        );
    }
}
