<?php
/**
 * The Front Page template file.
 *
 * @package ALLIANCE
 * @since ALLIANCE 1.0.31
 */
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
	.download-row{
		color:green;
	}
	</style>
</head>
<body>

<div class="container" style="margin-top:-55px;">
<div class="row">
    <div class="col" >
    <div class="d-flex justify-content-between align-items-center">
		<h1 class="my-5">Projects</h1>
	<div>
	<button  class="btn btn-success"><a class="text-white" href="https://devinx.com/estimation/add-estimation/">Create Estimation</a></button>
	</div>
   
    </div>
    </div>
</div>
</div>
	
</body>
</html>


<?php


$results = $wpdb->get_results("SELECT * FROM `projects_list`");
echo '<table align="center" width="100%" border="1" style="margin-top:15px;">
    <tr >
        <th>ID</th>
        <th>Name</th>
        <th>Created by</th>
        <th>Time</th>
        <th>Status</th>
		<th>Action</th>
        </tr>
        <tbody>';

		$counter = 1; // Initialize the counter variable

if (!empty($results)) {
    foreach ($results as $row) {
       echo ' <tr style=" text-align: center;  background-color:#C8C3FF54;">
        <td>'.$counter.'</td>
        <td>'.$row->project_name.'</td>
        <td>'.get_userdata($row->created_by)->display_name.'</td>
        <td>'.$row->created_at.'</td>
        <td>'.$row->status.'</td>
		<td>
		<div>
		<a target="_blank" href="http://localhost/Estimation//data/?view_id='.$row->id.'"><i class="fa fa-eye" aria-hidden="true"></i></a>
		<a href="http://localhost/Estimation//update-project/?sid='.$row->id.'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
		</a>
		<a class="download-row" target="_blank" href="http://localhost/Estimation//download-pdf?row='.$row->id.'"><i class="fa fa-download" aria-hidden="true"></i>
		</a>
		<a class="delete-row"  href="http://localhost/Estimation//delete-project/?delete_id='.$row->id.'"><i class="fa fa-trash-o" aria-hidden="true"></i>
		</a>
		</div>
		</td>
        </tr>';

		$counter++; // Increment the counter variable
    }

}


echo '</tbody>
</table>';




// If front-page is a static page
if ( get_option( 'show_on_front' ) == 'page' ) {

	// If Front Page Builder is enabled - display sections
	if ( alliance_is_on( alliance_get_theme_option( 'front_page_enabled', false ) ) ) {

		if ( have_posts() ) {
			the_post();
		}

		$alliance_sections = alliance_array_get_keys_by_value( alliance_get_theme_option( 'front_page_sections' ) );
		if ( is_array( $alliance_sections ) ) {
			foreach ( $alliance_sections as $alliance_section ) {
				get_template_part( apply_filters( 'alliance_filter_get_template_part', 'front-page/section', $alliance_section ), $alliance_section );
			}
		}


		

		// Else if this page is a blog archive
	} elseif ( is_page_template( 'blog.php' ) ) {
		get_template_part( apply_filters( 'alliance_filter_get_template_part', 'blog' ) );

		// Else - display a native page content
	} else {
		get_template_part( apply_filters( 'alliance_filter_get_template_part', 'page' ) );
	}

	// Else get the template 'index.php' to show posts
} else {
	get_template_part( apply_filters( 'alliance_filter_get_template_part', 'index' ) );
}




get_footer();