<?php

// Template Name: Data display Form

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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .delete-row{
            color:red;
        }
    </style>
</head>
<body>
<div class="container" style="margin-top:-55px;">
<div class="row">
    <div class="col">
    <div class="d-flex justify-content-between align-items-center">
		<h1 class="my-5">Estimations</h1>
	<div>
        <?php
         $id = $_GET['view_id'];
       
         ?>
	<button  class="btn btn-success"><a class="text-white" href="http://localhost/Estimation/updateform?id=<?php echo $id?>">Edit Estimation</a></button>
	</div>
   
    </div>
    </div>
</div>
</div>
</body>
</html>

<?php


// Hours form
$qry="SELECT SUM(total_task_hours) as total_task_hours,SUM(qa_hours) as qa_hours,SUM(pm_hours) as pm_hours,SUM(discount) as discount,SUM(sub_total_cost) as sub_total_cost FROM `hours_list`";
if(isset($_GET['view_id'])){
    $qry.=" WHERE project_id=".$_GET['view_id'];
}
$results = $wpdb->get_results($qry);
echo '<table  width="100%" border="1" style="margin-top:10px;" >
    <tr>
        <th>Total Hours</th>
        <th>QA Hours</th>
        <th>PM Hours</th>
        <th>Discount</th>
        <th>Sub Total Hours</th>
        <th>Total Cost</th>
        <th>Per Hour Rate</th>
        </tr>
        <tbody>';

if (!empty($results)) {
    foreach ($results as $row) {

        // Multiply sub_total_cost by 5
        $total_cost = $row->sub_total_cost * 5;

       echo ' <tr style=" text-align: center; background-color:#C8C3FF54;">
       <td>'.$row->total_task_hours.'</td>
       <td>'.$row->qa_hours.'</td>
       <td>'.$row->pm_hours.'</td>
       <td>'.$row->discount.'</td>
       <td>'.$row->sub_total_cost.'</td>
       <td>' . $total_cost . '$</td>
       <td>5$</td>
        </tr>';
    }

}


echo '</tbody>
</table>';
$qry="SELECT * FROM `tasks_list`";
if(isset($_GET['view_id'])){
    $qry.=" WHERE project_id=".$_GET['view_id'];
}
$results = $wpdb->get_results($qry);
echo '<table align="center"  width="100%" border="1" style="margin-top:50px;">
    <tr style="background-color:#000; color:#fff;">
        <th>No</th>
        <th>Task</th>
        <th>Description</th>
        <th>Comments</th>
        <th>Hours</th>
        <th>Action</th>
        </tr>
        <tbody>';
        $counter = 1; // Initialize the counter variable

if (!empty($results)) {
    foreach ($results as $row) {
       echo ' <tr style=" text-align: center;">
        <td>'.$counter.'</td>
        <td>'.$row->task.'</td>
        <td>'.$row->description.'</td>
        <td>'.$row->comments.'</td>
        <td>'.$row->hours.'</td>
        <td>
        <a class="delete-row" href="http://localhost/Estimation/deleteform?delete_id='.$row->id.'"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
        </tr>';

        $counter++; // Increment the counter variable
    }

}


echo '</tbody>
</table>';


get_footer();