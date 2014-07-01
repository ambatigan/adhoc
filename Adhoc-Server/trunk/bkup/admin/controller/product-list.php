<?php
$title="List Product";
$heading="List Product";

require_once(COMMON_CORE_MODEL . "product.class.php");
$objProduct = new product();
$objPagging = new pagging(RECORDS_PER_PAGE,"post");
$pageno = (isset($_REQUEST['pageno']))?$_REQUEST['pageno']:1;

$sortBy =(isset($_REQUEST['sort_by']) && $_REQUEST['sort_by'] != '' && !intval($_REQUEST['sort_by']))?strval($_REQUEST['sort_by']):"title";
$sortOrder =(isset($_REQUEST['sort_order']) && $_REQUEST['sort_order'] != '')?strval($_REQUEST['sort_order']):"ASC";


//-- Search Text --//
if ($_REQUEST['searchtext'] != "")
{
    if (!isset($_REQUEST['reset'])) {
      $search_user = trim($_REQUEST['searchtext']);
      $product = $objProduct->select($objPagging, "WHERE p.deleted = 0 AND p.title like '%" . mysql_real_escape_string($search_user) . "%' OR c.name like '%" . mysql_real_escape_string($search_user) . "%' ",$sortBy,$sortOrder);

    } else {
        $product = $objProduct->select($objPagging, "WHERE p.deleted = 0",$sortBy,$sortOrder);
    }
}
else
{
    //$product = $objProduct->select($objPagging, "as p inner join categories as c on p.category_id = c.id ", "p.id" , "");
    $product = $objProduct->select($objPagging,"WHERE p.deleted = 0",$sortBy,$sortOrder);
}

//DELETE
if($_REQUEST['id'] !='' && $_REQUEST['action']=='delete')
{
	$objProduct->id = $_REQUEST['id'];
	if($objProduct->delete())
	{
		$commonFunction->Redirect('./?page=product-list&action=view&msg=3');
        //$successMsg[] = "Category deleted successfully";
	}
    else
    {
      //$objCms->error_message =
      $errorMsg[] = $objProduct->error_message;
    }
}

//-- Success Messages --//
if ($_REQUEST['msg']!=''){
  if($_REQUEST['msg'] == '1'){$successMsg[] = "Product added successfully";}
  if($_REQUEST['msg'] == '2'){$successMsg[] = "Product updated successfully";}
  if($_REQUEST['msg'] == '3'){$successMsg[] = "Product deleted successfully";}
}

//-- Pagination --//
$extraVars = (isset($_GET) ? $_GET : array());
$pagging = $objPagging->print_pagging($extraVars, ADMIN_PAGGING_STYLE);
$infoArray = $objPagging->InfoArray();
$startOffset  = $infoArray['START_OFFSET'];
$endOffset    = $infoArray['END_OFFSET'];
$totalResults = $infoArray['TOTAL_RESULTS'];


?>