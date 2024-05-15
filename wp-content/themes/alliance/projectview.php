<?php

// Template Name: project view Estimation


get_header();


while ( have_posts() ) {
	the_post();

	get_template_part( apply_filters( 'alliance_filter_get_template_part', 'templates/content', 'page' ), 'page' );

	// If comments are open or we have at least one comment, load up the comment template.
	if ( ! is_front_page() && ( comments_open() || get_comments_number() ) ) {
		comments_template();
	}
}



$results = $wpdb->get_results("SELECT * FROM `projects_list`");
echo '<table align="center" width="100%" border="1" style="margin-top:10px;">
    <tr style="background-color:#000; color:#fff;">
        <th>ID</th>
        <th>Name</th>
        <th>Created by</th>
        <th>Time</th>
        <th>Status</th>
        <th>Edit</th>
        <th>Del</th>
        <th>View</th>
        </tr>
        <tbody>';
        

if (!empty($results)) {
    foreach ($results as $row) {
    
       echo ' <tr style=" text-align: center;">
       <td>'.$row->id.'</td>
        <td>'.$row->project_name.'</td>
        <td>'.get_userdata($row->created_by)->display_name.'</td>
        <td>'.$row->created_at.'</td>
        <td>'.$row->status.'</td>
        <td><a href="https://devinx.com/estimation/update-project/?sid='.$row->id.'">Edit</a></td>
        <td><a href="https://devinx.com/estimation/delete-project/?delete_id='.$row->id.'">Del</a></td>
        <td><a href="https://devinx.com/estimation/data/?view_id='.$row->id.'">View</a></td>
        </tr>';
     
        
    }

}


echo '</tbody>
</table>';




get_footer();