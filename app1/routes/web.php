<?php

use Illuminate\Support\Facades\Route;
use Google\Client;
use Illuminate\Http\Request;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Http\MediaFileUpload;
use Illuminate\Support\Facades\Config;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/drive', function () {
    $client = new Client();

    $client->setClientId(Config::get('services.google-drive.id'));
    $client->setClientSecret(Config::get('services.google-drive.secret'));
    $client->setRedirectUri(Config::get('services.google-drive.redirect_url'));
    
    $client->addScope([
        "https://www.googleapis.com/auth/drive",
        "https://www.googleapis.com/auth/drive.file",
    ]);

    $url = $client->createAuthUrl();

    return redirect($url);
});
 
Route::get('/google-drive/callback', function () {
    $client = new Client();

    $client->setClientId(Config::get('services.google-drive.id'));
    $client->setClientSecret(Config::get('services.google-drive.secret'));
    $client->setRedirectUri(Config::get('services.google-drive.redirect_url'));

    $accessToken = $client->fetchAccessTokenWithAuthCode(request('code'));
    return $accessToken;
});

Route::get('upload', function () {
    $client = new Client();
    
    $accessToken = env('HARD_CODED_GOOGLE_ACCESS_TOKEN');

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

    // return $result;
});
