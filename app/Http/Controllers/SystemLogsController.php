<?php

namespace App\Http\Controllers;

use App\Models\SystemLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SystemLogsController extends Controller
{
    public function index(Request $request)
    {
        $query = SystemLog::query();
        $emailColumn = Schema::hasColumn('system_logs', 'user_email') ? 'user_email' : 'user_name';
        $roleColumn = Schema::hasColumn('system_logs', 'user_role') ? 'user_role' : 'role_name';

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        // Filter by role
        if ($request->filled('role') && Schema::hasColumn('system_logs', $roleColumn)) {
            $query->where($roleColumn, $request->input('role'));
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        // Search by description or email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search, $emailColumn) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere($emailColumn, 'like', "%{$search}%")
                    ->orWhere('route_name', 'like', "%{$search}%");
            });
        }

        // Paginate results
        $logs = $query->orderByDesc('created_at')->paginate(30)->appends($request->except('page'));

        // Get unique users for filter dropdown
        $users = User::query()
            ->orderBy('full_name')
            ->pluck('full_name', 'id');

        // Get unique roles for filter dropdown (safely handle if column doesn't exist)
        $roles = collect();
        try {
            $roles = SystemLog::distinct()
                ->whereNotNull($roleColumn)
                ->pluck($roleColumn)
                ->filter()
                ->values();
        } catch (\Exception $e) {
            $roles = collect();
        }

        // Get unique actions for filter dropdown
        $actions = SystemLog::distinct()
            ->whereNotNull('action')
            ->pluck('action')
            ->filter()
            ->values();

        // Get statistics
        $stats = [
            'total' => SystemLog::count(),
            'today' => SystemLog::whereDate('created_at', today())->count(),
            'this_week' => SystemLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        return view('system_logs.index', compact('logs', 'users', 'roles', 'actions', 'stats', 'emailColumn', 'roleColumn'));
    }

    public function show(SystemLog $systemLog)
    {
        return view('system_logs.show', compact('systemLog'));
    }
}
