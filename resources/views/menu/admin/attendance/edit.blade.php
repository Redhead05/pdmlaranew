<!-- Edit Modal -->
<div class="modal fade" id="editModal-{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel-{{ $item->id }}">Edit Attendance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.attendance.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title-{{ $item->id }}" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title-{{ $item->id }}" name="title" value="{{ $item->title }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="description-{{ $item->id }}" class="form-label">Description</label>
                                <textarea class="form-control" id="description-{{ $item->id }}" name="description" rows="6" required>{{ $item->description }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="mb-3">
                                <label for="type-{{ $item->id }}" class="form-label">Type</label>
                                <select class="form-select" id="type-{{ $item->id }}" name="type" required>
                                    <option value="asesor" {{ $item->type == 'asesor' ? 'selected' : '' }}>Asesor</option>
                                    <option value="internal" {{ $item->type == 'internal' ? 'selected' : '' }}>Internal</option>
                                    <option value="umum" {{ $item->type == 'umum' ? 'selected' : '' }}>Umum</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="start_date-{{ $item->id }}" class="form-label">Start Date</label>
                                <input type="datetime-local" class="form-control" id="start_date-{{ $item->id }}" name="start_date" value="{{ $item->start_date }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="end_date-{{ $item->id }}" class="form-label">End Date</label>
                                <input type="datetime-local" class="form-control" id="end_date-{{ $item->id }}" name="end_date" value="{{ $item->end_date }}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
