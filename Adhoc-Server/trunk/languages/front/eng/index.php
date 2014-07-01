<?php

	class index_lang
	{
	    //Doc type
       public static $BussinessDocument='Business Document';
       public static $EmailDocument='Email Document';
       public static $WebPage='Web Page';
       public static $Done='Done';
		// General
	   	public static $add='Add';
		public static $edit='Edit';
		public static $delete='Delete';
        public static $viewprofile='View Profile';
		public static $showing='Showing';
		public static $no='No.';
        public static $Id='Id';
		public static $of='of';
		public static $to='to';
		public static $action='Action';
		public static $no_items='No Items';
		public static $suredelete='Are you sure you want to delete?';
        public static $fieldisrequired = 'This field is required';
        public static $info_success_add='Information added successfully.';
        public static $info_success_update='Information updated successfully.';
        public static $info_success_delete='Information deleted successfully.';
        public static $access_denied='You do not have enough permissions, please contact to admin.';

        // Newsletter
		public static $newsletter_success_add='Thank you. Your email address subscribed successfully.';
        public static $newsletter_error_email='Invalid email address. Please try again.';
        public static $newsletter_error_emailexist='You have already subscibed with alexaffiliate.';

        // Login
		public static $login_username_req='Thank you. Your email address subscribed successfully.';
        public static $login_password_req='Invalid email address. Please try again.';
        public static $login_invalid_user='Your user/password combination is wrong. Try again.';

        //Brand Type
        public static $brandtype_name_req='Name is required.';
        public static $brandtype_status_req='Status is required.';
        public static $brandtype_success_add='Brand type added successfully..';
        public static $brandtype_success_update='Brand type updated successfully.';
        public static $brandtype_success_delte='Brand type deleted successfully.';


        //Clients
        public static $clientname_name_req='Client name is required.';
        public static $client_status_req='Status is required.';
        public static $client_country_req='Country is required.';
        public static $client_logo_uploaderror='Error in uploading image.';
        public static $clientnote_req='Client note is required.';

        //Brands
        public static $brand_name_req='Brand name is required.';
        public static $brand_status_req='Status is required.';
        public static $brand_clientbelonging_req='Client belonging is required.';
        public static $brand_availrestricted_req='Avail Restricted is required.';
        public static $brand_logo_uploaderror='Error in uploading image.';

	
        //Campaign Access
        public static $info_approval_req='Updated information is now in queue for approval.';

      
  		public static $invalidBannerImageSize = 'Please upload image with specified dimensions.';
  		
  		public static $mininvalidBannerImageSize = 'Minimum image size required.';
  

  		// krtik added
  		 public static $cms_titreq = "Title is required for language ";
  		
        function translate($text){

            global $dbAccess,$cur_lang,$cur_lang_id;
            $cur_lang_id = (isset($_SESSION['language_id']))?$_SESSION['language_id']:'2';
            $lang_id = $cur_lang_id;
            //echo  $lang_id; exit;
			$db = $dbAccess;
            /*$data = $db->SimpleOneQuery( "SELECT * FROM ".TBL_LANGUAGES." WHERE lng_code='".$cur_lang."'");

            if(isset($data->id) && $data->id !='')
            {
               $lang_id = $data->id;
            }   */

            $text = utf8_decode($text);
    		$text = html_entity_decode($text);
    		$text = utf8_encode($text);

            if($text != ""){
               // echo "SELECT id FROM ".TBL_LANGUAGES_VAR." WHERE name = '" . ucfirst(strtolower(htmlspecialchars(htmlspecialchars_decode($text), ENT_QUOTES))) . "'";
                //$query_textvar_data =  $db->SimpleOneQuery("SELECT id FROM ".TBL_LANGUAGES_VAR." WHERE name = '" . ucfirst(strtolower(htmlspecialchars(htmlspecialchars_decode($text), ENT_QUOTES))) . "'");
                $query_textvar_data =  $db->SimpleOneQuery("SELECT id FROM ".TBL_LANGUAGES_VAR." WHERE name = '" . addslashes($text) . "'");
                if(isset($query_textvar_data->id) && $query_textvar_data->id != ''){
                       $lang_var_id = $query_textvar_data->id;
                } else {
                    //$values_lang_var['name'] = ucfirst(strtolower(htmlspecialchars(htmlspecialchars_decode($text), ENT_QUOTES)));
                    $values_lang_var['name'] = $text;
				    $db->InsertUpdateQuery(TBL_LANGUAGES_VAR,$values_lang_var,false);

                    $lang_var_id = $db->lastInsertedId;

                    $values_lang_text['lng_var_id'] = $lang_var_id;
                    $values_lang_text['lng_id'] = $lang_id;
                    //$values_lang_text['txt'] = ucfirst(strtolower(htmlspecialchars(htmlspecialchars_decode($text), ENT_QUOTES)));
                    $values_lang_text['txt'] = $text;
				    $db->InsertUpdateQuery(TBL_LANGUAGES_TEXTS,$values_lang_text,false);
                }


                if($lang_var_id > 0){
                    $data_txt = $db->SimpleOneQuery( "SELECT * FROM ".TBL_LANGUAGES_TEXTS." WHERE lng_var_id='".$lang_var_id."' AND lng_id='".$lang_id."'");

                    if(trim($data_txt->txt)!='' &&trim($data_txt->txt)!='&nbsp;')
                    {
                      $text = stripslashes($data_txt->txt);
                    }
                   

                }



            }
            return $text;

        }


	}

?>