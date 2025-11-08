@php
  $container = 'container-xxl';
  $containerNav = 'container-xxl';
@endphp

@extends('layouts/contentNavbarLayout')

@section('title', 'Create User')

<head>
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

@section('content')
  <div class="{{ $container }}">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Create New Account</h5>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Back</a>
      </div>

      <div class="card-body">
        <form method="POST" action="{{ route('users.store') }}">
          @csrf

          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>

          <!-- Password Field -->
          <div class="mb-3 position-relative">
            <label class="form-label">Password</label>
            <div class="input-group">
              <input type="password" name="password" class="form-control password-field" required>
              <span class="input-group-text toggle-password" style="cursor:pointer;">
                <i class="bi bi-eye-slash"></i>
              </span>
            </div>
          </div>

          <!-- Re-type Password Field -->
          <div class="mb-3 position-relative">
            <label class="form-label">Re-type Password</label>
            <div class="input-group">
              <input type="password" name="password_confirmation" class="form-control password-field" required>
              <span class="input-group-text toggle-password" style="cursor:pointer;">
                <i class="bi bi-eye-slash"></i>
              </span>
            </div>
            <!-- Inline error message -->
            <div class="text-danger mt-1" id="password-match-error" style="display:none; font-size: 0.9rem;">
              Passwords do not match
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Account Role</label>
            <select name="account_role" class="form-select" required>
              <option value="">Select Role</option>
              <option value="Student_Organization">Student Organization</option>
              <option value="SDSO_Head">SDSO Head</option>
              <option value="Faculty_Adviser">Faculty Adviser</option>
              <option value="VP_SAS">VP SAS</option>
              <option value="SAS_Director">SAS Director</option>
              <option value="BARGO">BARGO</option>
              <option value="admin">Admin</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary">Create</button>
        </form>
      </div>
    </div>
  </div>

  {{-- Scripts --}}
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Toggle password visibility
      document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', function () {
          const input = this.parentElement.querySelector('.password-field');
          const eyeIcon = this.querySelector('i');
          if (input.type === 'password') {
            input.type = 'text';
            eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
          } else {
            input.type = 'password';
            eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
          }
        });
      });

      // Live password match check
      const passwordInput = document.querySelector('input[name="password"]');
      const confirmInput = document.querySelector('input[name="password_confirmation"]');
      const errorDiv = document.getElementById('password-match-error');
      const form = document.querySelector('form');

      confirmInput.addEventListener('input', function() {
        if (confirmInput.value === '') {
          errorDiv.style.display = 'none';
          return;
        }
        if (confirmInput.value !== passwordInput.value) {
          errorDiv.style.display = 'block';
        } else {
          errorDiv.style.display = 'none';
        }
      });

      // Prevent form submission if passwords do not match
      form.addEventListener('submit', function(e) {
        if (confirmInput.value !== passwordInput.value) {
          e.preventDefault();
          confirmInput.focus();
        }
      });
    });
  </script>

@endsection
