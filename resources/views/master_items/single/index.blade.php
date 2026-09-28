@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Detail Master Item</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            @if ($item->foto_url)
                                <img src="{{ $item->foto_url }}" class="img-fluid img-thumbnail" alt="{{ $item->nama }}">
                            @else
                                <span class="text-muted">Tidak ada foto</span>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <table class="table">
                                <tr><th width="150">Kode</th><td>{{ $item->kode }}</td></tr>
                                <tr><th>Nama</th><td>{{ $item->nama }}</td></tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td>
                                        @forelse ($item->kategoris as $k)
                                            <a href="{{ url('kategori/view/' . $k->kode) }}"
                                            class="badge bg-info text-dark text-decoration-none">{{ $k->nama }}</a>
                                        @empty
                                            -
                                        @endforelse
                                    </td>
                                </tr>
                                <tr><th>Jenis</th><td>{{ $item->jenis }}</td></tr>
                                <tr><th>Supplier</th><td>{{ $item->supplier }}</td></tr>
                                <tr><th>Harga Beli</th><td>{{ number_format($item->harga_beli, 0, ',', '.') }}</td></tr>
                                <tr><th>Laba</th><td>{{ $item->laba }}%</td></tr>
                                <tr><th>Harga Jual</th><td>{{ number_format($item->harga_jual, 0, ',', '.') }}</td></tr>
                            </table>
                        </div>
                    </div>
                    <a href="{{ url('master-items') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection