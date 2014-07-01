<?php

$title = "List Language Variable";
$heading = "List Language Variable";

//error_reporting()
require_once(COMMON_CORE_MODEL . "language_var.class.php");

$objPagging = new pagging(RECORDS_PER_PAGE);

if($_POST['hdn_sorting'] != ""){
    $_REQUEST['sort_order'] = $_POST['hdn_sorting'];
    $_REQUEST['sort_by'] = 'l.name';
}

//echo $Sorting;exit;
$sort_by = (isset($_REQUEST['sort_by']) && $_REQUEST['sort_by'] != '') ? $_REQUEST['sort_by'] : "l.name ";
$sort_order = (isset($_REQUEST['sort_order']) && $_REQUEST['sort_order'] != '') ? $_REQUEST['sort_order'] : "ASC";

// Create Object of category class
$language_var = new language_var();
// $objCategory = new category();
// searching data
if ($_REQUEST['searchtext'] != "") {
    if (!isset($_REQUEST['reset'])) {
        $search_user = trim($_REQUEST['searchtext']);
        $categories = $language_var->select($objPagging, "where l.name like '%" . mysql_real_escape_string($search_user) . "%'", $sort_by, $sort_order);
        //$categories = $language_var->select($objPagging, "WHERE name like '%" . mysql_real_escape_string($search_user) . "%' ");
        $paggingRecord = $objPagging->InfoArray();
    } else {
        $categories = $language_var->select($objPagging, "where 1=1", $sort_by, $sort_order);
        $paggingRecord = $objPagging->InfoArray();
    }
} else {

    // admin category listing
    $categories = $language_var->select($objPagging, "where 1=1", $sort_by, $sort_order);
}


// show success message
if (isset($_REQUEST['msg']) && intval($_REQUEST['msg'])) {
    if ($_REQUEST['msg'] == '3') {
        $successMsg[] = "Language Variable has been Deleted Successfully";
    }
    if ($_REQUEST['msg'] == '1') {
        $successMsg[] = "Language Variable has been Added Successfully";
    }
}

//DELETE 
if ($_REQUEST['id'] != '' && $_REQUEST['action'] == 'delete') {
    $language_var->id = $_REQUEST['id'];
    if ($language_var->delete()) {
        $commonFunction->Redirect('./?page=language_var_list&action=view&msg=3');
    }
}

$extraVars = (isset($_GET) ? $_GET : array());
$pagging = $objPagging->print_pagging($extraVars, ADMIN_PAGGING_STYLE);

$infoArray = $objPagging->InfoArray();

$startOffset = $infoArray['START_OFFSET'];
$endOffset = $infoArray['END_OFFSET'];
$totalResults = $infoArray['TOTAL_RESULTS'];
?>