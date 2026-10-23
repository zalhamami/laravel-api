<?php

namespace App\Providers;

use Google\Client as GoogleClient;
use Google\Service\Drive;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Masbug\Flysystem\GoogleDriveAdapter;

class GoogleDriveServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('gdrive', function ($app, $config) {
            $client = new GoogleClient();
            $client->setClientId($config['client_id']);
            $client->setClientSecret($config['client_secret']);
            $client->refreshToken($config['refresh_token']);
            $service = new Drive($client);

            $options = [];
            if (! empty($config['folder_id'])) {
                $options['sharedFolderId'] = $config['folder_id'];
            }
            $adapter = new GoogleDriveAdapter($service, null, $options);

            return new FilesystemAdapter(new Filesystem($adapter, $config), $adapter, $config);
        });
    }
}
