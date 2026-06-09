<?php

namespace App\Http\Controllers;

use App\Models\Petani;
use Illuminate\Http\Request;

class PetaniController extends Controller
{
    public function index()
    {
        $petani = Petani::paginate(10);
        return view('petani.index', compact('petani'));
    }

    public function create()
    {
        return view('petani.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode'      => 'required|string|unique:petanis,kode',
            'nama'      => 'required|string|min:3',
            'email'     => 'required|email|unique:petanis,email',
            'telepon'   => 'nullable|string|max:20',
            'komoditas' => 'required|in:Padi,Jagung,Sayur,Buah',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('petani', 'public');
        }

        Petani::create([
            'user_id'   => auth()->id(),
            'kode'      => $request->kode,
            'nama'      => $request->nama,
            'email'     => $request->email,
            'telepon'   => $request->telepon,
            'komoditas' => $request->komoditas,
            'foto'      => $fotoPath,
        ]);

        return redirect()->route('petani.index')->with('success', 'Petani berhasil ditambahkan!');
    }

    public function show(Petani $petani)
    {
        return view('petani.show', compact('petani'));
    }

    public function edit(Petani $petani)
    {
        return view('petani.edit', compact('petani'));
    }

    public function update(Request $request, Petani $petani)
    {
        $request->validate([
            'kode'      => 'required|string|unique:petanis,kode,' . $petani->id,
            'nama'      => 'required|string|min:3',
            'email'     => 'required|email|unique:petanis,email,' . $petani->id,
            'telepon'   => 'nullable|string|max:20',
            'komoditas' => 'required|in:Padi,Jagung,Sayur,Buah',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoPath = $petani->foto;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('petani', 'public');
        }

        $petani->update([
            'kode'      => $request->kode,
            'nama'      => $request->nama,
            'email'     => $request->email,
            'telepon'   => $request->telepon,
            'komoditas' => $request->komoditas,
            'foto'      => $fotoPath,
        ]);

        return redirect()->route('petani.index')->with('success', 'Data petani berhasil diperbarui!');
    }

    public function destroy(Petani $petani)
    {
        $petani->delete();
        return redirect()->route('petani.index')->with('success', 'Petani berhasil dihapus!');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $petani = Petani::where(function($q) use ($query) {
            $q->where('nama', 'like', "%$query%")
              ->orWhere('kode', 'like', "%$query%")
              ->orWhere('komoditas', 'like', "%$query%");
        })->get(['id', 'kode', 'nama', 'email', 'telepon', 'komoditas', 'foto']);

        return response()->json($petani);
    }
}