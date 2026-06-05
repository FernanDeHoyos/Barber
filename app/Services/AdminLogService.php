<?php

namespace App\Services;

use App\Models\AdminLog;
use Illuminate\Http\Request;

class AdminLogService
{
    public static function log(Request $request, string $event, ?string $description = null, array $metadata = []): AdminLog
    {
        return AdminLog::create([
            'user_id' => $request->user()?->id,
            'event' => $event,
            'target_type' => $metadata['target_type'] ?? null,
            'target_id' => $metadata['target_id'] ?? null,
            'description' => $description,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => $metadata,
        ]);
    }
}
