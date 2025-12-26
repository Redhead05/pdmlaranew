<form action="{{ route('admin.attendance.store') }}" method="POST">
    @csrf
    <div class="modal fade" id="exampleModallg" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Attendance</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <!-- Title Field -->
                            <div class="mb-3">
                                <h3>
                                    <label for="title" class="form-label fs-15">Title</label>
                                </h3>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>

                            <!-- Description Field -->
                            <div class="mb-3">
                                <h3>
                                    <label for="description" class="form-label fs-15">Description</label>
                                </h3>
                                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <!-- Type Field -->
                            <div class="mb-3">
                                <h3>
                                    <label for="type" class="form-label fs-15">Type</label>
                                </h3>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="asesor">Asesor</option>
                                    <option value="internal">Internal</option>
                                    <option value="umum">Umum</option>
                                </select>
                            </div>

                            <!-- Start Date Field -->
                            <div class="mb-3">
                                <h3>
                                    <label for="start_date" class="form-label fs-15">Start Date</label>
                                </h3>
                                <input type="datetime-local" class="form-control" id="start_date" name="start_date" required>
                            </div>

                            <!-- End Date Field -->
                            <div class="mb-3">
                                <h3>
                                    <label for="end_date" class="form-label fs-15">End Date</label>
                                </h3>
                                <input type="datetime-local" class="form-control" id="end_date" name="end_date" required>
                            </div>
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
