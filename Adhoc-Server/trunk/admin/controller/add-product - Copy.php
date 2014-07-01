<?php
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
$allCategories = $objCategory->select($objPagging, "");
$where_lng = " WHERE lv.name = 'Push Notification - Product Section'";
$lng_var = $language_var->select_lng($where_lng);

/*echo "<pre>";
print_r($lng_var);
//exit;*/

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
	 $cms['mark_product_for'] = explode(',',$cms['mark_product_for']);
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
$objProduct->name = (isset($_REQUEST['name']) && $_REQUEST['name'] != '')?$_REQUEST['name']:$objProduct->name;
$objProduct->status = (isset($_REQUEST['status']) && $_REQUEST['status'] != '')?$_REQUEST['status']:$objProduct->status;
$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';

if($_POST['productFormSubmit'])
{
    $delete=0;
    $validateData['required'] = array('title' => 'Product title required');

    $errorMsg = $commonFunction->validate_form($_POST, $validateData);
     if (!isset($_POST['category_id']))
    {
        $errorMsg[] = "Product category required";
    }
    if (!isset($_POST['mark_product_for']))
    {
        $errorMsg[] = "One mark product required";
    }
    $mark_product = implode(',',$_POST['mark_product_for']);
    $objProduct->mark_product_for = $mark_product;
    if($_FILES['icon_logo']['name']=='' && $_REQUEST['action']!='edit')
    {
        $errorMsg[] =  'Logo/Icon image required';
    }
    $cms = (array) $objProduct;

    //$cntlng = count($sitelanguages);
    foreach ($sitelanguages as $lng)
    {
      if(trim($_REQUEST['product_name'][$lng['id']]) == "")
        {
           $errorMsg[] = "Product name required";
        }
        if(trim($_REQUEST['price'][$lng['id']]) == "" && $_REQUEST['price'][$lng['id']]!=0)
        {
           $errorMsg[] = "Product price required";
        }else{
          if (!is_numeric($_REQUEST['price'][$lng['id']]) && $_REQUEST['price'][$lng['id']]!=0){
              $errorMsg[] = "Product price not proper";
          }
        }
        if ($_REQUEST['download_link'][$lng['id']]!='')
        {
            if (!preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $_REQUEST['download_link'][$lng['id']]))
            {
                $errorMsg[] = "Download link not proper";
            }
        }
        //break;//-- If you require more language, remove this break; --//
    }

    if($_FILES['image1']['name']=='' && $_REQUEST['action']!='edit')
    {
        $errorMsg[] =  'Product image 1 required';
    }
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
            else if($width != 128 || $height != 128)
            {
                @unlink(ICON_LOGO_PATH.$icon_logo);
                $errorMsg[] = 'Icon image width and height should be 128 X 128';
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
//echo "<pre>";print_r($_FILES);die;
//-- Other Images 1 to 5  Uploads --//
//    image1
      if($_FILES['image1']['name']!="")
      {
          $ext=$objProduct->commonFunction->GetFileExtension($_FILES['image1']['name']);
          $name1=str_replace(' ','',rtrim($_FILES['image1']['name'],".".$ext)."_".strtotime("now").".".$ext);
          $res = $objProduct->commonFunction->UploadFile($_FILES['image1'], PRODUCT_IMAGE_PATH,$name1,IMAGE_EXTENSIONS);

          $array = getimagesize(PRODUCT_IMAGE_PATH.$name1);
		  $width = $array[0];
          $height = $array[1];

          if ($res==0){
              $errorMsg[] =  'Image 1 allow only jpg, png or gif files.';
          }else if($width != 180 || $height != 320){
              @unlink(PRODUCT_IMAGE_PATH.$name1);
              $errorMsg[] = 'Image 1 width and height should be 180 X 320';
          }
		  
          if(empty($errorMsg)){
            if($res==1)
            {
                //if (file_exists(PRODUCT_IMAGE_PATH . $objProduct->image1) && $action=='edit'){
                  //echo PRODUCT_IMAGE_PATH.$_REQUEST['old_image1'];die;
                  @unlink(PRODUCT_IMAGE_PATH.$_REQUEST['old_image1']);
                //}
                //echo $_REQUEST['old_image1'];die;
                $objProduct->image1[1] = $name1;
                $UploadedFile1=$objProduct->image1;
            }
            else {
                $errorMsg[] =  'Error in upload';
            }
          }
      }
/***********************************************************************/

/***********************************************************************/
//echo "<pre>";print_r($_FILES);die;
//-- Other Images 2 to 5  Uploads --//
//    image2
      if($_FILES['image2']['name']!="")
      {                                    //echo $_FILES['image'.$i]['name'];die;
          $ext=$objProduct->commonFunction->GetFileExtension($_FILES['image2']['name']);
          $name2=str_replace(' ','',rtrim($_FILES['image2']['name'],".".$ext)."_".strtotime("now").".".$ext);
          $res = $objProduct->commonFunction->UploadFile($_FILES['image2'], PRODUCT_IMAGE_PATH,$name2,IMAGE_EXTENSIONS);

          $array = getimagesize(PRODUCT_IMAGE_PATH.$name2);
          $width = $array[0];
          $height = $array[1];

          if ($res==0){
              $errorMsg[] =  'Image 2 allow only jpg, png or gif files.';
          }else if($width != 180 || $height != 320){
              @unlink(PRODUCT_IMAGE_PATH.$name2);
              $errorMsg[] = 'Image 2 width and height should be 180 X 320';
          }
          if(empty($errorMsg)){
            if($res==1)
            {
                if (file_exists(PRODUCT_IMAGE_PATH . $objProduct->image2) && $action=='edit')
                {
                  //echo PRODUCT_IMAGE_PATH.$_REQUEST['old_image2'];die;
                  @unlink(PRODUCT_IMAGE_PATH.$_REQUEST['old_image2']);
                }
                $objProduct->image2[1] = $name2;
                $UploadedFile2=$objProduct->image2;
            }
            else {
                $errorMsg[] =  'Error in upload';
            }
          }
      }
/***********************************************************************/
/***********************************************************************/
//echo "<pre>";print_r($_FILES);die;
//-- Other Images 3 to 5  Uploads --//
//    image3
      if($_FILES['image3']['name']!="")
      {                                    //echo $_FILES['image'.$i]['name'];die;
          $ext=$objProduct->commonFunction->GetFileExtension($_FILES['image3']['name']);
          $name3=str_replace(' ','',rtrim($_FILES['image3']['name'],".".$ext)."_".strtotime("now").".".$ext);
          $res = $objProduct->commonFunction->UploadFile($_FILES['image3'], PRODUCT_IMAGE_PATH,$name3,IMAGE_EXTENSIONS);

          $array = getimagesize(PRODUCT_IMAGE_PATH.$name3);
          $width = $array[0];
          $height = $array[1];

          if ($res==0){
              $errorMsg[] =  'Image 3 allow only jpg, png or gif files.';
          }else if($width != 180 || $height != 320){
              @unlink(PRODUCT_IMAGE_PATH.$name3);
              $errorMsg[] = 'Image 3 width and height should be 180 X 320';
          }
          if(empty($errorMsg)){
            if($res==1)
            {
                if (file_exists(PRODUCT_IMAGE_PATH . $objProduct->image3) && $action=='edit')
                {
                  //echo PRODUCT_IMAGE_PATH.$_REQUEST['old_image3'];die;
                  @unlink(PRODUCT_IMAGE_PATH.$_REQUEST['old_image3']);
                }
                $objProduct->image3[1] = $name3;
                $UploadedFile3=$objProduct->image3;
            }
            else {
                $errorMsg[] =  'Error in upload';
            }
          }
      }
/***********************************************************************/
/***********************************************************************/
//echo "<pre>";print_r($_FILES);die;
//-- Other Images 4 to 5  Uploads --//
//    image4
      if($_FILES['image4']['name']!="")
      {                                    //echo $_FILES['image'.$i]['name'];die;
          $ext=$objProduct->commonFunction->GetFileExtension($_FILES['image4']['name']);
          $name4=str_replace(' ','',rtrim($_FILES['image4']['name'],".".$ext)."_".strtotime("now").".".$ext);
          $res = $objProduct->commonFunction->UploadFile($_FILES['image4'], PRODUCT_IMAGE_PATH,$name4,IMAGE_EXTENSIONS);

          $array = getimagesize(PRODUCT_IMAGE_PATH.$name4);
          $width = $array[0];
          $height = $array[1];

          if ($res==0){
              $errorMsg[] =  'Image 4 allow only jpg, png or gif files.';
          }else if($width != 180 || $height != 320){
              @unlink(PRODUCT_IMAGE_PATH.$name4);
              $errorMsg[] = 'Image 4 width and height should be 180 X 320';
          }
          if(empty($errorMsg)){
            if($res==1)
            {
                if (file_exists(PRODUCT_IMAGE_PATH . $objProduct->image4) && $action=='edit')
                {
                  //echo PRODUCT_IMAGE_PATH.$_REQUEST['old_image4'];die;
                  @unlink(PRODUCT_IMAGE_PATH.$_REQUEST['old_image4']);
                }
                $objProduct->image4[1] = $name4;
                $UploadedFile4=$objProduct->image4;
            }
            else {
                $errorMsg[] =  'Error in upload';
            }
          }
      }
/***********************************************************************/
/***********************************************************************/
//echo "<pre>";print_r($_FILES);die;
//-- Other Images 5 to 5  Uploads --//
//    image5
      if($_FILES['image5']['name']!="")
      {                                    //echo $_FILES['image'.$i]['name'];die;
          $ext=$objProduct->commonFunction->GetFileExtension($_FILES['image5']['name']);
          $name5=str_replace(' ','',rtrim($_FILES['image5']['name'],".".$ext)."_".strtotime("now").".".$ext);
          $res = $objProduct->commonFunction->UploadFile($_FILES['image5'], PRODUCT_IMAGE_PATH,$name5,IMAGE_EXTENSIONS);

          $array = getimagesize(PRODUCT_IMAGE_PATH.$name5);
          $width = $array[0];
          $height = $array[1];

          if ($res==0){
              $errorMsg[] =  'Image 5 allow only jpg, png or gif files.';
          }else if($width != 180 || $height != 320){
              @unlink(PRODUCT_IMAGE_PATH.$name5);
              $errorMsg[] = 'Image 5 width and height should be 180 X 320';
          }
          if(empty($errorMsg)){
            if($res==1)
            {
                if (file_exists(PRODUCT_IMAGE_PATH . $objProduct->image5) && $action=='edit')
                {
                  //echo PRODUCT_IMAGE_PATH.$_REQUEST['old_image5'];die;
                  @unlink(PRODUCT_IMAGE_PATH.$_REQUEST['old_image5']);
                }
                $objProduct->image5[1] = $name5;
                $UploadedFile5=$objProduct->image5;
            }
            else {
                $errorMsg[] =  'Error in upload';
            }
          }
      }
/***********************************************************************/
}

