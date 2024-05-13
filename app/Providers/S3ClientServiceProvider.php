<?php

namespace App\Providers;

use Aws\S3\S3Client;
use Illuminate\Support\ServiceProvider;

class S3ClientServiceProvider extends ServiceProvider
{
    // we are using service provider to make the S3Client singleton, this is
    // the best practice recommended by Laravel, however we can also just simply
    // instantiate the S3Client instance on MainController.
    public function register(): void
    {
        $this->app->singleton(S3Client::class, function(){
            return new S3Client([
                'version'     => 'latest',
                'region'      => config('filesystems.disks.s3.region'),
                'credentials' => [
                    'key'    => config('filesystems.disks.s3.key'),
                    'secret' => config('filesystems.disks.s3.secret'),
                ],
                'endpoint' => config('filesystems.disks.s3.endpoint'),
                'use_path_style_endpoint' => config('filesystems.disks.s3.use_path_style_endpoint')
            ]);
        });
    }

    public function boot(): void
    {
    }
}
