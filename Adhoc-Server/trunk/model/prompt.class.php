<?php
#============================================================================================================
#	Created By  			: chandresh
#	Purpose                 : Admin Comment Model file
#============================================================================================================
 
class prompts {

    public function __construct() {
        global $commonFunction, $dbAccess;
        $this->db = $dbAccess;
        $this->commonFunction = $commonFunction;
        $fields = $this->db->SelectFields(TBL_PROMPTS);
        foreach ($fields as $k => $d) {
            $this->$d = null;
        }
    }

    public function select($pagging, $condition='', $sort_by='id', $sort_order = 'DESC') {

        $query = "SELECT *  FROM " . TBL_PROMPTS  . $condition  ." ORDER BY  " . $sort_by . " " . $sort_order ;
         $userList = $this->db->SimpleQuery($query, "", $pagging, false);
        return $userList;
    }


    // insert,update query
    public function insertUpdate($id) {

        $fields = $this->db->SelectFields(TBL_PROMPTS);
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
            $this->db->InsertUpdateQuery(TBL_PROMPTS, $values, $where, false);
            return true;
        } else {
            print_r($values);
            $this->db->InsertUpdateQuery(TBL_PROMPTS, $values, false);
            return true;
        }
    }

    // delete functionality
    public function delete() {
        $fields['deleted'] = 1;
        $fields['deleted_by'] = logged_in_user::id();
        $fields['deleted_on'] = $this->commonFunction->GetDateTime();
         $fields['status'] = 'Inactive';
        $this->db->InsertUpdateQuery(TBL_PROMPTS, $fields, "id = " . mysql_real_escape_string($this->id), false);
        return true;
    }

  
      
    public function search_name($pagging, $condition, $sort_by='id', $sort_order = 'DESC') {

        $query = "SELECT c.id,c.prompt,c.flag,c.status FROM " . TBL_PROMPTS . " c
        ORDER BY  " . $sort_by . " " . $sort_order;
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
        $getuserDetail = $this->db->SelectQuery(TBL_PROMPTS, "*", "id = " . mysql_real_escape_string($this->id) . " and deleted=0", "", false, "", "");
        foreach ($this as $k => $d) {
            if ($k != 'db' && $k != 'commonFunction') {
                $this->$k = $getuserDetail[0][$k];
            }
        }
    }

    function updateStatus($currStatus,$Id){
        $query = "UPDATE " . TBL_PROMPTS . " SET status = '" . $currStatus . "' WHERE id = '".$Id."'";
        $userList = $this->db->SimpleQuery($query, "", $pagging, false);

        //$userList = $this->db->SelectQuery(TBL_PHOTO, "*", "", "", "", "ORDER BY $sort_by", $sort_order, $pagging, false);
        return $userList;

    }

}

?>