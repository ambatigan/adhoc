<?php
class users {

    public function __construct() {
        global $commonFunction, $dbAccess;
        $this->db = $dbAccess;
        $this->commonFunction = $commonFunction;
        $fields = $this->db->SelectFields(TBL_USERS);
        foreach ($fields as $k => $d) {
            $this->$d = null;
        }
    }

    public function select($pagging, $condition='', $sort_by='id', $sort_order = 'DESC') {

        $query = "SELECT u.* FROM " . TBL_USERS . " u " . $condition . "ORDER BY  " . $sort_by . " " . $sort_order;
        $userList = $this->db->SimpleQuery($query, "", $pagging, false);

        //$userList = $this->db->SelectQuery(TBL_USERS, "*", "", "", "", "ORDER BY $sort_by", $sort_order, $pagging, false);
        return $userList;
    }

    public function checkExists() {
        $where = "";
        if (logged_in_user::id() != '') {
            $where = " AND id!='" . $this->id . "'  AND deleted=0";
        }
        $query = "SELECT id FROM " . TBL_USERS . " WHERE user_name='" . mysql_real_escape_string($this->user_name) . "' " . $where;
        $getUserId = $this->db->SimpleQuery($query, false);
        return $getUserId;
    }


    public function checkEmailExists() {
        if (logged_in_user::id() != '') {
            $where = " AND id!='" . $this->id . "'";
        }
        $query = "SELECT id FROM " . TBL_USERS . " WHERE email='" . mysql_real_escape_string($this->email) . "' AND deleted=0 " . $where;
        $getUserId = $this->db->SimpleQuery($query, false);
        return $getUserId;
    }

    function check_user_availibility($email) {

        $query = "select * from " . TBL_USERS . "  where email = '" . $email . "' and deleted=0";
        $result = $this->db->SimpleQuery($query, false);
        return $result;
    }

    function update_pass_byemail($password, $email) {
        $query = "UPDATE " . TBL_USERS . " set password='" . md5($password) . "' where email='" . $email . "'";
        $this->db->SimpleUpdateQuery($query, false);
    }

    // insert,update query
    public function insertUpdate($id) {

        $Check = $this->checkExists();
        $CheckMail = $this->checkEmailExists();

        if (count($Check) > 0) {
            $this->error_message = "Username already exists. Please enter another username and try again.";
            return false;
        }
        if (count($CheckMail) > 0) {
            $this->error_message = "Email already exists. Please enter another email and try again.";
            return false;
        }

        $fields = $this->db->SelectFields(TBL_USERS);
        foreach ($fields as $k => $d) {
            $values[$d] = $this->$d;
        }
        if ($id != '') {
            $values['modified_by'] = logged_in_user::id();
            $values['modified_on'] = date('Y-m-d H:i:s');
		unset($values['created_by']);
		unset($values['deleted_by']);
		unset($values['deleted_on']);
        } else {
            $values['created_by'] = logged_in_user::id();
            $values['created_on'] = date('Y-m-d H:i:s');
        }
         //***************** Image Case *****************//
         if($id!='')
		{

			$where = "id = ".$id;

                //********** EDIT image case ***************//
                if(isset($_FILES['image']) && $_FILES['image']['error'] == '0')
                {
                         $file = $_FILES['image'];

                        $image_name = $this->InsertUpdate_Image($file);

                        if($image_name == false)
                        {
                           // $this->error_message = "Error in Advertising Banner Image upload. Please try again.";
                            return false;
                        }
                        else
                        {
                            if($image_name != '' && $_POST['photo_delete'] != '')
                            {
                                    $delete_path = USER_PATH.$_POST['photo_delete'];
                                    unlink($delete_path);
                            }
                             $values['image'] = $image_name;
                        }
                }

			$this->db->InsertUpdateQuery(TBL_USERS,$values,$where,false);
			return true;
		}
		else
		{

            //************ ADD Image case **********//
              if(isset($_FILES['image']) && $_FILES['image']['error'] == '0')
              {
                      $file = $_FILES['image'];

                      //$file['image_type']=$values['image_type'];
                      $image_name = $this->InsertUpdate_Image($file);
                      if($image_name == false)
                      {
                          //$this->error_message = "Error in Advertising Banner Image upload. Please try again.";
                          return false;
                      }
                      else
                      {
                          $values['image'] = $image_name;
                      }
              }

			$this->db->InsertUpdateQuery(TBL_USERS,$values,false);
			return true;
		}

        if ($id != '') {
            $where = "id = " . $id;
            $this->db->InsertUpdateQuery(TBL_USERS, $values, $where, false);
            return true;
        } else {
            $this->db->InsertUpdateQuery(TBL_USERS, $values, false);
            return true;
        }
    }

