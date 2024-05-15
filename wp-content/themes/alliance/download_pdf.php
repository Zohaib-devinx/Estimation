<?php

// Template Name: Download pdf

$qry="SELECT SUM(total_task_hours) as total_task_hours,SUM(qa_hours) as qa_hours,SUM(pm_hours) as pm_hours,SUM(discount) as discount,SUM(sub_total_cost) as sub_total_cost FROM `hours_list`";
if(isset($_GET['row'])){
    $qry.=" WHERE project_id=".$_GET['row'];
}
$results = $wpdb->get_results($qry);
$pdf_html1='<table align="center" width="100%" border="1" style="margin-top:10px; padding-top:10px; padding-bottom:10px" >
    <tr style="background-color:#1caae7; color:#fff; font-size:13px;">
        <th>Total Hours</th>
        <th>QA Hours</th>
        <th>PM Hours</th>
        <th>Discount</th>
        <th>Sub Total Cost</th>
        </tr>
        <tbody>';

if (!empty($results)) {
    foreach ($results as $row) {
      $pdf_html1.=' <tr>
       <td>'.$row->total_task_hours.'</td>
       <td>'.$row->qa_hours.'</td>
       <td>'.$row->pm_hours.'</td>
       <td>'.$row->discount.'</td>
       <td>'.$row->sub_total_cost.'</td>
        </tr>';
    }

}


$pdf_html1.='</tbody>
</table>';







$qry="SELECT * FROM `tasks_list`";
if(isset($_GET['row'])){
    $qry.=" WHERE project_id=".$_GET['row'];
}
$results = $wpdb->get_results($qry);
$pdf_html='<table align="center"  width="100%" border="1"  style="margin-top:50px; padding-top:20px; padding-bottom:20px">
    <tr style="background-color:#1caae7; color:#fff; font-size:13px;">
        <th>No</th>
        <th>Task</th>
        <th>Description</th>
        <th>Comments</th>
        <th>Hours</th>
        </tr>
        <tbody>';

if (!empty($results)) {
    foreach ($results as $row) {
      $pdf_html.=' <tr style=" text-align:center; font-size:13px;">
        <td>'.$row->id.'</td>
        <td>'.$row->task.'</td>
        <td>'.$row->description.'</td>
        <td>'.$row->comments.'</td>
        <td>'.$row->hours.'</td>
                </tr>';
    }

}


$pdf_html.='</tbody>
</table>';
require_once('tcpdf/tcpdf.php');


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.'logo_example.jpg';
        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

 
}

if (isset($_GET['row'])) {
    $row = $_GET['row'];

    // Fetch the row data from the database or any other source
    $data = $wpdb->get_row("SELECT * FROM tasks_list WHERE id = $row");

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Admin');
    $pdf->SetTitle('Estimation');
    $pdf->SetSubject('Devinx Tasks');
    $pdf->SetKeywords('Devinx, PDF, example, test, guide');
    
    // set default header data
    // $logoPath = 'https://devinx.com/estimation/wp-content/uploads/2023/03/Devinx-logo-01-01-1.png';
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Tasks', 'Admin', array(0,64,255), array(0,64,128));
    $pdf->setFooterData(array(0,64,0), array(0,64,128));
    
    // set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    
    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    
    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    
    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    
    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $pdf->setLanguageArray($l);
    }
    
    // ---------------------------------------------------------
    
    // set default font subsetting mode
    $pdf->setFontSubsetting(true);
    
    // Set font
    // dejavusans is a UTF-8 Unicode font, if you only need to
    // print standard ASCII chars, you can use core fonts like
    // helvetica or times to reduce file size.
    $pdf->SetFont('dejavusans', '', 14, '', true);
    
    // Add a page
    // This method has several options, check the source code documentation for more information.
    $pdf->AddPage();
    
    // set text shadow effect
    $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
    
    // Set some content to print
    $html ='<h1>Estimations</h1>'.$pdf_html.$pdf_html1;
    
    // Print text using writeHTMLCell()
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    // ---------------------------------------------------------
    
    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    $pdf->Output('Estimation_01.pdf', 'I');
}