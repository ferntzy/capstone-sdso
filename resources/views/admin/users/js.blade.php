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

        // Optional: focus back on username
        $("#frmCreate input[name='username']").focus();
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
