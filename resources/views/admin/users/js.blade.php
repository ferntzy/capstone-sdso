<script>

  $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});


$(document).on("click", "#btnCreate", function(e){
  e.preventDefault();
  $.ajax({
    url: "{{ route('users.store') }}",
    method: "POST",
    data: $("#frmCreate").serialize(),
    cache: false,

    beforeSend: function() {
      $("#btnCreate").prop("disabled", true).html("Creating account...");
    },

    success: function(response) {
      $("#btnCreate").prop("disabled", false).html("CREATE");

      Swal.fire({
        title: "Success",
        icon: "success",
        text: response.message || "User created successfully"
      }).then(() => {
        // ✅ Clear the form after success
        $("#frmCreate")[0].reset();

        $("#frmCreate input[name='username']").focus();
        window.location.href = "{{ route('users.index') }}";
      });
    },

    error: function(xhr) {
      $("#btnCreate").prop("disabled", false).html("CREATE");
      // Handle Laravel exception messages
      let message = "An unexpected error occurred.";

      if (xhr.responseJSON) {
        if (xhr.responseJSON.error) {
          message = xhr.responseJSON.error; // from catch(Exception $e)
        } else if (xhr.responseJSON.errors) {
          // from validation errors (array)
          message = Object.values(xhr.responseJSON.errors).flat().join("\n");
        } else if (xhr.responseJSON.message) {
          // fallback for generic Laravel JSON responses
          message = xhr.responseJSON.message;
        }
      }

      Swal.fire({
        title: "Error!",
        text: message,
        icon: "error"
      });
    }
  })
})


</script>



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
