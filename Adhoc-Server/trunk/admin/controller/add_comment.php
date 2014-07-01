<?php
$action = isset($_GET['action']) ? $_GET['action'] : '';

if($_GET['action']=='edit' && $_GET['id']!=''){
    $heading = "Comment Management";
    $title = "Edit Comment";
}
else{
    $heading = "Comment Management";
    $title = "Add Comment";
}

require_once(COMMON_CORE_MODEL . "comment.class.php");
require_once(COMMON_CORE_MODEL . "users.class.php");
require_once(COMMON_CORE_MODEL . "photo.class.php");

$objComment = new comments();
$objUser = new users();
$objPhoto = new photo();

$userDetail = $objUser->select("",'where deleted="0" and status="Active"',"id","ASC");
$photo = $objPhoto->select("",'where deleted="0" and status="Active"',"id","ASC");

$objComment->id = (isset($_GET['id']) && $_GET['id'] != '' && is_numeric($_GET['id'])) ? htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8') : $objComment->id;
$comment = array();

///******* Edit Case *********//
if ($objComment->id != '' && $action == 'edit')
{
    $objComment->selectById();
    if ($objComment->id == "")
    {
    	$_SESSION['message_type'] = 'error';
        $_SESSION['message'] = "Record does not exist";
        $objComment->commonFunction->Redirect('./?page=comment_listing');
    }
    else
    {
        $comment = (array) $objComment;
    }
}

///******* Insert / Update Case *********//
if (isset($_POST['addEditCommentSubmit']))
{
      foreach ($objComment as $k => $d)
    {
        if($k!='phto_id'){
            $objComment->$k = (isset($_POST[$k])) ? htmlspecialchars($_POST[$k], ENT_QUOTES, 'UTF-8') : $objComment->$k;
        }
    }
    if($_GET['action']=='edit' && $_GET['id']!='')
    {
         $validateData['required'] = array('photo_id' => 'Please provide photo id',
                                           'comment' => 'Please provide commet',
                                           'user_id' => 'Please provide user_id'
                                           );
    }
    else
    {
         $validateData['required'] = array('photo_id' => 'Please provide photo id',
                                           'comment' => 'Please provide commet',
                                           'user_id' => 'Please provide user_id'
                                           );
    }
    $errorMsg = $commonFunction->validate_form($_POST, $validateData);
    $comment = (array) $objComment;

    if (count($errorMsg) == 0) {
        if($objComment->insertUpdate($objComment->id) == false)
        {
            $errorMsg[] = $objComment->error_message;
        }
        else
        {
            if (!isset($objComment->id))
            {
               $_SESSION['message_type'] = 'success';
               $_SESSION['message'] = "Comment successfully added";
               $commonFunction->Redirect('./?page=comment_listing');
               exit;
            }
            else
            {
               $_SESSION['message_type'] = 'success';
               $_SESSION['message'] = "Comment successfully updated";
               $commonFunction->Redirect('./?page=comment_listing');
               exit;
            }
        }
    }
}
if ($objComment->id != '' && $action == 'statusupdate')
{
                $currentStatus = $_REQUEST['status'];
                if($currentStatus == 'Active'){
                $currentStatus = 'Inactive';
                } else {
                $currentStatus = 'Active';

                }
                $commentId = $_REQUEST['id'];
                $photo = $objComment->updateStatus($currentStatus,$commentId);
                $_SESSION['message_type'] = 'success';
               $_SESSION['message'] = "Comment successfully updated";
               $commonFunction->Redirect('./?page=comment_listing');
               exit;

}
///******* Insert / Update Case *********//
?>