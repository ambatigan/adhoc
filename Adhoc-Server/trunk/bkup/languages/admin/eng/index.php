<?php
class index_lang {   //Login
    public static $welcome = 'Welcome to Admin Panel of IMF';
    public static $Search = 'Search';
    public static $BlogCategoryAddSuccess = "Blog category added successfully";
    public static $BlogCategoryUpdatedSuccess = "Blog category updated successfully";
    public static $BlogCategoryDeletedSuccess = "Blog category deleted successfully";
    public static $BlogCategoryNameReq = '* Blog category name is required';
    public static $languagestareq = "* Status is required";
    public static $cms_titreq = "* Title is required for language ";
    // General
    public static $username = 'Username';
    public static $password = 'Password';
    public static $cnfrm_password = 'Confirm Password';
    public static $first_name = 'First Name';
    public static $middle_name = 'Middle Name';
    public static $last_name = 'Last Name';
    public static $DOB = 'DOB';
    public static $login = 'Login';
    public static $add = 'Add';
    public static $edit = 'Edit';
    public static $delete = 'Delete';
    public static $suredelete = 'Are you sure want to delete this record?';
    public static $no_items = 'No Records Found';
    public static $username_required = 'Username is required';
    public static $pwd_change_instructions = "Keep password and confirm password blank if u don't want to change.";
    public static $submit = "Submit";
    public static $cancel = "Cancel";
    public static $upload = "Upload";
    public static $confirmpassword_req = 'Confirm Password is required';
    public static $AdminManagement = 'Admin Management';
    public static $add_staff_user = 'Add Staff User';
    public static $edit_staff_user = 'Edit Staff User';
    public static $EmailAddress = 'Email Address';
    public static $staff_user_mgmt = 'Staff User Management';
    public static $first_name_req = 'First Name is required';
    public static $last_name_req = 'Last name is required';
    public static $email_address_req = 'Email Address is required';
    public static $address1_req = ' Address Line1 is required';
    public static $complemento_req = 'Complemento is required';
    public static $district_req = 'Street is required';
    public static $city_req = 'City is required';
    public static $gender_req = 'Sex is required';
    public static $state_req = 'State is required';
    public static $zip_req = 'Zip is required';
    public static $country_req = 'Country is required';
    public static $invalid_email = 'Invalid Email Address';
    public static $invalid_phone = 'Invalid Contact Number';
    public static $fax_req = 'Fax is required';
    public static $add_user = 'Add New User';
    public static $view_all_user = 'View All Users';
    public static $phone_req = 'Phone is required';
    public static $pwd_required = 'Password is required';
    public static $invalid_username_password = "Invalid Username or Password";
    public static $user_success_add = "User Successfully Added";
    public static $user_success_update = "User Successfully Updated";
    public static $user_success_delete = "User Successfully Deleted ";
    public static $record_not_exist = "Record does not exist";
    public static $Gender = "Gender";
    public static $Male = "Male";
    public static $Female = "Female";
    public static $AddressLine1 = "Address Line1";
    public static $Complemento = "Complemento";
    public static $District = "District";
    public static $Country = "Country";
    public static $Select = "SELECT";
    public static $State = "State";
    public static $City = "City";
    public static $ZipCode = "Zip Code";
    public static $ContactNo = "Contact No.";
    public static $Fax = "Fax";
    public static $Status = "Status";
    public static $Active = "Active";
    public static $Inactive = "Inactive";
    public static $ForgotPassword = 'Forget Password';
    public static $showing = 'Showing';
    public static $no = 'No.';
    public static $of = 'of';
    public static $to = 'to';
    public static $action = 'Action';
    public static $fieldisrequired = 'This field is required';
    //login
    //file
    public static $File_management = 'File Management';
    public static $File = 'File';
    // Add CMS Page
    public static $cms_title = 'Add/Update CMS';
    public static $cms_header = 'Add/Update CMS';
    public static $cms_pagename = 'Page Name';
    public static $cms_description = 'Description';
    public static $cms_metadesc = 'Meta Description';
    public static $cms_metakey = 'Meta Keywords';
    public static $cms_status = 'Status';
    public static $cms_description_req = 'Description is required';
    public static $cms_pagename_req = 'Page Name is required';
    public static $cms_status_req = 'Status is required';
    public static $cms_metadesc_email = 'Invalid Email Address in Meta Description';
    public static $cms_metakey_num = 'Meta Keywords must be numeric value';
    // CMS List Page
    public static $cms_add = 'Add CMS';
    public static $cms_management = 'CMS Management';
    public static $cms_success_add = 'CMS Added Successfully';
    public static $cms_success_update = 'CMS Updated Successfully';
    public static $cms_success_delete = 'CMS Deleted Successfully';
    public static $cms_lblSearch = 'Search CMS';
    public static $cms_btnSearch = 'Search';
    public static $cmslist_header = 'CMS';
    //kartik added
    //CMS Mgmt

