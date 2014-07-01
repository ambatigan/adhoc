<?php

#============================================================================================================
#	Created By  			: -
#	Created Date			: 22-03-2011
#	Purpose					: For Handling  user
#	includes / Obj(Req)		: Files:
#	Last update date		: 22-03-2011
#	Update Purpose			: For generation
#============================================================================================================

class admin_user {

    public function __construct() {
        global $commonFunction, $dbAccess;
        $this->db = $dbAccess;
        $this->commonFunction = $commonFunction;
        $fields = $this->db->SelectFields(TBL_ADMIN_USER);
        foreach ($fields as $k => $d) {
            $this->$d = null;
        }
    }

    public function select($pagging, $condition='', $sort_by='id', $sort_order = 'ASC') {

        $query = "SELECT u.* FROM " . TBL_ADMIN_USER . " u " . $condition . "ORDER BY  " . $sort_by . " " . $sort_order;
        $userList = $this->db->SimpleQuery($query, "", $pagging, false);

        //$userList = $this->db->SelectQuery(TBL_ADMIN_USER, "*", "", "", "", "ORDER BY $sort_by", $sort_order, $pagging, false);
        return $userList;
    }


    // insert,update query
    public function insertUpdate($id) {

        $Check = $this->checkExists();
        $CheckMail = $this->checkEmailExists();
       // $CheckLicense = $this->checkLicense();
        if (count($Check) > 0) {
            $this->error_message = index_lang::translate("Username already exists");
            return false;
        }
        if (count($CheckMail) > 0) {
            $this->error_message = index_lang::translate("Email Address already exists");
            return false;
        }
         
        $fields = array('user_name','user_type', 'password', 'first_name', 'last_name', 'middle_name', 'gender', 'dob', 'email_address', 'address1', 'street_no', 'complemento', 'city_id', 'state_id', 'country_id', 'zip', 'phone', 'fax', 'status');
        foreach ($fields as $k => $d) {
            $values[$d] = $this->$d;
        }
        if ($id != '') {
            $values['modified_on'] = date('Y-m-d H:i:s');
        } else {
            $values['created_on'] = date('Y-m-d H:i:s');
        }

        if ($id != '') {
            $where = "user_id = " . $id;
            $this->db->InsertUpdateQuery(TBL_USER, $values, $where, false);
            return true;
        } else {
            $this->db->InsertUpdateQuery(TBL_USER, $values, false);
            return true;
        }
    }

    // delete functionality
    public function delete() {
        $fields['deleted'] = 1;
        $fields['deleted_on'] = $this->commonFunction->GetDateTime();
        $this->db->InsertUpdateQuery(TBL_USER, $fields, "user_id = " . mysql_real_escape_string($this->user_id), false);
        return true;
    }

    // select by id
    public function selectById() {
        $getuserDetail = $this->db->SelectQuery(TBL_USER, "*", "user_id = " . mysql_real_escape_string($this->user_id) . " and deleted=0", "", false, "", "");
        foreach ($this as $k => $d) {
            if ($k != 'db' && $k != 'commonFunction') {
                $this->$k = $getuserDetail[0][$k];
            }
        }
    }

}
?>