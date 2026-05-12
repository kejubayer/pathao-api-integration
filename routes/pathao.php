<?php

use Illuminate\Support\Facades\Route;
use Kejubayer\PathaoIntegration\Http\Controllers\PathaoWebhookController;

Route::post(config('pathao.webhook_route', 'pathao/webhook/parcel-status'), [PathaoWebhookController::class, 'parcelStatus'])
    ->name('pathao.webhook.parcel-status');
