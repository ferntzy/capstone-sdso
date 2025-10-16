<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>

  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}">
</head>

<body class="bg-light d-flex justify-content-center align-items-center" style="height: 100vh;">

  <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
    <h4 class="text-center mb-4">Login</h4>

    @if ($errors->any())
      <div class="alert alert-danger">
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
      @csrf

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="text" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
  </div>

  {{-- ✅ Logout Success Modal --}}
  @if(session('logout_success'))
  <div class="modal fade" id="logoutSuccessModal" tabindex="-1" aria-labelledby="logoutSuccessLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center p-3">
        <div class="modal-body">
          <h4 class="text-success mb-3">✅ Logged Out Successfully</h4>
          <p>You have been signed out of your account.</p>
          <button type="button" class="btn btn-primary w-50 mt-2" data-bs-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      var logoutModal = new bootstrap.Modal(document.getElementById('logoutSuccessModal'));
      logoutModal.show();

      // Auto-close after 3 seconds (optional)
      setTimeout(() => {
        logoutModal.hide();
      }, 3000);
    });
  </script>
  @endif

  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>

</body>

</html>
