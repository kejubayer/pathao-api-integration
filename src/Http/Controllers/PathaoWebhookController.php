<?php

namespace Kejubayer\PathaoIntegration\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Kejubayer\PathaoIntegration\Models\PathaoParcelStatus;

class PathaoWebhookController extends Controller
{
    public function parcelStatus(Request $request)
    {
        $payload = $request->all();

        $status = PathaoParcelStatus::create([
            'consignment_id' => $payload['consignment_id'] ?? $payload['consignmentId'] ?? null,
            'merchant_order_id' => $payload['merchant_order_id'] ?? $payload['merchantOrderId'] ?? null,
            'status' => $payload['order_status'] ?? $payload['parcel_status'] ?? $payload['status'] ?? null,
            'payload' => $payload,
            'received_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Parcel status received.',
            'data' => [
                'id' => $status->id,
            ],
        ]);
    }
}
