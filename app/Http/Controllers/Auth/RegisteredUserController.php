<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Petani;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', 'in:pelanggan,petani'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        // Kalau daftar sebagai petani, auto insert ke tabel petanis
        if ($request->role === 'petani') {
            Petani::create([
                'user_id'   => $user->id,
                'kode'      => 'PTN' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'nama'      => $user->name,
                'email'     => $user->email,
                'komoditas' => 'Padi', // default, bisa diubah nanti
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        // Redirect berdasarkan role
        if ($request->role === 'petani') {
            return redirect()->route('petani.produk.index');
        }

        return redirect()->route('beranda');
    }
}