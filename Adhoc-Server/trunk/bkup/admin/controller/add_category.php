<?php
 
if (isset($_REQUEST['id']) && $_REQUEST['id'] != "")
    $title = "Edit Category";
else
    $title = "Add Category";
$heading= "Category Management";
require_once(COMMON_CORE_MODEL . "category.class.php");

$objCategory = new category();

$objCategory->id =(isset($_REQUEST['id']) && $_REQUEST['id'] != '')?$_REQUEST['id']:$objCategory->id;
$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';

///******* Edit Case *********//
if($objCategory->id!='' && $action=='edit')
{
    $objCategory->selectById();
    $cms = (array) $objCategory;
}
///******* Edit Case *********//
foreach($objCategory as $k=>$d)
{
    if($k!='db')
    {
            $objCategory->$k =(isset($_REQUEST[$k]) && $_REQUEST[$k] != '')?$_REQUEST[$k]:$objCategory->$k;
    }
}

$objCategory->title = (isset($_REQUEST['title']) && $_REQUEST['title'] != '')?$_REQUEST['title']:$objCategory->title;
$objCategory->name = (isset($_REQUEST['name']) && $_REQUEST['name'] != '')?$_REQUEST['name']:$objCategory->name;
$objCategory->grms_100ml = (isset($_REQUEST['grms_100ml']) && $_REQUEST['grms_100ml'] != '')?$_REQUEST['grms_100ml']:$objCategory->grms_100ml;
$objCategory->status = (isset($_REQUEST['status']) && $_REQUEST['status'] != '')?$_REQUEST['status']:$objCategory->status;
$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';

if($_POST['blogFormSubmit'])
{
    $delete=0;
    $validateData['required'] = array('name'=>"Category name required",'grms_100ml'=>"Category grams/100ml required",'status'=>"Status required");
    $errorMsg = $commonFunction->validate_form($_POST, $validateData);
     if (!is_numeric($_POST['grms_100ml']) && $_POST['grms_100ml'] != "")
    {
        $errorMsg[] = "Category grams/100ml should be numeric value";
    }
    $cms = (array) $objCategory;

    $cntlng = count($sitelanguages);
    $cnt = 0;


    if($cntlng == $cnt)
    {
        $errorMsg[] = "Category title required";
    }

    if(count($errorMsg) == 0)
    {                    
        if($objCategory->insertUpdate($objCategory->id)==false)
        {
            $errorMsg[] = $objCategory->error_message;
        }
        else
        {
            if(!isset($objCategory->id))
            {
                $commonFunction->Redirect('./?page=category_list&action=view&msg=1');
                exit;
            }
            else
            {
                $commonFunction->Redirect('./?page=category_list&action=view&msg=2');
                exit;
            }
        }
    }
}
?>