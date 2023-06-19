// this javascript program runs all the necessary operations needed on live during the use of the page_teacher.php
// operations like button clicks to request certain data or even a mouse hover to make it responsive

// this button click logs out the user
$("#logout-btn").click(function () {
  $.get(
    "http://localhost/a_product/official/php/js-request/current_session_destroy.php",
    function () {
      window.location =
        "http://localhost/a_product/official/pages/page_register_login.php";
    }
  );
});

// this button click publishes a question to the students to answer
$("#post-question").click(function () {
  document.getElementById("overlay").style.display = "block";
  document.getElementById("show-loading").style.display = "block";
  $.post(
    "http://localhost/a_product/official/php/ml_all_steps.php",
    {
      question: document.getElementById("post-question-content").value,
      hps: document.getElementById("choose-grade").value,
      due: document.getElementById("due-input").value,
    },
    function (response) {
      $.get(
        "http://localhost/a_product/official/php/js-request/page_teacher_start_data.php",
        function (data) {
          data = JSON.parse(data);
          if (data.length > 0) {
            document.getElementById("if-empty-table-question").style.display =
              "none";
            $("#teacher-left-side-table-tr").empty();
            for (i = 0; i < data.length; i++) {
              arr_questions.push(data[i]["question"]);
              arr_collected_links.push(data[i]["collected_links"]);
              arr_time_of_issue.push(data[i]["time_of_issue"]);
              arr_grades.push(data[i]["HPS"]);
              $("#teacher-left-side-table-tr").append(
                "<tr><td>" +
                  data[i]["question"] +
                  "</td><td>" +
                  data[i]["HPS"] +
                  "</td><td>" +
                  data[i]["due_date"] +
                  "</td><td><span class='material-icons'>edit</span><span class='material-icons'>delete</span></td></tr>"
              );
            }
          }
          document.getElementById("overlay").style.display = "none";
          document.getElementById("show-loading").style.display = "none";
          alert(response);
        }
      );
    }
  );
});

// this button click unblurs or blurs the course code
$("#account-course-code-show").click(function () {
  if (
    document.getElementById("account-course-code-show").innerHTML ==
    "visibility_off"
  ) {
    document.getElementById("account-course-code").style.color = "black";
    document.getElementById("account-course-code").style.textShadow = "none";
    document.getElementById("account-course-code-show").innerHTML =
      "visibility";
  } else {
    document.getElementById("account-course-code").style.color = "transparent";
    document.getElementById("account-course-code").style.textShadow =
      "0 0 8px #000";
    document.getElementById("account-course-code-show").innerHTML =
      "visibility_off";
  }
});

//this button shows the prompt to change the account's username
$("#edit-username").click(function () {
  document.getElementById("overlay").style.display = "block";
  document.getElementById("green-prompt").style.display = "block";
  document.getElementById("insert-title").innerHTML = "Changing Username";
  document.getElementById("green-prompt-content").innerHTML =
    "<h2>Username:</h2><input id='new-username-input' value='" +
    document.getElementById("inserted-name").innerHTML +
    "'><br><br><button onclick='cancel_btn_function()'>CANCEL</button><button onclick='apply_change_username()'>APPLY</button>";
});

// this button click closes the overlay and the prompt form
function cancel_btn_function() {
  document.getElementById("overlay").style.display = "none";
  document.getElementById("green-prompt").style.display = "none";
}

// this button confirms the change of the account's username
function apply_change_username() {
  if (
    document.getElementById("inserted-name").innerHTML ==
    document.getElementById("new-username-input").value
  ) {
    alert("You still have the same Username");
  } else {
    $.post(
      "http://localhost/a_product/official/php/js-request/change_username.php",
      {
        username: document.getElementById("new-username-input").value,
        role: 1,
      },
      function (data) {
        alert(data);
        document.getElementById("inserted-name").innerHTML =
          document.getElementById("new-username-input").value;
        cancel_btn_function();
      }
    );
  }
}
