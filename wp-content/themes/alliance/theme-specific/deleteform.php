<?php
// Template Name: Delete Form

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

if (isset($_GET['delete_id'])) {
    // Delete the row from the database
    $sid = $_GET['delete_id'];
    $wpdb->delete('tasks_list', array('id' => $sid), array('%d'));

    
    if ($wpdb) {
        // Redirect to a different page after deleting the data
        wp_redirect('http://localhost/Estimation');
    }
    exit;


    
     
}


?>



<?php
get_footer();
?>