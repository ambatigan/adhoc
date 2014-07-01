<?php
#============================================================================================================
#	Created By  			: Jay Shah
#	Created Date			: 21 Jan 2013
#	Purpose                         : Admin User Model file
#============================================================================================================

class tag {

    public function __construct() {
        global $commonFunction, $dbAccess;
        $this->db = $dbAccess;
        $this->commonFunction = $commonFunction;
        $fields = $this->db->SelectFields(TBL_TAG);
        foreach ($fields as $k => $d) {
            $this->$d = null;
        }
    }

    public function select($pagging, $condition='', $sort_by='id', $sort_order = 'DESC') {

       $query = "SELECT t.* FROM " . TBL_TAG . " t JOIN ". TBL_PHOTO ." p ON t.photo_id = p.id JOIN " . TBL_USERS . " u ON t.user_id = u.id " . $condition . "ORDER BY  " . $sort_by . " " . $sort_order;
        $tagList = $this->db->SimpleQuery($query, "", $pagging, false);

        //$tagList = $this->db->SelectQuery(TBL_TAG, "*", "", "", "", "ORDER BY $sort_by", $sort_order, $pagging, false);
        return $tagList;
    }

    // Fetch user details from user ID
    public function get_user_details($user_id){
        $query = "SELECT * FROM " . TBL_USERS . " where id=".$user_id ;
        $user_details = $this->db->SimpleQuery($query, false);
        return $user_details[0];
    }

    public function saearch_username($pagging, $condition, $sort_by='id', $sort_order = 'DESC') {

        $query = "SELECT p.id,p.image,p.user_id,p.name,p.tag,u.user_name FROM " . TBL_TAG . " p LEFT JOIN ".TBL_USERS." u ON p.user_id = u.id " . $condition . " ORDER BY  " . $sort_by . " " . $sort_order;
        $tagList = $this->db->SimpleQuery($query, "", $pagging, false);

        //$tagList = $this->db->SelectQuery(TBL_TAG, "*", "", "", "", "ORDER BY $sort_by", $sort_order, $pagging, false);
        return $tagList;
    }

    public function Username($pagging, $condition, $sort_by='id', $sort_order = 'DESC') {

        $query = "SELECT t.id, t.user_id, t.name, t.photo_id,t.status, u.user_name FROM " . TBL_TAG . " t LEFT JOIN ".TBL_USERS." u ON t.user_id = u.id " . $condition . " ORDER BY  " . $sort_by . " " . $sort_order;
        //echo $query; exit;
        $tagUserName = $this->db->SimpleQuery($query, "", $pagging, false);

        //$tagList = $this->db->SelectQuery(TBL_TAG, "*", "", "", "", "ORDER BY $sort_by", $sort_order, $pagging, false);
        return $tagUserName;
    }

    public function PhotoName($pagging, $condition, $sort_by='id', $sort_order = 'DESC') {

        $query = "SELECT t.id, t.user_id, t.name, t.photo_id,t.status, p.name FROM " . TBL_TAG . " t LEFT JOIN ".TBL_PHOTO." p ON t.photo_id = p.id " . $condition . " ORDER BY  " . $sort_by . " " . $sort_order;

        $tagPhotoName = $this->db->SimpleQuery($query, "", $pagging, false);

        return $tagPhotoName;
    }

    public function checkExists() {
        $where = "";
        if (logged_in_user::id() != '') {
            $where = " AND id!='" . $this->id . "'  AND deleted=0";
        }
        $query = "SELECT id FROM " . TBL_TAG . " WHERE name='" . mysql_real_escape_string($this->name) . "' " . $where;
        $getUserId = $this->db->SimpleQuery($query, false);
        return $getUserId;
    }


    // insert,update query
    public function insertUpdate($id) {
        
        $Check = $this->checkExists();

        if (count($Check) > 0) {
            $this->error_message = "Name already exists. Please enter another name and try again.";
            return false;
        }
         
        $fields = $this->db->SelectFields(TBL_TAG);
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

        if ($id != '') {
            $where = "id = " . $id;
            $this->db->InsertUpdateQuery(TBL_TAG, $values, $where, false);
            return true;
        } else {
            $this->db->InsertUpdateQuery(TBL_TAG, $values, false);
            return true;
        }
    }

    // delete functionality
    public function delete() {
        $fields['deleted'] = 1;
        $fields['deleted_by'] = logged_in_user::id();
        $fields['deleted_on'] = $this->commonFunction->GetDateTime();
        $this->db->InsertUpdateQuery(TBL_TAG, $fields, "id = " . mysql_real_escape_string($this->id), false);
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
        $getuserDetail = $this->db->SelectQuery(TBL_TAG, "*", "id = " . mysql_real_escape_string($this->id) . " and deleted=0", "", false, "", "");
        foreach ($this as $k => $d) {
            if ($k != 'db' && $k != 'commonFunction') {
                $this->$k = $getuserDetail[0][$k];
            }
        }
    }

    public function getPhotoName($photo_id){
		if(mysql_num_rows($rs = mysql_query("SELECT name FROM photo WHERE id = $photo_id"))> 0 ){
			$data = mysql_fetch_assoc($rs);
			return $data['name'];
		}else{
			return 'NA';
		}
	}

     public function getUserName($user_id){
		if(mysql_num_rows($rs = mysql_query("SELECT user_name FROM users WHERE id = $user_id"))> 0 ){
			$data = mysql_fetch_assoc($rs);
			return $data['user_name'];
		}else{
			return 'NA';
		}
	}

     public function search_name($pagging, $condition, $sort_by='id', $sort_order = 'DESC') {

        $query = "SELECT t.* FROM " . TBL_TAG ." t ". $condition ."  ORDER BY  " . $sort_by . " " . $sort_order;
        $userList = $this->db->SimpleQuery($query, "", $pagging, false);

        //$userList = $this->db->SelectQuery(TBL_PHOTO, "*", "", "", "", "ORDER BY $sort_by", $sort_order, $pagging, false);
        return $userList;
    }

    function updateStatus($currStatus,$Id){
        $query = "UPDATE " . TBL_TAG . " SET status = '" . $currStatus . "' WHERE id = '".$Id."'";
        $userList = $this->db->SimpleQuery($query, "", $pagging, false);

        //$userList = $this->db->SelectQuery(TBL_PHOTO, "*", "", "", "", "ORDER BY $sort_by", $sort_order, $pagging, false);
        return $userList;

    }


}

?>