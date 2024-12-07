$(function () {
  console.log('here');
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });

  $(".back").on("click", () => history.back());

  $(document).on("click", "#password_toggle", function () {
    const icon = $(this).find("i");
    const passwordField = $("#password");
    showHidePassword(passwordField, icon);
  });

  $(document).on("click", "#new_password_confirm_toggle", function () {
    const icon = $(this).find("i");
    const passwordField = $("#new_password_confirmation");
    showHidePassword(passwordField, icon);
  });

  $(document).on("click", ".destroy_btn", function () {
    console.log('here');
    Swal.fire({
      //title: "Are you sure?",
      text: $(this).attr("data-text"),
      icon: "warning",
      buttons: true,
      dangerMode: true,
      showCancelButton: true,
    }).then((response) => {
      if (response.isConfirmed) {
        var form_id = $(this).attr("data-origin");
        $("#" + form_id).submit();
      }
    });
  });
});

function showHidePassword(passwordField, icon) {
  const type = passwordField.attr("type") === "password" ? "text" : "password";
  passwordField.attr("type", type);
  if (type === "password") {
    icon.removeClass("fa-eye").addClass("fa-eye-slash");
  } else {
    icon.removeClass("fa-eye-slash").addClass("fa-eye");
  }
}
