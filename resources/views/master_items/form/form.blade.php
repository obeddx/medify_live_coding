@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" enctype="multipart/form-data">
    @csrf
    @if($method == 'edit')
    <div class="form-group">
        <label>Kode Barang</label>
        <input type="text" class="form-control" name="kode_barang" required readonly value="{{ $item->kode ?? '' }}">
    </div>
    @endif

    <div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" name="nama" required value="{{ old('nama', $item->nama ?? '') }}">
    </div>

    <div class="form-group">
        <label>Harga Beli</label>
        <input type="number" class="form-control" name="harga_beli" required value="{{ old('harga_beli', $item->harga_beli ?? '') }}">
    </div>

    <div class="form-group">
        <label>Laba (dalam persen)</label>
        <input type="number" class="form-control" name="laba" required value="{{ old('laba', $item->laba ?? '') }}">
    </div>

    @php $selected = old('supplier', $item->supplier ?? ''); @endphp
    <div class="form-group">
        <label>Supplier</label>
        <select class="form-control" required name="supplier">
            <option value="">--Pilih--</option>
            @foreach (['Tokopaedi', 'Bukulapuk', 'TokoBagas', 'E Commurz', 'Blublu'] as $opt)
                <option value="{{ $opt }}" {{ $selected == $opt ? 'selected' : '' }}>{{ $opt }}</option>
            @endforeach
        </select>
    </div>

    @php $selected = old('jenis', $item->jenis ?? ''); @endphp
    <div class="form-group">
        <label>Jenis</label>
        <select class="form-control" required name="jenis">
            <option value="">--Pilih--</option>
            @foreach (['Obat', 'Alkes', 'Matkes', 'Umum', 'ATK'] as $opt)
                <option value="{{ $opt }}" {{ $selected == $opt ? 'selected' : '' }}>{{ $opt }}</option>
            @endforeach
        </select>
    </div>

    {{-- Kategori (many to many) --}}
    @php
        $selectedKategori = old('kategori_ids', isset($item->id) ? $item->kategoris->pluck('id')->all() : []);
    @endphp
    <div class="form-group">
        <label>Kategori</label>
        <select class="form-control" name="kategori_ids[]" multiple size="5">
            @foreach ($kategoris as $kategori)
                <option value="{{ $kategori->id }}" {{ in_array($kategori->id, $selectedKategori) ? 'selected' : '' }}>
                    {{ $kategori->nama }} ({{ $kategori->kode }})
                </option>
            @endforeach
        </select>
        <small class="text-muted">Tahan Ctrl/Cmd untuk memilih lebih dari satu kategori.</small>
    </div>

    {{-- Foto --}}
    <div class="form-group">
        <label>Foto</label>
        <input type="file" class="form-control" name="foto" id="input-foto" accept="image/*">
        <img id="preview-foto" src="{{ $item->foto_url ?? '' }}" alt="Preview"
             class="img-thumbnail mt-2" style="max-height:150px; {{ !empty($item->foto_url) ? '' : 'display:none;' }}">
    </div>

    <button class="btn btn-primary mt-3">Submit</button>
</form>

<script>
    document.getElementById('input-foto').addEventListener('change', function (e) {
        var file = e.target.files[0];
        var img = document.getElementById('preview-foto');
        if (file) {
            img.src = URL.createObjectURL(file);
            img.style.display = 'block';
        }
    });
</script>