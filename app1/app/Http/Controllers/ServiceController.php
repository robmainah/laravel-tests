<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Illuminate\Support\Facades\Config;
use App\Models\Service;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Http\MediaFileUpload;

class ServiceController extends Controller
{
    public const GOOGLE_DRIVE_SCOPES = [
        "https://www.googleapis.com/auth/drive",
        "https://www.googleapis.com/auth/drive.file",
    ];

    public function connect(Request $request, Client $client)
    {
        if (request('service') == 'google-drive') {
            $client->addScopes(self::GOOGLE_DRIVE_SCOPES);

            $url = $client->createAuthUrl();

            return response(['url' => $url]);
        }
    }
    
    public function callback(Client $client)
    {
        $accessToken = $client->fetchAccessTokenWithAuthCode(request('code'));

        $service = Service::create([
            'name' => request('name'),
            'user_id' => auth()->id(),
            'token' => ['access_token' => $accessToken],
        ]);

        return $service;
    }

    public function store(Request $request, Service $service, Client $client)
    {
        $accessToken = $service->token['access_token'];

        $client->setAccessToken($accessToken);
        $service = new Drive($client);
        $file = new DriveFile($client);

        DEFINE("TESTFILE", 'testfile-small.txt');
        if (!file_exists(TESTFILE)) {
            $fh = fopen(TESTFILE, 'w');
            fseek($fh, 1024 * 1024);
            fwrite($fh, "!", 1);
            fclose($fh);
        }

        $file->setName("Hello World!");
        $result = $service->files->create(
            $file,
            [
                'data' => file_get_contents(TESTFILE),
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart'
            ]
        );
        return response('Uploaded', 201);
    }
}
