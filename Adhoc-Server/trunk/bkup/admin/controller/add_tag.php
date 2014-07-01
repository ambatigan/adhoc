<?php

$username_id = logged_in_user::id();

$action = isset($_GET['action']) ? $_GET['action'] : '';

if($_GET['action']=='edit' && $_GET['id']!=''){
    $heading = "Tag Management";
    $title = "Edit Tag";
}
else{
    $heading = "Tag Management";
    $title = "Add Tag";
}
 
require_once(COMMON_CORE_MODEL . "users.class.php");
require_once(COMMON_CORE_MODEL . "tag.class.php");
require_once(COMMON_CORE_MODEL . "photo.class.php");
$objTag = new tag();
$objTag->id = (isset($_GET['id']) && $_GET['id'] != '' && is_numeric($_GET['id'])) ? htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8') : $objTag->id;
$tag = array();

$objPhoto = new photo();
$photo = $objPhoto->select("",'where deleted="0" and status="Active" and flag="Inactive"',"id","ASC");
$objUser = new users();
$user = $objUser->select("",'where deleted="0" and status="Active"',"id","ASC");

///******* Edit Case *********//
if ($objTag->id != '' && $action == 'edit')
{
    $objTag->selectById();  
    if ($objTag->id == "")
    {
    	$_SESSION['message_type'] = 'error';
        $_SESSION['message'] = "Record does not exist";
        $objTag->commonFunction->Redirect('./?page=tag_listing');
    }
    else
    {
        $tag = (array) $objTag;
    }
} 

///******* Insert / Update Case *********//
if (isset($_POST['addEditTagSubmit']))
{
   foreach ($objTag as $k => $d)
    {
        if($k!='password'){
            $objTag->$k = (isset($_POST[$k])) ? htmlspecialchars($_POST[$k], ENT_QUOTES, 'UTF-8') : $objTag->$k;
        }
    }

    if($_GET['action']=='edit' && $_GET['id']!='')
    {
         $validateData['required'] = array('name' => 'Please provide name',
                                           'photo_id' => 'Please provide Photo ID',
                                           'user_id' => 'Please provide User ID'
                                           );
    }
    else
    {
         $validateData['required'] = array('name' => 'Please provide name',
                                           'photo_id' => 'Please provide Photo ID',
                                           'user_id' => 'Please provide User ID'
                                           );
    }

    $errorMsg = $commonFunction->validate_form($_POST, $validateData);

    $tag = (array) $objTag;

    if (count($errorMsg) == 0) {
        if($objTag->insertUpdate($objTag->id) == false)
        {
            $errorMsg[] = $objTag->error_message;
        }
        else
        {
            if (!isset($objTag->id))
            {
               $_SESSION['message_type'] = 'success';
               $_SESSION['message'] = "Tag successfully added";
               $commonFunction->Redirect('./?page=tag_listing');
               exit;
            }
            else
            {
               $_SESSION['message_type'] = 'success';
               $_SESSION['message'] = "Tag successfully updated";
               $commonFunction->Redirect('./?page=tag_listing');
               exit;
            }
        }
    }
}
if ($objTag->id != '' && $action == 'statusupdate')
{
                $currentStatus = $_REQUEST['status'];
                if($currentStatus == 'Active'){
                $currentStatus = 'Inactive';
                } else {
                $currentStatus = 'Active';

                }
                $commentId = $_REQUEST['id'];
                $photo = $objTag->updateStatus($currentStatus,$commentId);
                $_SESSION['message_type'] = 'success';
               $_SESSION['message'] = "Tag successfully updated";
               $commonFunction->Redirect('./?page=tag_listing');
               exit;

}
///******* Insert / Update Case *********//
?>