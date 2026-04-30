<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')
            ->when(request('role'), fn($q, $r) => $q->whereHas('role', fn($q) => $q->where('role_name', $r)))
            ->latest()->paginate(15)->withQueryString();
        $roles = Role::all();
        return view('users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        User::create($request->validated());
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load('role');
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(StoreUserRequest $request, User $user)
    {
        $data = $request->validated();
        if (empty($data['password'])) unset($data['password']);
        $user->update($data);
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Cannot delete your own account
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Dynamically check every table that has a user foreign key
        // Only checks tables that actually exist — safe regardless of table names
        $tableChecks = [
            'finance_records'      => 'recorded_by',
            'productions'          => 'created_by',
            'production_records'   => 'created_by',
            'quality_controls'     => 'inspected_by',
            'quality_inspections'  => 'inspected_by',
            'stock_transactions'   => 'transacted_by',
            'inventories'          => 'managed_by',
        ];

        $blocked = [];

        foreach ($tableChecks as $table => $column) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, $column)) {
                if (DB::table($table)->where($column, $user->id)->exists()) {
                    $blocked[] = ucwords(str_replace('_', ' ', $table));
                }
            }
        }

        if (!empty($blocked)) {
            $list = implode(', ', $blocked);
            return back()->with('error',
                "Cannot delete \"{$user->full_name}\" — this user has existing records in: {$list}. " .
                "Reassign or remove those records first."
            );
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}




























































































































































































































































































































































































































































































































































































