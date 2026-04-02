<form action="{{ route('admin.tahap.store') }}" method="POST">
    @csrf
    <div class="modal fade" id="exampleModallg" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Tahap</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3">
                        <h3>
                            <label for="tahap" class="form-label fs-15">Tahap</label>
                        </h3>
                        <input
                            type="text"
                            class="form-control @error('tahap') is-invalid @enderror"
                            id="tahap"
                            name="tahap"
                            value="{{ old('tahap') }}"
                            required
                        >
                        @error('tahap')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                            </div>

                        <div class="mb-3">
                            <h3>
                                <label for="surat_keputusan" class="form-label fs-15">SK</label>
                            </h3>
                            <input
                                type="text"
                                class="form-control @error('surat_keputusan') is-invalid @enderror"
                                id="surat_keputusan"
                                name="surat_keputusan"
                                value="{{ old('surat_keputusan') }}"
                                required
                            >
                            @error('surat_keputusan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <h3>
                                <label for="allowed_kesanggupan_csv" class="form-label fs-15">Kesanggupan</label>
                            </h3>
                            <input
                                type="text"
                                class="form-control @error('allowed_kesanggupan_csv') is-invalid @enderror"
                                id="allowed_kesanggupan_csv"
                                name="allowed_kesanggupan_csv"
                                value="{{ old('allowed_kesanggupan_csv', '2,3,4,5,6') }}"
                                placeholder="e.g. 2,3,7,10"
                                required
                            >
                            <small class="text-secondary fs-15">Masukan Kesanggupan yang di pisahkan oleh comma, <br>Contoh: 2,3,7,10</small>
                            @error('allowed_kesanggupan_csv')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <h3>
                                <label for="start_date" class="form-label fs-15">Start date</label>
                            </h3>
                            <input
                                type="datetime-local"
                                class="form-control @error('start_date') is-invalid @enderror"
                                id="start_date"
                                name="start_date"
                                value="{{ old('start_date') }}"
                                required
                            >
                            @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <h3>
                                <label for="end_date" class="form-label fs-15">End date</label>
                            </h3>
                            <input
                                type="datetime-local"
                                class="form-control @error('end_date') is-invalid @enderror"
                                id="end_date"
                                name="end_date"
                                value="{{ old('end_date') }}"
                                required
                            >
                            @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary text-white">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
