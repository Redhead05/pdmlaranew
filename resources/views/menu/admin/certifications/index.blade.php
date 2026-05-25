@extends('app.layout')
@section('title', 'Certification Asesor')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

@endpush

@section('content')
    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Certifications — {{ $year }}</h4>
                        <div>
                            <button id="btn-create" class="btn btn-primary">Create Certification</button>
                        </div>
                    </div>

                    <div aria-live="polite" aria-atomic="true" class="position-relative">
                        <div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1080"></div>
                    </div>

                    <div class="default-table-area all-products">
                        <div class="table-responsive">
                            <table class="display table align-middle" id="certTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Judul</th>
                                    <th>Nama</th>
                                    <th>No Surat</th>
                                    <th>Tanggal Buat</th>
                                    <th>Active</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($certifications as $cert)
                                    <tr>
                                        <td>{{ $cert->id }}</td>
                                        <td>{{ $cert->title }}</td>
                                        <td>{{ $cert->user->name ?? '-' }}</td>
                                        <td>{{ $cert->issuer }}</td>
                                        <td>{{ optional($cert->issued_at)->format('Y-m-d') }}</td>
                                        <td>{{ $cert->year }}</td>
                                        <td>{{ ucfirst($cert->status) }}</td>
                                        <td class="text-end">
                                            <button class=" btn-edit ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-id="{{ $cert->id }}" title="Edit">
                                                <i class="material-symbols-outlined fs-16 text-body">edit</i>
                                            </button>
{{--                                            <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $cert->id }}">Delete</button>--}}
                                            <button type="button"
                                                    class=" btn-delete ps-0 border-0 bg-transparent lh-1 position-relative top-2"
                                                    data-bs-toggle="modal"
                                                    data-id="{{ $cert->id }}
                                                    data-bs-target="#deleteModal-{{ $cert->id }}"
                                                    data-bs-title="Delete">
                                                <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                                            </button>
                                            @if($cert->file_path)
                                                <a href="{{ Storage::disk('public')->url($cert->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">Open</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal for create/edit --}}
    <div class="modal fade" id="certModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="certModalLabel">Certification</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="certForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="form_method" value="POST">
                <div class="mb-3">
                    <label for="user_id">Asesor</label>
                    <select name="user_id" id="user_id" class="form-control">
                        @foreach(
                            \App\Models\User::role('asesor')->get() as $a)
                            <option value="{{ $a->id }}">{{ $a->name }} ({{ $a->nia }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3"><label>Title</label><input name="title" class="form-control"></div>
                <div class="mb-3"><label>Certificate Number</label><input name="certificate_number" class="form-control"></div>
                <div class="mb-3"><label>Issuer</label><input name="issuer" class="form-control"></div>
                <div class="mb-3"><label>Issued At</label><input type="date" name="issued_at" class="form-control"></div>
                <div class="mb-3"><label>Expires At</label><input type="date" name="expires_at" class="form-control"></div>
                <div class="mb-3"><label>File</label><input type="file" name="file" class="form-control"></div>
                <div class="mb-3"><label>Notes</label><textarea name="notes" class="form-control"></textarea></div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="saveCert">Save</button>
          </div>
        </div>
      </div>
    </div>

    {{-- Delete confirmation modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirm Delete</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Apakah anda yakin untuk menghapus sertifikasi ini?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
          </div>
        </div>
      </div>
    </div>

@endsection


@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="//cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script>
        (function(){
            const toastContainer = $('#toast-container');
            function showToast(title, body, type = 'success') {
                const toastId = 'toast-' + Math.random().toString(36).substr(2, 9);
                const toastHtml = `
                    <div id="${toastId}" class="toast align-items-center text-bg-${type} border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                      <div class="d-flex">
                        <div class="toast-body">
                          <strong>${title}</strong><br>${body}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                      </div>
                    </div>`;
                toastContainer.append(toastHtml);
                const el = document.getElementById(toastId);
                const bsToast = new bootstrap.Toast(el, { delay: 4000 });
                bsToast.show();
            }

            // initialize DataTable
            const table = $('#certTable').DataTable({
                responsive: true,
                order: [[0, 'desc']],
                pageLength: 25,
                columnDefs: [
                    { orderable: false, targets: -1 },
                ],
            });

            // wire up create
            const certModal = new bootstrap.Modal(document.getElementById('certModal'));
            $(document).on('click', '#btn-create', function(){
                $('#certForm')[0].reset();
                $('#form_method').val('POST');
                $('#certForm').data('action', '{{ route('admin.certifications.store') }}');
                certModal.show();
            });

            // edit (delegated)
            $(document).on('click', '.btn-edit', function(){
                const id = $(this).data('id');
                $.get("{{ url('admin/certifications') }}/"+id+"/edit", function(res){
                    const cert = res.cert;
                    $('#certForm')[0].reset();
                    $('#certForm').data('action', '{{ url('admin/certifications') }}/'+id);
                    $('#form_method').val('PUT');
                    $('#user_id').val(cert.user_id);
                    $('#certForm [name="title"]').val(cert.title);
                    $('#certForm [name="certificate_number"]').val(cert.certificate_number);
                    $('#certForm [name="issuer"]').val(cert.issuer);
                    $('#certForm [name="issued_at"]').val(cert.issued_at ? cert.issued_at.split(' ')[0] : '');
                    $('#certForm [name="expires_at"]').val(cert.expires_at ? cert.expires_at.split(' ')[0] : '');
                    $('#certForm [name="notes"]').val(cert.notes);
                    certModal.show();
                });
            });

            // save (create/update)
            $(document).on('click', '#saveCert', function(){
                const form = $('#certForm')[0];
                const action = $('#certForm').data('action');
                const method = $('#form_method').val();
                const formData = new FormData(form);
                if (method && method !== 'POST') formData.append('_method', method);

                $.ajax({
                    url: action,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(resp){
                        certModal.hide();
                        showToast('Sukses', resp.message || 'Saved', 'success');
                        setTimeout(()=> location.reload(), 900);
                    },
                    error: function(xhr){
                        const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal menyimpan';
                        showToast('Gagal', msg, 'danger');
                    }
                });
            });

            // delete (delegated)
            let deleteId = null;
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            $(document).on('click', '.btn-delete', function(){
                deleteId = $(this).data('id');
                deleteModal.show();
            });
            $(document).on('click', '#confirmDelete', function(){
                if (!deleteId) return;
                $.ajax({
                    url: '{{ url('admin/certifications') }}/' + deleteId,
                    method: 'POST',
                    data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
                    success: function(resp){
                        deleteModal.hide();
                        showToast('Sukses', resp.message || 'Deleted', 'success');
                        setTimeout(()=> location.reload(), 800);
                    },
                    error: function(){
                        showToast('Gagal', 'Gagal menghapus', 'danger');
                    }
                });
            });

            // server-flashed success
            @if(session('success'))
                showToast('Sukses', `{!! addslashes(session('success')) !!}`, 'success');
            @endif
        })();
    </script>
@endpush

