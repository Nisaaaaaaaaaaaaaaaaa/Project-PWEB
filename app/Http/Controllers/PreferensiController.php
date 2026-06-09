<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class PreferensiController extends Controller
{
    public function index(Request $request)
    {
        $raw  = $request->cookie('preferensi');
        $pref = $raw ? json_decode($raw, true) : [
            'tema'       => 'terang',
            'ukuran_font'=> 'sedang',
        ];

        return view('preferensi', compact('pref'));
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'tema'        => 'required|in:terang,gelap,hijau',
            'ukuran_font' => 'required|in:kecil,sedang,besar',
        ]);

        $data = $request->only(['tema', 'ukuran_font']);

        $cookie = Cookie::make(
            'preferensi',
            json_encode($data),
            60 * 24 * 30  
        );

        return response()->json([
            'status'  => 'ok',
            'message' => 'Preferensi berhasil disimpan!',
            'data'    => $data,
        ])->withCookie($cookie);
    }

    public function baca(Request $request)
    {
        $raw = $request->cookie('preferensi');

        if (! $raw) {
            return response()->json([
                'status'  => 'kosong',
                'message' => 'Belum ada preferensi tersimpan.',
                'data'    => null,
            ]);
        }

        return response()->json([
            'status'  => 'ok',
            'message' => 'Preferensi ditemukan.',
            'data'    => json_decode($raw, true),
        ]);
    }

    public function reset()
    {
        return response()->json([
            'status'  => 'ok',
            'message' => 'Preferensi direset ke default.',
        ])->withCookie(Cookie::forget('preferensi'));
    }
}