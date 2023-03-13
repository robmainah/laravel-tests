<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use App\Models\Service;
use App\Models\Task;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Http\MediaFileUpload;
use ZipArchive;

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
        $tasks = Task::where('created_at', '>=', now()->subDays(7))
            ->get();
        
        $fileName = "tasks_dump.json";
        Storage::put("public/temp/$fileName", $tasks->toJson());

        $zip = new ZipArchive();
        $zipFileName = storage_path('app/public/temp/'.now()->timestamp.'-task.zip');

        if ($zip->open($zipFileName, ZipArchive::CREATE) == true) {
            $zip->addFile(storage_path('app/public/temp/'.$fileName));
            $zip->close();
        }        
        
        $accessToken = $service->token['access_token'];

        $client->setAccessToken($accessToken);

        $service = new Drive($client);
        $file = new DriveFile($client);

        $file->setName("new zip!");
        $result = $service->files->create(
            $file,
            [
                'data' => file_get_contents($zipFileName),
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart'
            ]
        );
        return response('Uploaded', 201);
    }
}
