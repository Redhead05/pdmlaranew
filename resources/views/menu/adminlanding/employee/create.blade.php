<form action="{{ route('adminlanding.employee.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="exampleModallg" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Buat Struktur Organisasi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input id="name" name="name" type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required maxlength="191">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="position" class="form-label">Jabatan</label>
                            <select id="position" name="position"
                                    class="form-select @error('position') is-invalid @enderror" required>
                                <option value="" disabled {{ old('position') ? '' : 'selected' }}>-- Pilih Jabatan --</option>
                                <option value="Ketua" {{ old('position') == 'Ketua' ? 'selected' : '' }}>Ketua</option>
                                <option value="Sekretaris" {{ old('position') == 'Sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                                <option value="Anggota" {{ old('position') == 'Anggota' ? 'selected' : '' }}>Anggota</option>
                                <option value="Sekretariat" {{ old('position') == 'Sekretariat' ? 'selected' : '' }}>Sekretariat</option>
                            </select>
                            @error('position') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="start_year" class="form-label">Start Year</label>
                            <input id="start_year" name="start_year" type="number"
                                   min="1900" max="2100"
                                   class="form-control @error('start_year') is-invalid @enderror"
                                   value="{{ old('start_year') }}" required>
                            @error('start_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="end_year" class="form-label">End Year (optional)</label>
                            <input id="end_year" name="end_year" type="number"
                                   min="1900" max="2100"
                                   class="form-control @error('end_year') is-invalid @enderror"
                                   value="{{ old('end_year') }}">
                            @error('end_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email (opsional)</label>
                            <input id="email" name="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- optional file input for future handling (not validated by current controller) -->
                        <div class="col-md-6">
                            <label for="photo_file" class="form-label">Upload Photo (optional)</label>
                            <input id="photo_file" name="photo_file" type="file" accept="image/*"
                                   class="form-control">
                            <small class="text-muted">If you upload a file, update controller to handle saving and set `photo` to stored path.</small>
                        </div>

                        <div class="col-md-6">
                            <label for="instagram" class="form-label">Instagram (url)</label>
                            <input id="instagram" name="instagram" type="url"
                                   class="form-control @error('instagram') is-invalid @enderror"
                                   value="{{ old('instagram') }}">
                            @error('instagram') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="facebook" class="form-label">Facebook (url)</label>
                            <input id="facebook" name="facebook" type="url"
                                   class="form-control @error('facebook') is-invalid @enderror"
                                   value="{{ old('facebook') }}">
                            @error('facebook') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="linkedin" class="form-label">LinkedIn (url)</label>
                            <input id="linkedin" name="linkedin" type="url"
                                   class="form-control @error('linkedin') is-invalid @enderror"
                                   value="{{ old('linkedin') }}">
                            @error('linkedin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary text-white">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
