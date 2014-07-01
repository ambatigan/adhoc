<?php
mysql_connect(DB_HOST,DB_USER_NAME,DB_PASSWORD) or throw_ex('Failed to connect server');
mysql_select_db(DB_NAME) or throw_ex('Failed to select database');

function throw_ex($message=''){
    throw new app_exception(sprintf("%s MySQL.Error(%d): %s", $message, mysql_errno(), mysql_error()));
}
