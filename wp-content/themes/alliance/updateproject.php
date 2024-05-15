<?php
// Template Name: Update project

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

$sid = $_GET['sid'];
$sql = "SELECT * FROM `projects_list` WHERE `id`='$sid'";
$row = $wpdb->get_row($sql);

if (isset($_POST['update_project'])) {
    $project_name = $_POST['Project_name'];
    $status = $_POST['projects'];
    $sid = $_POST['sid'];

    // Update the row in the database
    $wpdb->update(
        'projects_list',
        array(
            'project_name' => $project_name,
            'status' => $status
        ),
        array('id' => $sid)
    );
    echo "<meta http-equiv='refresh' content='0'>"; 
}

?>

<form method="post" action="#" enctype="multipart/form-data">
    <table align="center" border="1" style="width:70%; margin-top:40px;">
        <tr>
            <th>Name</th>
            <td><input type="text" name="Project_name" value="<?php echo $row ? $row->project_name : ''; ?>" required></td>
        </tr>
        <tr>
            <th>Status</th>
            <td> <select name="projects" id=""> 
        <option value="In Progress" <?php echo $row->status=='In Progress'?'selected' : ''; ?>>In Progress</option> 
        <option value="Completed" <?php echo $row->status=='Completed'?'selected' : ''; ?>>Completed</option> 
    </select></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="hidden" name="sid" value="<?php echo $sid; ?>">
                <input type="submit" name="update_project" value="Update">
            </td>
        </tr>
    </table>
</form>

<?php

get_footer();
?>