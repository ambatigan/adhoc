<?php
$title = "Comment Flag Moderation";
$heading = "Comment Flag Moderation";

require_once(COMMON_CORE_MODEL . "comment_flag.class.php");
$objCommentFlag = new comment_flags();
$objPagging = new pagging(RECORDS_PER_PAGE, "post");
$pageno = (isset($_REQUEST['pageno']))?$_REQUEST['pageno']:1;
$sort_by = (isset($_REQUEST['sort_by']) && $_REQUEST['sort_by'] != '') ? $_REQUEST['sort_by'] : "id";
$sort_order = (isset($_REQUEST['sort_order']) && $_REQUEST['sort_order'] != '') ? $_REQUEST['sort_order'] : "DESC";

//delete
if ($_REQUEST['id'] != '' && $_REQUEST['action'] == 'delete') {
    if(!is_numeric($_REQUEST['id'])){
        $_SESSION['message_type'] = 'error';
        $_SESSION['message'] = "No record found";
        $commonFunction->Redirect('./?page=comment_flag_listing');
        exit;
    }
    else{
        $objCommentFlag->id = $_REQUEST['id'];
        $objCommentFlag->selectById();
        if ($objCommentFlag->delete()) {
            if ($objCommentFlag->deleteGreetings()) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Comment successfully deleted";
                if(isset($_REQUEST['pageno'])){
                    $etvars = '&pageno='.$_REQUEST['pageno'];
                }
                if(isset($_REQUEST['searchtext'])){
                        $etvars .= '&searchtext='.$_REQUEST['searchtext'];
                }
                if(isset($_REQUEST['search'])){
                        $etvars .= '&search='.$_REQUEST['search'];
                }
                $commonFunction->Redirect('./?page=comment_flag_listing'.$etvars);
            }
        }
    }
}

if ($_REQUEST['searchtext'] != "")
{
    if ($_REQUEST['search'] == '1')
    {
      $search_name = trim($_REQUEST['searchtext']);
      $comment_flag = $objCommentFlag->search_name($objPagging, " WHERE name like '%" . mysql_real_escape_string($search_name) . "%' and c.deleted=0 ", $sort_by, $sort_order);
    }
    elseif ($_REQUEST['search'] == '2')
    {
        $search_user_name = trim($_REQUEST['searchtext']);
        $comment_flag = $objCommentFlag->search_user_name($objPagging, " WHERE user_name like '%" . mysql_real_escape_string($search_user_name) . "%' and c.deleted=0 ", $sort_by, $sort_order);
    }
    $paggingRecord = $objPagging->InfoArray();
}
else
{
        $comment_flag = $objCommentFlag->select($objPagging, " WHERE c.deleted=0 ", $sort_by, $sort_order);
        $paggingRecord = $objPagging->InfoArray();
}

///******* Edit Flag Case *********//
if ($_REQUEST['id'] != '' && $_REQUEST['action'] == 'edit') {
     $objCommentFlag->id = $_REQUEST['id'];
     $objCommentFlag->selectById();
     if ($objCommentFlag->changeFlag()) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Comment flag successfully Inactivated";
                $commonFunction->Redirect('./?page=comment_flag_listing'.$etvars);
   }
  }

$extraVars = (isset($_GET) ? $_GET : array());
$pagging = $objPagging->print_pagging($extraVars, ADMIN_PAGGING_STYLE);
$starting_record = $paggingRecord['START_OFFSET'];
$ending_record = $paggingRecord['END_OFFSET'];
$total_records = $paggingRecord['TOTAL_RESULTS'];
?>