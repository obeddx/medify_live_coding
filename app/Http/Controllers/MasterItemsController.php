<?php

namespace App\Http\Controllers;

use App\Exports\MasterItemsExport;
use App\Models\Kategori;
use App\Models\MasterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class MasterItemsController extends Controller
{
    public function index()
    {
        // dipakai untuk dropdown filter kategori
        $data['kategoris'] = Kategori::orderBy('nama')->get();
        return view('master_items.index.index', $data);
    }

    public function search(Request $request)
    {
        $data_search = MasterItem::query()->with('kategoris:id,nama');

        if ($request->filled('kode')) {
            $data_search->where('kode', $request->kode);
        }
        if ($request->filled('nama')) {
            $data_search->where('nama', 'LIKE', '%' . $request->nama . '%');
        }
        if ($request->filled('hargamin')) {
            $data_search->where('harga_beli', '>=', $request->hargamin);
        }
        if ($request->filled('hargamax')) {
            $data_search->where('harga_beli', '<=', $request->hargamax);
        }
        if ($request->filled('kategori_id')) {
            $kategori_id = $request->kategori_id;
            $data_search->whereHas('kategoris', function ($q) use ($kategori_id) {
                $q->where('kategoris.id', $kategori_id);
            });
        }

        $data = $data_search->orderBy('id')->get()->map(function ($item) {
            return [
                'id'         => $item->id,
                'kode'       => $item->kode,
                'nama'       => $item->nama,
                'jenis'      => $item->jenis,
                'harga_beli' => $item->harga_beli,
                'harga_jual' => $item->harga_jual,
                'supplier'   => $item->supplier,
                'foto_url'   => $item->foto_url,
                'kategoris'  => $item->kategoris->map(function ($k) {
                    return ['id' => $k->id, 'nama' => $k->nama];
                })->values(),
            ];
        });

        return response()->json([
            'status' => 200,
            'data'   => $data,
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = new MasterItem;
        } else {
            $item = MasterItem::with('kategoris')->findOrFail($id);
        }

        $data['item'] = $item;
        $data['method'] = $method;
        $data['kategoris'] = Kategori::orderBy('nama')->get();
        return view('master_items.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['item'] = MasterItem::with('kategoris')->where('kode', $kode)->firstOrFail();
        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $request->validate([
            'nama'           => 'required|string|max:255',
            'harga_beli'     => 'required|integer|min:0',
            'laba'           => 'required|integer|min:0',
            'supplier'       => 'required|string|max:255',
            'jenis'          => 'required|string|max:255',
            'foto'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'kategori_ids'   => 'nullable|array',
            'kategori_ids.*' => 'exists:kategoris,id',
        ]);

        if ($method == 'new') {
            $data_item = new MasterItem;
            $kode = MasterItem::count('id');
            $kode = $kode + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
            sleep(3);
        } else {
            $data_item = MasterItem::findOrFail($id);
            $kode = $data_item->kode;
        }

        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;
        $data_item->jenis = $request->jenis;

        // upload foto (hapus foto lama jika diganti)
        if ($request->hasFile('foto')) {
            if ($data_item->foto) {
                Storage::disk('public')->delete($data_item->foto);
            }
            $data_item->foto = $request->file('foto')->store('master_items', 'public');
        }

        $data_item->save();

        // sinkronisasi kategori (harus setelah save() agar id sudah ada)
        $data_item->kategoris()->sync($request->input('kategori_ids', []));

        return redirect('master-items');
    }

    public function delete($id)
    {
        MasterItem::find($id)->delete();
        return redirect('master-items');
    }

    public function export()
    {
        return Excel::download(
            new MasterItemsExport,
            'master-items-' . now()->format('Ymd-His') . '.xlsx'
        );
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach ($data as $item) {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100, 1000000);
            $item->laba = rand(10, 99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi', 'Bukulapuk', 'TokoBagas', 'E Commurz', 'Blublu'];
        $random = rand(0, 4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat', 'Alkes', 'Matkes', 'Umum', 'ATK'];
        $random = rand(0, 4);
        return $array[$random];
    }
}