@php
  $container = 'container-xxl';
  $containerNav = 'container-xxl';
@endphp

@extends('layouts/contentNavbarLayout')
@section('title', 'Users Management')

@section('content')
  <div class="{{ $container }}">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">User Accounts</h5>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
          <i class="bx bx-plus"></i> Create Account
        </a>
      </div>

      @if(session('success'))
        <div class="alert alert-success m-3">{{ session('success') }}</div>
      @endif

      <div class="table-responsive text-nowrap">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>Email</th>
              <th>Role</th>
              <th>Created</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $user)
              <tr>
                <td>{{ $user->user_id }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->email }}</td>
                <td><span class="badge bg-label-info">{{ $user->account_role }}</span></td>
                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                <td>
                  <a href="{{ route('users.edit', $user->user_id) }}" class="btn btn-sm btn-warning">
                    <i class="bx bx-edit"></i> Edit
                  </a>
                  <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                    data-bs-target="#confirmDeleteModal" data-user-id="{{ $user->user_id }}"
                    data-username="{{ $user->username }}">
                    <i class="bx bx-trash"></i> Delete
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted">No users found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Success Modal --}}
  @if(session('success'))
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="successModalLabel">Success</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            {{ session('success') }}
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
      });
    </script>
  @endif

  {{-- Delete Confirmation Modal --}}
  <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" id="deleteUserForm">
        @csrf
        @method('DELETE')
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="confirmDeleteLabel">Confirm Delete</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p id="deleteMessage">Are you sure you want to delete this account?</p>

            <div class="mb-3">
              <label for="adminPassword" class="form-label">Enter your admin password to confirm:</label>
              <div class="input-group">
                <input type="password" class="form-control" id="adminPassword" name="admin_password" required>
                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                  <i class="bx bx-show"></i>
                </button>
              </div>
            </div>

            <div id="passwordError" class="text-danger d-none">Incorrect password. Please try again.</div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">Delete</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
  <script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteModal = document.getElementById('confirmDeleteModal');
    const togglePasswordBtn = document.getElementById('togglePassword');
    const adminPasswordInput = document.getElementById('adminPassword');
    const passwordError = document.getElementById('passwordError');
    const deleteForm = document.getElementById('deleteUserForm');

    // Show/Hide password
    togglePasswordBtn.addEventListener('click', function () {
        if (adminPasswordInput.type === 'password') {
            adminPasswordInput.type = 'text';
            this.innerHTML = '<i class="bx bx-hide"></i>';
        } else {
            adminPasswordInput.type = 'password';
            this.innerHTML = '<i class="bx bx-show"></i>';
        }
    });

    // Set form action and username on modal show
    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const userId = button.getAttribute('data-user-id');
        const username = button.getAttribute('data-username');
        const message = document.getElementById('deleteMessage');

        deleteForm.action = "{{ route('users.destroy', ':id') }}".replace(':id', userId);
        message.textContent = `Are you sure you want to delete the account "${username}"?`;
        passwordError.classList.add('d-none');
        adminPasswordInput.value = '';
    });

    // AJAX form submission
    deleteForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(deleteForm);

        fetch(deleteForm.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                const bsDeleteModal = bootstrap.Modal.getInstance(deleteModal);
                bsDeleteModal.hide();

                // Remove deleted row from table
                const row = document.querySelector(`button[data-user-id="${data.user_id}"]`).closest('tr');
                if (row) row.remove();

                // Show success modal
                const successModalEl = document.getElementById('successModal');
                document.getElementById('successModalLabel').textContent = 'Deleted';
                document.querySelector('#successModal .modal-body').textContent = data.message;
                const successModal = new bootstrap.Modal(successModalEl);
                // successModal.show();
            } else if (data.error) {
                passwordError.textContent = data.error;
                passwordError.classList.remove('d-none');
            }
        })
        .catch(err => {
            console.error(error);
        });
    });
});
</script>

@endsection
