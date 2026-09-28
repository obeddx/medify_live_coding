<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    public function index()
    {
        return view('kategori.index.index');
    }

    public function search(Request $request)
    {
        // withCount = 1 query agregat, tidak ada N+1 saat menampilkan jumlah item
        $data_search = Kategori::query()->withCount('masterItems');

        if ($request->filled('kode')) {
            $data_search->where('kode', 'LIKE', '%' . $request->kode . '%');
        }
        if ($request->filled('nama')) {
            $data_search->where('nama', 'LIKE', '%' . $request->nama . '%');
        }

        $data = $data_search->orderBy('id')->get()->map(function ($kategori) {
            return [
                'id'          => $kategori->id,
                'kode'        => $kategori->kode,
                'nama'        => $kategori->nama,
                'jumlah_item' => $kategori->master_items_count,
            ];
        });

        return response()->json([
            'status' => 200,
            'data'   => $data,
        ]);
    }

    public function formView($method, $id = 0)
    {
        $data['kategori'] = $method == 'new' ? new Kategori : Kategori::findOrFail($id);
        $data['method'] = $method;
        return view('kategori.form.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $request->validate([
            'kode' => ['required', 'alpha_dash', 'max:50', Rule::unique('kategoris', 'kode')->ignore($id)],
            'nama' => 'required|string|max:255',
        ]);

        $kategori = $method == 'new' ? new Kategori : Kategori::findOrFail($id);
        $kategori->kode = $request->kode;
        $kategori->nama = $request->nama;
        $kategori->save();

        return redirect('kategori');
    }

    public function singleView($kode)
    {
        // eager loading item yang memiliki kategori ini
        $data['kategori'] = Kategori::with('masterItems')->where('kode', $kode)->firstOrFail();
        return view('kategori.single.index', $data);
    }

    public function delete($id)
    {
        Kategori::findOrFail($id)->delete();
        return redirect('kategori');
    }

    public function pdf($kode)
    {
        $kategori = Kategori::with('masterItems')->where('kode', $kode)->firstOrFail();

        $pdf = Pdf::loadView('kategori.pdf', [
            'kategori'  => $kategori,
            'printedAt' => now()->format('d-m-Y H:i:s'),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('kategori-' . $kategori->kode . '.pdf');
    }
}