    // delete functionality
    public function delete() {
        $fields['deleted'] = 1;
        $fields['deleted_by'] = logged_in_user::id();
        $fields['deleted_on'] = $this->commonFunction->GetDateTime();
        $this->db->InsertUpdateQuery(TBL_USERS, $fields, "id = " . mysql_real_escape_string($this->id), false);
        return true;
    }

    //
    public function deleteGreetings() {
        $fields['deleted'] = 1;
        $fields['deleted_by'] = logged_in_user::id();
        $fields['deleted_on'] = $this->commonFunction->GetDateTime();
        $this->db->InsertUpdateQuery(TBL_ADMIN_GREETINGS, $fields, "created_by = " . mysql_real_escape_string($this->id), false);
        $this->db->InsertUpdateQuery(TBL_USER_GREETINGS, $fields, "created_by = " . mysql_real_escape_string($this->id), false);
        $this->db->InsertUpdateQuery(TBL_USER_GREETINGS, $fields, "user_id = " . mysql_real_escape_string($this->id), false);
        return true;
    }

    // select by id
    public function selectById() {
        $getuserDetail = $this->db->SelectQuery(TBL_USERS, "*", "id = " . mysql_real_escape_string($this->id) . " and deleted=0", "", false, "", "");
        foreach ($this as $k => $d) {
            if ($k != 'db' && $k != 'commonFunction') {
                $this->$k = $getuserDetail[0][$k];
            }
        }
    }

    public function genrate_password($length=8, $strength=4)
    {
	   $vowels = 'aeuy';
	   $consonants = 'bdghjmnpqrstvz';
	   if ($strength & 1) {
	    $consonants .= 'BDGHJLMNPQRSTVWXZ';
	   }
	   if ($strength & 2) {
	    $vowels .= "AEUY";
	   }
	   if ($strength & 4) {
	    $consonants .= '23456789';
	   }
	   if ($strength & 8) {
	    $consonants .= '@#$%';
	   }

	   $password = '';
	   $alt = time() % 2;
	   for ($i = 0; $i < $length; $i++) {
	    if ($alt == 1) {
	     $password .= $consonants[(rand() % strlen($consonants))];
	     $alt = 0;
	    } else {
	     $password .= $vowels[(rand() % strlen($vowels))];
	     $alt = 1;
	    }
	   }
	   return $password;
    }

    public function get_userName_from_email($email) {
        $query = "select user_name from " . TBL_USERS . "  where email = '" . $email . "'";
        $user_name = $this->db->SimpleQuery($query, false);
        $user_name = $user_name['0']['user_name'];
        return $user_name;
    }

    //********** Insert update Image **************//
    public function InsertUpdate_Image($file)
        {

            if($file['type'] == 'image/jpeg' || $file['type'] == 'image/gif' || $file['type'] == 'image/png' || $file['type'] == 'image/pjpeg' || $file['type'] == 'image/jpg')
            {
                if($file['size'] >  '0')
                    {
                        if($file['error'] == '0')
                            {
                                
                                //$image_name = time().'_'.strtolower(str_replace(" ", '', $file['name']));
                                $image_name = 'user_iphone_'.$_REQUEST['id'].'.jpg';
                                $path= USER_IMAGE_PATH.$image_name;
                                //echo $path;
                                if(move_uploaded_file($file["tmp_name"], $path))
                                {
                                        //echo "done";
                                        return $image_name;
                                }
                                else
                                {
                                        //$this->error_message = "Error in image upload. Please try again.";
                                        return false;
                                }
                            }
                    }
                    else
                    {
                            //$this->error_message = "This is not valid image size.";
                            return false;
                    }
            }
            else
            {
                    $this->error_message = "Invalid image type.Please upload (JPEG,PNG,GIF,JPG).";
                    return false;
            }
        }

    function updateStatus($currStatus,$Id){
        $query = "UPDATE " . TBL_USERS . " SET status = '" . $currStatus . "' WHERE id = '".$Id."'"; 
        $userList = $this->db->SimpleQuery($query, "", $pagging, false);

        //$userList = $this->db->SelectQuery(TBL_PHOTO, "*", "", "", "", "ORDER BY $sort_by", $sort_order, $pagging, false);
        return $userList;

    }

}

?>