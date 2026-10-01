<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class FirebaseNotificationService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Services\FirebaseNotificationService::class;
    }
}