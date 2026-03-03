<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog; // Pastikan model ini sudah dibuat

class ActivityLogController extends Controller
{
    public function index()
    {
        // Ambil log terbaru, batasi misalnya 500 data terakhir agar tidak berat
        $logs = ActivityLog::with('user')->latest()->paginate(10);

        return view('admin.log.index', compact('logs'));
    }
}
