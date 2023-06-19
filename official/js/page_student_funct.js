// this javascript program runs all the necessary operations needed on live during the use of the page_student.php
// operations like button clicks to request certain data or even a mouse hover to make it responsive

$("#logout-btn").click(function () {
  $.get(
    "http://localhost/a_product/official/php/js-request/current_session_destroy.php",
    function () {
      window.location =
        "http://localhost/a_product/official/pages/page_register_login.php";
    }
  );
});

$("#edit-username").click(function () {
  document.getElementById("overlay").style.display = "block";
  document.getElementById("green-prompt").style.display = "block";
  document.getElementById("insert-title").innerHTML = "Changing Username";
  document.getElementById("green-prompt-content").innerHTML =
    "<h2>Username:</h2><input id='new-username-input' value='" +
    document.getElementById("inserted-name").innerHTML +
    "'><br><br><button onclick='cancel_btn_function()'>CANCEL</button><button onclick='apply_change_username()'>APPLY</button>";
});

function cancel_btn_function() {
  document.getElementById("overlay").style.display = "none";
  document.getElementById("green-prompt").style.display = "none";
}

function apply_change_username() {
  if (
    document.getElementById("inserted-name").innerHTML ==
    document.getElementById("new-username-input").value
  ) {
    alert("You still have the same Username");
  } else {
    $.post(
      "http://localhost/a_product/official/php/js-request/change_username.php",
      { username: document.getElementById("new-username-input").value },
      function (data) {
        alert(data);
        document.getElementById("inserted-name").innerHTML =
          document.getElementById("new-username-input").value;
        cancel_btn_function();
      }
    );
  }
}