//-- Insert Or Update Query --//
    if(count($errorMsg) == 0)
    {
        if($objProduct->insertUpdate($objProduct->id)==false)
        {
            $errorMsg[] = $objProduct->error_message;
        }
        else
        {
            //-- Send Push Notification --//
            if($objProduct->status=='A')
            {
                $language_var->name = 'Push Notification - Product Section';
                $language_var->id = $_POST['language_variable_id'][1];
                $language_var->txt[1] = $_POST['language_variable'][1];
                $language_var->insertUpdate($language_var->id);

                $notify_message = str_replace('[XXX]',$_REQUEST['product_name'][1],$_POST['language_variable'][1]);
                /********************** Push Notification ***********************/
                require_once(COMMON_CORE_MODEL . "gcm_users.class.php");
                $objGcmUsers = new gcm_users();
                  $limit = GSM_USERS_MAIL_LIMIT;
                  // GCM Users Listing
                  $message = array("data" => $notify_message);

                  $GcmUsersCnt =  $objGcmUsers->cntRecords();
                  $senttimes = ceil($GcmUsersCnt/$limit);

                  $start_limit = 0;
                  $final_limit =  GSM_USERS_MAIL_LIMIT;
                  $cnt = 0;
                  $errcnt=0;
                  for($i=0;$i<$senttimes;$i++)
                  {
                      $regId = array();
                      $GcmUsersList = $objGcmUsers->selectGcmUsers($start_limit, GSM_USERS_MAIL_LIMIT);
                      $start_limit = $final_limit;
                      $final_limit =  $final_limit+GSM_USERS_MAIL_LIMIT;
                      // Send Notification messages
                      foreach ($GcmUsersList as $key => $gcmusers)
                      {
                          $regId[] = $gcmusers['gcm_regid'];
                      }
                      if (!empty($regId))
                      {
                          //print_r($regId);
                          $sendnotify = $commonFunction->send_notification($regId, $message);
                        //  echo '<pre>';print_r($sendnotify);exit;
                          if($sendnotify)
                          {
                              $cnt++;
                          }
                          else
                          {

                              $errcnt++;
                            //break;
                          }
                      }
                  }
/*****************************************************************/
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
        @unlink(PRODUCT_IMAGE_PATH.$name1);
        @unlink(PRODUCT_IMAGE_PATH.$name2);
        @unlink(PRODUCT_IMAGE_PATH.$name3);
        @unlink(PRODUCT_IMAGE_PATH.$name4);
        @unlink(PRODUCT_IMAGE_PATH.$name5);
    }
}
?>