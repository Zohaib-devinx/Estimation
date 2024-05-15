<?php

// Template Name: projects


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
    <title>projects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .btn{
            color:white !important;
            background-color: green !important;
        }
    </style>
</head>
<body>

<form method="post" class="Multiplerecord">
  <table class="transcribe-table white-space-nowrap w-100">
    <thead>
      <tr>
        <th>ID</th>
        <th>Project name</th>
        <th>status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody class="duplicate-row">
      <tr id="test1">
        <td>1</td>
        <td class="abc">
            <input type="text" name="Project_name" required class="form-control" id="username">
        </td>
        <td class="abc">
    <select name="projects" id=""> 
        <option value="In Progress">In Progress</option> 
        <option value="Completed">Completed</option> 
    </select>
        </td>
        <td>
          <div class="action-box text-center">
          <input type="submit" name="submit" value="Add Projects" class="btn">
          </div>
        </td>

      </tr>
   
    </tbody>

  </table>
  </form>
</body>
</html>


<?php


if(isset($_POST['submit']))
{
  $data=array(
    'project_name'=>$_POST['Project_name'],
    'status'=>$_POST['projects'],
    'created_by'=>get_current_user_id()
);
//$wpdb->insert('projects_list', $data);
$result = $wpdb->insert('projects_list', $data); // Assign the result of the insert operation to $result

  if ($result === false) {
        echo "Error inserting data into the database.";
        // Handle error gracefully
    } else {
        echo "Data inserted successfully.";
        // Redirect or do something else after successful insertion
    }

}







get_footer();