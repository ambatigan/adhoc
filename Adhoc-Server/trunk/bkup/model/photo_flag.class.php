<?php
#============================================================================================================
#	Created By  			: Jay Shah
#	Created Date			: 21 Jan 2013
#	Purpose                         : Admin User Model file
#============================================================================================================
 
class photo_flags {


    public function __construct() {
        global $commonFunction, $dbAccess;
        $this->db = $dbAccess;
        $this->commonFunction = $commonFunction;
        $fields = $this->db->SelectFields(TBL_PHOTO);
        foreach ($fields as $k => $d) {
            $this->$d = null;
        }
    }

    public function select($pagging, $condition='', $sort_by='id', $sort_order = 'DESC') {

        $query = "SELECT p.* FROM " . TBL_PHOTO . " p " . $condition . " AND p.flag ='active' ORDER BY  " . $sort_by . " " . $sort_order;
        $userList = $this->db->SimpleQuery($query, "", $pagging, false);

        //$userList = $this->db->SelectQuery(TBL_PHOTO, "*", "", "", "", "ORDER BY $sort_by", $sort_order, $pagging, false);
        return $userList;
    }

    // Fetch user details from user ID
    public function get_user_details($user_id){
        $query = "SELECT * FROM " . TBL_USERS . " where id=".$user_id ;
        $user_details = $this->db->SimpleQuery($query, false);
        return $user_details[0];
    }

    public function search_username($pagging, $condition, $sort_by='id', $sort_order = 'DESC') {

        $query = "SELECT p.id,p.image,p.user_id,p.name,p.tag,u.user_name FROM " . TBL_PHOTO . " p LEFT JOIN ".TBL_USERS." u ON p.user_id = u.id " . $condition . " AND p.flag = 'active' ORDER BY  " . $sort_by . " " . $sort_order;
        $userList = $this->db->SimpleQuery($query, "", $pagging, false);

        //$userList = $this->db->SelectQuery(TBL_PHOTO, "*", "", "", "", "ORDER BY $sort_by", $sort_order, $pagging, false);
        return $userList;
    }

    public function checkExists() {
        $where = "";
        if (logged_in_user::id() != '') {
            $where = " AND id!='" . $this->id . "'  AND deleted=0";
        }
        $query = "SELECT id FROM " . TBL_PHOTO . " WHERE name='" . mysql_real_escape_string($this->name) . "' " . $where;
        $getUserId = $this->db->SimpleQuery($query, false);
        return $getUserId;
    }


    //change flag
    public function changeFlag($id)
    {
        $fields['flag'] = "Inactive";
        $this->db->InsertUpdateQuery(TBL_PHOTO, $fields, "id = " . mysql_real_escape_string($this->id), false);
        return true;
    }

    // insert,update query
    public function insertUpdate($id) {

        $Check = $this->checkExists();

        if (count($Check) > 0) {
            $this->error_message = "Name already exists. Please enter another name and try again.";
            return false;
        }
         
        $fields = $this->db->SelectFields(TBL_PHOTO);
        foreach ($fields as $k => $d) {
            $values[$d] = $this->$d;
        }
        if ($id != '') {
            $values['modified_by'] = logged_in_user::id();
            $values['modified_on'] = date('Y-m-d H:i:s');
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
                                    $delete_path = PHOTO_PATH.$_POST['photo_delete'];
                                    unlink($delete_path);
                            }
                             $values['image'] = $image_name;
                        }
                }

			$this->db->InsertUpdateQuery(TBL_PHOTO,$values,$where,false);
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

            $this->db->InsertUpdateQuery(TBL_PHOTO, $values, false);
            $last_ID = $this->db->LastInsertedID();
            $photo_tag = $_POST['tag'];
            $seprate_tag = explode(',',$photo_tag);
            $count_tag = count($seprate_tag);
            //print_r($seprate_tag);
            //echo $count_tag; exit;

            for($i=0;$i<$count_tag;$i++)
            {
              //$values_tag =  $seprate_tag[$i];
              $values_tag['name'] = $seprate_tag[$i];
              $values_tag['user_id'] = logged_in_user::id();
              $values_tag['photo_id'] = $last_ID;
              $values_tag['created_by'] = logged_in_user::id();
              $values_tag['created_on'] = date('Y-m-d H:i:s');

              $this->db->InsertUpdateQuery(TBL_TAG,$values_tag,false);
            }
			return true;
		}


    }

    // delete functionality
    public function delete() {
        $fields['deleted'] = 1;
        $fields['deleted_by'] = logged_in_user::id();
        $fields['deleted_on'] = $this->commonFunction->GetDateTime();
        $this->db->InsertUpdateQuery(TBL_PHOTO, $fields, "id = " . mysql_real_escape_string($this->id), false);
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
        $getuserDetail = $this->db->SelectQuery(TBL_PHOTO, "*", "id = " . mysql_real_escape_string($this->id) . " and deleted=0", "", false, "", "");
        foreach ($this as $k => $d) {
            if ($k != 'db' && $k != 'commonFunction') {
                $this->$k = $getuserDetail[0][$k];
            }
        }
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
                                $image_name = time().'_'.strtolower(str_replace(" ", '', $file['name']));
                                $path= PHOTO_PATH.$image_name;
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


}

?>