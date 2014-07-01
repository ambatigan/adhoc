<?php
$title = "Tags Listing";
$heading = "Tags Listing";

require_once(COMMON_CORE_MODEL . "tag.class.php");
$objTag = new tag();
$objPagging = new pagging(RECORDS_PER_PAGE, "post");
$pageno = (isset($_REQUEST['pageno']))?$_REQUEST['pageno']:1;
$sort_by = (isset($_REQUEST['sort_by']) && $_REQUEST['sort_by'] != '') ? $_REQUEST['sort_by'] : "id";
$sort_order = (isset($_REQUEST['sort_order']) && $_REQUEST['sort_order'] != '') ? $_REQUEST['sort_order'] : "DESC";

//DELETE
if ($_REQUEST['id'] != '' && $_REQUEST['action'] == 'delete') {
    if(!is_numeric($_REQUEST['id'])){
        $_SESSION['message_type'] = 'error';
        $_SESSION['message'] = "No record found";
        $commonFunction->Redirect('./?page=tag_listing');
        exit;
    }
    else{
        $objTag->id = $_REQUEST['id'];
        $objTag->selectById();
        if ($objTag->delete()) {
            if ($objTag->deleteGreetings()) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Tag successfully deleted";
                if(isset($_REQUEST['pageno'])){
                    $etvars = '&pageno='.$_REQUEST['pageno'];
                }
                if(isset($_REQUEST['searchtext'])){
                        $etvars .= '&searchtext='.$_REQUEST['searchtext'];
                }
                if(isset($_REQUEST['search'])){
                        $etvars .= '&search='.$_REQUEST['search'];
                }
                $commonFunction->Redirect('./?page=tag_listing'.$etvars);
                //exit;
            }
        }
    }
}

if ($_REQUEST['searchtext'] != "")
{
    if ($_REQUEST['search'] == '1')
    {
      $search_name = trim($_REQUEST['searchtext']);
      $tag = $objTag->select($objPagging, " WHERE t.name like '%" . mysql_real_escape_string($search_name) . "%' and t.deleted=0 ", $sort_by, $sort_order);
    }
    elseif ($_REQUEST['search'] == '3')
    {
        $search_photo_name = trim($_REQUEST['searchtext']);

        $tag = $objTag->Username($objPagging, " WHERE u.user_name like '%" . mysql_real_escape_string($search_photo_name) . "%' and t.deleted=0 and u.deleted=0 ", $sort_by, $sort_order);

    }
    /*elseif ($_REQUEST['search'] == '2')
    {
        $search_photo_name = trim($_REQUEST['searchtext']);

        $tag = $objTag->PhotoName($objPagging, " WHERE p.name like '%" . mysql_real_escape_string($search_photo_name) . "%' and t.deleted=0 and t.status='Active' and p.deleted=0 and p.status='Active' and p.flag='Inactive' ", $sort_by, $sort_order);
    }*/
    $paggingRecord = $objPagging->InfoArray();
}
else
{
        $tag = $objTag->select($objPagging, " WHERE t.deleted=0 ", $sort_by, $sort_order);
        $paggingRecord = $objPagging->InfoArray();
}
//echo "<pre>"; print_r($photo); exit;

$extraVars = (isset($_GET) ? $_GET : array());
$pagging = $objPagging->print_pagging($extraVars, ADMIN_PAGGING_STYLE);
$starting_record = $paggingRecord['START_OFFSET'];
$ending_record = $paggingRecord['END_OFFSET'];
$total_records = $paggingRecord['TOTAL_RESULTS'];
?>