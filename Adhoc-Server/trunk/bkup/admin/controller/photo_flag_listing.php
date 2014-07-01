<?php
$title = "Photo Flag Moderation";
$heading = "Photo Flag Moderation";

require_once(COMMON_CORE_MODEL . "photo_flag.class.php");
$objPhotoFlag = new photo_flags();
$objPagging = new pagging(RECORDS_PER_PAGE, "post");
$pageno = (isset($_REQUEST['pageno']))?$_REQUEST['pageno']:1;
$sort_by = (isset($_REQUEST['sort_by']) && $_REQUEST['sort_by'] != '') ? $_REQUEST['sort_by'] : "id";
$sort_order = (isset($_REQUEST['sort_order']) && $_REQUEST['sort_order'] != '') ? $_REQUEST['sort_order'] : "DESC";

//DELETE
if ($_REQUEST['id'] != '' && $_REQUEST['action'] == 'delete') {
    if(!is_numeric($_REQUEST['id'])){
        $_SESSION['message_type'] = 'error';
        $_SESSION['message'] = "No record found";
        $commonFunction->Redirect('./?page=photo_listing');
        exit;
    }
    else{
        $objPhotoFlag->id = $_REQUEST['id'];
        $objPhotoFlag->selectById();
        if ($objPhotoFlag->delete()) {
            if ($objPhotoFlag->deleteGreetings()) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Photo successfully deleted";
                if(isset($_REQUEST['pageno'])){
                    $etvars = '&pageno='.$_REQUEST['pageno'];
                }
                if(isset($_REQUEST['searchtext'])){
                        $etvars .= '&searchtext='.$_REQUEST['searchtext'];
                }
                if(isset($_REQUEST['search'])){
                        $etvars .= '&search='.$_REQUEST['search'];
                }
                $commonFunction->Redirect('./?page=photo_listing'.$etvars);
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
      $photo_flag = $objPhotoFlag->select($objPagging, "WHERE p.name like '%" . mysql_real_escape_string($search_name) . "%' and p.deleted=0 ", $sort_by, $sort_order);
    }
    elseif ($_REQUEST['search'] == '2')
    {
        $search_user_name = trim($_REQUEST['searchtext']);
        $photo_flag = $objPhotoFlag->search_username($objPagging, " WHERE u.user_name like '%" . mysql_real_escape_string($search_user_name) . "%' and p.deleted=0 ", $sort_by, $sort_order);
    }
    $paggingRecord = $objPagging->InfoArray();
}
else
{
        $photo_flag = $objPhotoFlag->select($objPagging, " WHERE p.deleted=0 ", $sort_by, $sort_order);
        $paggingRecord = $objPagging->InfoArray();
}

///******* Edit Flag Case *********//
if ($_REQUEST['id'] != '' && $_REQUEST['action'] == 'edit') {
     $objPhotoFlag->id = $_REQUEST['id'];
     $objPhotoFlag->selectById();
     if ($objPhotoFlag->changeFlag()) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Photo flag successfully Inactivated";
                $commonFunction->Redirect('./?page=photo_flag_listing'.$etvars);
   }
  }

$extraVars = (isset($_GET) ? $_GET : array());
$pagging = $objPagging->print_pagging($extraVars, ADMIN_PAGGING_STYLE);
$starting_record = $paggingRecord['START_OFFSET'];
$ending_record = $paggingRecord['END_OFFSET'];
$total_records = $paggingRecord['TOTAL_RESULTS'];
?>