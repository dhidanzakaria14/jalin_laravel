<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        $data = Layanan::all();
        return view('layanan.index', compact('data'));
    }

    public function store(Request $request) {
        Layanan::create($request->all());
        return redirect('/layanan')->with('success', 'Data berhasil ditambah!');
    }

    public function destroy($id_layanan) {
        Layanan::destroy($id_layanan);
        return redirect('/layanan')->with('success', 'Data berhasil dihapus!');
    }
}
//ini punya marsela 