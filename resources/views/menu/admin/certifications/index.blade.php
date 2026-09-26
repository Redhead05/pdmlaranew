@extends('app.layout')
@section('title', 'Certification Asesor')

@push('styles')
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
                                        <th>No Surat</th>
                                        <th>Tanggal Buat</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($certifications as $cert)
                                        <tr>
                                            <td></td>
                                            <td>
                                                {{ $cert->title }}
                                                @if($cert->recipient_count > 1)
                                                    <span class="badge text-bg-info ms-1">{{ $cert->recipient_count }} asesor</span>
                                                @endif
                                            </td>
                                            <td>{{ $cert->certificate_number ?? '-' }}</td>
                                            <td>{{ optional($cert->issued_at)->format('Y-m-d') }}</td>
                                            <td class="text-end">
                                                <button type="button"
                                                        class="btn-detail ps-0 border-0 bg-transparent lh-1 position-relative top-2 me-1"
                                                        data-batch="{{ $cert->batch_id ?? 'single-'.$cert->id }}"
                                                        title="Detail penerima">
                                                    <i class="material-symbols-outlined fs-16 text-primary">visibility</i>
                                                </button>
                                                <button type="button"
                                                        class="btn-edit ps-0 border-0 bg-transparent lh-1 position-relative top-2 me-1"
                                                        data-id="{{ $cert->id }}" title="Edit">
                                                    <i class="material-symbols-outlined fs-16 text-body">edit</i>
                                                </button>
                                                <button type="button"
                                                        class="btn-delete ps-0 border-0 bg-transparent lh-1 position-relative top-2"
                                                        data-id="{{ $cert->id }}" title="Delete">
                                                    <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                                                </button>
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
                <div class="mb-3" id="single-asesor-wrap">
                    <label for="user_id">Asesor</label>
                    <select name="user_id" id="user_id" class="form-control">
                        @foreach(\App\Models\User::role('asesor')->get() as $a)
                            <option value="{{ $a->id }}">{{ $a->name }} ({{ $a->nia }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3" id="bulk-wrap">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="send_to_all" id="send_to_all" value="1">
                        <label class="form-check-label" for="send_to_all">Kirim ke semua asesor</label>
                    </div>
                </div>
                <div class="mb-3" id="except-wrap" style="display:none;">
                    <label for="except_nia">Kecuali (NIA, pisahkan koma)</label>
                    <textarea name="except_nia" id="except_nia" class="form-control" rows="2" placeholder="misal: 1122334455, 9988776655"></textarea>
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

    {{-- Detail penerima modal --}}
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Detail Penerima — <span id="detail-title"></span></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <ul class="nav nav-tabs mb-3" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="recipient-tab" data-bs-toggle="tab" data-bs-target="#recipient-pane" type="button" role="tab">Mendapat (<span id="recipient-count">0</span>)</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="nonrecipient-tab" data-bs-toggle="tab" data-bs-target="#nonrecipient-pane" type="button" role="tab">Tidak Mendapat (<span id="nonrecipient-count">0</span>)</button>
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane fade show active" id="recipient-pane" role="tabpanel">
                <div class="table-responsive">
                  <table class="table align-middle table-sm" id="recipient-table" style="width:100%">
                    <thead><tr><th>No.</th><th>NIA</th><th>Nama</th><th>Email</th></tr></thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane fade" id="nonrecipient-pane" role="tabpanel">
                <div class="table-responsive">
                  <table class="table align-middle table-sm" id="nonrecipient-table" style="width:100%">
                    <thead><tr><th>No.</th><th>NIA</th><th>Nama</th><th>Email</th></tr></thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
            </div>
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

            // initialize DataTable (nomor kolom diisi otomatis)
            const table = $('#certTable').DataTable({
                responsive: true,
                order: [],
                pageLength: 25,
                columnDefs: [
                    { orderable: false, targets: [0, -1] },
                ],
            });

            table.on('order.dt search.dt', function () {
                table.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();

            // wire up create
            const certModal = new bootstrap.Modal(document.getElementById('certModal'));
            $(document).on('click', '#btn-create', function(){
                $('#certForm')[0].reset();
                $('#form_method').val('POST');
                $('#certForm').data('action', '{{ route('admin.certifications.store') }}');
                $('#bulk-wrap').show();
                $('#single-asesor-wrap').show();
                $('#except-wrap').hide();
                $('#send_to_all').prop('checked', false);
                certModal.show();
            });

            // toggle bulk vs single
            $('#send_to_all').on('change', function(){
                const on = this.checked;
                $('#single-asesor-wrap').toggle(!on);
                $('#except-wrap').toggle(on);
            });

            // edit (delegated)
            $(document).on('click', '.btn-edit', function(){
                const id = $(this).data('id');
                $.get("{{ url('admin/certifications') }}/"+id+"/edit", function(res){
                    const cert = res.cert;
                    const toDate = (v) => v ? String(v).substring(0, 10) : '';
                    $('#certForm')[0].reset();
                    $('#certForm').data('action', '{{ url('admin/certifications') }}/'+id);
                    $('#form_method').val('PUT');
                    $('#bulk-wrap').hide();
                    $('#except-wrap').hide();
                    $('#single-asesor-wrap').show();
                    $('#send_to_all').prop('checked', false);
                    $('#user_id').val(cert.user_id);
                    $('#certForm [name="title"]').val(cert.title);
                    $('#certForm [name="certificate_number"]').val(cert.certificate_number);
                    $('#certForm [name="issuer"]').val(cert.issuer);
                    $('#certForm [name="issued_at"]').val(toDate(cert.issued_at));
                    $('#certForm [name="expires_at"]').val(toDate(cert.expires_at));
                    $('#certForm [name="notes"]').val(cert.notes);
                    certModal.show();
                });
            });

            // detail penerima (DataTable + search di dalam modal)
            const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
            let recipientTable = null, nonrecipientTable = null;
            let currentDetail = { recipients: [], non_recipients: [] };

            function toRows(rows) {
                return rows.map(function (r, i) {
                    return [i + 1, r.nia ?? '-', r.name ?? '-', r.email ?? '-'];
                });
            }

            function detailTableConfig(placeholder) {
                return {
                    searching: true,
                    paging: true,
                    pageLength: 10,
                    info: true,
                    lengthChange: false,
                    order: [[1, 'asc']],
                    language: { search: '', searchPlaceholder: placeholder },
                    columnDefs: [
                        { orderable: false, targets: 0 },
                    ],
                    autoWidth: false,
                };
            }

            detailModal._element.addEventListener('shown.bs.modal', function () {
                if (!recipientTable) {
                    recipientTable = $('#recipient-table').DataTable(detailTableConfig('Cari NIA / nama / email'));
                }
                if (!nonrecipientTable) {
                    nonrecipientTable = $('#nonrecipient-table').DataTable(detailTableConfig('Cari NIA / nama / email'));
                }
                recipientTable.clear().rows.add(toRows(currentDetail.recipients)).draw();
                nonrecipientTable.clear().rows.add(toRows(currentDetail.non_recipients)).draw();
                recipientTable.columns.adjust();
                nonrecipientTable.columns.adjust();
            });

            // adjust saat tab pindah agar lebar kolom pas
            $('#recipient-tab').on('shown.bs.tab', function () { recipientTable && recipientTable.columns.adjust(); });
            $('#nonrecipient-tab').on('shown.bs.tab', function () { nonrecipientTable && nonrecipientTable.columns.adjust(); });

            $(document).on('click', '.btn-detail', function(){
                const batch = $(this).data('batch');
                $.get('{{ route('admin.certifications.detail', '__BATCH__') }}'.replace('__BATCH__', batch), function(res){
                    currentDetail = res;
                    $('#detail-title').text(res.batch.title ?? '');
                    $('#recipient-count').text(res.recipients.length);
                    $('#nonrecipient-count').text(res.non_recipients.length);
                    detailModal.show();
                }).fail(function(){
                    showToast('Gagal', 'Gagal memuat detail', 'danger');
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
