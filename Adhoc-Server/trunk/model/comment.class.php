<?php
#============================================================================================================
#	Created By  			: chandresh
#	Purpose                 : Admin Comment Model file
#============================================================================================================
 
class comments {

    public function __construct() {
        global $commonFunction, $dbAccess;
        $this->db = $dbAccess;
        $this->commonFunction = $commonFunction;
        $fields = $this->db->SelectFields(TBL_COMMENTS);
        foreach ($fields as $k => $d) {
            $this->$d = null;
        }
    }

    public function select($pagging, $condition='', $sort_by='id', $sort_order = 'DESC') {

        $query = "SELECT c.*  FROM " . TBL_COMMENTS . " c JOIN users AS u ON c.user_id = u.id LEFT JOIN ".TBL_PHOTO." p ON c.photo_id = p.id  " . $condition  ." AND c.flag = 'Inactive' AND c.deleted = 0
        ORDER BY  " . $sort_by . " " . $sort_order ;
        $userList = $this->db->SimpleQuery($query, "", $pagging, false);
        return $userList;
    }


    // insert,update query
    public function insertUpdate($id) {

        $fields = $this->db->SelectFields(TBL_COMMENTS);
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

        if ($id != '') {
            $where = "id = " . $id;
            $this->db->InsertUpdateQuery(TBL_COMMENTS, $values, $where, false);
            return true;
        } else {
            $this->db->InsertUpdateQuery(TBL_COMMENTS, $values, false);
            return true;
        }
    }

    // delete functionality
    public function delete() {
        $fields['deleted'] = 1;
        $fields['deleted_by'] = logged_in_user::id();
        $fields['deleted_on'] = $this->commonFunction->GetDateTime();
         $fields['status'] = 'Inactive';
        $this->db->InsertUpdateQuery(TBL_COMMENTS, $fields, "id = " . mysql_real_escape_string($this->id), false);
        return true;
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

      public function search_user_name($pagging, $condition, $sort_by='id', $sort_order = 'DESC') {

        echo $query = "SELECT c.id,c.comment,c.user_id,p.name,c.photo_id,u.user_name,c.status,p.status FROM " . TBL_COMMENTS . " c LEFT JOIN ".TBL_USERS." u ON c.user_id = u.id
        JOIN ".TBL_PHOTO." p ON c.photo_id = p.id  " . $condition . " AND c.flag = 'Inactive' AND c.status = 'Active' AND c.deleted=0 AND p.status = 'Active' AND p.deleted=0 ORDER BY  " . $sort_by . " " . $sort_order; exit;
        $userList = $this->db->SimpleQuery($query, "", $pagging, false);

        //$userList = $this->db->SelectQuery(TBL_PHOTO, "*", "", "", "", "ORDER BY $sort_by", $sort_order, $pagging, false);
        return $userList;
    }

    public function search_name($pagging, $condition, $sort_by='id', $sort_order = 'DESC') {

        $query = "SELECT c.id,c.comment,c.user_id,c.flag,c.status,c.photo_id,p.name,u.user_name FROM " . TBL_COMMENTS . " c
        JOIN ".TBL_USERS." u ON c.user_id = u.id LEFT JOIN ".TBL_PHOTO." p ON c.photo_id = p.id  " . $condition . " AND c.flag = 'inactive' AND c.status = 'Active' AND p.status = 'Active' ORDER BY  " . $sort_by . " " . $sort_order;
        $userList = $this->db->SimpleQuery($query, "", $pagging, false);

        //$userList = $this->db->SelectQuery(TBL_PHOTO, "*", "", "", "", "ORDER BY $sort_by", $sort_order, $pagging, false);
        return $userList;
    }

    //
    public function deleteGreetings() {
        $fields['deleted'] = 1;
        $fields['deleted_by'] = logged_in_user::id();
        $fields['deleted_on'] = $this->commonFunction->GetDateTime();
        $fields['status'] = 'Inactive';
        $this->db->InsertUpdateQuery(TBL_ADMIN_GREETINGS, $fields, "created_by = " . mysql_real_escape_string($this->id), false);
        $this->db->InsertUpdateQuery(TBL_USER_GREETINGS, $fields, "created_by = " . mysql_real_escape_string($this->id), false);
        $this->db->InsertUpdateQuery(TBL_USER_GREETINGS, $fields, "user_id = " . mysql_real_escape_string($this->id), false);
        return true;
    }

    // select by id
    public function selectById() {
        $getuserDetail = $this->db->SelectQuery(TBL_COMMENTS, "*", "id = " . mysql_real_escape_string($this->id) . " and deleted=0", "", false, "", "");
        foreach ($this as $k => $d) {
            if ($k != 'db' && $k != 'commonFunction') {
                $this->$k = $getuserDetail[0][$k];
            }
        }
    }

    function updateStatus($currStatus,$Id){
        $query = "UPDATE " . TBL_COMMENTS . " SET status = '" . $currStatus . "' WHERE id = '".$Id."'";
        $userList = $this->db->SimpleQuery($query, "", $pagging, false);

        //$userList = $this->db->SelectQuery(TBL_PHOTO, "*", "", "", "", "ORDER BY $sort_by", $sort_order, $pagging, false);
        return $userList;

    }

}

?>