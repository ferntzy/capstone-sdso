<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>

  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}">

  <!-- SweetAlert2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

  <style>
    body {
      /* background: url('{{ asset('images/calendar.png') }}') no-repeat center center fixed; */
      background-size: 1000px;
      background-color: #050361;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }


  </style>
</head>

<body>

  {{-- <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
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
  </div> --}}

  <div style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f8f9fa;">
  <div style="display: flex; background-color: #fff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden; max-width: 900px; width: 100%;">

    <!-- LEFT SIDE: LOGIN FORM -->
    <div style="flex: 1; padding: 40px; display: flex; flex-direction: column; justify-content: center;">
      <h3 class="text-center mb-4" style="font-weight: 600;">STUDENT INFORMATION SYSTEM (SIS)</h3>

      <div class="card shadow p-4" style="width: 100%; max-width: 400px; margin: 0 auto;">
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

            <button type="submit" class="btn btn-primary w-100" style="background-color: #e74c3c; border-color: #e74c3c;">Login</button>
          </form>
      </div>
    </div>

    <!-- RIGHT SIDE: ILLUSTRATION -->
    <div style="flex: 1; background-color: #f4f6f8; display: flex; justify-content: center; align-items: center; padding: 40px;">
      <img src="{{ asset('images/calendar.png') }}" alt="SIS Illustration" style="max-width: 90%; height: auto;">
    </div>

  </div>
</div>




  <!-- jQuery + Bootstrap -->
  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>

  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  @if (session('logout_success'))
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        Swal.fire({
          icon: 'success',
          title: 'Logged Out',
          text: '{{ session('logout_success') }}',
          showConfirmButton: false,
          timer: 2500,
          timerProgressBar: true
        });
      });
    </script>
  @endif

</body>

</html>
