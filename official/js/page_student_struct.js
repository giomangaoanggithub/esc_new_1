function start_page() {
  $.get(
    "http://localhost/a_product/official/php/js-request/page_student_start.php",
    function (data) {
      data = JSON.parse(data);
      document.getElementById("inserted-name").innerHTML = data[0]["username"];
      $.get(
        "http://localhost/a_product/official/php/js-request/page_student_start_data.php",
        function (data) {
          data = JSON.parse(data);
          if (data.length > 0) {
            document.getElementById("show-no-publish").style.display = "none";
            $("#student-table-content").empty();
            for (i = 0; i < data.length; i++) {
              if (document.getElementById("current-time").innerHTML) {
              }
              $("#student-table-content").append(
                "<tr><td>" +
                  data[i]["username"] +
                  "</td><td>" +
                  data[i]["question"] +
                  "</td><td>" +
                  data[i]["due_date"] +
                  "</td><td>" +
                  data[i]["HPS"] +
                  "</td><td><span class='material-icons'>history_edu</span></td></tr>"
              );
            }
          }
        }
      );
      $.get(
        "http://localhost/a_product/official/php/js-request/page_student_start_teachers.php",
        function (data) {
          data = JSON.parse(data);
          if (data.length > 0) {
            for (i = 0; i < data.length; i++) {}
          }
        }
      );
    }
  );
}

start_page();
