<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use App\Exports\ActivityExport;
use Maatwebsite\Excel\Facades\Excel;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('user')->latest();

        // Filter search (description or user name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  })
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        // Filter Type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter Date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $activities = $query->paginate(10)->withQueryString();
        
        // Get unique types for filter dropdown
        $types = Activity::select('type')->distinct()->pluck('type');

        return view('activities.index', compact('activities', 'types'));
    }
    public function export(Request $request)
    {
        $query = Activity::with('user')->latest();

        // Re-apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  })
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        return Excel::download(new ActivityExport($query), 'riwayat_aktivitas_' . now()->format('Ymd_His') . '.xlsx');
    }
}
