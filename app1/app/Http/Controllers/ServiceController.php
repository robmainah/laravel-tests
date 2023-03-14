<?php

namespace App\Http\Controllers;

use Google\Client;
use App\Models\Task;
use App\Models\Service;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Storage;
use App\Services\GoogleDriveService;
use App\Services\ZipperService;
use Symfony\Component\HttpFoundation\Response;

class ServiceController extends Controller
{
    public const GOOGLE_DRIVE_SCOPES = [
        "https://www.googleapis.com/auth/drive",
        "https://www.googleapis.com/auth/drive.file",
    ];

    public function connect($service, Client $client)
    {
        if ($service == 'google-drive') {
            $client->addScope(self::GOOGLE_DRIVE_SCOPES);

            $url = $client->createAuthUrl();

            return response(['url' => $url]);
        }
    }
    
    public function callback(Client $client)
    {
        $accessToken = $client->fetchAccessTokenWithAuthCode(request('code'));

        $service = Service::create([
            'name' => 'google-drive',
            'user_id' => auth()->id(),
            'token' => $accessToken,
        ]);

        return $service;
    }

    public function store(Service $service, GoogleDriveService $googleDriveService)
    {
        $tasks = Task::where('created_at', '>=', now()->subDays(7))->get();

        $jsonFileName = "tasks_dump.json";
        Storage::put("public/temp/$jsonFileName", TaskResource::collection($tasks)->toJson());

        $zipFileName = ZipperService::createZipFile($jsonFileName);

        $googleDriveService->uploadFile($service->token['access_token'], $zipFileName);

        Storage::deleteDirectory('public/temp');
        return response('Uploaded', Response::HTTP_CREATED);
    }
}
