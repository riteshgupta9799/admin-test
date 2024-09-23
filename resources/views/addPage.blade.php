@extends('layout')
@section('title', 'AddData')
@section('content')

    <div class="container">
        <div class="p-4">
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Add Data</h3>
                <a href="/home" class="btn btn-info">Back</a>
            </div>

            <div class="card">
                <div class="card-body">

                    <form id="addData" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="employee_id" name="employee_id" />
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" id="name" class="form-control" name="name"
                                    placeholder="Enter Name" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" class="form-control" name="email"
                                    placeholder="Enter Email" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" id="image" class="form-control" name="image" />
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-success" id="add-btn">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $("#addData").on("submit", function(event) {
            event.preventDefault();

            var data = new FormData(this);

            var settings = {
                "url": "http://127.0.0.1:8000/api/adddata",
                "method": "POST",
                "timeout": 0,
                "processData": false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                "contentType": false,
                "data": data
            };

            $.ajax(settings).done(function(response) {


                if (response.status === 'true') {
                    $("#addData").trigger("reset");

                    toastr.success('Data Added Successfully', 'Success', {
                        positionClass: 'toast-top-center',
                        timeOut: 3000
                    });

                    setTimeout(function() {
                        window.location.href = '/home';
                    }, 1000);
                }
            }).fail(function(jqXHR) {
                if (jqXHR.status === 422) {
                    var response = jqXHR.responseJSON;
                    var errorMessages = '';

                    for (var key in response.errors) {
                        if (response.errors.hasOwnProperty(key)) {
                            errorMessages += response.errors[key].join(", ") + "\n";
                        }
                    }

                    toastr.error(errorMessages, '', {
                        positionClass: 'toast-top-center',
                        timeOut: 5000
                    });
                } else {
                    toastr.error('An unexpected error occurred.', 'Error', {
                        positionClass: 'toast-top-center',
                        timeOut: 3000
                    });
                }
            });
        });
    </script>



@endsection
