@extends('layout')
@section('title', 'Home')
@section('content')

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include SweetAlert2 CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <div class="container">
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <h5 class="card-title mb-0 bold"><b>List Data</b></h5>
                            <a href="/addPage" class="btn btn-info">Add</a>
                        </div>
                    </div>
                    <div class="card-body mb-2" style="overflow-x: auto;">
                        <table id="abc" class="table table-bordered align-middle table-nowrap mb-0"
                            style="text-align:center;font-size:13px;width:100%;">
                            <thead class="table-light text-center">
                                <tr>
                                    <th style="text-align: center">ID</th>
                                    <th style="text-align: center">Name</th>
                                    <th style="text-align: center">Email</th>
                                    <th style="text-align: center">Image</th>
                                    <th style="text-align: center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="abcd"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header bg-soft-info p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editData" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="employee_id" name="employee_id" />
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" id="name" class="form-control" name="name"
                                placeholder="Enter Name" required />
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" class="form-control" name="email"
                                placeholder="Enter Email" required />
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" id="image" class="form-control" name="image" />
                            <img id="existing_image" src="" class="existing_image" alt="Existing Image"
                                style="max-width: 70px; max-height: 70px; display: none;" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="edit-btn">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            listdata();
        });

        function listdata() {
            var form = new FormData();
            var settings = {
                "url": "http://127.0.0.1:8000/api/getdata",
                "method": "POST",
                "timeout": 0,
                "processData": false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                "mimeType": "multipart/form-data",
                "contentType": false,
                "data": form
            };

            $.ajax(settings).done(function(response) {
                response = jQuery.parseJSON(response);
                console.log(response);

                var data = response.data;


                $.each(data, function(index, value) {
                    var newRow = '<tr>' +
                        '<td style="text-align:center;">' + value.employee_id + '</td>' +
                        '<td style="text-align:center;">' + value.name + '</td>' +
                        '<td style="text-align:center;">' + value.email + '</td>' +
                        '<td style="text-align:center;"><img src="' + value.image +
                        '" alt="Image" style="width:50px; height:auto;"></td>' +
                        '<td style="text-align:center;">' +
                        '<button class="btn btn-info mx-2" onclick="EditPopupModal(\'' + value.employee_id +
                        '\', \'' + value.name + '\', \'' + value.image + '\', \'' + value.email +
                        '\')">Edit</button>' +
                        '<button class="btn btn-danger" onclick="deleteData(\'' + value.employee_id +
                        '\')">Delete</button>' +
                        '</td>' +
                        '</tr>';
                    $("#abcd").append(newRow);
                });

                $("#abc").DataTable();
            });
        }

        function EditPopupModal(employee_id, name, image, email) {
            $('#employee_id').val(employee_id);
            $('#name').val(name);
            $('#email').val(email);
            $('#existing_image').attr('src', image).show();
            $('#editModal').modal('show');
        }

        $("#editData").on("submit", function(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to edit the data.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, edit it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    var form = new FormData(this);

                    var settings = {
                        url: "http://127.0.0.1:8000/api/updatedata",
                        method: "POST",
                        timeout: 0,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: form
                    };

                    $.ajax(settings).done(function(response) {
                        if (response.status === 'true') {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Data has been successfully edited.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message ||
                                    'An error occurred while editing the data.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }).fail(function(jqXHR) {
                        let errorMessage = jqXHR.responseJSON?.message ||
                            'An unexpected error occurred.';
                        Swal.fire({
                            title: 'Error!',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                }
            });
        });



        function deleteData(employee_id) {

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: 'http://127.0.0.1:8000/api/deletedata',
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: JSON.stringify({
                            employee_id: employee_id
                        }),
                        success: function(response) {
                            if (response.status === 'true') {

                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Data has been deleted successfully.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {

                                    setTimeout(() => {
                                        location.reload();
                                    }, 1000);
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'There was an issue deleting the data.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to communicate with the server.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
