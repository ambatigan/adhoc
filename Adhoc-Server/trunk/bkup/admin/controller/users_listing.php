<?php
$title = "Users Listing";
$heading = "Users Listing";

// Report all PHP errors (see changelog)
//error_reporting(E_ALL);

require_once(COMMON_CORE_MODEL . "users.class.php");
$objUser = new users();
$objPagging = new pagging(RECORDS_PER_PAGE, "post");
$pageno = (isset($_REQUEST['pageno']))?$_REQUEST['pageno']:1;
$sort_by = (isset($_REQUEST['sort_by']) && $_REQUEST['sort_by'] != '') ? $_REQUEST['sort_by'] : "id";
$sort_order = (isset($_REQUEST['sort_order']) && $_REQUEST['sort_order'] != '') ? $_REQUEST['sort_order'] : "DESC";

//DELETE
if ($_REQUEST['id'] != '' && $_REQUEST['action'] == 'delete') {
    if(!is_numeric($_REQUEST['id'])){
        $_SESSION['message_type'] = 'error';
        $_SESSION['message'] = "No record found";
        $commonFunction->Redirect('./?page=users_listing');
        exit;
    }
    else{
        $objUser->id = $_REQUEST['id'];
        $objUser->selectById();
        if($objUser->type=='admin'){
            $_SESSION['message_type'] = 'error';
            $_SESSION['message'] = "Admin user cannot be deleted";
            $commonFunction->Redirect('./?page=users_listing');
            exit;
        }
        if ($objUser->delete()) {
            if ($objUser->deleteGreetings()) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "User successfully deleted";
                if(isset($_REQUEST['pageno'])){
                    $etvars = '&pageno='.$_REQUEST['pageno'];
                }
                if(isset($_REQUEST['searchtext'])){
                        $etvars .= '&searchtext='.$_REQUEST['searchtext'];
                }
                if(isset($_REQUEST['search'])){
                        $etvars .= '&search='.$_REQUEST['search'];
                }
                $commonFunction->Redirect('./?page=users_listing'.$etvars);
                //exit;
            }
        }
    }
}

if ($_REQUEST['searchtext'] != "")
{
    if ($_REQUEST['search'] == '1')
    {
      $search_user = trim($_REQUEST['searchtext']);
      $user = $objUser->select($objPagging, "WHERE u.user_name like '%" . mysql_real_escape_string($search_user) . "%' and u.deleted=0 ", $sort_by, $sort_order);
    }
    elseif ($_REQUEST['search'] == '2')
    {
        $search_email = trim($_REQUEST['searchtext']);
        $user = $objUser->select($objPagging, " WHERE u.email like '%" . mysql_real_escape_string($search_email) . "%' and u.deleted=0 ", $sort_by, $sort_order);
    }
    elseif ($_REQUEST['search'] == '3') 
    {
        $search_phone = trim($_REQUEST['searchtext']);
        $user = $objUser->select($objPagging, " WHERE u.phone like '%" . mysql_real_escape_string($search_phone) . "%' and u.deleted=0 ", $sort_by, $sort_order);
    }
    $paggingRecord = $objPagging->InfoArray();
}
else
{
        $user = $objUser->select($objPagging, " WHERE u.deleted=0 ", $sort_by, $sort_order);
        $paggingRecord = $objPagging->InfoArray();
}



//echo "<pre>"; print_r($user); exit;

$extraVars = (isset($_GET) ? $_GET : array());
$pagging = $objPagging->print_pagging($extraVars, ADMIN_PAGGING_STYLE);
$starting_record = $paggingRecord['START_OFFSET'];
$ending_record = $paggingRecord['END_OFFSET'];
$total_records = $paggingRecord['TOTAL_RESULTS'];
?>