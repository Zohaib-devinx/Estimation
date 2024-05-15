<?php

// Template Name: Form Submit

global $wpdb;
get_header();

while ( have_posts() ) {
	the_post();

	get_template_part( apply_filters( 'alliance_filter_get_template_part', 'templates/content', 'page' ), 'page' );

	// If comments are open or we have at least one comment, load up the comment template.
	if ( ! is_front_page() && ( comments_open() || get_comments_number() ) ) {
		comments_template();
	}
}

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
      .choose{
        font-size:20px;
        font-weight:bold;
        margin-bottom:2px;
        color:black;
      }
      .btn{
            color:white !important;
            background-color: red !important;
        }
        .btn-1{
            color:white !important;
            background-color: green !important;
        }
    </style>

</head>
<body>
<!-- //submit form -->


<div class="table-box">
  <form method="post" class="Multiplerecord">
<?php $projects = $wpdb->get_results("SELECT * FROM projects_list");?>
  <label class="choose" for="select project">Choose a Project</label> 
    <select required name="choose_project"> 
        <option value="" disabled selected>Select project</option> 
      <?php foreach ($projects as $key => $project) {?>
        <option value="<?php echo $project->id;?>"><?php echo $project->project_name;?></option> 

      <?php } ?>
    </select>

  <table class="transcribe-table white-space-nowrap w-100 mb-4 mt-4" style="background-color:#C8C3FF54; color:#fff;">
    <thead style="background-color:#C8C3FF54; color:#fff;">
      <tr>
        <th>Total Hours</th>
        <th>QA Hours</th>
        <th>PM Hours</th>
        <th>Discount</th>
        <th>Sub Total Hours</th>
        <!-- <th>Total</th>
        <th>Submit</th> -->
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="abc">
            <input type="number" value="0" name="totalhours" readonly id="total_hours"  class="form-control" id="username">
        </td>
        <td class="abc">
            <input type="number" value="0" name="QAHours" id="qa_hours" onchange="final_hours_cal()" class="form-control">
        </td>
        <td class="abc">
              <input type="number" value="0" name="PMHours" id="pm_hours" onchange="final_hours_cal()" class="form-control">
        </td>
        <td class="abc">
              <input type="number" value="0" name="Discount" id="dis_hours" onchange="final_hours_cal()" class="form-control">
        </td>
        <td class="abc">
              <input type="number" readonly value="0" name="totalcost" id="final_hours" class="form-control">
        </td>


      </tr>
   
    </tbody>

  </table>
  <table class="transcribe-table white-space-nowrap w-100">
    <thead>
      <tr>
        <th>ID</th>
        <th>Task</th>
        <th>Discription</th>
        <th>Comments</th>
        <th>hours</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody class="duplicate-row">
      <tr id="test1">
        <td>1</td>
        <td class="abc">
            <input type="text" required name="task1" class="form-control" id="username">
        </td>
        <td class="abc">
            <input type="text"  name="description1" class="form-control">
        </td>
        <td class="abc">
              <input type="text"  name="comments1" class="form-control">
        </td>
        <td class="abc">
              <input type="number" required name="hours1" id="hours1" onchange="hours_calculation(1)" class="form-control">
        </td>
        <td>
          <div class="action-box">
        <input type="button" value="Del" class="btn btn-danger btn-remove">
          </div>
        </td>

      </tr>
   
    </tbody>

  </table>

<div class="row">
    <div class="col" style="margin-top:20px;">
    <div class="d-flex justify-content-between">
    <input type="submit" name="submit" value="submit">
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
                $('#total_row').val($(".duplicate-row tr").length);
            }
 
  });
  function hours_calculation(row){
    var total=0;
    for (let index = 1; index <= row; index++) {
      total+=parseInt($('#hours'+index).val());
      
    }
    $('#final_hours').val(total);
    $('#total_hours').val(total);
  }
  function final_hours_cal(){
    var total = $('#total_hours').val();
    var qa_hours = $('#qa_hours').val();
    var pm_hours = $('#pm_hours').val();
    var dis_hours = $('#dis_hours').val();
    var final_hours = parseInt(parseInt(total)+parseInt(qa_hours)+parseInt(pm_hours))-parseInt(dis_hours);
    console.log(final_hours);
    $('#final_hours').val(final_hours);
  }
</script>
</body>
</html>


<?php


if(isset($_POST['submit'])) {
  $project_id = $_POST['choose_project'];
  
  // Check if there is an existing record for the project in the hours_list table
  $project = $wpdb->get_row("SELECT * FROM hours_list WHERE project_id = '$project_id'");
  
  // Prepare data for hours_list table
  $hours_data = array(
      'total_task_hours' => $_POST['totalhours'],
      'qa_hours' => $_POST['QAHours'],
      'pm_hours' => $_POST['PMHours'],
      'discount' => $_POST['Discount'],
      'sub_total_cost' => $_POST['totalcost'],
      'project_id' => $_POST['choose_project']
  );

  // If there is an existing record, update it; otherwise, insert a new record
  if(!empty($project)) {
      // Update existing record in hours_list table
      $wpdb->update(
          'hours_list',
          $hours_data,
          array('project_id' => $project_id)
      );
  } else {
      // Insert new record into hours_list table
      $wpdb->insert('hours_list', $hours_data);
      $last_id = $wpdb->insert_id;
  }

  // Insert task data into tasks_list table
  for ($i = 1; $i <= $_POST['total_row']; $i++) {
      $task_data = array(
          'task' => $_POST['task'.$i],
          'description' => $_POST['description'.$i],
          'comments' => $_POST['comments'.$i],
          'hours' => $_POST['hours'.$i],
          'hours_id' => isset($last_id) ? $last_id : $project->id, // Use the last inserted ID if available, otherwise use the project ID
          'project_id' => $_POST['choose_project']
      );
      $wpdb->insert('tasks_list', $task_data);
  }

  // Display success message or perform any other actions
  if ($wpdb == true) {
      ?>
      <script>
          alert('Successfully Submitted.');
      </script>
      <?php
  }
   // Redirect to the estimation page after form submission
   wp_redirect('http://localhost/Estimation');
   exit;
}



get_footer();