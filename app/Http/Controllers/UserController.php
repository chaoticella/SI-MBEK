<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $role = $request->input('role');
        $status = $request->input('status');

        $query = User::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (!empty($role) && in_array($role, ['pengelola', 'pegawai'])) {
            $query->where('role', $role);
        }

        if ($status !== null && $status !== '') {
            if ($status === 'aktif') {
                $query->where('status_aktif', true);
            } elseif ($status === 'nonaktif') {
                $query->where('status_aktif', false);
            }
        }

        // Summary stats
        $totalUsers = User::count();
        $totalPengelola = User::whereIn('role', ['pengelola', 'pemilik', 'admin'])->count();
        $totalPegawai = User::where('role', 'pegawai')->count();
        $totalAktif = User::where('status_aktif', true)->count();

        $users = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();

        return view('users.index', compact(
            'users',
            'totalUsers',
            'totalPengelola',
            'totalPegawai',
            'totalAktif',
            'search',
            'role',
            'status'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['status_aktif'] = $request->boolean('status_aktif', true);

        $user = User::create($data);

        return redirect()->route('users.index')
            ->with('success', "Akun pengguna {$user->name} berhasil ditambahkan!");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['status_aktif'] = $request->boolean('status_aktif', true);

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', "Akun pengguna {$user->name} berhasil diperbarui!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "Akun pengguna {$userName} berhasil dihapus dari sistem.");
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri yang sedang aktif digunakan.');
        }

        $user->status_aktif = !$user->status_aktif;
        $user->save();

        $statusText = $user->status_aktif ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Status akun {$user->name} berhasil {$statusText}.");
    }
}
