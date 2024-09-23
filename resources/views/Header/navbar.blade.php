<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="/home">Home</a>
        </li>

        <li class="nav-item">
            @if(session('user_id'))
            <li class="nav-item">
                <a class="nav-link cursor-pointer" href="/addPage">Add</a>
              </li>
               <li class="nav-item"> <a class="nav-link cursor-pointer btn" onclick="logout()">Logout</a></li>
            @else
                <a class="nav-link cursor-pointer" href="/loginpage">Login</a>

            @endif
        </li>


      </ul>
    </div>
  </nav>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    function logout() {
        var settings = {
            "url": "http://127.0.0.1:8000/api/logout",
            "method": "POST",
            "timeout": 0,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        };

        $.ajax(settings)
            .done(function(response) {
                if (response.status === 'true') {
                    toastr.success('Logout Successfully', 'Success', {
                    positionClass: 'toast-top-center',
                    timeOut: 3000
                });
                    window.location.href = '/home';
                } else {
                    alert('Logout failed. Please try again.');
                }
            })
            .fail(function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('An error occurred while logging out.');
            });
    }
</script>
