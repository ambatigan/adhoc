<?php
error_reporting(1);
if(isset($_REQUEST['id']) && $_REQUEST['id'] != "")
{
	$id = $_REQUEST['id'];
    $title = "Edit Product";
}else
{
  $title = "Add Product";
  $heading= "Product Management";
}
require_once(COMMON_CORE_MODEL . "product.class.php");
require_once(COMMON_CORE_MODEL . "category.class.php");
require_once(COMMON_CORE_MODEL . "language_var.class.php");

$objProduct = new product();
$objCategory = new category();
$language_var = new language_var();
$allCategories = $objCategory->select($objPagging, "WHERE  status = 'A'");
$where_lng = " WHERE lv.name = 'Push Notification - Product Section'";
$lng_var = $language_var->select_lng($where_lng);

$objProduct->id =(isset($_REQUEST['id']) && $_REQUEST['id'] != '')?$_REQUEST['id']:$objProduct->id;
$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';

if($objProduct->id!='' && $action=='delete')
{
    $objProduct->selectById();
    $cms = (array) $objProduct;
    $product_id = $_REQUEST['id'];
    $image_no = $_REQUEST['image'];
    $image_name = $cms['image'.$image_no][1];

    @unlink(PRODUCT_IMAGE_PATH.$image_name);

    $objProduct->delete_produdct_image($product_id,$image_no);
    $commonFunction->Redirect('.?page=add-product&action=edit&id='.$product_id);
//    echo "<pre>";print_r($cms);die;
}

///******* Edit Case *********//
if($objProduct->id!='' && $action=='edit')
{
    $objProduct->selectById();
    $cms = (array) $objProduct;
    $product_cat = $objProduct->select_categories($objProduct->id);
}
/*echo "<pre>";
print_r($cms);
exit;*/
///******* Edit Case *********//
foreach($objProduct as $k=>$d)
{
    if($k!='db')
    {
        $objProduct->$k =(isset($_REQUEST[$k]) && $_REQUEST[$k] != '')?$_REQUEST[$k]:$objProduct->$k;
    }
}

$objProduct->title = (isset($_REQUEST['title']) && $_REQUEST['title'] != '')?$_REQUEST['title']:$objProduct->title;
$objProduct->weight = (isset($_REQUEST['weight']) && $_REQUEST['weight'] != '')?$_REQUEST['weight']:$objProduct->weight;
$objProduct->barcode = (isset($_REQUEST['barcode']) && $_REQUEST['barcode'] != '')?$_REQUEST['barcode']:$objProduct->barcode;
$objProduct->name = (isset($_REQUEST['name']) && $_REQUEST['name'] != '')?$_REQUEST['name']:$objProduct->name;
$objProduct->status = (isset($_REQUEST['status']) && $_REQUEST['status'] != '')?$_REQUEST['status']:$objProduct->status;
$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';

if($_POST['productFormSubmit'])
{
    /*print_r($_POST);
    exit;*/
    $delete=0;
    $validateData['required'] = array('title' => 'Product title required','weight' => 'Product weight capacity required','size' => 'Product size required','weightg' => 'Product weight(1ml) G for 100ml capacity required','barcode' => 'Product barcode required');

    $errorMsg = $commonFunction->validate_form($_POST, $validateData);
     if (!isset($_POST['category_id']) || $_POST['category_id'] == "0")
    {
        $errorMsg[] = "Product category required";
    }
    if (!is_numeric($_POST['weight']) && $_POST['weight'] != "")
    {
        $errorMsg[] = "Product weight should be numeric value";
    }
    if (!preg_match('/^[0-9,]+$/i', $_POST['size']) && $_POST['size'] != "")
    {
        $errorMsg[] = "Product size should be numeric value";
    }
    if (!is_numeric($_POST['weightg']) && $_POST['weightg'] != "")
    {
        $errorMsg[] = "Product weight (1ml) G for 100ml should be numeric value";
    }
    /*if($_FILES['icon_logo']['name']=='' && $_REQUEST['action']!='edit')
    {
        $errorMsg[] =  'Logo/Icon image required';
    }*/
    $cms = (array) $objProduct;


    if(count($errorMsg) == 0)
    {
        /***********************************************************************/
        //-- Icon Image Upload --//
        if($_FILES['icon_logo']['name']!="")
        {
            $ext=$objProduct->commonFunction->GetFileExtension($_FILES['icon_logo']['name']);
            $icon_logo=str_replace(' ','',rtrim($_FILES['icon_logo']['name'],".".$ext)."_".strtotime("now").".".$ext);

            $res = $objProduct->commonFunction->UploadFile($_FILES['icon_logo'], ICON_LOGO_PATH,$icon_logo,IMAGE_EXTENSIONS);

            //list($width, $height, $type, $attr) = @getimagesize(SITE_HOST.ICON_LOGO_PATH.$icon_logo);
            $array = getimagesize(ICON_LOGO_PATH.$icon_logo);
            $width = $array[0];
            $height = $array[1];

            if ($res==0)
            {
                $errorMsg[] =  'Icon image allow only jpg, png or gif files.';
            }

            if(empty($errorMsg))
            {
            if($res==1)
            {
              if (file_exists(ICON_LOGO_PATH . $objProduct->icon_logo) && $action=='edit')
              {
                //echo ICON_LOGO_PATH.$_REQUEST['old_icon_logo'];die;
                @unlink(ICON_LOGO_PATH.$_REQUEST['old_icon_logo']);
              }
              $objProduct->icon_logo = $icon_logo;
              $UploadedFile1=$objProduct->icon_logo;
          }
          else {
              $errorMsg[] =  'Error in upload';
          }
        }
    }
/***********************************************************************/

/***********************************************************************/

/***********************************************************************/
}

//-- Insert Or Update Query --//
    if(count($errorMsg) == 0)
    {

        if($objProduct->insertUpdate($objProduct->id)==false)
        {
            $errorMsg[] = $objProduct->error_message;
        }
            if(empty($errorMsg))
            {
                if(!$objProduct->id)
                {
                    $commonFunction->Redirect('.?page=product-list&action=view&msg=1'); //-- Added Sucessfully --//
                    exit;
                }
                else
                {
                  $commonFunction->Redirect('.?page=product-list&action=view&msg=2'); //-- Edited Sucessfully --//
                  exit;
                }
            }
    }
    else{
        @unlink(PRODUCT_IMAGE_PATH.$icon_logo);
    }
}
?>