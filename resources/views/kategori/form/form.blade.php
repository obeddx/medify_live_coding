@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST">
    @csrf
    <div class="form-group">
        <label>Kode Kategori</label>
        <input type="text" class="form-control" name="kode" required value="{{ old('kode', $kategori->kode ?? '') }}">
    </div>

    <div class="form-group">
        <label>Nama Kategori</label>
        <input type="text" class="form-control" name="nama" required value="{{ old('nama', $kategori->nama ?? '') }}">
    </div>

    <button class="btn btn-primary mt-3">Submit</button>
</form>