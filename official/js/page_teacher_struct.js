// this javascript program runs in the background of pages/page_teacher.php. It fetches all the data requested from this account.
// The requested data are the contents of the account initially whenever they login.

// public variables

var arr_questions = [];
var arr_collected_links = [];
var arr_time_of_issue = [];
var arr_grades = [];
var arr_students = [];
var arr_assigned_questions = [];

function start_page() {
  $.get(
    current_hosting_url + "php/js-request/page_teacher_start.php",
    function (data) {
      // alert(data);
      data = JSON.parse(data);
      document.getElementById("inserted-name").innerHTML = data[0]["username"];
      document.getElementById("account-course-code").innerHTML =
        "Course Code: " + data[0]["course_code"];
      $.get(
        current_hosting_url + "php/js-request/page_teacher_start_data.php",
        function (data) {
          data = JSON.parse(data);

          if (data.length > 0) {
            document.getElementById("if-empty-table-question").style.display =
              "none";
            for (i = 0; i < data.length; i++) {
              arr_questions.push(data[i]["question"]);
              arr_collected_links.push(data[i]["collected_links"]);
              arr_time_of_issue.push(data[i]["time_of_issue"]);
              arr_grades.push(data[i]["HPS"]);
              $("#teacher-left-side-table-tr").append(
                "<tr class='tr-questions-action' id='trquestion_" +
                  i +
                  "' onclick='click_tr_question(" +
                  data[i]["question_id"] +
                  ")'><td>" +
                  data[i]["question"] +
                  "</td><td>" +
                  data[i]["HPS"] +
                  "</td><td>" +
                  data[i]["due_date"] +
                  "</td><td><span class='material-icons'>edit</span><span class='material-icons'>delete</span></td></tr>"
              );
            }
          }
        }
      );
      $.get(
        current_hosting_url + "php/js-request/page_teacher_start_students.php",
        function (data) {
          data = JSON.parse(data);
          if (data.length > 0) {
            for (i = 0; i < data.length; i++) {
              arr_students.push(data[i]["username"]);
              document.getElementById("no-show-students").style.display =
                "none";
              $("#teacher-student-left-side-table-tr").append(
                "<tr><td>" +
                  data[i]["username"] +
                  "</td><td>" +
                  data[i]["email"] +
                  "</td><td><span class='material-icons'>cancel</span></td></tr>"
              );
            }
          }
        }
      );
    }
  );
}

start_page();
