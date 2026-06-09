<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
public function index()
{
    $query = Order::where('user_id', auth()->id());

    if (request('status')) {
        $query->where('status', request('status'));
    }

    $semua = $query->get();
    return view('riwayat', compact('semua'));
}
public function updateStatus(Request $request, $id)
{
    $request->validate(['status' => 'required|in:Menunggu,Diproses,Selesai']);
    Order::where('user_id', auth()->id())->findOrFail($id)->update(['status' => $request->status]);
    return redirect()->route('riwayat')->with('success', 'Status diperbarui!');
}

public function hapus($id)
{
    Order::where('user_id', auth()->id())->findOrFail($id)->delete();
    return redirect()->route('riwayat')->with('success', 'Pesanan dihapus!');
}

public function hapusSemua()
{
    Order::where('user_id', auth()->id())->delete();
    return redirect()->route('riwayat')->with('success', 'Semua riwayat dihapus!');
}
}