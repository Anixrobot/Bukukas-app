<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;

class GoogleController extends Controller
{
    // Fungsi buat ngelempar user ke halaman login Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Fungsi buat nangkep balikan data dari Google
    public function handleGoogleCallback()
    {
        try {
            // Tarik data user dari Google
            $googleUser = Socialite::driver('google')->user();

            // Cek apakah user dengan google_id ini udah ada di database
            $findUser = User::where('google_id', $googleUser->id)->first();

            if ($findUser) {
                // Skenario 1: User udah pernah login pakai Google -> Langsung masukin
                Auth::login($findUser);
                return redirect()->intended('/pribadi'); // Sesuaikan sama rute dashboard lu bro
                
            } else {
                // Skenario 2: User belum punya google_id, kita cek emailnya
                $existingUser = User::where('email', $googleUser->email)->first();

                if ($existingUser) {
                    // Kalau emailnya udah pernah daftar manual, kita gabungin (link) google_id-nya
                    $existingUser->update([
                        'google_id' => $googleUser->id,
                    ]);
                    Auth::login($existingUser);
                    
                } else {
                    // Skenario 3: Bener-bener user baru, otomatis daftarin!
                    $newUser = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'password' => null // Password dikosongin karena login pake Google
                    ]);
                    Auth::login($newUser);
                }
                
                return redirect()->intended('/pribadi'); // Arahin ke dashboard
            }
            
        } catch (Exception $e) {
            // Kalau misal tiba-tiba gagal/cancel
            return redirect('/login')->with('error', 'Waduh gagal login Google bro: ' . $e->getMessage());
        }
    }
}