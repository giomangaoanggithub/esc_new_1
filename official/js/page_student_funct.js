// this javascript program runs all the necessary operations needed on live during the use of the page_student.php
// operations like button clicks to request certain data or even a mouse hover to make it responsive

$("#logout-btn").click(function () {
  $.get(
    current_hosting_url + "php/js-request/current_session_destroy.php",
    function () {
      window.location = current_hosting_url + "pages/page_register_login.php";
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
  document.getElementById("green-prompt").style.height = "100%";
  document.getElementById("answering-area").style.display = "none";
}

function apply_change_username() {
  if (
    document.getElementById("inserted-name").innerHTML ==
    document.getElementById("new-username-input").value
  ) {
    alert("You still have the same Username");
  } else {
    $.post(
      current_hosting_url + "php/js-request/change_username.php",
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

function answer_question(question) {
  document.getElementById("overlay").style.display = "block";
  document.getElementById("green-prompt").style.display = "block";
  document.getElementById("insert-title").innerHTML = "Assessment Task";
  document.getElementById("green-prompt-content").innerHTML =
    arr_questions[question] + "<br>• You must write atleast 350 words.";
  document.getElementById("green-prompt-content").style.height = "10%";
  document.getElementById("answering-area").style.display = "block";
  answering_currently = arr_questions_id[question];
  // alert("arr_questions_id: " + arr_questions_id);
  // alert("arr_questions_id: " + answering_currently);
}

function grammarly_stopper() {
  editor = document.querySelector("grammarly-editor-plugin");
  editor.disconnect();
}

function check_num_words() {
  if (parseInt(document.getElementById("count-words").innerHTML) >= 350) {
    grading();
  } else {
    alert("Sorry you must have enough words to submit your answer.");
  }
}
function grading() {
  Grammarly.init().then((grammarly) => {
    editor = document.querySelector("textarea");
    grammarly.addPlugin(editor);
    editor.config = {
      underlines: "on",
    };
    editor.config = {
      suggestionCards: "on",
    };
  });
  // alert("grading()");
  document.getElementById("overlay").style.zIndex = "3";
  document.getElementById("show-loading").style.zIndex = "3";
  document.getElementById("show-loading").style.display = "block";
  // Grading essay answer via grammar, spelling and other text composition

  setTimeout(grammarly_function, 7000);
}

function grammarly_function() {
  var grade_grammar = "";
  // alert(
  //   "for start = " +
  //     document.querySelector("#grammarlyapi").shadowRoot.innerHTML[
  //       document
  //         .querySelector("#grammarlyapi")
  //         .shadowRoot.innerHTML.indexOf('data-grammarly-count="') +
  //         'data-grammarly-count="'.length
  //     ]
  // );
  for (
    i =
      document
        .querySelector("#grammarlyapi")
        .shadowRoot.innerHTML.indexOf('data-grammarly-count="') +
      'data-grammarly-count="'.length;
    document.querySelector("#grammarlyapi").shadowRoot.innerHTML[i] != '"';
    i++
  ) {
    grade_grammar +=
      document.querySelector("#grammarlyapi").shadowRoot.innerHTML[i];
  }
  text = document.getElementById("textarea-answer").value;
  // alert("grammarly_function() grade_grammar output = " + grade_grammar);
  essaycontent_function(text, answering_currently, grade_grammar);
}

function essaycontent_function(answer, question_id, grade_grammar) {
  // alert(
  //   "throwing answer | question_id == " + answer + " | " + answering_currently
  // );
  $.post(
    current_hosting_url + "php/checkstudent_all_steps.php",
    {
      answer: answer,
      question_id: question_id,
    },
    function (grade_content) {
      data = grade_content.split(",");
      grade_content = data[0];
      score = data.length - 1;
      hps = 0;
      viability = 0;
      // alert("score: " + score);
      examine = "";
      for (
        i = 1;
        i < data.length && answer.toLowerCase().includes(data[i]) != true;
        i++
      ) {
        score--;
      }
      for (i = 1; i < data.length; i++) {
        if (answer.toLowerCase().includes(data[i]) == true) {
          examine += data[i] + " ";
          viability += data.length - i;
        }
        hps += data.length - i;
      }
      // alert("data: " + data);
      // alert(
      //   "grammar: " +
      //     grade_grammar +
      //     " | content: " +
      //     grade_content +
      //     " | score: " +
      //     score
      // );
      // alert(viability + " / " + hps);
      // alert(examine);
      $.post(
        current_hosting_url + "php/js-request/save_answer.php",
        {
          question_id: question_id,
          answer: answer,
          grade:
            grade_content + "<&,&>" + grade_grammar + "<&,&>" + viability / hps,
        },
        function (data) {
          alert(data);
          window.location = current_hosting_url + "pages/page_student.php";
        }
      );
      document.getElementById("overlay").style.zIndex = "2";
      document.getElementById("show-loading").style.zIndex = "2";
      document.getElementById("show-loading").style.display = "none";
    }
  );
}

$("#enter-course-code").click(function () {
  if (document.getElementById("course-code-input").value.length == 0) {
    alert("Please input the course code...");
  } else {
    $.post(
      current_hosting_url +
        "php/js-request/page_student_fetch_existing_coursecode.php",
      { course_code: document.getElementById("course-code-input").value },
      function (data) {
        // alert(data);
        data = JSON.parse(data);
        if (data.length == 1) {
          $.post(
            current_hosting_url +
              "php/js-request/page_student_create_connection.php",
            { teacher_id: data[0]["user_id"] },
            function (data) {
              alert(data);
              window.location = current_hosting_url + "pages/page_student.php";
            }
          );
        } else {
          alert("You entered non-existent course code...");
        }
      }
    );
  }
});

function count_words() {
  arr_text = document
    .getElementById("textarea-answer")
    .value.split(" ")
    .filter(function (el) {
      return el != "";
    });
  word_count = 0;
  if (arr_text[arr_text.length - 1] == "") {
    document.getElementById("count-words").innerHTML = arr_text.length - 1;
    word_count = arr_text.length - 1;
  } else {
    document.getElementById("count-words").innerHTML = arr_text.length;
    word_count = arr_text.length;
  }
  if (word_count > 450) {
    alert("Sorry you cannot add more words.");
    output = "";
    while (word_count > 450) {
      num_text = document.getElementById("textarea-answer").value.split(" ");
      output = num_text[0];
      for (i = 1; i < 450; i++) {
        output += " " + num_text[i];
      }
      word_count = output.split(" ").length;
      document.getElementById("count-words").style.color = "white";
      document.getElementById("count-words").value = word_count;
    }
    document.getElementById("textarea-answer").value = output;
    document.getElementById("count-words").innerHTML = word_count;
  }
  if (word_count < 350) {
    document.getElementById("count-words").style.color = "red";
  } else {
    document.getElementById("count-words").style.color = "white";
  }
}
