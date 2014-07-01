<?php

$title = "List Category";
$heading = "List Category";

require_once(COMMON_CORE_MODEL . "category.class.php");

// Create Object of category class
$objCategory = new category();
$objPagging = new pagging(RECORDS_PER_PAGE,"post");
$sortBy =(isset($_REQUEST['sort_by']) && $_REQUEST['sort_by'] != '' && !intval($_REQUEST['sort_by']))?strval($_REQUEST['sort_by']):"name";
$sortOrder =(isset($_REQUEST['sort_order']) && $_REQUEST['sort_order'] != '')?strval($_REQUEST['sort_order']):"ASC";
// searching data
if ($_REQUEST['searchtext'] != "") {
    if (!isset($_REQUEST['reset'])) {
        $search_user = trim($_REQUEST['searchtext']);
        $categories = $objCategory->select($objPagging, "WHERE name like '%" . mysql_real_escape_string($search_user) . "%' ",$sortBy,$sortOrder);
        $paggingRecord = $objPagging->InfoArray();
    } else {
        $categories = $objCategory->select($objPagging, "",$sortBy,$sortOrder);
        $paggingRecord = $objPagging->InfoArray();
    }
} else {

    // admin category listing
    $categories = $objCategory->select($objPagging, "",$sortBy,$sortOrder);
    $paggingRecord = $objPagging->InfoArray();
}


//DELETE
if($_REQUEST['id'] !='' && $_REQUEST['action']=='delete')
{
	$objCategory->id = $_REQUEST['id'];
	if($objCategory->delete())
	{
		$commonFunction->Redirect('./?page=category_list&action=view&msg=3');
        //$successMsg[] = "Category deleted successfully";
	}
    else
    {
      //$objCms->error_message =
      $errorMsg[] = $objCategory->error_message;
    }
}

//-- Success Messages --//
if ($_REQUEST['msg']!=''){
  if($_REQUEST['msg'] == '1'){$successMsg[] = "Category added successfully";}
  if($_REQUEST['msg'] == '2'){$successMsg[] = "Category updated successfully";}
  if($_REQUEST['msg'] == '3'){$successMsg[] = "Category deleted successfully";}
}
$extraVars = (isset($_GET) ? $_GET : array());
$pagging = $objPagging->print_pagging($extraVars, ADMIN_PAGGING_STYLE);
//exit;
$startOffset  = $paggingRecord['START_OFFSET'];
$endOffset    = $paggingRecord['END_OFFSET'];
$totalResults = $paggingRecord['TOTAL_RESULTS'];
?>