<form action="{{ route('adminlanding.StrukturOrganisasi.store') }}" method="POST" enctype="multipart/form-data">
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
                            <label for="name" class="form-label">Nama</label>
                            <select class="form-control @error('user_id') is-invalid @enderror"
                                    id="user_id" name="user_id" required>
                                <option value="">Pilih nama</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="position" class="form-label">Jabatan</label>
                            <select id="position" name="position" class="form-select @error('position') is-invalid @enderror" required>
                                <option value="" disabled {{ old('position') ? '' : 'selected' }}>-- Pilih Jabatan --</option>
                                <option value="Ketua" {{ old('position') == 'Ketua' ? 'selected' : '' }}>Ketua</option>
                                <option value="Sekretaris" {{ old('position') == 'Sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                                <option value="Anggota" {{ old('position') == 'Anggota' ? 'selected' : '' }}>Anggota</option>
                                <option value="Sekretariat" {{ old('position') == 'Sekretariat' ? 'selected' : '' }}>Sekretariat</option>
                            </select>
                            @error('position') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="period" class="form-label">Periode (contoh: 2018-2022)</label>
                            <input type="text" class="form-control @error('period') is-invalid @enderror"
                                   id="period" name="period" value="{{ old('period') }}" required>
                            @error('period') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="image" class="form-label">Foto / Gambar (opsional)</label>
                            <input type="file" accept="image/*" class="form-control @error('avatar') is-invalid @enderror"
                                   id="avatar" name="avatar">
                            @error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email (opsional)</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="instagram" class="form-label">Instagram (url)</label>
                            <input type="url" class="form-control @error('instagram') is-invalid @enderror"
                                   id="instagram" name="instagram" value="{{ old('instagram') }}">
                            @error('instagram') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="facebook" class="form-label">Facebook (url)</label>
                            <input type="url" class="form-control @error('facebook') is-invalid @enderror"
                                   id="facebook" name="facebook" value="{{ old('facebook') }}">
                            @error('facebook') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="linkedin" class="form-label">LinkedIn (url)</label>
                            <input type="url" class="form-control @error('linkedin') is-invalid @enderror"
                                   id="linkedin" name="linkedin" value="{{ old('linkedin') }}">
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
