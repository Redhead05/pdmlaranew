@extends('app.layout')
@section('title', 'Tambah Lembaga')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h4>Tambah Lembaga</h4>
                <form action="{{ route('admin.lembagas.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">NPSN</label>
                        <input type="text" name="npsn" class="form-control" value="{{ old('npsn') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama (Satuan Pen)</label>
                        <input type="text" name="satuan_pen" class="form-control" value="{{ old('satuan_pen') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control">{{ old('alamat') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kecamatan</label>
                        <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kabupaten</label>
                        <input type="text" name="kabupaten" class="form-control" value="{{ old('kabupaten') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Latitude</label>
                        <input type="text" name="latitude" class="form-control" value="{{ old('latitude') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Longitude</label>
                        <input type="text" name="longitude" class="form-control" value="{{ old('longitude') }}">
                    </div>
                    <button class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection

