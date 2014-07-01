<?php

class CommonFunction {

    var $tagBlacklist = array();
    var $AllowedImageExtensions = array("jpg", "jpeg", "gif", "png");
    var $AllowedVideoExtensions = array("avi", "mpeg");
    var $AllowedFileExtensions = array(ALLOWED_UPLOAD_FILE_TYPES);
    var $mainmenu = array();
    var $menuitem = array();

    function sort_devide($value) {
        $result = explode(":", $value);
        return $result; 
    }

    /*
     * @param value:'fieldname to be sorted':'direction of sorting' (e.g. name:asc)
     * @return none
     * */

    function sort_url($value, $url=null) {
        $dir = '';
        $arguments = $_GET;
        unset($arguments['sortby']);
        $i = 0;
        $querystring = '';
        foreach ($arguments as $key => $val) {
            if ($i == 0)
                $querystring .= "?$key=$val";
            else
                $querystring .= "&$key=$val";
            $i++;
        }
        if (isset($_REQUEST['sortby'])) {
            list($sort, $dir) = $this->sort_devide($value);
            if ($value == $_REQUEST['sortby']) {
                if ($dir == "asc") {
                    $dir = "desc";
                } else {
                    $dir = "asc";
                }
            }
            $querystring .='&sortby=' . $sort . ":" . $dir;
        } else {
            $querystring .= "&sortby=" . $value;
        }
        return $querystring;
    }

    public function select_country() {

        $query = "SELECT * FROM " . TBL_COUNTRY . " ORDER BY country_name ASC ";

        $userList = $this->db->SimpleQuery($query, "", null, false);

//                                $userList = $this->db->SelectQuery(TBL_COUNTRY, "*", "", "", "", "ORDER BY $sort_by", $sort_order, $pagging, false);
        return $userList;
    }

    function ConvertFate($date, $metric) {
        //mm/dd/yyyy
        if ($metric == 1) {
            $tempDate = explode("-", $date);
            return $tempDate[1] . "-" . $tempDate[2] . "-" . $tempDate[0];
        }
        //dd/mm/yyyy
        else {
            $tempDate = explode("-", $date);
            return $tempDate[2] . "-" . $tempDate[0] . "-" . $tempDate[1];
        }
    }

    function DateFormat($date = "", $format = "") {
        if ($format == "")
            $format = "Y-m-d H:i:s";

        if ($date == "")
            $date = time();

        $converted = strtotime($date);

        if ($converted === false)
            return date($format, $date);
        else
            return date($format, $converted);
    }

    // converted perticuler date formate
    function date_format($date = "", $format = "") {
        if ($format == "")
            $format = "Y-m-d H:i:s";

        if ($date == "")
            return "";

        $converted = strtotime($date);

        if ($converted === false)
            return date($format, $date);
        else
            return date($format, $converted);
    }

    // converted perticuler date formate
    function datetime_format($date = "", $format = "") {
        if ($format == "")
            $format = "Y-m-d H:i:s";

        if ($date == "")
            return "";

        $converted = strtotime($date);

        if ($converted === false)
            return date($format, $date);
        else
            return date($format, $converted);
    }

