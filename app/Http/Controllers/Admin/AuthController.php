<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Organizer;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login admin
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses login admin
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password salah.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // Admin
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Organizer
        if ($user->role === 'organizer') {

            $organizer = Organizer::where('user_id', $user->id)->first();

            if (!$organizer) {
                Auth::logout();

                return redirect()
                    ->route('login')
                    ->with('error', 'Data organizer tidak ditemukan.');
            }

            if ($organizer->status !== 'approved') {
                Auth::logout();

                return redirect()
                    ->route('login')
                    ->with('error', 'Akun organizer belum disetujui admin.');
            }

            return redirect()->route('organizer.dashboard');
        }

        Auth::logout();

        return redirect()
            ->route('admin.login')
            ->with('error', 'Role tidak dikenali.');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'logo'        => 'nullable|url|max:500',
            'email'       => 'required|email|unique:users,email|unique:organizers,email',
            'phone'       => 'required|string|max:20',
            'description' => 'nullable|string',
            'password'    => 'required|confirmed|min:8',
        ]);

        // Simpan akun login
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'organizer',
        ]);

        // Simpan data organizer
        Organizer::create([
            'user_id'     => $user->id,
            'name'        => $validated['name'],
            'logo'        => $validated['logo'] ?? null,
            'email'       => $validated['email'],
            'phone'       => $validated['phone'],
            'description' => $validated['description'] ?? null,
            'status'      => 'pending',
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Registrasi berhasil. Silakan tunggu persetujuan admin.');
    }

    /**
     * Proses logout admin/organizer
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
