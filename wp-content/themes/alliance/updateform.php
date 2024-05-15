<?php
// Template Name: Update Form

global $wpdb;
get_header();

while (have_posts()) {
    the_post();

    get_template_part(apply_filters('alliance_filter_get_template_part', 'templates/content', 'page'), 'page');

    // If comments are open or we have at least one comment, load up the comment template.
    if (!is_front_page() && (comments_open() || get_comments_number())) {
        comments_template();
    }
}

$sid = $_GET['id'];

$task_list = $wpdb->get_results("SELECT * FROM `tasks_list` WHERE `project_id`='$sid'");

$sql_hours = $wpdb->get_results("SELECT id,project_id, SUM(total_task_hours) as total_task_hours, SUM(qa_hours) as qa_hours, SUM(pm_hours) as pm_hours, SUM(discount) as discount, SUM(sub_total_cost) as sub_total_cost FROM `hours_list` WHERE `project_id`='$sid' GROUP BY project_id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    

    <style>
      .choose {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 2px;
        color: black;
      }
      .btn {
        color: white !important;
        background-color: red !important;
      }
      .btn-1 {
        color: white !important;
        background-color: green !important;
      }
    </style>

</head>
<body>
<!-- submit form -->
<div class="table-box">
  <form method="post" class="Multiplerecord" id="taskListUpdate">
    <table class="transcribe-table white-space-nowrap w-100 mb-4 mt-4" style="background-color:#C8C3FF54; color:#fff;">
      <thead style="background-color:#C8C3FF54; color:#fff;">
        <tr>
          <th>Total Hours</th>
          <th>QA Hours</th>
          <th>PM Hours</th>
          <th>Discount</th>
          <th>Sub Total Cost</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        if (!empty($sql_hours)) {
          foreach ($sql_hours as $row) {
         
            echo '<tr style=" text-align: center; background-color:#C8C3FF54;">
              <td hidden><input type="hidden" name="project_id" value="' . $row->project_id . '"></td>
              <td class="abc">
                <input type="number" value="' . $row->total_task_hours . '" name="totalhours" readonly id="total_hours" class="form-control">
              </td>
              <td class="abc">
                <input type="number" value="' . $row->qa_hours . '" name="QAHours" id="qa_hours" onchange="final_hours_cal()" class="form-control">
              </td>
              <td class="abc">
                <input type="number" value="' . $row->pm_hours . '" name="PMHours" id="pm_hours" onchange="final_hours_cal()" class="form-control">
              </td>
              <td class="abc">
                <input type="number" value="' . $row->discount . '" name="Discount" id="dis_hours" onchange="final_hours_cal()" class="form-control">
              </td>
              <td class="abc">
                <input type="number" readonly value="' . $row->sub_total_cost . '" name="totalcost" id="final_hours" class="form-control">
              </td>
            </tr>';
          }
        }
        ?>
      </tbody>
    </table>

    <table class="transcribe-table white-space-nowrap w-100">
      <thead>
        <tr>
          <th>ID</th>
          <th>Task</th>
          <th>Description</th>
          <th>Comments</th>
          <th>Hours</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody class="duplicate-row">
        <?php
        if (!empty($task_list)) {
          foreach ($task_list as $key => $row) {
            
            echo '<tr style=" text-align: center; background-color:#C8C3FF54;" id="test1">
              <td>' . $row->id . '</td>
              <td><input type="text" onchange="updateValue(' . $row->id . ')" required name="task[]" class="form-control" value="' . $row->task . '"></td>
              <td><input type="text" required name="description[]" class="form-control" value="' . $row->description . '"></td>
              <td><input type="text" name="comments[]" class="form-control" value="' . $row->comments . '"></td>
              <td><input type="number" required name="hours[]" id="hours' . $key . '" onchange="hours_calculation()" class="form-control" value="' . $row->hours . '"></td>
              <td><input type="button" value="Del" class="btn btn-danger btn-remove"></td>
            </tr>';
          }
        }
        
        ?>
      </tbody>
    </table>
        
    <div class="row">
      <div class="col" style="margin-top:20px;">
        <div class="d-flex justify-content-between">
          <input type="hidden" name="sid" value="<?php echo $sid; ?>">
          <input type="submit" id="update_btn" name="submit" value="Update">
          <input type="button" value="Add More" class="btn-1 rounded-pill" id="addmorebtn" onclick="addrow()">
        </div>
      </div>
    </div>
    <input name="total_row" id="total_row" value="1" hidden />
  </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
function addrow() {
  var row = parseInt($(".duplicate-row tr").length)+1;
  var html=` <tr id="test1">
        <td>${row}</td>
        <td class="abc">
            <input type="text" name="task${row}" class="form-control" id="username">
        </td>
        <td class="abc">
            <input type="text" name="description${row}" class="form-control">
        </td>
        <td class="abc">
              <input type="text" name="comments${row}" class="form-control">
        </td>
        <td class="abc">
              <input type="number" name="hours${row}" id="hours${row}"  onchange="hours_calculation(${row})" class="form-control">
        </td>
  <td>
          <div class="action-box">
        <input type="button" value="Del" class="btn btn-danger btn-remove">
          </div>
        </td>

      </tr>`;
            $(".duplicate-row:last-child").append(html);
            $('#total_row').val(row);
        }
        $('body').on('click','.btn-remove',function() {
            console.log($(".duplicate-row tr").length);
            if($(".duplicate-row tr").length>1){

                $(this).closest("#test1").remove();
            }
 
  });


  function hours_calculation() {
    var total = 0;
    $("input[name='hours[]']").each(function() {
      if ($(this).val() !== '') {
        total += parseInt($(this).val());
      }
    });
    $('#final_hours').val(total);
    $('#total_hours').val(total);
    // var total = parseInt($('#total_hours').val());
    var qa_hours = parseInt($('#qa_hours').val());
    var pm_hours = parseInt($('#pm_hours').val());
    var dis_hours = parseInt($('#dis_hours').val());
    var final_hours = total + qa_hours + pm_hours - dis_hours;
    $('#final_hours').val(final_hours);
  }

  function final_hours_cal() {
    var total = parseInt($('#total_hours').val());
    var qa_hours = parseInt($('#qa_hours').val());
    var pm_hours = parseInt($('#pm_hours').val());
    var dis_hours = parseInt($('#dis_hours').val());
    var final_hours = total + qa_hours + pm_hours - dis_hours;
    $('#final_hours').val(final_hours);
  }
</script>





</body>
</html>

<?php
if (isset($_POST['submit'])) {
  // echo '<pre>';
  // print_r($_REQUEST);
  // exit;
  $total_task_hours = $_POST['totalhours'];
  $qa_hours = $_POST['QAHours'];
  $pm_hours = $_POST['PMHours'];
  $discount = $_POST['Discount'];
  $sub_total_cost = $_POST['totalcost'];
  $project_id = $_POST['project_id'];

  $wpdb->update(
    'hours_list',
    array(
      'total_task_hours' => $total_task_hours,
      'qa_hours' => $qa_hours,
      'pm_hours' => $pm_hours,
      'discount' => $discount,
      'sub_total_cost' => $sub_total_cost
    ),
    array('project_id' => $project_id)
  );

  $task = $_POST['task'];
  $description = $_POST['description'];
  $comments = $_POST['comments'];
  $hours = $_POST['hours'];

  for ($i = 0; $i < count($task); $i++) {
    $wpdb->update(
      'tasks_list',
      array(
        'task' => $task[$i],
        'description' => $description[$i],
        'comments' => $comments[$i],
        'hours' => $hours[$i]
      ),
      array('id' => $task_list[$i]->id)
    );
    
  }
  echo "<meta http-equiv='refresh' content='0'>"; 
}


get_footer();
?>


