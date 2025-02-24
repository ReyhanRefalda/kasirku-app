<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Str;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = Activity::latest()->paginate(10);
    
        $logs->setCollection($logs->getCollection()->map(function ($log) {
            $log->formatted_description = ucfirst(str_replace(
                ['created', 'updated', 'deleted'],
                ['Dibuat', 'Diperbarui', 'Dihapus'], // "D" besar
                strtolower($log->description) // Pastikan huruf kecil dulu
            ));
            return $log;
        }));
    
        return view('log.index', compact('logs'));
    }
    
    
    
    
    
}
