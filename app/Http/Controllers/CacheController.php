<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\JsonResponse;

class CacheController extends Controller
{
    public function clearAllCache(): JsonResponse
    {
        $commands = [
            'cache:clear',
            'config:clear',
            'config:cache',
            'route:clear',
            'route:cache',
            'view:clear'
        ];

        $output = [];
        foreach ($commands as $command) {
            Artisan::call($command);
            $output[] = Artisan::output();
        }

        return response()->json(['message' => 'All caches cleared', 'output' => $output]);
    }
}
