@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Detail Kategori</span>
                    <a href="{{ url('kategori/pdf/' . $kategori->kode) }}" class="btn btn-danger btn-sm">Download PDF</a>
                </div>
                <div class="card-body">
                    <table class="table w-auto">
                        <tr><th width="150">Kode Kategori</th><td>{{ $kategori->kode }}</td></tr>
                        <tr><th>Nama Kategori</th><td>{{ $kategori->nama }}</td></tr>
                    </table>

                    <h5 class="mt-4">Item dengan kategori ini</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Jenis</th>
                                    <th>Supplier</th>
                                    <th>Harga Beli</th>
                                    <th>Laba</th>
                                    <th>Harga Jual</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse ($kategori->masterItems as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->kode }}</td>
                                    <td><a href="{{ url('master-items/view/' . $item->kode) }}">{{ $item->nama }}</a></td>
                                    <td>{{ $item->jenis }}</td>
                                    <td>{{ $item->supplier }}</td>
                                    <td>{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                    <td>{{ $item->laba }}%</td>
                                    <td>{{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center">Belum ada item pada kategori ini.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <a href="{{ url('kategori') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection