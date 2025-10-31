import Toastify from "toastify-js";
import Swal from "sweetalert2";

function validateForm(fields) {
  let valid = true;

  fields.forEach(field => {
    const input = document.getElementById(field.id);
    const value = input.value.trim();
    const errorMessages = [];

    if (!value) {
      valid = false;
      errorMessages.push(`${field.label} is required.`);
      Toastify({
        text: errorMessages.join("\n"),
        duration: 3000,
        close: true,
        gravity: "top",
        position: "right",
        style: { background: "#cc3300" },
      }).showToast();
    }
  });

  return valid;
}

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("createUserForm");
  const submitBtn = document.getElementById("createUserBtn");

  submitBtn.addEventListener("click", function (event) {
    event.preventDefault();

    const fields = [
      { id: "username", label: "Username" },
      { id: "email", label: "Email" },
      { id: "password", label: "Password" },
      { id: "account_role", label: "Account Role" },
    ];

    const isValid = validateForm(fields);
    if (!isValid) return;

    Swal.fire({
      title: "Create this user?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Yes, create it!",
      cancelButtonText: "Cancel",
    }).then(result => {
      if (result.isConfirmed) {
        form.submit();
      }
    });
  });
});