    public static $cms_edit = 'Edit CMS';
    public static $cms_list = 'CMS List';
    public static $cms_alt_banner_image = 'Sidebar image title 1';
    public static $cms_alt_content_image = 'Sidebar image title 2';
    public static $cms_slug_url = 'Slug URL';
    public static $view_cms_list = 'View CMS List';
    public static $ContentImgTit = "Sub header Description";
    public static $Description = 'Description';
    public static $Externallink = 'External Link';
    public static $MetaDescription = 'Meta Description';
    public static $MetaKeyword = 'Meta Keywords';
    public static $cms_descreq = "* Description is required for language ";
    public static $cms_titreq = "* Title is required for language ";
    public static $cms_slugtitreq = "* Slug URL is required for language ";
    public static $cms_imgtitreq = "* Short description is required for language ";
    public static $cms_metadescreq = "* Meta description is required for language ";
    public static $cms_metakeyreq = "* Meta keywords is required for language ";
    public static $cms_contactus = "* Contact Description is required for language ";
    public static $cms_pageexists = "Name already exists";
    public static $DescReq = '* Description is required';
    public static $cms_description = 'Description';
    public static $ContentImageTitleReq = '* Sub header is required';

    function translate($text) {

        global $dbAccess, $cur_lang, $cur_lang_id;
        $lang_id = $cur_lang_id;

        $db = $dbAccess;
        /* $data = $db->SimpleOneQuery( "SELECT * FROM ".TBL_LANGUAGES." WHERE lng_code='".$cur_lang."'");

          if(isset($data->id) && $data->id !='')
          {
          $lang_id = $data->id;
          } */

        $text = utf8_decode($text);
        $text = html_entity_decode($text);
        $text = utf8_encode($text);

        if ($text != "") {
            // echo "SELECT id FROM ".TBL_LANGUAGES_VAR." WHERE name = '" . ucfirst(strtolower(htmlspecialchars(htmlspecialchars_decode($text), ENT_QUOTES))) . "'";
            //$query_textvar_data =  $db->SimpleOneQuery("SELECT id FROM ".TBL_LANGUAGES_VAR." WHERE name = '" . ucfirst(strtolower(htmlspecialchars(htmlspecialchars_decode($text), ENT_QUOTES))) . "'");
            $query_textvar_data = $db->SimpleOneQuery("SELECT id FROM " . TBL_LANGUAGES_VAR . " WHERE name = '" . addslashes($text) . "'");
            if (isset($query_textvar_data->id) && $query_textvar_data->id != '') {
                $lang_var_id = $query_textvar_data->id;
            } else {
                //$values_lang_var['name'] = ucfirst(strtolower(htmlspecialchars(htmlspecialchars_decode($text), ENT_QUOTES)));
                $values_lang_var['name'] = $text;
                $db->InsertUpdateQuery(TBL_LANGUAGES_VAR, $values_lang_var, false);

                $lang_var_id = $db->lastInsertedId;

                $values_lang_text['lng_var_id'] = $lang_var_id;
                $values_lang_text['lng_id'] = $lang_id;
                //$values_lang_text['txt'] = ucfirst(strtolower(htmlspecialchars(htmlspecialchars_decode($text), ENT_QUOTES)));
                $values_lang_text['txt'] = $text;
                $db->InsertUpdateQuery(TBL_LANGUAGES_TEXTS, $values_lang_text, false);
            }


            if ($lang_var_id > 0) {
                $data_txt = $db->SimpleOneQuery("SELECT * FROM " . TBL_LANGUAGES_TEXTS . " WHERE lng_var_id='" . $lang_var_id . "' AND lng_id='" . $lang_id . "'");
                if (isset($data_txt->txt) && $data_txt->txt != '') {
                    $text = stripslashes($data_txt->txt);
                }
            }
        }
        return utf8_encode($text);
    }

}

?>