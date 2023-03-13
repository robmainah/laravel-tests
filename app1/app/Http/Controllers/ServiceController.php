<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Illuminate\Support\Facades\Config;
use App\Models\Service;

class ServiceController extends Controller
{
    public const GOOGLE_DRIVE_SCOPES = [
        "https://www.googleapis.com/auth/drive",
        "https://www.googleapis.com/auth/drive.file",
    ];

    public function connect(Request $request)
    {
        if (request('service') == 'google-drive') {
            $client = new Client();

            $client->setClientId(Config::get('services.google-drive.id'));
            $client->setClientSecret(Config::get('services.google-drive.secret'));
            $client->setRedirectUri(Config::get('services.google-drive.redirect_url'));

            $client->addScope(self::GOOGLE_DRIVE_SCOPES);

            $url = $client->createAuthUrl();

            return response(['url' => $url]);
        }
    }
    
    public function callback(Request $request)
    {
        $client = app(Client::class);
        
        $client->setClientId(Config::get('services.google-drive.id'));
        $client->setClientSecret(Config::get('services.google-drive.secret'));
        $client->setRedirectUri(Config::get('services.google-drive.redirect_url'));

        $accessToken = $client->fetchAccessTokenWithAuthCode(request('code'));

        $service = Service::create([
            'name' => request('name'),
            'user_id' => auth()->id(),
            'token' => json_encode(['access_token' => $accessToken])
        ]);

        return $service;
    }
}
