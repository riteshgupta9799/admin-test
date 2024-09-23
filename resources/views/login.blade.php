@extends('layout')
@section('title','Login')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.css">

    <div class="container">
        <form style="width: 500px" class="ms-auto me-auto mt-5" id="loginSubmit" method="POST">

            <div class="form-group">
              <label for="">Email address</label>
              <input type="email" class="form-control" id="email" name="email"  placeholder="Enter email">
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Password</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Password">
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="/registerpage" class="btn btn-primary">Register here</a>
            </div>
          </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<!-- Toastify CSS -->

<!-- Toastify JS -->
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<!-- jQuery Official CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
     $("#loginSubmit").on("submit", function(event) {
            event.preventDefault();

            var data = new FormData(this);

            var settings = {
                "url": "http://127.0.0.1:8000/api/login",
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
                    $("#loginSubmit").trigger("reset");

                    toastr.success('Login Successfully', 'Success', {
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
