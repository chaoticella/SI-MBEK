<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Ensure user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengakses sistem.');
        }

        $user = Auth::user();

        // 2. Check if user account is active
        if (!$user->status_aktif) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Akun Anda dinonaktifkan oleh Pengelola. Silakan hubungi admin peternakan.');
        }

        // If no specific roles required, allow access
        if (empty($roles)) {
            return $next($request);
        }

        // Normalize roles (pengelola, pemilik, admin are treated with pengelola privileges)
        $userRole = strtolower($user->role ?? '');
        $allowedRoles = array_map('strtolower', $roles);

        $hasAccess = in_array($userRole, $allowedRoles);

        // Alias support: if requirement is 'pengelola', 'pemilik' is also allowed
        if (!$hasAccess && in_array('pengelola', $allowedRoles) && in_array($userRole, ['pemilik', 'admin'])) {
            $hasAccess = true;
        }

        if (!$hasAccess) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Anda tidak memiliki hak akses ke halaman ini.'
                ], 403);
            }

            // If user has insufficient permissions, show customized 403 or redirect back with flash message
            return response()->view('errors.403', [
                'message' => 'Akses Terbatas: Halaman ini hanya dapat diakses oleh peran ' . implode(', ', array_map('ucfirst', $roles)) . '.'
            ], 403);
        }

        return $next($request);
    }
}
