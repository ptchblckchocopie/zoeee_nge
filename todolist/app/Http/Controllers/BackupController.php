<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{
    public function runBackup(Request $request)
    {
        Artisan::call('backup:run');
        return response()->json(['message' => 'Backup started successfully.']);
    }
}