    function current_url() {

        //return $_SERVER["REQUEST_URI"];
        $page_url = 'http';
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
            $page_url.= "s";
        }
        $page_url.= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $page_url.= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $page_url.= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $page_url;
    }

    //to add days,month etc in given date e.g. DateAdd($date,"d",1);
    function DateAdd($date, $datepart, $number, $operation='add', $returnFormat="Y-m-d H:i:s") {
        $adbit = explode(" ", $date);
        $bdbit = explode("-", $adbit[0]);
        if (count($adbit) > 1) {
            $cdbit = explode(":", $adbit[1]);
        } else {
            $cdbit = array();
            $cdbit[0] = 0;
            $cdbit[1] = 0;
            $cdbit[2] = 0;
        }
        switch ($datepart) {
            case "l": // Millisecond (Lower case 'L')
                $e = 60 / 1000;
                break;
            case "s": // Second
                $e = 1;
                break;
            case "n": // Minute
                $e = 60;
                break;
            case "h": // Hour
                $e = 60 * 60;
                break;
            case "ww": // Week
                $e = ((60 * 60) * 24) * 7;
                break;
            case "d": // Day
                $e = (( 60 * 60) * 24);
                break;
            case "m": // Month
                $e = (((60 * 60) * 24) * 365) / 12;
                break;
            case "yyyy": // Year
                $e = (((60 * 60) * 24) * 365);
                break;
            default:
                $e = "error";
        }
        if ($e == "error") {
            return false;
        }
        $intTime = @mktime($cdbit[0], $cdbit[1], $cdbit[2], $bdbit[1], $bdbit[2], $bdbit[0]);

        if ($operation == "add")
            $nTime = $intTime + ($e * $number);
        else
            $nTime = $intTime - ($e * $number);
        //for substract day use following
        //$nTime = $intTime - ($e * $number);
        return date($returnFormat, $nTime);
    }

    // to get diffrence between two dates e.g. echo DateDifference("d", "2008-01-01", "2008-01-02") 
    function DateDifference($interval, $dateFrom, $dateTo, $usingTimestamps = false) {
        /*
          $interval can be:
          yyyy - Number of full years
          q - Number of full quarters
          m - Number of full months
          y - Difference between day numbers
          (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
          d - Number of full days
          w - Number of full weekdays
          ww - Number of full weeks
          h - Number of full hours
          n - Number of full minutes
          s - Number of full seconds (default)
         */

        if (!$usingTimestamps) {
            $dateFrom = strtotime($dateFrom, 0);
            $dateTo = strtotime($dateTo, 0);
        }

        $difference = $dateTo - $dateFrom; // Difference in seconds

        switch ($interval) {
            case 'yyyy': // Number of full years
                $yearsDifference = floor($difference / 31536000);
                if (mktime(date("H", $dateFrom), date("i", $dateFrom), date("s", $dateFrom), date("n", $dateFrom), date("j", $dateFrom), date("Y", $dateFrom) + $yearsDifference) > $dateTo) {
                    $yearsDifference--;
                }
                if (mktime(date("H", $dateTo), date("i", $dateTo), date("s", $dateTo), date("n", $dateTo), date("j", $dateTo), date("Y", $dateTo) - ($yearsDifference + 1)) > $dateFrom) {
                    $yearsDifference++;
                }
                $datediff = $yearsDifference;
                break;
            case "q": // Number of full quarters
                $quartersDifference = floor($difference / 8035200);
                while (mktime(date("H", $dateFrom), date("i", $dateFrom), date("s", $dateFrom), date("n", $dateFrom) + ($quartersDifference * 3), date("j", $dateTo), date("Y", $dateFrom)) < $dateTo) {
                    $monthsDifference++;
                }
                $quartersDifference--;
                $datediff = $quartersDifference;
                break;
            case "m": // Number of full months
                $monthsDifference = floor($difference / 2678400);
                while (mktime(date("H", $dateFrom), date("i", $dateFrom), date("s", $dateFrom), date("n", $dateFrom) + ($monthsDifference), date("j", $dateTo), date("Y", $dateFrom)) < $dateTo) {
                    $monthsDifference++;
                }
                $monthsDifference--;
                $datediff = $monthsDifference;
                break;
            case 'y': // Difference between day numbers
                $datediff = date("z", $dateTo) - date("z", $dateFrom);
                break;
            case "d": // Number of full days
                $datediff = floor($difference / 86400);
                break;
            case "w": // Number of full weekdays
                $daysDifference = floor($difference / 86400);
                $weeks_difference = floor($daysDifference / 7); // Complete weeks
                $firstDay = date("w", $dateFrom);
                $daysRemainder = floor($daysDifference % 7);
                $oddDays = $firstDay + $daysRemainder; // Do we have a Saturday or Sunday in the remainder?
                if ($oddDays > 7) {
                    // Sunday
                    $daysRemainder--;
                }
                if ($oddDays > 6) {
                    // Saturday
                    $daysRemainder--;
                }
                $datediff = ($weeks_difference * 5) + $daysRemainder;
                break;

            case "ww": // Number of full weeks
                $datediff = floor($difference / 604800);
                break;
            case "h": // Number of full hours
                $datediff = floor($difference / 3600);
                break;
            case "n": // Number of full minutes
                $datediff = floor($difference / 60);
                break;
            default: // Number of full seconds (default)
                $datediff = $difference;
                break;
        }
        return $datediff;
    }

    function GetDateTime() {
        return date("Y-m-d H:i:s");
    }

    function GetCurrentDate() {
        return date("Y-m-d");
    }

    function SetTimeArray() {
        $jj = 0;
        $kk = 12;
        $am = " am";
        for ($ii = 1; $ii <= 24; $ii++) {
            if ($ii >= 13) {
                $am = " pm";
            }

            if ($kk >= 13)
                $kk = 1;

            if ($jj <= 10) {
                $add_zero = "0";
            } else {
                $add_zero = "";
            }

            $timeArray[$add_zero . $jj . ":00:00"] = number_format($kk, 2) . $am;
            $timeArray[$add_zero . $jj . ":30:00"] = number_format(($kk + 0.30), 2) . $am;

            $jj++;
            $kk++;
        }
        return $timeArray;
    }

    function GetCurrentFileName() {
        $tmp = explode("/", $_SERVER['PHP_SELF']);

        $fileName = $tmp[count($tmp) - 1];
        return $fileName;
    }

    function Redirect($url) {
        if (isset($_REQUEST['destination'])) {
            extract(parse_url(urldecode($_REQUEST['destination'])));
        }
        header("Location: " . $url);
        exit;
    }

    function RedirectByJavascript($url) {
        global $successMsg;

        if (!empty($successMsg)) {
            $_SESSION['successMsg'] = $successMsg;
        }

        if (isset($_REQUEST['destination']) && $_REQUEST['destination'] != '') {
            $url = SITE_URL . urldecode($_REQUEST['destination']);
        }
        ?>
        <script language="javascript">
            window.location = '<?php echo $url; ?>';
        </script>
        <?php
        exit;
    }

    function CurlDataFetch($url, $method, $queryString="") {
        $ch = curl_init();
        //echo $url;

        if ($method == "get") {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
            curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");


            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // to get the result within the variable ie here it is $return
        } elseif ($method == "post") {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $queryString);

            curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
            curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");

            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // to get the result within the variable ie here it is $return
        }
        $return = trim(curl_exec($ch));
        $return = unserialize($return);

        curl_close($ch);
        return $return;
    }

    function RemoveExtraSpace($string) {
        for ($i = 0; $i < strlen($string); $i++) {
            if (substr($string, $i, 1) == " ") {
                if (substr($string, $i + 1, 1) != " ") {
                    $t = $t . " " . substr($string, $i + 1, 1);
                    $i = $i + 1;
                }
            }
            else
                $t = $t . substr($string, $i, 1);
        }
        return $t;
    }

    function FilterInputVariable() {
        $post = $_POST;
        $get = $_GET;
        $session = $_SESSION;

        if (!empty($post)) {
            foreach ($post as $key => $value) {
                if (!is_array($post[$key])) {
                    $post[$key] = str_replace($this->tagBlacklist, '', $post[$key]);

                    if (get_magic_quotes_gpc() == false) {
                        $post[$key] = addslashes($post[$key]);
                    }
                }
            }
            return $post;
        }

        if (!empty($get)) {
            foreach ($get as $key => $value) {
                if (!is_array($get[$key])) {
                    $get[$key] = str_replace($this->tagBlacklist, '', $get[$key]);

                    if (get_magic_quotes_gpc() == false) {
                        $get[$key] = addslashes($get[$key]);
                    }
                }
            }
            return $get;
        }
    }

    function FilterVariable($inputVariable) {
        $returnInputVariable = str_replace($this->tagBlacklist, '', $inputVariable);
        if (get_magic_quotes_gpc() == false) {
            $returnInputVariable = addslashes($returnInputVariable);
        }
        return $returnInputVariable;
    }

    function GenratePassword($length=8, $strength=4) {
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

    function TimeDiff($startTime, $endTime = "", $detailed=true, $short = false) {
        if ($endTime == "")
            $endTime = date("Y-m-d H:i:s");

        if (!is_int($startTime))
            $startTime = strtotime($startTime);
        if (!is_int($endTime))
            $endTime = strtotime($endTime);

        $diff = ($startTime >= $endTime ? $startTime - $endTime : $endTime - $startTime);

        # Set the periods of time
        /* $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
          $lengths = array(1, 60, 3600, 86400, 604800, 2630880, 31570560, 315705600);
         */
        $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths = array(1, 60, 3600, 86400, 604800, 2630880, 31570560, 315705600);



        if ($short) {
            $periods = array("s", "m", "h", "d", "m", "y");
            $lengths = array(1, 60, 3600, 86400, 2630880, 31570560);
        }

        # Go from decades backwards to seconds
        $ii = 4; //sizeof($lengths) - 2; # Size of the lengths / periods in case you change them
        $time = ""; # The string we will hold our times in
        $jj = 1;
        while ($ii >= 0) {
            if ($diff > $lengths[$ii - 1]) { # if the difference is greater than the length we are checking... continue
                $val = @floor($diff / $lengths[$ii - 1]);    # 65 / 60 = 1.  That means one minute.  130 / 60 = 2. Two minutes.. etc
                if ($val != 0) {
                    $time .= $val . ($short ? '' : ' ') . $periods[$ii - 1] . ((!$short && $val > 1) ? 's ' : ' ');  # The value, then the name associated, then add 's' if plural
                    $diff -= ($val * $lengths[$ii - 1]);    # subtract the values we just used from the overall diff so we can find the rest of the information

                    if (!$detailed) {
                        $ii = 0;
                    }
                    if ($jj == 2)
                        return $time;
                    $jj++;
                }
                # if detailed is turn off (default) only show the first set found, else show all information
            }
            $ii--;
        }
        if ($time == "")
            return "1 second";
        else
            return $time;
    }

    function RedirectForm($postData, $formAction) {
        $ret = '<body onload="submit_form();">';
        $ret .= '<form method="post" name="frm_test" action="' . $formAction . '">';
        foreach ($postData as $key => $value) {
            $ret .= '<input type="hidden" name="' . $key . '" value="' . $value . '">';
        }

        $ret .='</form></body>';
        $ret .='<script>
						function submit_form()
						{
							document.frm_test.submit();
						}
					</script>';

        echo $ret;
    }

    /**
     * this function will encode the given id
     * @param int => id that need to be encoded
     * @return string
     */
    function EncodeVal($val) {
        return base64_encode("P" . $val . "E");
    }

    /**
     * this function will decode the given string encoded by encodeVal function
     * @param string => id that need to be decoded
     * @return int
     */
    function DecodeVal($val) {
        return substr(substr(base64_decode($val), 0, -1), 1);
    }

    /**
     * Encode special characters in a plain-text string for display as HTML.
     *
     * Uses ValidateUtf8 to prevent cross site scripting attacks on
     * Internet Explorer 6.
     */
    function CheckPlain($text) {
        return $this->ValidateUtf8($text) ? htmlspecialchars($text, ENT_QUOTES) : '';
    }

    /**
     * Format an attribute string to insert in a tag.
     *
     * @param $attributes
     *   An associative array of HTML attributes.
     * @return
     *   An HTML string ready for insertion in a tag.
     */
    function SetAttributes($attributes = array()) {
        if (is_array($attributes)) {
            $t = '';
            foreach ($attributes as $key => $value) {
                $t .= " $key=" . '"' . $this->CheckPlain($value) . '"';
            }
            return $t;
        }
    }

    /**
     * Checks whether a string is valid UTF-8.
     *
     * All functions designed to filter input should use ValidateUtf8
     * to ensure they operate on valid UTF-8 strings to prevent bypass of the
     * filter.
     *
     * When text containing an invalid UTF-8 lead byte (0xC0 - 0xFF) is presented
     * as UTF-8 to Internet Explorer 6, the program may misinterpret subsequent
     * bytes. When these subsequent bytes are HTML control characters such as
     * quotes or angle brackets, parts of the text that were deemed safe by filters
     * end up in locations that are potentially unsafe; An onerror attribute that
     * is outside of a tag, and thus deemed safe by a filter, can be interpreted
     * by the browser as if it were inside the tag.
     *
     * This function exploits preg_match behaviour (since PHP 4.3.5) when used
     * with the u modifier, as a fast way to find invalid UTF-8. When the matched
     * string contains an invalid byte sequence, it will fail silently.
     *
     * preg_match may not fail on 4 and 5 octet sequences, even though they
     * are not supported by the specification.
     *
     * The specific preg_match behaviour is present since PHP 4.3.5.
     *
     * @param $text
     *   The text to check.
     * @return
     *   TRUE if the text is valid UTF-8, FALSE if not.
     */
    function ValidateUtf8($text) {
        if (strlen($text) == 0) {
            return TRUE;
        }
        return (preg_match('/^./us', $text) == 1);
    }

    /**
     * Format an internal project link.
     *
     * @param $text
     *   The text to be enclosed with the anchor tag.
     * @param $path
     *   The path being linked to, such as "admin/blog/blog_categories.php". Can be an
     *   external or internal URL.
     *     - If you provide the full URL, it will be considered an external URL.
     *     - If you provide only the path (e.g. "admin/blog/blog_categories.php"), it is
     *       considered an internal link. In this case, it must be a system URL
     *       as the url() function will generate the alias.
     *     - If you provide '<front>', it generates a link to the site's
     *       base URL (again via the url() function).
     *     - If you provide a path, and 'alias' is set to TRUE (see below), it is
     *       used as is.
     * @param $options
     *   An associative array of additional options, with the following keys:
     *     - 'attributes'
     *       An associative array of HTML attributes to apply to the anchor tag.
     *     - 'query'
     *       A query string to append to the link, or an array of query key/value
     *       properties.
     *     - 'fragment'
     *       A fragment identifier (named anchor) to append to the link.
     *       Do not include the '#' character.
     *     - 'absolute' (default FALSE)
     *       Whether to force the output to be an absolute link (beginning with
     *       http:). Useful for links that will be displayed outside the site, such
     *       as in an RSS feed.
     *     - 'html' (default FALSE)
     *       Whether the title is HTML, or just plain-text. For example for making
     *       an image a link, this must be set to TRUE, or else you will see the
     *       escaped HTML.
     *     - 'alias' (default FALSE)
     *       Whether the given path is an alias already.
     * @return
     *   an HTML string containing a link to the given path.
     */
    function Link($text, $path, $options = array()) {
        // Merge in defaults.
        $options += array(
            'attributes' => array(),
            'html' => FALSE,
        );


        // Remove all HTML and PHP tags from a tooltip. For best performance, we act only
        // if a quick strpos() pre-check gave a suspicion (because strip_tags() is expensive).
        if (isset($options['attributes']['title']) && strpos($options['attributes']['title'], '<') !== FALSE) {
            $options['attributes']['title'] = strip_tags($options['attributes']['title']);
        }

        return '<a' . ( ($path) ? ' href="' . SITE_URL . '/' . $path . '"' : '') . $this->SetAttributes($options['attributes']) . '>' . ($options['html'] ? $text : $this->CheckPlain($text)) . '</a>';
    }

    /*
     * Name:     	TruncateStr
     * Purpose:  	Truncate a string to a certain length if necessary, 
     *           	optionally splitting in the middle of a word, and 
     *           	appending the $etc string. 
     * @param    	$string - string to be truncated
     * @param    	$length - truncation length
     * @param        $etc - truncation style
     * @breakWords   $breakWords - it breaks the word if set to true.
     */

    function TruncateStr($string, $length = 80, $etc = '...', $breakWords = false) {
        if ($length == 0)
            return '';

        if (strlen($string) > $length) {
            $length -= strlen($etc);
            $fragment = substr($string, 0, $length + 1);
            if ($break_words)
                $fragment = substr($fragment, 0, -1);
            else
                $fragment = preg_replace('/\s+(\S+)?$/', '', $fragment);
            return $fragment . " " . $etc;
        } else
            return $string;
    }

    /*
     * Name:     	GetFileExtension
     * Purpose:  	Upload image or video files.
     * @fileName 	name of the file
     * @return
     *   return extension without ".".
     */

    function GetFileExtension($fileName) {
        return end(explode(".", $fileName));
    }

    /*
     * Name:     	UploadFile
     * Purpose:  	Upload image or video files. 
     * @param    	$fileArray - $_FILE Array
     * @param    	$dest - Image destination path
     * @param        $etc - truncation style
     * @return
     *   Uploade image or video file name.
     */
    /* function UploadFile($fileArray, $dest, $type = 'image',$filename = '')
      {
      global $smarty;
      $ext = $this->GetFileExtension($fileArray['name']);

      if($filename)
      $name = $filename.".".$ext;
      else
      $name = date('dmYHis').".".$ext;

      if($type == 'image'){
      if (!in_array(strtolower($ext),$this->AllowedImageExtensions)) {
      $errorMsg[] = "File type is not supported.";
      $smarty->assign("errorMsg",$errorMsg);
      return 0;
      }
      }elseif($type == 'video'){
      if (!in_array(strtolower($ext),$this->AllowedVideoExtensions)) {
      $errorMsg[] = "File type is not supported.";
      $smarty->assign("errorMsg",$errorMsg);
      return 0;
      }
      }else{
      if (!in_array(strtolower($ext),$this->AllowedFileExtensions)) {
      $errorMsg[] = "File type is not supported.";
      $smarty->assign("errorMsg",$errorMsg);
      return 0;
      }
      }


      if(move_uploaded_file($fileArray["tmp_name"],$dest.$name))
      {
      return $name;
      }else{
      $errorMsg[] = "File cannot be uploaded.";
      $smarty->assign("errorMsg",$errorMsg);
      return 0;
      }
      } */

    function UploadFile($fileArray, $dest, $filename = '', $allowed_file_type=ALLOWED_UPLOAD_FILE_TYPES) {
        global $smarty;
        $ext = $this->GetFileExtension($fileArray['name']);

        if ($filename) {
            $file_ = substr($filename, 0, strlen($filename) - strlen($ext) - 1);
            $name = $file_ . "." . $ext;
        }
        else
            $name = $fileArray['name'];
        //$name = date('dmYHis').".".$ext;


        $allowed = $allowed_file_type;
        $AllowedFileExtensions = explode(',', $allowed);
        if ($allowed != '')
            $AllowedFileExtensions = explode(',', $allowed);

        if (!in_array(strtolower($ext), $AllowedFileExtensions) && !empty($AllowedFileExtensions)) {
            return 0;
        }
        if (move_uploaded_file($fileArray["tmp_name"], $dest . $name)) {
//   			if(move_uploaded_file($fileArray["tmp_name"],"../uploads/products/icon_logo/icon_1367228416.jpg"))
            return 1;
        } else {
            return 2;
        }
    }

    /*
     * Name:     	GetDates
     * Purpose:  	Calculate Days or Months or Weeks or Yeard between Given Date. 
     * @param    	$startDate - Start Date
     * @param    	$endDate - End Date
     * @param        $every =if 1 then it will return every days,
     * 						if 2 then it will return every week,
     * 						if 3 then it will return ever month
     * 						if 4 then it will return ever year
     * @return
     *   Uploade image or video file name.
     */

    function GetDates($startDate, $endDate, $every = 1, $keyFormat='', $valueFormat='') {
        $days = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24);

        $ExplodeStDate = explode("-", $startDate);

        $i = 0;
        $j = 0;
        $k = 0;

        $dateArray = array();
        while ($days > 0) {
            $tmpDate = date("Y-m-d", mktime(0, 0, 0, $ExplodeStDate[1] + $j, $ExplodeStDate[2] + $i, $ExplodeStDate[0] + $k));

            if ($keyFormat) {
                if (function_exists($keyFormat))
                    $arrayKey = $keyFormat(trim($tmpDate));
                else
                    $arrayKey = str_ireplace('%key%', trim($tmpDate), $keyFormat);
            }else {
                $arrayValue = $tmpDate;
            }
            if ($valueFormat) {
                if (function_exists($valueFormat))
                    $arrayValue = $valueFormat(trim($tmpDate));
                else
                    $arrayValue = str_ireplace('%value%', trim($tmpDate), $arrayValue);
            }else {
                $arrayValue = $tmpDate;
            }

            $dateArray[$arrayKey] = $arrayValue;

            if ($every == 1) {
                $i++;
                $days--;
            } elseif ($every == 2) {
                $i = $i + 7;
                $days = $days - 7;
            } elseif ($every == 3) {
                $j++;
                $days = $days - 30;
            } elseif ($every == 4) {
                $k++;
                $days = $days - 365;
            }
        }
        return $dateArray;
    }

    /*
     * Name:     	CheckEmail
     * Purpose:  	do the email address validation. 
     * @param    	$email - email address to be validated.
     * @return		bool
     */

    function CheckEmail($email) {
        if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
            return false;
        } else {
            return true;
        }
    }

    /*
     * Name:     	SpamCheck
     * Purpose:  	Filters a variable with a specified filter. 
     * @param    	$email - email address to be filtered.
     * @return		bool
     */

    function SpamCheck($email) {
        //filter_var() sanitizes the e-mail address using FILTER_SANITIZE_EMAIL
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        //filter_var() validates the e-mail address using FILTER_VALIDATE_EMAIL
        if (filter_var($email, FILTER_VALIDATE_EMAIL))
            return TRUE;
        else
            return FALSE;
    }

    /**
     * Truncate a UTF-8-encoded string safely to a number of bytes.
     *
     * If the end position is in the middle of a UTF-8 sequence, it scans backwards
     * until the beginning of the byte sequence.
     *
     * Use this function whenever you want to chop off a string at an unsure
     * location. On the other hand, if you're sure that you're splitting on a
     * character boundary (e.g. after using strpos() or similar), you can safely use
     * substr() instead.
     *
     * @param $string
     *   The string to truncate.
     * @param $len
     *   An upper limit on the returned string length.
     * @return
     *   The truncated string.
     */
    function TruncateBytes($string, $len) {
        if (strlen($string) <= $len) {
            return $string;
        }
        if ((ord($string[$len]) < 0x80) || (ord($string[$len]) >= 0xC0)) {
            return substr($string, 0, $len);
        }
        while (--$len >= 0 && ord($string[$len]) >= 0x80 && ord($string[$len]) < 0xC0) {
            
        };
        return substr($string, 0, $len);
    }

    /**
     * Encodes MIME/HTTP header values that contain non-ASCII, UTF-8 encoded
     * characters.
     *
     * For example, mime_header_encode('t&#65533;st.txt') returns "=?UTF-8?B?dMOpc3QudHh0?=".
     *
     * See http://www.rfc-editor.org/rfc/rfc2047.txt for more information.
     *
     * Notes:
     * - Only encode strings that contain non-ASCII characters.
     * - We progressively cut-off a chunk with truncate_utf8(). This is to ensure
     *   each chunk starts and ends on a character boundary.
     * - Using \n as the chunk separator may cause problems on some systems and may
     *   have to be changed to \r\n or \r.
     */
    function MimeHeaderEncode($string) {
        if (preg_match('/[^\x20-\x7E]/', $string)) {
            $chunk_size = 47; // floor((75 - strlen("=?UTF-8?B??=")) * 0.75);
            $len = strlen($string);
            $output = '';
            while ($len > 0) {
                $chunk = $this->TruncateBytes($string, $chunk_size);
                $output .= ' =?UTF-8?B?' . base64_encode($chunk) . "?=\n";
                $c = strlen($chunk);
                $string = substr($string, $c);
                $len -= $c;
            }
            return trim($output);
        }
        return $string;
    }

    /**
     * Send an e-mail message, using Drupal variables and default settings.
     * More information in the <a href="http://php.net/manual/en/function.mail.php">
     * PHP function reference for mail()</a>. See drupal_mail() for information on
     * how $message is composed.
     *
     * @param $message
     *  Message array with at least the following elements:
     *   - id
     *      A unique identifier of the e-mail type. Examples: 'contact_user_copy',
     *      'user_password_reset'.
     *   - to
     *      The mail address or addresses where the message will be sent to. The
     *      formatting of this string must comply with RFC 2822. Some examples are:
     *       user@example.com
     *       user@example.com, anotheruser@example.com
     *       User <user@example.com>
     *       User <user@example.com>, Another User <anotheruser@example.com>
     *   - subject
     *      Subject of the e-mail to be sent. This must not contain any newline
     *      characters, or the mail may not be sent properly.
     *   - body
     *      Message to be sent. Accepts both CRLF and LF line-endings.
     *      E-mail bodies must be wrapped. You can use drupal_wrap_mail() for
     *      smart plain text wrapping.
     *   - headers
     *      Associative array containing all mail headers.
     * @return
     *   Returns TRUE if the mail was successfully accepted for delivery,
     *   FALSE otherwise.
     */
    function EMail($message) {
        $mimeheaders = array();
        foreach ($message['headers'] as $name => $value) {
            $mimeheaders[] = $name . ': ' . $this->MimeHeaderEncode($value);
        }

        return mail(
                        $message['to'], $this->MimeHeaderEncode($message['subject']),
                        // Note: e-mail uses CRLF for line-endings, but PHP's API requires LF.
                        // They will appear correctly in the actual e-mail that is sent.
                        str_replace("\r", '', $message['body']),
                        // For headers, PHP's API suggests that we use CRLF normally,
                        // but some MTAs incorrecly replace LF with CRLF. See #234403.
                        join("\n", $mimeheaders)
        );
    }

    /*
     * Name:     	SendEmail
     * Purpose:  	To send the mail.
     * @param    	$to - email address to whom email will be sent
     * @param    	$subject - email subject
     * @param    	$body - email body
     * @param    	$from - from whom email is sent
     * @param    	$cc - email cc address
     * @param    	$ccc - email bcc address
     * @param    	$type - email type i.e. text/plain,text/html
     * @return		bool
     */

    function SendEmail($to, $subject, $body, $from="", $cc="", $bcc="", $type="text/plain") {
        global $dbAccess, $smarty;

        if ($from) {
            $fromMailcheck = $this->SpamCheck($from);
            if ($fromMailcheck == false) {
                $errorMsg[] = "Invalid From email address.";
                $smarty->assign("errorMsg", $errorMsg);
                return false;
            }
        }

        if ($to) {
            $toMailcheck = $this->SpamCheck($to);
            if ($toMailcheck == false) {
                $errorMsg[] = "Invalid To email address.";
                $smarty->assign("errorMsg", $errorMsg);
                return false;
            }
        }

        $user = $dbAccess->UserLoad("UserType = 'admin'");

        $defaultFrom = $user['UserEmail'];

        // Bundle up the variables into a structured array for altering.
        $message = array(
            'to' => $to,
            'from' => (isset($from) && $from != '') ? $from : $defaultFrom,
            'subject' => '',
            'body' => array()
        );


        // Build the default headers
        $headers = array(
            'MIME-Version' => '1.0',
            'Content-Type' => $type . '; charset=UTF-8; format=flowed; delsp=yes',
            'Content-Transfer-Encoding' => '8Bit',
            'X-Mailer' => PROJECT_NAME
        );

        if ($defaultFrom) {
            // To prevent e-mail from looking like spam, the addresses in the Sender and
            // Return-Path headers should have a domain authorized to use the originating
            // SMTP server. Errors-To is redundant, but shouldn't hurt.
            $headers['From'] = $headers['Reply-To'] = $headers['Sender'] = $headers['Return-Path'] = $headers['Errors-To'] = $defaultFrom;
        }
        if ($from) {
            $headers['From'] = $headers['Reply-To'] = $from;
        }
        $message['headers'] = $headers;

        $message['body'] = $body;
        $message['subject'] = $subject;

        // Optionally send e-mail.
        $message['result'] = $this->EMail($message);

        // Log errors
        if (!$message['result']) {
            $errorMsg[] = "Unable to send e-mail. Please contact the site admin, if the problem persists.";
            $smarty->assign("errorMsg", $errorMsg);
        }

        return $message['result'];
    }

    /**
     * give the current url of the site with querystring.
     *
     */
    function CurrPageURL() {
        $pageURL = 'http';
        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

    /**
     * Prepare a destination query string for use in combination with RedirectByJavascript().
     *
     * Used to direct the user back to the referring page after completing a form.
     * By default the current URL is returned. If a destination exists in the
     * previous request, that destination is returned. As such, a destination can
     * persist across multiple pages.
     *
     * @see RedirectByJavascript()
     */
    function GetDestination() {
        if (isset($_REQUEST['destination'])) {
            return 'destination=' . urlencode($_REQUEST['destination']);
        } else {
            return 'destination=' . urlencode(str_ireplace("/" . PROJECT_NAME, "", $_SERVER["REQUEST_URI"]));
        }
    }

    /**
     * This function will add the google map for multiple addresses
     * @param  $addresses
     */
    function InitializeGoogleMap($addresses, $width=500, $height=300, $markerIconColor = 'NAUTICA', $mapZoom = 12) {
        $googleMap = "";

        if (is_array($addresses) && count($addresses) > 0) {
            include_once(SITE_PATH . '/include/google_map.php');

            $gm = & new EasyGoogleMap(GOOGLE_KEY);

            $gm->SetMapWidth($width);
            $gm->SetMapHeight($height);
            $gm->SetMarkerIconStyle('GT_FLAT');
            $gm->SetMapZoom($mapZoom);
            $gm->mContinuousZoom = TRUE;
            $gm->mMapType = TRUE;
            for ($a = 0; $a < count($addresses); $a++) {
                $gm->SetAddress($addresses[$a]['location']);
                $gm->SetInfoWindowText($addresses[$a]['html']);
                $gm->SetCountry($addresses[$a]['country'], $addresses[$a]['location']);
            }
            $googleMap.=$gm->GmapsKey();
            $googleMap.=$gm->MapHolder();
            $googleMap.=$gm->InitJs();
            $googleMap.=$gm->UnloadMap();
        }
        return $googleMap;
    }

    /**
     * Sending Push Notification
     */
    function send_notification($registatoin_ids, $message) {
        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';

        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $message,
        );
        
        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = json_decode(curl_exec($ch));
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);
       return array($result);
    }

    /**
     * This function will return the longitude and latitude of a give address
     * @param  $location  i.e array("city"=>"ahmedabad", "street"=>"ghodasar", "state"=>"gujarat");
     * @param  $country  i.e "India";
     */
    function FileLatitudeLongitudeFromAddress($location, $country) {
        ini_set("max_execution_time", "200");

        if ($location['street'])
            $output = implode("%20", explode(" ", $location['street'])) . '+';
        if ($location['city'])
            $output .= implode("%20", explode(" ", $location['city'])) . '+';
        if ($location['state'])
            $output .= implode("%20", explode(" ", $location['state'])) . '+';

        $country = ($country) ? implode("%20", explode(" ", $country)) : '';

        $gmapUrl = "http://maps.google.com/maps/geo?q=" . $output . $country . "&output=csv";

        $content = explode(",", file_get_contents("$gmapUrl"));

        //maps.google.com return a content with csv form, which is seperated with ",",
        //the 2-th parameter is the latitude, the 3-th parament is longitude.
        return array("latitude" => $content[2], "longitude" => $content[3]);
    }

    /* ::  This routine calculates the distance between two points (given the     : */
    /* ::  latitude/longitude of those points). It is being used to calculate     : */
    /* ::  the distance between two ZIP Codes or Postal Codes using our           : */
    /* ::  ZIPCodeWorld(TM) and PostalCodeWorld(TM) products.                     : */
    /* ::                                                                         : */
    /* ::  Definitions:                                                           : */
    /* ::    South latitudes are negative, east longitudes are positive           : */
    /* ::                                                                         : */
    /* ::  Passed to function:                                                    : */
    /* ::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  : */
    /* ::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  : */
    /* ::    unit = the unit you desire for results                               : */
    /* ::           where: 'M' is statute miles                                   : */
    /* ::                  'K' is kilometers (default)                            : */
    /* ::                  'N' is nautical miles                                  : */
    /* ::  United States ZIP Code/ Canadian Postal Code databases with latitude & : */
    /* ::  longitude are available at http://www.zipcodeworld.com                 : */
    /* ::	echo Distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";	: */
    /* ::	echo Distance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";	: */
    /* ::	echo Distance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>";	: */
    /* ::                                                                         : */

    function Distance($lat1, $lon1, $lat2, $lon2, $unit) {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    /*
     * Function strallpos 
     * Returns all positions of the search term in the  
     * string as an array 
     */

    function strallpos($haystack, $needle, $offset = 0) {
        $result = array();

        // Loop through the $haystack/string starting at offset 
        for ($i = $offset; $i < strlen($haystack); $i++) {
            $pos = strpos($haystack, $needle, $i);
            if ($pos !== FALSE) {
                $offset = $pos;
                if ($offset >= $i) {
                    $i = $offset;

                    // Add found results to return array 
                    $result[] = $offset;
                }
            }
        }
        return $result;
    }

    /*
     * Function mailing for Send Email
     */

    function mailing($query_mail, $row) {
        global $dbAccess, $smarty;
        require_once COMMON_CORE_PATH . "/mailer.class.php";
        $m = new mailer($dbAccess);
        $res = $m->send_email(
                $query_mail, array(
            "vars" => $row['mail_vars'],
            "to" => array(
                array(
                    "email" => $row["recipient_email"],
                    "name" => $row["recipient_name"],
                )
            ),
            "cc" => array(
                array(
                    "email" => $row["cc_email"],
                    "name" => $row["cc_name"],
                )
            ),
            "mail_body" => $row['mail_body'],
            "attach" => isset($row["attach"]) ? $row["attach"] : "",
            "images" => isset($row["images"]) ? $row["images"] : ""
                )
        );
    }

    /*
     * Function parseLetterTemplate to View Letter template before sending mail
     */

    function parseLetterTemplate($template_name, $param=NULL) {
        global $dbAccess;
        $this->db = $dbAccess;
        $data = $this->db->SimpleQuery("SELECT * FROM " . TBL_LETTER_TEMPLATES . " WHERE letter_pseudo_name=\"" . $template_name . "\"");

        $tmpl_data = $data[0];
        if (count($tmpl_data) > 0) {
            if (isset($param["mail_vars"])) {
                $vars = $param["mail_vars"];
            } else {
                $vars = array();
            }

            $message = $this->srepltags_array($vars, $tmpl_data["body"]);
            return $message;
        } else {
            return false;
        }
    }

    /**
     * ����������� �������� ���������� � ���������� �� �������
     *
     * @param mixed ����������
     * @param string ������ � �����������
     * @return string ������ � ����������� � �� ����������
     */
    public function srepltags_array($arr, $str) {
        if (is_array($arr)) {
            reset($arr);
            $keys = array_keys($arr);
            array_walk($keys, create_function('&$val', '$val = "[$val]";'));
            $vals = array_values($arr);
            return ereg_replace("[([0-9A-Za-z\_\s\-]+)]", "", str_replace($keys, $vals, $str));
        } else {
            return $str;
        }
    }

    public function GetQuerystringValue($URL, $VAR_Name) {
        $qArray = explode("?", $URL);

        if (is_array($qArray)) {
            $VAR_Array = explode("&", $qArray[1]);

            $total_vars = count($VAR_Array);
            $i = 0;
            if ($total_vars > 0) {
                while ($i < $total_vars) {
                    $VAR_VALUE = explode("=", $VAR_Array[$i]);
                    if ($VAR_VALUE[0] == $VAR_Name)
                        $pageName = $VAR_VALUE[1];
                    $i++;
                }
            }
        }
        return $pageName;
    }

    /*     * *************************************************************
     * Name      :   CheckEmpty
     * Purpose   :   To check that anyfiled has been null or not
     *               [ i.e ServerSide validation ].
     * Creadted  :   25-03-2011
     * @param     $field_array - Filed Array whichever you want
     *               to check
     * @return       Error Message array
      Added by Hardik
     * ************************************************************* */

    function CheckEmpty($field_array) {
        for ($i = 0; $i < count($field_array); $i++) {
            $explode_array = explode("~~", $field_array[$i]);
            if (trim($explode_array[0]) == '')
                $errorMsg[] = $explode_array[1];
        }
        return $errorMsg;
    }

    function send_resume($type, $html, $file_name, $path=UPLOADED_STUFF_PATH) {
        switch ($type) {
            case 'doc':
            case 'rtf':
            case 'html':
                $fp = fopen($path . '/' . $file_name . '.' . $type, 'w');
                fwrite($fp, $html);
                fclose($fp);
                break;

            case 'pdf':
                $mpdf = new mPDF();
                $mpdf->WriteHTML($html);
                $mpdf->Output($path . '/' . $file_name . '.' . $type, 'F');
                break;
        }
    }

    // Validate Forms on Server Side
    function validate_form($postedData, $validateData) {
        $error_messages = array();
        foreach ($validateData as $k => $d) {
            foreach ($d as $k1 => $d1) {
                $postedData[$k1] = str_replace('&nbsp;', ' ', $postedData[$k1]);
                if ($k == 'required' && trim($postedData[$k1]) == '') {
                    $error_messages[] = $d1;
                } elseif ($postedData[$k1] != '') {
                    if ($k == 'email' && !$this->CheckEmail($postedData[$k1])) {
                        $error_messages[] = $d1;
                    } elseif ($k == 'numeric' && !is_numeric($postedData[$k1])) {
                        $error_messages[] = $d1;
                    }
                }
            }
        }
        return $error_messages;
    }

    /*
     * Function to get all languages
     */

    function getSiteLanguages() {
        global $dbAccess;
        $this->db = $dbAccess;
        $data = $this->db->SimpleQuery("SELECT * FROM " . TBL_LANGUAGES . " WHERE status='1' ORDER BY language_name");

        return $data;
    }

    /*
     * Function to get all user languages
     */

    function getUserLanguages() {
        global $dbAccess;
        $this->db = $dbAccess;
        $data = $this->db->SimpleQuery("SELECT * FROM " . TBL_LANGUAGES_USER . " WHERE status='1' ORDER BY language_name");

        return $data;
    }

    /*
     * Function to check user login
     */

    function check_user_login($last_page='') {
        global $loggedin_userid;
        if ($loggedin_userid == '') {
            if ($last_page != '') {
                $querstr = "&last_page=" . $last_page;
            }
            header("location: ./?page=login" . $querstr);
            exit;
        }
    }

    /*
     * Function to get all countries
     */

    function getSiteCountries() {
        global $dbAccess;
        $this->db = $dbAccess;
        $data = $this->db->SimpleQuery("SELECT * FROM " . TBL_COUNTRIES . " WHERE status='1' ORDER BY country_name");

        return $data;
    }

    /*
     * Function to get all brand types
     */

    function getAllBrandType() {
        global $dbAccess;
        $this->db = $dbAccess;
        $data = $this->db->SimpleQuery("SELECT * FROM " . TBL_BRAND_TYPE . " WHERE status='1' ORDER BY name");

        return $data;
    }

    /*
     * Function to get all contact methods
     */

    function getAllContactMethods() {
        global $dbAccess;
        $this->db = $dbAccess;
        $data = $this->db->SimpleQuery("SELECT * FROM " . TBL_CONTACT_METHODS . " WHERE status='1' ORDER BY name");

        return $data;
    }

    /*
     * Function to get all Commission Types
     */

    function getAllCommissionTypes() {
        global $dbAccess;
        $this->db = $dbAccess;
        $data = $this->db->SimpleQuery("SELECT * FROM " . TBL_COMMISSION_TYPE . " WHERE status='1' ORDER BY name");

        return $data;
    }

    /*
     * Function to get all Api methods
     */

    function getAllApiMethods() {
        global $dbAccess;
        $this->db = $dbAccess;
        $data = $this->db->SimpleQuery("SELECT * FROM " . TBL_API_METHODS . " WHERE status='1' ORDER BY name");

        return $data;
    }

    //Select all clients
    public function selectAllClients() {
        $query = "SELECT * FROM " . TBL_CLIENTS . " ORDER BY name";
        $allClientsList = $this->db->SimpleQuery($query, "", "", false);

        return $allClientsList;
    }

    //Select all Brands
    public function selectAllBrands() {
        $query = "SELECT * FROM " . TBL_BRANDS . " ORDER BY brand_type";
        $allClientsList = $this->db->SimpleQuery($query, "", "", false);

        return $allClientsList;
    }

    //Select all clients
    public function getUserInformationById($userid) {
        $userInformation = $this->db->SimpleOneQuery("SELECT * FROM " . TBL_USERS . " WHERE id=" . $userid);
        return $userInformation;
    }

    //Select all clients
    public function getManagerPermissionById($userid) {
        $managerPermissions = $this->db->SimpleOneQuery("SELECT * FROM " . TBL_USER_MANAGER_ACCESS . " WHERE user_id=" . $userid);
        return $managerPermissions;
    }

}
?>