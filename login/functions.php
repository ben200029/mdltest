<?php
/**
*  Redirect url
*/
function Leave($url) {
    header("Location: $url");exit;
}
/**
 * Clean String
 * @param type $string
 * @return type
 */
function cleanString($string) {
   $string = str_replace(' ', '', $string); // Replaces all spaces with empty.
   $string = str_replace('-', '', $string); // Replaces all hyphens with empty.

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

/**
*  Encode given text to hash using md5
*/
function encode_password($password) {
    return md5($password);
}

/**
 * Generates random string.
 *
 * @param int $length
 * @return string
 */
function str_random($length = 16)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $randomString;
}

/**
*  Generate a token string
*
* @return $str
*/
function generateTokenString(){
   // generate as random of a token as possible for lower PHP versions
   $str = sha1(uniqid(sha1(PASSWORD_SALT), true) . time() . str_random(20));
   
   return $str;
}
function escape($str){
    switch (gettype($str))
    {
        case 'string' : $str = addslashes(stripslashes($str));
        break;
        case 'boolean' : $str = ($str === FALSE) ? 0 : 1;
        break;
        default : $str = ($str === NULL) ? 'NULL' : $str;
        break;
    }

    return $str;
}
function Clean($str) {
    if (is_array($str)) {
        $return = array();
        foreach ($str as $k => $v) {
            $return[Clean($k)] = Clean($v);
        }
        return $return;
    } else {
        $str = @trim($str);
        if (get_magic_quotes_gpc()) {
            $str = stripslashes($str);
        }
        return mres($str);
    }
}
function CleanXSS($str) {
    if (is_array($str)) {
        $return = array();
        foreach ($str as $k => $v) {
            $return[CleanXSS($k)] = CleanXSS($v);
        }
        return $return;
    } else {
        $str = @trim($str);
        $str = preg_replace('#<script(.*?)>(.*?)</script(.*?)>#is', '', $str);
        $str = preg_replace('#<style(.*?)>(.*?)</style(.*?)>#is', '', $str);
        if (get_magic_quotes_gpc()) {
            $str = stripslashes($str);
        }
        return mres($str);
    }
}
function mres($value) {
    $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
    $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");

    //return str_replace($search, $replace, $value);
    return $value;
}
function remove_accent($str) {
    $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', '‘');
    $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', '\'');
    return str_replace($a, $b, $str);
}
/**
*   Return page url
*/
function thisUrl(){
    if(isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    }
    else{
        $protocol = 'http';
    }
    return $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}
/**
*   Return Base url
*/
function baseUrl(){
    if(isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    }
    else{
        $protocol = 'http';
    }
    return $protocol . "://" . $_SERVER['HTTP_HOST'] . "/dev/login";
}
/**
 * Get logged in user INFO
 * @return boolean
 */
function getThisUserInfo(){
    if(isset($_SESSION['user'])){
        return $_SESSION['user'];
    } else {
        return FALSE;
    }
}
/**
 * Get Logged in user ID
 * @return boolean
 */
function getThisUserID(){
    if(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != ''){
        return $_SESSION['user']['id'];
    } else {
        return FALSE;
    }
}
/**
 * Is user has given role
 * @param type $db
 * @param type $role
 * @return boolean
 */
function isUser($db, $role){
    $userID = $_SESSION['user']['id'];    
    $query = "SELECT r.Guid_role, r.role FROM tblrole r LEFT JOIN tbluser u ON r.Guid_role = u.Guid_role WHERE u.Guid_user = " . $userID;
    $userInfo = $db->row($query);
    if( $userInfo['role'] == $role ){
        return TRUE;
    }    
    return FALSE;  
}
/**
 * Deactivate User by user id 
 * @param type $db
 * @param type $Guid_user
 * @return boolean
 */
function deactivateUser($db, $Guid_user){
    updateTable($db, 'tbluser', array('status'=>'0'), array('Guid_user'=>$Guid_user));
    return TRUE;
}
/**
 * Delete User By given ID
 * @param type $userID
 * @return boolean
 */
function deleteUserByID($db, $userID){
    deleteByField($db, 'tbladmins','Guid_user', $userID);
    deleteByField($db, 'tblsalesrep','Guid_user', $userID);
    deleteByField($db, 'tblprovider','Guid_user', $userID);
    deleteByField($db, 'tblpatient','Guid_user', $userID);
    //delet from users table
    deleteByField($db, 'tbluser','Guid_user', $userID);    
    return TRUE;
}
/**
 * Get logged in user name, if not found take user type
 * used for menu to show "Welcome, username" message
 * @param type $db
 * @param type $userID
 * @return type
 */
function getUserName($db, $userID){    
    $query = "SELECT * FROM tblrole r LEFT JOIN tbluser u ON r.Guid_role = u.Guid_role WHERE u.Guid_user = " . $userID;
    $userInfo = $db->row($query);
    
    if($userInfo['role']=='Patient'){
        $query = "SELECT aes_decrypt(firstname_enc, 'F1rstn@m3@_%') as firstname FROM `tblpatient` WHERE Guid_user=:Guid_user";
        $patientInfo = $db->row($query, array("Guid_user"=>$userInfo['Guid_user']));
        
        if($patientInfo){
            $result = $patientInfo['firstname'];
        } else {
            $result = $_SESSION['user']['type'];
        }
    } elseif ($userInfo['role']=='Sales Rep' || $userInfo['role']=='Sales Manager') {
        $query = "SELECT first_name FROM `tblsalesrep` WHERE Guid_user=:Guid_user";
        $salesrepInfo = $db->row($query, array("Guid_user"=>$userInfo['Guid_user']));
        if($salesrepInfo){
            $result = $salesrepInfo['first_name'];
        } else {
            $result = $_SESSION['user']['type'];
        }
    } elseif ($userInfo['role']=='Physician') {
        $query = "SELECT first_name FROM `tblprovider` WHERE Guid_user=:Guid_user";
        $providerInfo = $db->row($query, array("Guid_user"=>$userInfo['Guid_user']));
        if($providerInfo){
            $result = $providerInfo['first_name'];
        } else {
            $result = $_SESSION['user']['type'];
        }
    } elseif ($userInfo['role']=='Admin') {
        $query = "SELECT first_name FROM `tbladmins` WHERE Guid_user=:Guid_user";
        $providerInfo = $db->row($query, array("Guid_user"=>$userInfo['Guid_user']));
        if($providerInfo){
            $result = $providerInfo['first_name'];
        } else {
            $result = $_SESSION['user']['type'];
        }
    }
    else {
        $result = $_SESSION['user']['type'];
    }
    
    return $result;
}

function getUserFullInfo($db, $userID){    
    $query = "SELECT * FROM tblrole r LEFT JOIN tbluser u ON r.Guid_role = u.Guid_role WHERE u.Guid_user = $userID";
    
    $userInfo = $db->row($query);
    $result = "";
    
    if ($userInfo['role']=='Sales Rep' || $userInfo['role']=='Sales Manager') {
        $query = "SELECT * FROM `tblsalesrep` WHERE Guid_user=:Guid_user";
        $salesrepInfo = $db->row($query, array("Guid_user"=>$userID));
        if(!empty($salesrepInfo)){
            $result = $salesrepInfo;
        }
    } elseif ($userInfo['role']=='Physician') {
        $query = "SELECT * FROM `tblprovider` WHERE Guid_user=:Guid_user";
        $providerInfo = $db->row($query, array("Guid_user"=>$userID));
        if(!empty($providerInfo)){
            $result = $providerInfo;
        }
    } elseif ($userInfo['role']=='Admin') {
        $query = "SELECT * FROM `tbladmins` WHERE Guid_user=:Guid_user";
        $providerInfo = $db->row($query, array("Guid_user"=>$userID));
        if(!empty($providerInfo)){
            $result = $providerInfo;
        }
    } else {
        
        $query = "SELECT *, aes_decrypt(firstname_enc, 'F1rstn@m3@_%') AS first_name, aes_decrypt(lastname_enc, 'L@stn@m3&%#') AS last_name FROM `tblpatient` WHERE Guid_user=:Guid_user";
        $patientInfo = $db->row($query, array("Guid_user"=>$userID));
        if(!empty($patientInfo)){
            $result = $patientInfo;
        }
    }
    
    return $result;
}
/**
 * Check if current user has access to the fields
 * All configurations doing from Access Roles page
 * @param type $db
 * @param type $accessKey
 * @param type $userID
 * @return boolean
 */
function isUserHasAccess($db, $accessKey, $userID){
    $role = getRole($db, $userID);    
    $roleKey = $role['role'];
    $roleID = $role['Guid_role'];    
    if($roleKey == 'Admin'){
        return TRUE;
    }    
    $query = "SELECT * FROM `tbl_mdl_options` WHERE key_id=:key";
    $accessRole = $db->row($query, array("key"=>$accessKey));
    if(isset($accessRole['value'])){
        $accessRoleIDs = unserialize($accessRole['value']);
        $accessRoleIDs = explode(';', $accessRoleIDs['ids']);
        if(in_array($roleID, $accessRoleIDs)){
            return TRUE;
        } else {
            return FALSE;
        }        
    }
}

function isCheckedRoleTableCheckbox($tableID, $fieldKey, $roelID, $action){    
    $accessRole = getAccessRoleByKey($tableID);    
    $roleIds = unserialize($accessRole['value']);
    //var_dump($roleIds);
    $ids = array();
    if($roleIds) {  
        
        if(isset($roleIds[$fieldKey][$action])){           
            $ids[$action] = explode(";",$roleIds[$fieldKey][$action]);
        } 
    } 
    
    if($ids){
        if(in_array($roelID, $ids[$action])){
            return " checked";
        } 
    }    
    return FALSE;  
}

function isChecked($pageKey, $roelID, $action){    
    $accessRole = getAccessRoleByKey($pageKey); 
    $roleIds = unserialize($accessRole['value']);   
    $ids = array();
    if($roleIds) {     
        if(isset($roleIds[$action])){
            $ids[$action] = explode(";",$roleIds[$action]);
        } 
    }    
    if($ids){
        if(in_array($roelID, $ids[$action])){
            return " checked";
        } 
    }    
    return "";  
}
function isUserHasAnyAccess($data, $thisUserRole, $action) {
    if($thisUserRole==1){
        return TRUE;
    }
    $i = 0;
    if($data){
        foreach ($data as $k=>$v){
            if(stripos($v[$action], $thisUserRole) !== false){
               $i++;
            }        
        }    
    }
    if($i>0){
        return TRUE;
    }
    return FALSE;
}
function isFieldVisibleByRole($data, $thisUserRole){
    if($thisUserRole==1){
        return TRUE;
    }
    if(stripos($data, $thisUserRole) !== false){
        return TRUE;
    }
    return FALSE;
}

function saveTableAccessRole($db, $data){
    extract($data); 
    $actions = array('view', 'add', 'edit', 'delete');
    foreach ($data as $tableKey => $tableFields){
        $saveData = array(); 
        foreach ($tableFields as $fieldK=>$fieldV){
            foreach ($actions as $k=>$action){
                $roleIds= "";
                if(isset($fieldV[$action]) && !empty($fieldV[$action])){
                    foreach ($fieldV[$action] as $k=>$v) {
                        $roleIds .= $k.";";
                    }
                }
                $saveData[$fieldK][$action] = rtrim($roleIds, ';');
            }
        }            
        if( getAccessRoleByKey($tableKey) ){ 
            //update option
            $updateData = array('value'=>serialize($saveData), 'type'=>'table');
            updateTable($db,'tbl_mdl_options', $updateData, array('key_id'=>$tableKey));         

        }else{ 
            //insert option
            $insertData = array('key_id'=>$tableKey, 'value'=>serialize($saveData), 'type'=>'table');
            insertIntoTable($db, 'tbl_mdl_options', $insertData);
        } 
    }
    
}
function savePageAccessRole($db, $data){
    extract($data);   
    $actions = array('view', 'add', 'edit', 'delete');
    foreach ($data as $pageKey => $accessData){
        if(!empty($accessData)){
            $saveData = array(); 
            foreach ($actions as $k=>$action){
                $roleIds= "";
                if(isset($accessData[$action]) && !empty($accessData[$action])){
                    foreach ($accessData[$action] as $k=>$v) {
                        $roleIds .= $k.";";
                    }
                }
                $saveData[$action] = rtrim($roleIds, ';');
            }        
            if( getAccessRoleByKey($pageKey) ){ 
                //update option
                $updateData = array('value'=>serialize($saveData), 'type'=>'page');
                updateTable($db,'tbl_mdl_options', $updateData, array('key_id'=>$pageKey));         

            }else{ 
                //insert option
                $insertData = array('key_id'=>$pageKey, 'value'=>serialize($saveData), 'type'=>'page');
                insertIntoTable($db, 'tbl_mdl_options', $insertData);
            } 
        }
    }   
}
function getOption($db, $key){
    $query = "SELECT * FROM `tbl_mdl_options` WHERE key_id=:key_id";
    $result = $db->row($query, array('key_id'=>$key));
    
    return $result;    
}
function setOption($db, $key, $val, $type='page'){
    //check if key exists
    $checkKey = getOption($db, $key);
    if($checkKey){ //update existing
        $where = array('key_id'=>$key);
        updateTable($db, 'tbl_mdl_options', array('key_id'=>$key,'value'=>$val,'type'=>$type), $where);
    } else { //insert new key and value
        insertIntoTable($db, 'tbl_mdl_options', array('key_id'=>$key,'value'=>$val,'type'=>$type));
    }
}

function getUsersAndRoles($db){
    $query = "SELECT u.*, r.* FROM `tbluser` u
                LEFT JOIN `tblrole` r ON r.Guid_role=u.Guid_role";
    $users = $db->query($query);
    return $users;
}
function getUserAndRole($db, $userID){
    $query = "SELECT u.*, r.* FROM `tbluser` u
                LEFT JOIN `tblrole` r ON r.Guid_role=u.Guid_role 
                WHERE u.Guid_user=:Guid_user";    
    $user = $db->row($query, array("Guid_user"=>$userID));
    
    return $user;
}

function getRole($db, $userID){
    $query = "SELECT r.Guid_role, r.role FROM tblrole r "
            . "LEFT JOIN tbluser u ON r.Guid_role = u.Guid_role "
            . "WHERE u.Guid_user=:Guid_user";
   
    $result = $db->row($query, array("Guid_user"=>$userID));
    return $result;
}
function getUserDetails($db, $userRole, $userID, $patientID=""){
    $userDetail = "";
    $q = "";
    if($userRole == 'Admin'){
        $q = "SELECT first_name, last_name, photo_filename FROM tbladmins";
    } elseif ($userRole == 'Sales Rep' || $userRole == 'Sales Manager') {
        $q = "SELECT first_name, last_name, photo_filename FROM tblsalesrep";
    } elseif ($userRole == 'Physician') {
        $q = "SELECT first_name, last_name, photo_filename FROM tblprovider";    
    } elseif ($userRole == 'Patient' || $userRole == 'MDL Patient') {
        $q = "SELECT aes_decrypt(firstname_enc, 'F1rstn@m3@_%') as first_name, aes_decrypt(lastname_enc, 'L@stn@m3&%#') as last_name FROM tblpatient";
    }  
    
    if($q!=""){        
        $q .= " WHERE Guid_user=:Guid_user";
        $userDetail = $db->row($q, array("Guid_user"=>$userID));
    } else {//when patient is not exists on users table
        if($patientID!=""){
            $q = "SELECT aes_decrypt(firstname_enc, 'F1rstn@m3@_%') as first_name, aes_decrypt(lastname_enc, 'L@stn@m3&%#') as last_name FROM tblpatient  WHERE Guid_patient=:Guid_patient";
            $userDetail = $db->row($q, array("Guid_patient"=>$patientID));
        }
    }
    
    return $userDetail;    
}

function saveUserDetails($db, $Guid_user, $Guid_role, $userDetails){
    
    if($Guid_role=='1'){//admin
        $userQ = "SELECT Guid_user FROM tbladmins WHERE Guid_user=:Guid_user";
        $isUserExists = $db->row($userQ, array('Guid_user'=>$Guid_user));
        if($isUserExists){
            updateTable($db, 'tbladmins', $userDetails, array('Guid_user'=>$Guid_user));
        }else{
            $userDetails['Guid_user'] = $Guid_user;
            insertIntoTable($db, 'tbladmins', $userDetails);
        }
    } elseif ($Guid_role=='2') {//provider(Physician)
        $userQ = "SELECT Guid_user FROM tblprovider WHERE Guid_user=:Guid_user";
        $isUserExists = $db->row($userQ, array('Guid_user'=>$Guid_user));
        if($isUserExists){
            updateTable($db, 'tblprovider', $userDetails, array('Guid_user'=>$Guid_user));
        }else{
            $userDetails['Guid_user'] = $Guid_user;
            insertIntoTable($db, 'tblprovider', $userDetails);
        }        
    } elseif ($Guid_role=='3' || $Guid_role=='6') {//patient or mdl patient
        $userQ = "SELECT Guid_user FROM tblpatient WHERE Guid_user=:Guid_user";
        $isUserExists = $db->row($userQ, array('Guid_user'=>$Guid_user));
        if($isUserExists){
            $fname = $userDetails['firstname_enc'];
            $lname = $userDetails['lastname_enc'];
            
            $query = "UPDATE `tblpatient` SET firstname_enc=AES_ENCRYPT('".$fname."','F1rstn@m3@_%'), lastname_enc=AES_ENCRYPT('".$lname."', 'L@stn@m3&%#') WHERE `Guid_user`=$Guid_user";
            $update = $db->query($query);
        }else{
            $userDetails['Guid_user'] = $Guid_user;
            insertIntoTable($db, 'tblpatient', $userDetails);
        } 
    } elseif ($Guid_role=='4' || $Guid_role=='5') {//salesrep & salesmgr
        $userQ = "SELECT Guid_user FROM tblsalesrep WHERE Guid_user=:Guid_user";
        $isUserExists = $db->row($userQ, array('Guid_user'=>$Guid_user));
        if($isUserExists){
            updateTable($db, 'tblsalesrep', $userDetails, array('Guid_user'=>$Guid_user));
        }else{
            $userDetails['Guid_user'] = $Guid_user;
            insertIntoTable($db, 'tblsalesrep', $userDetails);
        } 
    }
    
}
/**
 * Move user to proper table if user role is changed
 * @param type $db
 * @param type $thisRole
 * @param type $prevRole
 */
//this function under development
function moveUserData($db, $Guid_user, $userDetails, $thisRole, $prevRole){
    //we'll work only with users Admin, Sales Rep and Sales Manager (Guid_role -> 1, 4, 5)    
    $fName = (isset($userDetails['first_name'])) ? $userDetails['first_name']: "";
    $lName = (isset($userDetails['last_name'])) ? $userDetails['last_name']: "";
    $filename = (isset($userDetails['photo_filename'])) ? $userDetails['photo_filename']: "";
    
    if($thisRole != $prevRole){        
        if($thisRole=='1'){//Admin
            //we need to move sales rep to admin table
            $salesrepQ = "SELECT * FROM tblsalesrep WHERE Guid_user=:Guid_user";
            $getSalserep = $db->row($salesrepQ, array('Guid_user'=>$Guid_user));           
            
            $adminData = array(
                'Guid_user' => $Guid_user,
                'first_name' => ($fName !="") ? $fName:$getSalserep['first_name'],
                'last_name' => ($lName !="") ? $lName:$getSalserep['last_name'],
                'photo_filename' => ($filename !="") ? $filename:$getSalserep['photo_filename'],
                'phone_number' => $getSalserep['phone_number'],
                'address' => $getSalserep['address'],
                'city' => $getSalserep['city'],
                'state' => $getSalserep['state'],
                'zip' => $getSalserep['zip']                
            );
            $insert = insertIntoTable($db, 'tbladmins', $adminData);
            
            if(isset($insert['insertID'])){
                $Guid_salesrep = $getSalserep['Guid_salesrep'];
                deleteByField($db, 'tblsalesrep', 'Guid_salesrep', $Guid_salesrep);
            }
            
            return $insert;
        } elseif ($thisRole=='4' || $thisRole=='5') {//Sales Rep OR Sales Manager
           
            if($prevRole == '4' || $prevRole == '5'){ 
               
                // need just change role, don't need of moving data
                $is_manager = ($thisRole=='5') ? '1' : '0'; //1-sales Mgr and 0-Sales Rep
                updateTable($db, 'tblsalesrep', array('is_manager'=>$is_manager), array('Guid_user'=>$Guid_user));
                return TRUE;
            } else { //we need to move admin to salesrep table
               
                $adminQ = "SELECT * FROM tbladmins WHERE Guid_user=:Guid_user";
                $getAdmin = $db->row($adminQ, array('Guid_user'=>$Guid_user));               

                $salesrepData = array(
                    'Guid_user' => $Guid_user,
                    'first_name' => ($fName !="") ? $fName:$getAdmin['first_name'],
                    'last_name' => ($lName !="") ? $lName:$getAdmin['last_name'],
                    'photo_filename' => ($filename !="") ? $filename:$getAdmin['photo_filename'],
                    'phone_number' => $getAdmin['phone_number'],
                    'address' => $getAdmin['address'],
                    'city' => $getAdmin['city'],
                    'state' => $getAdmin['state'],
                    'zip' => $getAdmin['zip']                
                );
                if($thisRole=='5'){
                    $salesrepData['is_manager'] = '1';
                }

                $insert = insertIntoTable($db, 'tblsalesrep', $salesrepData);
                if(isset($insert['insertID'])){                
                    $Guid_admin = $getAdmin['Guid_admin'];
                    deleteByField($db, 'tbladmins', 'Guid_admin', $Guid_admin);
                }
                return $insert;
            }
            
        }        
    }
    return FALSE;
}

function verify_input(&$error) {	
	if (strlen($_POST['from_date']) && (!strlen($_POST['to_date']))) {
		$error['to_date'] = 1;
	} elseif ((!strlen($_POST['from_date'])) && strlen($_POST['to_date'])) {
		$error['from_date'] = 1;
	} elseif (strlen($_POST['from_date']) && strlen($_POST['to_date']) && (strtotime($_POST['to_date']) < strtotime($_POST['from_date']))) {		
		$error['from_date'] = 1;
		$error['to_date'] = 1;
	}
}
function getUrlConfigurations($db, $userID){ 
    $query = "SELECT 
                    tblurlconfig.id, tblurlconfig.Guid_user, tblurlconfig.geneveda, tblurlconfig.pin, 
                    tblaccount.*,  
                    tblsource.description, tblsource.Guid_source, tblsource.code, 
                    tbldevice.deviceid AS device_id,   
                    tbldeviceinv.serial_number As serial_number                 
                    FROM tblurlconfig 
                    LEFT JOIN `tblaccount` ON tblurlconfig.account = tblaccount.Guid_account
                    LEFT JOIN `tblsource` ON tblurlconfig.location = tblsource.code
                    LEFT JOIN `tbldevice` ON tblurlconfig.device_id = tbldevice.deviceid
                    LEFT JOIN `tbldeviceinv` ON tbldevice.deviceid  = tbldeviceinv.deviceid
                    WHERE tblurlconfig.Guid_user=:Guid_user 
                    ORDER BY tblurlconfig.id DESC LIMIT 5";
    $urlConfigs = $db->query($query, array("Guid_user"=>$userID));
 
    return $urlConfigs;
}

function get_active_providers($db, $field, $id){
    $queryProviders = "SELECT * FROM tblprovider WHERE $field=:id";
    $providers = $db->query($queryProviders, array("id"=>$id));
    return $providers;
}
function get_provider_info($db, $provider_guid){
    $queryProvider = "SELECT * FROM tblprovider WHERE Guid_provider=:id";
    $provider = $db->row($queryProvider, array("id"=>$provider_guid));
    return $provider;
}
function get_provider_user_info($db, $provider_guid){
    $queryProvider = "SELECT p.*, u.Guid_user, u.email AS provider_email, u.password AS provider_password FROM tblprovider p 
                        LEFT JOIN tbluser u ON p.Guid_user=u.Guid_user
                        WHERE Guid_provider=:id";
    $provider = $db->row($queryProvider, array("id"=>$provider_guid));
    return $provider;
}
function get_row($db, $table, $where=1){
    $query = "SELECT * FROM $table $where";
    $row = $db->query($query);
    return $row;
}
function getDevicesBySalesrepID($db,$Guid_salesrep){
    $query = "SELECT tbldevice.*, tbldeviceinv.* FROM tbldevice "
                        . " LEFT JOIN `tbldeviceinv` ON tbldevice.deviceid  = tbldeviceinv.deviceid"
                        . " WHERE url_flag = :url_flag"
                        . " AND Guid_salesrep =:Guid_salesrep"
                        . " ORDER BY tbldeviceinv.serial_number DESC";
    $result = $db->query($query, array("url_flag"=>'1', "Guid_salesrep"=>$Guid_salesrep));
    return $result;
}
function getDevicesWithSalesRepInfo($db, $flag=FALSE){
    $query = "SELECT "
                    . "tbldevice.device_name, "
                    . "tbldeviceinv.id, tbldeviceinv.deviceid, tbldeviceinv.serial_number, tbldeviceinv.comment, tbldeviceinv.inservice_date, tbldeviceinv.outservice_date, "
                    . "tblsalesrep.first_name, tblsalesrep.last_name "
                    . "FROM tbldevice LEFT JOIN `tbldeviceinv` "
                    . "ON tbldevice.deviceid = tbldeviceinv.deviceid "
                    . "LEFT JOIN `tblsalesrep` "
                    . "ON tbldeviceinv.Guid_salesrep = tblsalesrep.Guid_salesrep ";    
    if($flag){
        $query.= "WHERE url_flag = :url_flag ";
    }
    $query.= "ORDER BY tblsalesrep.first_name, tblsalesrep.last_name, tbldevice.device_name, tbldeviceinv.serial_number  DESC";
    
    if($flag){
        $result = $db->query($query, array("url_flag"=>$flag));
    }else{
        $result = $db->query($query);
    }
    
    return $result;
}
function getDeviceInvsWithSalesRepInfo($db, $flag=FALSE){
    $query = "SELECT "
                    . "tbldevice.device_name, "
                    . "tbldeviceinv.id, tbldeviceinv.Guid_salesrep, tbldeviceinv.deviceid, tbldeviceinv.serial_number, tbldeviceinv.comment, tbldeviceinv.inservice_date, tbldeviceinv.outservice_date, "
                    . "tblsalesrep.first_name, tblsalesrep.last_name "
                    . "FROM tbldeviceinv LEFT JOIN `tbldevice` "
                    . "ON tbldevice.deviceid = tbldeviceinv.deviceid "
                    . "LEFT JOIN `tblsalesrep` "
                    . "ON tbldeviceinv.Guid_salesrep = tblsalesrep.Guid_salesrep ";    
    if($flag){
        $query.= "WHERE url_flag = :url_flag ";
    }
    $query.= "ORDER BY tblsalesrep.first_name, tblsalesrep.last_name, tbldevice.device_name, tbldeviceinv.serial_number  DESC";
    
    if($flag){
        $result = $db->query($query, array("url_flag"=>$flag));
    }else{
        $result = $db->query($query);
    }
    
    return $result;
}
function getDeviceinves($db){
    $query = "SELECT tbldeviceinv.*, tbldevice.device_name, tbldevice.url_flag"
            . " FROM tbldeviceinv "
            . " LEFT JOIN `tbldevice` ON tbldeviceinv.deviceid = tbldevice.deviceid";
    $result = $db->query($query);
    return $result;
}
function getAccountAndSalesrep($db, $accountGuid=NULL, $getRow=NULL){
    $query = "SELECT tblaccount.*, "
            . "tblsalesrep.Guid_salesrep, tblsalesrep.first_name AS salesrepFName, tblsalesrep.last_name AS salesrepLName,"
            . "tblsalesrep.email AS salesrepEmail, tblsalesrep.phone_number AS salesrepPhone , "
            . "tblsalesrep.region AS salesrepRegion, tblsalesrep.title AS salesrepTitle, "
            . "tblsalesrep.address AS salesrepAddress, tblsalesrep.city AS salesrepCity, "
            . "tblsalesrep.state AS salesrepState, tblsalesrep.zip AS salesrepZip, tblsalesrep.photo_filename AS salesrepPhoto "
            . "FROM tblaccount "
            . "LEFT JOIN tblaccountrep ON tblaccount.Guid_account = tblaccountrep.Guid_account "
            . "LEFT JOIN tblsalesrep ON tblsalesrep.Guid_salesrep=tblaccountrep.Guid_salesrep ";         
   
    if($accountGuid){
        $query .= " WHERE tblaccount.Guid_account=:id";
        $result = $db->query($query, array("id"=>$accountGuid));
    }
    elseif ($getRow) {
        $result = $db->row($query);
    }else{
        $query .= " GROUP BY tblaccount.Guid_account"; 
        $result = $db->query($query);
    }    
    return $result;
}
function getSalesrepAccounts($db, $Guid_user){
    $salesrepAccountIDs = $db->query("SELECT acc.account FROM tblsalesrep srep
                                        LEFT JOIN tblaccountrep arep ON arep.Guid_salesrep=srep.Guid_salesrep
                                        LEFT JOIN tblaccount acc ON arep.Guid_account=acc.Guid_account
                                        WHERE srep.Guid_user=:Guid_user", array('Guid_user'=>$Guid_user));
    $accountIds = "";
    foreach ($salesrepAccountIDs as $k=>$v){
        $accountIds .= "'".$v['account']."', ";
    }
    $accountIds = rtrim($accountIds, ', ');
    return $accountIds;
}
/**
 * Get Account Status Count By account number
 * @param type $db
 * @param type $account
 * @param type $Guid_status
 * @return type
 */
function getAccountStatusCount($db, $account, $Guid_status, $eventDate=NULL ){    
    $params = array('account'=>$account,'Guid_status'=>$Guid_status);
    $q = "SELECT COUNT(*) AS `count` FROM `tbl_mdl_status_log` l "
        . "LEFT JOIN tbluser u ON l.Guid_user = u.Guid_user "
        . "WHERE l.Guid_status =:Guid_status AND l.account=:account AND u.marked_test='0' "; 
    if($eventDate){
        $q .=  "AND DATE(l.Date)=:eventdate";
        $params['eventdate']=$eventDate;
    }
    
    $result = $db->row($q, $params);    
    return $result['count'];
}


/**
 * Get Slasrep Status count By Guid salesrep
 * @param type $db
 * @param type $Guid_salesrep
 * @param type $Guid_status
 * @return type
 */
function getSalesrepStatusCount($db, $Guid_salesrep, $Guid_status ){     
    $q = "SELECT COUNT(*) AS `count` FROM `tbl_mdl_status_log` l "
        . "LEFT JOIN tbluser u ON l.Guid_user = u.Guid_user "
        . "WHERE l.Guid_status =:Guid_status AND l.Guid_salesrep=:Guid_salesrep AND u.marked_test='0'"; 
    
    $result = $db->row($q, array('Guid_salesrep'=>$Guid_salesrep,'Guid_status'=>$Guid_status));    
    return $result['count'];
}
/**
 * Get Device Status count By Guid_salesrep
 * @param type $db
 * @param type $Guid_salesrep
 * @param type $Guid_status
 * @return type
 */
function getDeviceStatusCount($db, $Guid_salesrep, $Guid_status, $deviceinventoryID ){     
    $q = "SELECT COUNT(*) AS `count` FROM `tbl_mdl_status_log` l "
        . "LEFT JOIN tbluser u ON l.Guid_user = u.Guid_user "
        . "WHERE l.deviceid=:id AND l.Guid_status =:Guid_status AND l.Guid_salesrep=:Guid_salesrep AND u.marked_test='0'"; 
    $where = array(
                'Guid_salesrep'=>$Guid_salesrep,
                'Guid_status'=>$Guid_status,
                'id'=>$deviceinventoryID
            );
    $result = $db->row($q, $where);    
    return $result['count'];
}
/**
 * Get provider Status count by medicalNecessity and providerID 
 * @param type $db
 * @param type $medicalNecessity => Completed(Yes+No+Unknown), Incomplete
 * Registered => Incomplete + Completed(Yes+No+Unknown)
 * Completed => Completed(Yes+No+Unknown)
 * Qualified => Yes
 * Submitted => Specimen collected=>Yes from patients info screen
 * @param type $Guid_provider 
 * @param type $Guid_status
 * @return type
 */
function getProviderStatusCount($db, $medicalNecessity, $Guid_provider){
    
    if($medicalNecessity=='Incomplete'){
        $table = "tblqualify";
        $where = "WHERE NOT EXISTS(SELECT * FROM tbl_ss_qualify qs WHERE q.Guid_qualify=qs.Guid_qualify) "
                . "AND u.marked_test='0' ";
    } else {
        $table = "tbl_ss_qualify";
        $where = "WHERE u.marked_test='0' ";
        $where .= "AND q.`Date_created` = (SELECT MAX(Date_created) FROM tbl_ss_qualify AS m2 WHERE q.Guid_qualify = m2.Guid_qualify)";

    }
    
    $q  = "SELECT COUNT(*) AS count "
            . "FROM `".$table."` q "
            . "LEFT JOIN tbluser u ON q.Guid_user = u.Guid_user ";
    
    $q .= $where; 
    
    if($medicalNecessity=='Yes'){
        $q .= "AND q.qualified = 'Yes' ";
    }
    if($medicalNecessity=='No'){
        $q .= "AND q.qualified = 'No' ";
    }
    if($medicalNecessity=='Unknown'){
        $q .= "AND q.qualified = 'Unknown' ";
    }
    
    $q .= "AND q.provider_id = '" . $Guid_provider . "' ";
    
    $result = $db->row($q);    
    return $result['count'];
}

/**
 * Get Provider Submited count (Specimen Collected => Guid_status=1)
 * @param type $db
 * @param type $Guid_provider
 * @return string
 */
function getProviderSubmitedCount($db, $Guid_provider ){ 
    
    $andQ = "AND q.provider_id = '" . $Guid_provider . "' ";
    $andQ .= "AND q.`Date_created` = (SELECT MAX(Date_created) FROM tbl_ss_qualify AS m2 WHERE q.Guid_qualify = m2.Guid_qualify)";
    
    $completedQ  = "SELECT q.Guid_user "
                    . "FROM `tbl_ss_qualify` q "
                    . "LEFT JOIN tbluser u ON q.Guid_user = u.Guid_user ";
    $completedQ  .= "WHERE u.marked_test='0' ";
    $completedQ  .= $andQ;
    
    $incompleteQ  = "SELECT q.Guid_user "
                    . "FROM `tblqualify` q "
                    . "LEFT JOIN tbluser u ON q.Guid_user = u.Guid_user ";
    $incompleteQ .= "WHERE NOT EXISTS(SELECT * FROM tbl_ss_qualify qs WHERE q.Guid_qualify=qs.Guid_qualify) "
                   . "AND u.marked_test='0' ";
    $incompleteQ .= $andQ;
    
    $completedUsers = $db->query($completedQ);
    $incompleteUsers = $db->query($incompleteQ);
    $userIds = "";
    if(!empty($completedUsers)){
        foreach ($completedUsers as $k=>$v) {
            $userIds .= "'".$v['Guid_user']."', ";
        }
    }
    if(!empty($incompleteUsers)){
        foreach ($incompleteUsers as $k=>$v) {
            $userIds .= "'".$v['Guid_user']."', ";
        }
    }    
    if($userIds!=""){
        $userIds = rtrim($userIds, ', ');
        
        $submitedQ="SELECT COUNT(*) AS count FROM `tbl_mdl_status_log` l
                LEFT JOIN tbluser u ON u.Guid_user=l.Guid_user
                WHERE l.Guid_user IN(".$userIds.")
                AND u.marked_test='0'
                AND l.Guid_status=1";
               // AND l.currentstatus='Y'";
        $result = $db->row($submitedQ);
    }
    
    if(isset($result['count']) && $result['count']!=""){
        $count =  $result['count'];
    } else{
        $count = '0';
    }
    return $count;
    
}


/**
 * 
 * @param type $db
 * @param type $providerID
 * @return type
 */

function getProviderSalesRep($db, $providerID) {
    $query = "SELECT 
	p.`Guid_provider`, p.`account_id`, 
	a.`Guid_account`, asp.`Guid_salesrep`,
	s.* 
	FROM `tblprovider` p
        LEFT JOIN `tblaccount` a
        ON p.account_id = a.account
        LEFT JOIN `tblaccountrep` asp
        ON a.`Guid_account` = asp.`Guid_account`
        LEFT JOIN `tblsalesrep` s
        ON asp.`Guid_salesrep` = s.`Guid_salesrep`
        WHERE p.`Guid_user` =:providerID";
    $row = $db->row($query, array('providerID'=>$providerID));
    
    return $row;
}


function ifAccountIDValid($accountID, $Guid_account = NULL){
    $db = new Db(DB_SERVER, DB_NAME, DB_USER, DB_PASSWORD);
    $query = "";
    if($Guid_account){
       $query = "SELECT `account` FROM tblaccount WHERE Guid_account<>:Guid_account AND account=:account";
       $row = $db->row($query, array("Guid_account"=>$Guid_account,"account"=>$accountID));
    } else {
        $query = "SELECT `account` FROM tblaccount WHERE account=:account";
        $row = $db->row($query, array("account"=>$accountID));
    } 
    if($row['account']==$accountID || $accountID=="0"){
        return FALSE;
    } 
    return TRUE;
}
function ifDeviceSerialValid($serial_number, $deviceID=NULL){
    $db = new Db(DB_SERVER, DB_NAME, DB_USER, DB_PASSWORD);
    $query = "";
    if($serial_number){
       $query = "SELECT `serial_number` FROM tbldeviceinv WHERE id<>:id AND serial_number=:serial_number";
       $row = $db->row($query, array("id"=>$deviceID, 'serial_number'=>$serial_number));
    } else {
        $query = "SELECT `serial_number` FROM tbldeviceinv WHERE serial=:serial";
        $row = $db->row($query, array("serial"=>$serial_number));
    } 
    if($row['serial_number']==$serial_number){
        return FALSE;
    } 
    return TRUE;
}

function getAccessRoleByKey($keyID){
    $db = new Db(DB_SERVER, DB_NAME, DB_USER, DB_PASSWORD);
    $query = "SELECT * FROM `tbl_mdl_options` WHERE key_id=:key";
    $row = $db->row($query, array("key"=>$keyID));
    return $row;
}



function getAcount($db, $accountId){
    $query = "SELECT * FROM tblaccount WHERE account=:id";
    $row = $db->query($query, array("id"=>$accountId));
    return $row;
}

function get_field_value($db, $table, $extractFieldValue, $where=1){
    $field = $db->row("SELECT $extractFieldValue FROM `".$table."` $where");
    return $field;
}
function get_value($db, $table, $extractFieldValue, $where=array()){
    
    $field = $db->row("SELECT $extractFieldValue FROM `".$table."` $where");
    return $field;
}


function validateProviderId($db, $data, $npi){
    extract($data);    
    $providers = array();
    $query = "";
    if($action=='update'){
        $query = "SELECT `npi` FROM tblprovider WHERE `npi`=$npi AND Guid_provider<>$Guid_provider";
    } else {
        if($npi!=""){
            $query = "SELECT `npi` FROM tblprovider WHERE `npi`=$npi ";
        }
    }
    if($query){
        $providers = $db->query($query);
    } else {
        $providers= FALSE;
    }

    if(!$providers){
        return array('status'=>1, 'msg'=>'NPI Valid.');
    } else {
        return array('status'=>0, 'msg'=>'NPI already exists.');
    }
       
    
}

function validateSettings($db, $data){
    $isMatching = 0;
    extract($data);
    $query = "SELECT * FROM tblurlconfig "
            . "WHERE Guid_user=:currentUserId "
            . "AND geneveda=:geneveda "
            . "AND account=:account "
            . "AND location=:location "
            . "AND pin=:pin "
            . "AND device_id=:device_id";
    $row = $db->query($query, array(
                                    "currentUserId" => $currentUserId,
                                    "geneveda" => $geneveda,
                                    "account" => $account,
                                    "location" => $location,
                                    "account" => $account,
                                    "pin" => $pin,
                                    "device_id" => $device_id,
                    ));
                    
    if(empty($row)){
        $isMatching = 1;
    } 
    
    return $isMatching;
}

function saveUrlSettings($db, $data){
    extract($data);    
    $query = "INSERT INTO `".DB_PREFIX."urlconfig`"
            . "(Guid_user, geneveda, account, location, pin, device_id) VALUES"
            . "(:Guid_user,:geneveda, :account, :location, :pin, :device_id )";
    $insert = $db->query(
                $query, 
                array(
                    "Guid_user"=>"$currentUserId",
                    "geneveda"=>"$geneveda", 
                    "account"=>"$account", 
                    "location"=>"$location",
                    "pin"=>"$pin",
                    "device_id"=>"$device_id"                    
                ));
    
    if($insert > 0 ) {
        return array(
            'message'=>'Table View Succesfully created!',
            'status'=>'1'
        );
    } else {
        return array(
            'message'=>'Insert Issue',
            'status'=>'0'
        );
    } 
}

function insertDevice($db, $data){
    extract($data);    
    $query = "INSERT INTO `".DB_PREFIX."device`"
            . "(serial_number, Guid_salesrep, device_type, device_name, url_flag) VALUES"
            . "(:serial_number,:Guid_salesrep, :device_type, :device_name, :url_flag)";
    $insert = $db->query(
                $query, 
                array(
                    "serial_number"=>"$serial_number",
                    "Guid_salesrep"=>"$Guid_salesrep", 
                    "device_type"=>"$device_type", 
                    "device_name"=>"$device_name",
                    "url_flag"=>"$url_flag"                   
                ));    
    if($insert > 0 ) {
        return array(
            'message'=>'Table View Succesfully created!',
            'status'=>'1'
        );
    } else {
        return array(
            'message'=>'Insert Issue',
            'status'=>'0'
        );
    } 
}

function updateDevice($db, $data){
    //var_dump($data);
    extract($data);
    if($id && $id!=''){
        $query = "UPDATE `".DB_PREFIX."device` SET serial_number=:serial_number, Guid_salesrep=:Guid_salesrep, device_type=:device_type, device_name=:device_name, url_flag=:url_flag WHERE id = :id";
        $update = $db->query($query, array("serial_number"=>"$serial_number", "Guid_salesrep"=>"$Guid_salesrep", "device_type"=>"$device_type", "device_name"=>"$device_name", "url_flag"=>"$url_flag", "id"=>"$id"));

        if($update) {
          return array(
                  'message'=>'Table View Succesfully updated!',
                  'status'=>'1'
                );
        } else {
          return array(
                  'message'=>'Update Issue',
                  'status'=>'0'
                );
        }
    }
}

/**
 * 
 * ex. getTableRow($db, 'tblaccountrep', array('Guid_account'=>$_POST['Guid_account']))
 * @param type $db
 * @param type $table
 * @param type $where
 * @return type
 */
function getTableRow($db, $table, $where){
    
    $fields = "";
    $fieldsFlag = "";
    $executeArray = array();
    foreach ($where as $key => $val) {
        $whereStr = " WHERE `$key`=:$key";
        $executeArray["$key"] = $val;
    }
    
    $query = "SELECT * FROM `".$table."` $whereStr";
   
    $result = $db->row($query, $executeArray);
    return $result;    
}

/**
 * Update Table function
 * @param type $db
 * @param type $table - string table name
 * @param type $data - array which would be updated 
 * @param type $where - array with key and value 
 * @return type array with status and message
 */
function updateTable($db, $table, $data, $where ){   
    $updateFields = "";
    $whereStr = "";
    $executeArray = array();
    foreach ($data as $key => $val) {
        $updateFields .= "`$key`=:$key, ";
        $executeArray["$key"] = $val;
    }
    $updateFields = rtrim($updateFields,", ");   
    
    foreach ($where as $key => $val) {
        $whereStr = " WHERE `$key`=:$key";
        $executeArray["$key"] = $val;
    }     
    $query = "UPDATE `$table` SET $updateFields $whereStr";
    $update = $db->query($query, $executeArray);
    
    return $update;  
}
/**
 * Insert Table function
 * @param type $db
 * @param type $table - string table name
 * @param type $data - array which would be updated 
 * @param type $where - array with key and value 
 * @return type array with status and message
 */
function insertIntoTable($db, $table, $data, $msg=NULL ){   
    $insertFields = "";
    $insertFieldsFlag = "";
    $executeArray = array();
    foreach ($data as $key => $val) {
        $insertFields .= "$key, ";
        $insertFieldsFlag .= ":$key, ";
        $executeArray["$key"] = $val;
    }
    $insertFields = rtrim($insertFields,", ");   
    $insertFieldsFlag = rtrim($insertFieldsFlag,", "); 
       
    $query = "INSERT INTO `$table` ($insertFields) VALUES ($insertFieldsFlag)";
    $insert = $db->query( $query, $executeArray);    
    if($insert > 0 ) {
        $messageSuccess = isset($msg['success']) ? $msg['success'] : 'Data Succesfully created!';
        return array(
            'insertID' => $db->lastInsertId(),
            'message'=>$messageSuccess,
            'status'=>'1'
        );
    } else {
        $messageError = isset($msg['error']) ? $msg['error'] : 'Insert Issue';
        return array(
            'message'=>$messageError,
            'status'=>'0'
        );
    }    
}
/**
 * deleteRowByField where
 * @param type $db
 * @param type $table
 * @param type $where
 * @return boolean
 */
function deleteRowByField($db, $table, $where){
    $whereStr = "";
    foreach ($where as $key => $val) {
        $whereStr = " WHERE `$key`=:$key";
        $executeArray["$key"] = $val;
    }
    $query = "DELETE FROM `$table` $whereStr";
    $delete = $db->query($query, $executeArray);
    return FALSE;
}
function getLastUrlConfig($db){
    $lastConfig = $db->row("SELECT * FROM `tblurlconfig` ORDER BY id DESC");
    
    return $lastConfig;
}

function deleteUrlConfig($db, $id){
    $query = "DELETE FROM `".DB_PREFIX."urlconfig` WHERE id=:id";
    $delete = $db->query($query, array( "id"=>"$id"));
    return FALSE;
}

function deleteById($db, $table, $id){
    $query = "DELETE FROM `$table` WHERE id=:id";
    $delete = $db->query($query, array( "id"=>"$id"));
    return FALSE;
}

function deleteByField($db, $table, $field, $value){
    $query = "DELETE FROM `$table` WHERE $field=:$field";
    $delete = $db->query($query, array( "$field"=>"$value"));
    return FALSE;
}

function deleteAccountById($db, $table, $id){
    $query = "DELETE FROM `$table` WHERE Guid_account=:id";
    $delete = $db->query($query, array( "id"=>"$id"));
    return FALSE;
}


function uploadFile($uploadName, $uploadFolder=NULL){
    if(!$uploadFolder){
        $target_dir = "images/practice/";
    } else {
        $target_dir = $uploadFolder;
    }
    
    $target_file = $target_dir . basename($_FILES["$uploadName"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $message = array();
    // Check if image file is a actual image or fake image
    if(isset($_FILES)) {
        $check = getimagesize($_FILES["$uploadName"]["tmp_name"]);
        if($check !== false) {
            $message['msg'] = "File is an image - " . $check["mime"] . ".";
            $message['status'] = "1";
            $uploadOk = 1;
        } else {
            $message['msg'] =  "File is not an image.";
            $message['status'] = "0";
            $uploadOk = 0;
        }
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        $message['msg'] = "Sorry, file <span>".$_FILES["$uploadName"]["name"]."</span> already exists.";
        $message['status'] = "0";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["$uploadName"]["size"] > 500000) {
        $message['msg'] =  "Sorry, your file is too large.";
        $message['status'] = "0";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        $message['msg'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $message['status'] = "0";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $message['status'] = "0";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["$uploadName"]["tmp_name"], $target_file)) {
            $message['msg'] = "The file ". basename( $_FILES["$uploadName"]["name"]). " has been uploaded.";
            $message['status'] = "1";            
        } else {
            $message['msg'] = "Sorry, there was an error uploading your file.";
            $message['status'] = "0";
        }
    }
    
    return $message;
}
/**
 * format Money Function
 * @param type $number
 * @return string
 */
function formatMoney($number){
    $pieces = explode(".", $number);  
    $startPiece = $pieces[0];
    if(isset($pieces[1])){
        $endPiece = $pieces[1];
    }     
    $n=number_format(abs($startPiece));
    $newNum = $n;
    if(isset($endPiece)&&$endPiece!=""){        
        $newNum .= ".".$endPiece;
        if(strlen($endPiece)=='1'){
            $newNum .= "0";
        }
    } else {
        $newNum .= ".00";
    }
    
    return $newNum;
}

function formatLastName($lastName){
    $lastNameF = ucwords(strtolower($lastName));
    if(substr($lastNameF, 0, 2)=='Mc'){
        return substr_replace($lastNameF, strtoupper(substr($lastNameF, 2, 1)), 2, 1);
    } else {
        return $lastNameF;
    }
}
function formatAccountName($accountName){
    $accountName = ucwords(strtolower($accountName));
    $accountName = str_replace("Ob/gyn","OB/GYN",$accountName);
    $accountName = str_replace("Obgyn","OB/GYN",$accountName);
    return $accountName;
}

/**
 * Get nested status names 
 * used in patient info screen 
 * Test Status Change Log Table - status names
 */
function get_nested_statuses($db, $Guid_status, $Guid_user, $Log_group, $i=0){
    $statQ = "SELECT sl.Guid_status, s.status FROM tbl_mdl_status_log sl 
            LEFT JOIN tblpatient p ON sl.Guid_patient=p.Guid_user 
            Left Join tbl_mdl_status s ON sl.Guid_status=s.Guid_status
            WHERE sl.Guid_user=$Guid_user AND s.Guid_status=$Guid_status AND Log_group=$Log_group";
    $status = $db->query($statQ);
    $names = '';
    if(!empty($status)){
        foreach ($status as $k=>$v){   
            $parent = $v['Guid_status'];
            $children = $db->query("SELECT sl.Guid_status, s.status FROM tbl_mdl_status_log sl 
                        LEFT JOIN tblpatient p ON sl.Guid_patient=p.Guid_user 
                        Left Join tbl_mdl_status s ON sl.Guid_status=s.Guid_status
                        WHERE sl.Guid_user=$Guid_user AND s.parent_id=$parent AND Log_group=$Log_group");
            if($i==0 && !empty($children)){
                echo $v['status'].': ';
            } elseif ($i==1 && !empty($children) ) {
                echo $v['status'].', ';
            } else {
                echo $v['status'];
            }            
            if(!empty($children)){
                foreach ($children as $key => $value) {
                    get_nested_statuses($db, $value['Guid_status'], $Guid_user, $Log_group, $i=1);
                }
            } else {
                $i=2;
            }
        }
    }
}

function get_selected_log_dropdown($db, $Log_group, $parent="0") {
    $selectedStatuses = $db->query(
                "SELECT sl.Guid_status, st.status, st.parent_id FROM tbl_mdl_status_log sl "
                . " LEFT JOIN tbl_mdl_status st ON st.Guid_status=sl.Guid_status"                
                . " WHERE `Log_group`= ".$Log_group
                . " ORDER BY st.parent_id ASC, st.order_by ASC"
    );
    $content = "";
    foreach ($selectedStatuses as $k => $v){
        $getParent = $db->row("SELECT parent_id FROM tbl_mdl_status WHERE Guid_status=:Guid_status", array('Guid_status'=>$v['Guid_status']));
        $statuses = $db->query("SELECT * FROM tbl_mdl_status WHERE `parent_id` = ".$getParent['parent_id']."  ORDER BY order_by ASC, Guid_status ASC");
        $content .= '<div class="f2 valid ">
                        <div class="group">
                            <select data-parent="'.$parent.'" required class="status-dropdown" name="status[]" id="">
                                <option value="0">Select Status</option>';    
                                    if ( $statuses ) {
                                        foreach ( $statuses as $status ) {  
                                            $checkCildren = $db->query("SELECT * FROM tbl_mdl_status WHERE `parent_id` = ".$status['Guid_status']);

                                            $optionClass = '';
                                            if ( !empty($checkCildren) ) { 
                                                $optionClass = 'has_sub_menu';                 
                                            }  
                                            $selected = isStatusSelected($status['Guid_status'],$selectedStatuses) ? " selected": "";
                                            $content .= "<option ".$selected." value='".$status['Guid_status']."' class='".$optionClass."'>".$status['status'];

                                            $content .= '</option>';
                                        }
                                    }
        $content .= '</select><p class="f_status"><span class="status_icons"><strong></strong></span>';
        $content .= '</p></div></div>';    
    }
    return $content;
}

function isStatusSelected($status, $selectedStatuses){
    foreach ($selectedStatuses as $k=>$v){
         if($status == $v['Guid_status']){
            return TRUE;
        }
    }
   
    return FALSE;
}

function saveStatusLog($db,$statusIDs, $statusLogData){
    
    $i = 1;
    foreach ($statusIDs as $k=>$status){        
        $statusLogData ['Guid_status'] = $status;

        if($i==1){
            $insertStatusLog = insertIntoTable($db, 'tbl_mdl_status_log', $statusLogData);
            $insertID = $insertStatusLog['insertID'];
            if($insertID!=""){ 
                $LogGroupID=$insertID;
                $LogGroupData['Log_group']=$insertID;
                $where['Guid_status_log']=$insertID;
                //setting first insert id as a log group id
                updateTable($db, 'tbl_mdl_status_log', $LogGroupData, $where);
            }    
            $i++;
        } else {
            //after first insert seting first insert id as logGroupID
            $statusLogData['Log_group']=$LogGroupID;
            $insertStatusLog = insertIntoTable($db, 'tbl_mdl_status_log', $statusLogData);            
        }
    }
    return TRUE;
}

function updateCurrentStatusID($db, $Guid_patient){
    //SELECT statuses.*, statuslogs.`Guid_status_log`, statuslogs.`Guid_patient`, statuslogs.`Log_group`, statuslogs.`order_by`, statuslogs.`Date` 
    $q  =   "SELECT * 
            FROM `tbl_mdl_status` statuses
            LEFT JOIN `tbl_mdl_status_log` statuslogs
            ON statuses.`Guid_status`= statuslogs.`Guid_status` ";    
    $q  .=  "AND statuslogs.`Guid_status_log`<>''
            AND statuses.parent_id='0'
            AND statuslogs.Guid_patient=$Guid_patient
            ORDER BY statuslogs.`Date` DESC, statuses.order_by DESC LIMIT 1";
    $result = $db->row($q);
 
    updateTable($db, 'tbl_mdl_status_log', array('currentstatus'=>'N'), array('Guid_patient'=>$Guid_patient));
    updateTable($db, 'tbl_mdl_status_log', array('currentstatus'=>'Y'), array('Log_group'=>$result['Log_group']));
    
    return $result['Guid_status_log'];
}

function get_status_dropdown($db, $parent='0', $Guid_status=FALSE) {
    if($Guid_status){
        $statuses = $db->query("SELECT * FROM tbl_mdl_status WHERE `Guid_status` = ".$Guid_status);
        
    }else{
        $statuses = $db->query("SELECT * FROM tbl_mdl_status WHERE `parent_id` = ".$parent." AND visibility='1' ORDER BY order_by ASC, Guid_status ASC");
    }
    
    $content = '<div class="f2  ">
                    <div class="group">
                        <select data-parent="'.$parent.'" required class="status-dropdown" name="status[]" id="">
                            <option value="">Select Status</option>';    
    if ( $statuses ) {
        foreach ( $statuses as $status ) {  
            $checkCildren = $db->query("SELECT * FROM tbl_mdl_status WHERE `parent_id` = ".$status['Guid_status']);
             
            $optionClass = '';
            if ( !empty($checkCildren) ) { 
                $optionClass = 'has_sub_menu';                 
            }            
            $content .= "<option value='".$status['Guid_status']."' class='".$optionClass."'>".$status['status'];
            
            $content .= '</option>';
        }
    }
    $content .= '</select><p class="f_status"><span class="status_icons"><strong></strong></span>
                            </p></div></div>';
   
    return $content;
}

function get_nested_status_dropdown($db, $parent = 0) {
    $statuses = $db->query("SELECT * FROM tbl_mdl_status WHERE `parent_id` = ".$parent." AND visibility='1' ORDER BY order_by ASC, Guid_status ASC");
    
    $content = '<select class="no-selection" name="parent_id" id="parent">
                            <option value="0">Select Status Parent</option>';    
    if ( $statuses ) {
        foreach ( $statuses as $status ) {  
            $checkCildren = $db->query("SELECT * FROM tbl_mdl_status WHERE `parent_id` = ".$status['Guid_status']);
             
            $optionClass = '';
            if ( !empty($checkCildren) ) { 
                $optionClass = 'has_sub_menu';                 
            }            
            $content .= "<option value='".$status['Guid_status']."' class='".$optionClass."'>".$status['status'];
            if ( !empty($checkCildren) ) {
                $content .= get_option_of_nested_status( $db, $status['Guid_status'], "-&nbsp;" );
            }
            $content .= '</option>';
        }
    }
    $content .= "</select>";
   
    return $content;
}
function get_option_of_nested_status($db, $parent = 0,  $level = '', $checkboxes=FALSE) {
    $statuses = $db->query("SELECT * FROM tbl_mdl_status WHERE `parent_id` = ".$parent." ORDER BY order_by ASC, Guid_status ASC");
    if ( $statuses ) {
        $content ='';
        $prefix = 0;
        foreach ( $statuses as $status ) {  
            
            $checkCildren = $db->query("SELECT * FROM tbl_mdl_status WHERE `parent_id` = ".$status['Guid_status']);
            $optionClass = '';
           
            if ( !empty($checkCildren) ) { 
                $optionClass = 'has_sub';   
            }         
            if($checkboxes){
                //used for mdl-stat-details-config.php config field popup status select list
                $getOption = getOption($db, 'stat_details_config');
                $fieldId = $_GET['field_id'];
                $fieldOptions = unserialize($getOption['value']);
                $thisStatuses = isset($fieldOptions[$fieldId]['statuses'])? $fieldOptions[$fieldId]['statuses'] : "";
                $isSelected = "";
                if(isset($fieldOptions[$fieldId]) && $thisStatuses!=""){
                    $isSelected = in_array($status['Guid_status'], $thisStatuses)? " checked": "";
                }                
                $content .= $level."<input ".$isSelected." type='checkbox' name=stauses[] value='".$status['Guid_status']."' class='".$optionClass."'> " .$status['status'].'</br>';
            }else{
                $content .= "<option value='".$status['Guid_status']."' class='".$optionClass."'>".$level . " " .$status['status'];
            }
            if ( !empty($checkCildren) ) {
                $prefix .= '-';
                if(!$checkboxes){
                    $content .= get_option_of_nested_status( $db, $status['Guid_status'], $level . "-&nbsp;" );
                } else {
                    $content .= get_option_of_nested_status( $db, $status['Guid_status'], $level . "-&nbsp;", TRUE);
                }
            }
            if(!$checkboxes){
                $content .= '</option>';
            }
        }
    }
   
    return $content;
}

function get_nested_ststus_editable_rows($db, $parent = 0, $level = '') {
    $statuses = $db->query("SELECT * FROM tbl_mdl_status WHERE `parent_id` = ".$parent." ORDER BY order_by ASC, Guid_status ASC");
    $roles= $db->query('SELECT * FROM `tblrole` WHERE `role`<>"Admin" AND `role`<>"Patient" AND `role`<>"MDL Patient"');
    
    $content = "";
    if ( $statuses ) {
        $content ='';
        $prefix = 0;
        
        foreach ( $statuses as $status ) {  
         
            $checkCildren = $db->query("SELECT * FROM tbl_mdl_status WHERE `parent_id` = ".$status['Guid_status']);
            $optionClass = '';
           
            if ( !empty($checkCildren) ) { 
                $optionClass = 'has_sub';   
            }         
            
            $content .= "<td>".$status['Guid_status'] . "</td>";
            $content .= "<td  class='".$optionClass."'>".$level . " ";
            $content .= "<input type='hidden' name=status[Guid_status][] value='".$status['Guid_status']."' />";
            $content .= "<input type='text' name=status[name][] value='".$status['status']."' />";
            $content .= '</td>';
            $selectedY = ($status['visibility']=='1') ? " selected" : "";
            $selectedN = ($status['visibility']=='0') ? " selected" : "";
            $content .= "<td><input type='number' name='status[order][]' value='".$status['order_by']."' ></td>";
            $content .= "<td><select name='status[visibility][]'>
                                <option ".$selectedY." value='1'>Yes</option>
                                <option ".$selectedN." value='0'>No</option>
                            </select>
                        </td>";            
            $content .= "<td class='roles'>";
            if($roles){
                $content .= "<p><span class='toggleThisRoles pull-right far fa-eye-slash'></span></p>";
                $content .= "<div class='rolesBlock hidden'>";   //.hidden             
                foreach ($roles as $k => $v) {
                    $checked = "";                    
                    if($status['access_roles'] && $status['access_roles']!=""){
                        $accessRoles = unserialize($status['access_roles']); 
                        if($accessRoles){
                            if(array_key_exists($v['Guid_role'], $accessRoles)){
                                $checked = " checked";
                            }       
                        }
                    }                    
                    $content .= "<p><input name=status[roles][".$status['Guid_status']."][".$v["Guid_role"]."] type='checkbox' ".$checked." />".$v['role']."</p>";
                }
                $content .= "</div>";
            }
            $content .= "</td>";
            $content .= "</tr>";
            if ( !empty($checkCildren) ) {
                $prefix .= '-';            
                $content .= get_nested_ststus_editable_rows( $db, $status['Guid_status'], $level . "-&nbsp;" );
            }
        }
        
    }
   
    return $content;
}

/**
 * Get Mark as test user ids
 * @param type $db
 * @return type String 11,55,22,45
 */
function getMarkedTestUserIDs($db){
    $getTestUsers = $db->query("SELECT Guid_user FROM `tbluser` WHERE marked_test=:marked_test", array('marked_test'=>'1'));
    $userIds = "";
    foreach ($getTestUsers as $k=>$v){
        $userIds .= $v['Guid_user'].', ';
    }
    $markedTestUserIds = rtrim($userIds, ', ');
    
    return $markedTestUserIds;
}

function getTestUserIDs($db){
    $q = "SELECT p.Guid_user FROM tblpatient p 
            WHERE CONCAT(AES_DECRYPT(p.firstname_enc, 'F1rstn@m3@_%'), ' ', AES_DECRYPT(p.lastname_enc, 'L@stn@m3&%#')) LIKE '%test%' 
            OR CONCAT(AES_DECRYPT(p.firstname_enc, 'F1rstn@m3@_%'), ' ', AES_DECRYPT(p.lastname_enc, 'L@stn@m3&%#')) LIKE '%John Smith%' 
            OR CONCAT(AES_DECRYPT(p.firstname_enc, 'F1rstn@m3@_%'), ' ', AES_DECRYPT(p.lastname_enc, 'L@stn@m3&%#')) LIKE '%John Doe%' 
            OR CONCAT(AES_DECRYPT(p.firstname_enc, 'F1rstn@m3@_%'), ' ', AES_DECRYPT(p.lastname_enc, 'L@stn@m3&%#')) LIKE '%Jane Doe%' ";
    $getTestUsers = $db->query($q);
    $userIds = "";
    foreach ($getTestUsers as $k=>$v){
        $userIds .= $v['Guid_user'].', ';
    }
    $testUserIds = rtrim($userIds, ', ');
    
    return $testUserIds;    
}

function formatDate($date){
    if (empty($date)) {
	return '';
    } else {
	return date("n/j/Y", strtotime($date));
    }
}

function dbDateFormat($date){
    if (empty($date)) {
	return '';
    } else {
	return date("Y-m-d H:i:s", strtotime($date));
    }
}

/**
 * Get Stats info
 * @param type $db
 * @param type $statusID
 * @return type array ('count'=>5, 'info'=>array())
 */
function get_stats_info($db, $statusID, $hasChildren=FALSE, $searchData=array()){
    
    //exclude test users
    $markedTestUserIds = getMarkedTestUserIDs($db);
    $testUserIds = getTestUserIDs($db);
    $filterUrlStr = "";
    //$testUserIds = '';
    $q = "SELECT statuses.*, statuslogs.*,
            mdlnum.mdl_number as mdl_number
            FROM `tbl_mdl_status` statuses
            LEFT JOIN `tbl_mdl_status_log` statuslogs ON statuses.`Guid_status`= statuslogs.`Guid_status`
            LEFT JOIN `tbl_mdl_number` mdlnum ON statuslogs.Guid_user=mdlnum.Guid_user ";
    
    $q .=  "WHERE  statuslogs.`currentstatus`='Y' ";
    
    if(!empty($searchData)){ 
        if (isset($searchData['from_date']) && $searchData['from_date']!="" && isset($searchData['to_date']) && $searchData['to_date']!="") {
            if ($searchData['from_date'] == $searchData['to_date']) {
                $q .= " AND statuslogs.Date LIKE '%" . date("Y-m-d", strtotime($searchData['from_date'])) . "%'";
            } else {
                $q .= " AND statuslogs.Date BETWEEN '" . date("Y-m-d", strtotime($searchData['from_date'])) . "' AND '" . date("Y-m-d", strtotime($searchData['to_date'])) . "'";
            }
            $filterUrlStr .= "&from=".date("Y-m-d", strtotime($searchData['from_date']));
            $filterUrlStr .= "&to=".date("Y-m-d", strtotime($searchData['to_date']));
        }
        if(isset($searchData['mdl_number']) && $searchData['mdl_number']!=""){
            $q .= " AND mdl_number='".$searchData['mdl_number']."' ";
            $filterUrlStr .= "&mdnum=".$searchData['mdl_number'];
        }
        if(isset($searchData['Guid_salesrep']) && $searchData['Guid_salesrep']!=""){
            $q .= " AND statuslogs.Guid_salesrep='".$searchData['Guid_salesrep']."' ";
            $filterUrlStr .= "&salesrep=".$searchData['Guid_salesrep'];
        }
        if(isset($searchData['Guid_account']) && $searchData['Guid_account']!=""){
            $q .= " AND statuslogs.Guid_account='".$searchData['Guid_account']."' ";
            $filterUrlStr .= "&account=".$searchData['Guid_account'];
        }
    }
  
    $q .=  " AND statuslogs.`Guid_status_log`<>'' 
            AND statuslogs.Guid_status=$statusID ";
    if($markedTestUserIds!=""){
    $q .=  " AND statuslogs.Guid_user NOT IN(".$markedTestUserIds.") "; 
    }
    if($testUserIds!=""){
    $q .=  " AND statuslogs.Guid_user NOT IN(".$testUserIds.") ";   
    }
    $q .=  " AND statuslogs.Guid_patient<>'0' 
            ORDER BY statuslogs.`Date` DESC, statuses.`order_by` DESC";
    
    $stats = $db->query($q);
    $result['count'] = 0;
    if(!empty($stats)){
        $result['count'] = count($stats);
        $result['filterUrlStr'] = $filterUrlStr;
        $result['info'] = $stats;
    }  
    
    return $result;
}

function get_status_table_rows($db, $parent = 0, $searchData=array(), $linkArr=array()) {   
    
    $statusQ = "SELECT * FROM tbl_mdl_status WHERE `parent_id` = ".$parent." ORDER BY order_by ASC";
    $statuses = $db->query($statusQ);
    $filterUrlStr = "";
    $content = '';    
    if ( $statuses ) {
        foreach ( $statuses as $status ) {  
            $checkCildren = $db->query("SELECT * FROM tbl_mdl_status "
                    . "WHERE `parent_id` = ".$status['Guid_status']);
            $stats = get_stats_info($db, $status['Guid_status'], FALSE, $searchData);
            $link = '';
            if($stats['count']!=0){
                $optionClass = '';
                $filterUrlStr = $stats['filterUrlStr'];
                if(empty($linkArr)){
                    $link .= SITE_URL.'/mdl-stat-details.php?status_id='.$status['Guid_status'].$filterUrlStr;
                } else {
                    $link .=  SITE_URL.'/accounts.php?status_id='.$status['Guid_status'].'&';
                    foreach ($linkArr as $k=>$v){
                        $link .= $k.'='.$v.'&';
                    }
                    $link = rtrim($link,'&');                    
                }
                if ( !empty($checkCildren) ) { 
                    $optionClass = 'has_sub';                 
                } 
                $content .= "<tr id='".$status['Guid_status']."' class='parent ".$optionClass."'>";
                $content .= "<td class='text-left'><span>".$status['status'].'</span></td>';            
                $content .= '<td><a href="'.$link.'">'.$stats['count'].'</a></td>';
                if ( !empty($checkCildren) ) {
                    $content .= get_status_child_rows( $db, $status['Guid_status'], "&nbsp;", $searchData, $linkArr );
                }            
                $content .= "</tr>";
            }
        }
    }   
   
    return $content;
}
function get_status_child_rows($db, $parent = 0,  $level = '', $searchData=array(), $linkArr=array()) {
    $statuses = $db->query("SELECT * FROM tbl_mdl_status WHERE `parent_id` = ".$parent."  ORDER BY order_by ASC");
    if ( $statuses ) {
        $content ='';
        $prefix = 0;
        foreach ( $statuses as $status ) { 
            $checkCildren = $db->query("SELECT * FROM tbl_mdl_status WHERE `parent_id` = ".$status['Guid_status']);
            $optionClass = '';  
            $stats = get_stats_info($db, $status['Guid_status'], TRUE, $searchData);
            if($stats['count']!=0){
                $filterUrlStr = $stats['filterUrlStr'];
                if ( !empty($checkCildren) ) { 
                    $optionClass = 'parent has_sub';   
                }  
                $link = '';
                if(empty($linkArr)){
                    $link .= SITE_URL.'/mdl-stat-details.php?status_id='.$status['Guid_status'].'&parent='.$parent.$filterUrlStr;
                } else {
                    $link .=  SITE_URL.'/accounts.php?status_id='.$status['Guid_status'].'&parent='.$parent.'&';
                    foreach ($linkArr as $k=>$v){
                        $link .= $k.'='.$v.'&';
                    }
                    $link = rtrim($link,'&');                    
                }
                $content .= "<tr id='".$status['Guid_status']."' data-parent-id='".$parent."' class='sub ".$optionClass."'>";
                $content .= "<td class='text-left'><span>".$level . " " .$status['status'].'</span></td>';
                $content .= '<td><a href="'.$link.'">'.$stats['count']. '</a></td>';
                if ( !empty($checkCildren) ) {
                    $prefix .= '&nbsp;';
                    $content .= get_status_child_rows( $db, $status['Guid_status'], $level . "&nbsp;", $searchData, $linkArr );
                }
                 $content .= "</tr>";
            }
        }
    }
   
    return $content;
}

function get_status_table_rows_($db, $parent = 0) { 
    
    $q ='SELECT count(*) AS count, statuses.*, statuslogs.`Guid_status_log`, statuslogs.`Guid_patient`, statuslogs.`Log_group`, statuslogs.`order_by`, statuslogs.`Date` FROM `tbl_mdl_status` statuses
        LEFT JOIN `tbl_mdl_status_log` statuslogs
        ON statuses.`Guid_status`= statuslogs.`Guid_status`
        WHERE `visibility`="1" 
        AND statuslogs.`Guid_status_log`<>""        
        AND parent_id="'.$parent.'"
        ORDER BY statuslogs.`Date`, statuslogs.`order_by` DESC';
    $statuses = $db->query($q);
   
    $content = '';    
    if ( $statuses ) {
        foreach ( $statuses as $status ) {
            $q ='SELECT count(*) AS count, statuses.*, statuslogs.`Guid_status_log`, statuslogs.`Guid_patient`, statuslogs.`Log_group`, statuslogs.`order_by`, statuslogs.`Date` FROM `tbl_mdl_status` statuses
                LEFT JOIN `tbl_mdl_status_log` statuslogs
                ON statuses.`Guid_status`= statuslogs.`Guid_status`
                WHERE `visibility`="1" 
                AND statuslogs.`Guid_status_log`<>""
                
                AND parent_id="'.$status['Guid_status'].'"
                ORDER BY statuslogs.`Date`, statuslogs.`order_by` DESC';
            $checkCildren = $db->query($q);
                    
            if( isset($status['count']) && $status['count']!=0){
                $optionClass = '';
                if ( !empty($checkCildren) ) { 
                    $optionClass = 'has_sub';                 
                }    
                $content .= "<tr id='".$status['Guid_status']."' class='parent ".$optionClass."'>";
                $content .= "<td class='text-left'><span>".$status['status'].'</span></td>';            
                $content .= '<td><a href="'.SITE_URL.'/mdl-stat-details.php?status_id='.$status['Guid_status'].'">'.$status['count'].'</a></td>';
                if ( !empty($checkCildren) ) {
                    $content .= get_status_child_rows( $db, $status['Guid_status'], "&nbsp;" );
                }            
                $content .= "</tr>";
            }
        }
    }   
   
    return $content;
}

function getStatusName($db, $Guid_status, $parent){
    $name =""; $parentName="";
    $status = $db->row("SELECT `status` FROM tbl_mdl_status WHERE Guid_status=:Guid_status", array('Guid_status'=>$Guid_status));
    if($parent != ""){
        $parentRow = $db->row("SELECT `status` FROM tbl_mdl_status WHERE Guid_status=:Guid_status", array('Guid_status'=>$parent));
        $name .= $parentRow['status']." - ";
    }    
    if($status){
        $name .= $status['status'];
    }
    
    return $name;
}
function getStatusParentNames($db, $Guid_status){
    $status = $db->row("SELECT `status` FROM tbl_mdl_status WHERE Guid_status=:Guid_status AND parent_id='0' ", array('Guid_status'=>$Guid_status));
    $names = "";
    if($status){
        $names .= $status['status'].'; ';
    }    
    return $names;
}
function getRoleName($db, $roleID){
    $role = $db->row("SELECT role FROM `tblrole` WHERE Guid_role=:Guid_role", array('Guid_role'=>$roleID));
    $names = "";
    if($role){
        $names .= $role['role'];
    }    
    return $names;
}
/**
 * Checking if field status assigned for given field
 * Statuses configurable from MDL details config page(mdl-stat-details-config.php) 
 * Returns True if status assigned to that field 
 * @param type $db
 * @param type $fieldID
 * @param type $statusID
 * @return boolean
 */
function isFieldVisibleForStatus($db, $fieldID, $statusID){
    $configOptions = getOption($db, 'stat_details_config');
    $optionVal = unserialize($configOptions['value']);
    if(isset($optionVal[$fieldID]['statuses']) && !empty($optionVal[$fieldID]['statuses'])){
        if(in_array($statusID, $optionVal[$fieldID]['statuses'])){
            return TRUE;
        }     
        return FALSE;
    }
    return FALSE;    
}
/**
 * Checking if role assigned for given filed
 * Roles configurable from MDL details config page(mdl-stat-details-config.php) 
 * Returns True if role assigned to that field 
 * @param type $db
 * @param type $fieldID
 * @param type $roleID
 * @return boolean
 */
function isFieldVisibleForRole($db, $fieldID, $roleID){
    $configOptions = getOption($db, 'stat_details_config');
    $optionVal = unserialize($configOptions['value']);    
    if(isset($optionVal[$fieldID]['roles']) && !empty($optionVal[$fieldID]['roles'])){
        if(in_array($roleID, $optionVal[$fieldID]['roles'])){
            return TRUE;
        }     
        return FALSE;
    }
    return FALSE;    
}
/**
 * Get Revenue Details for stats page
 * @param type $db
 * @param type $Guid_user
 * @return type array
 */
function getRevenueStat($db, $Guid_user){
    $revenueData = array();
    $getPayorQ = "SELECT r.Guid_payor, p.name FROM `tbl_revenue` r "
                . "LEFT JOIN tbl_mdl_payors p ON r.Guid_payor=p.Guid_payor "
                . "WHERE r.Guid_payor<>'' AND r.Guid_user=:Guid_user "
                . "GROUP BY r.Guid_payor ORDER BY r.Guid_payor ";
    
    $payors = $db->query($getPayorQ, array('Guid_user'=>$Guid_user));
    
    $paidPatient = 0;
    $paidInsurance = 0;
    $total = 0;
    $insuranceName = "";
    if(!empty($payors)){
        foreach ($payors as $k=>$v){
            $Guid_payor = $v['Guid_payor'];
            $revenueAmmountQ = "SELECT r.amount FROM `tbl_revenue` r
                                LEFT JOIN `tbl_mdl_payors` p ON r.`Guid_payor`=p.`Guid_payor`
                                WHERE r.`Guid_payor`= $Guid_payor AND r.Guid_user=$Guid_user
                                ORDER BY r.`Guid_payor` "; 
            $revenueAmmount = $db->query($revenueAmmountQ);
            foreach ($revenueAmmount as $amount){
                if($Guid_payor=='1'){ // Payor ID with 1 is Patients
                    $paidPatient += $amount['amount'];
                }else{ //insurance
                    $paidInsurance += $amount['amount'];
                }
                $total += $amount['amount'];
            }
            if($Guid_payor!='1'){
                $insuranceName .= $v['name']."; ";
            }
        }         
    }
    $insuranceName = rtrim($insuranceName, '; ');
    $revenueData['patient_paid'] =  $paidPatient;   
    $revenueData['insurance_paid'] =  $paidInsurance;   
    $revenueData['total'] =  $total;   
    $revenueData['insurance_name'] =  $insuranceName;  
    
    return $revenueData;
}
/**
 * Get Totals for given status
 * @param type $db
 * @param type $Guid_status
 * @return type array
 */
function getStatusRevenueTotals($db, $Guid_status, $searchData=array()){    
    $usersQ = "SELECT sl.*, mn.mdl_number FROM `tbl_mdl_status_log` sl "
            . "LEFT JOIN tbl_mdl_number mn "
            . "ON sl.Guid_user=mn.Guid_user "; 
    
    $usersQ .= " WHERE sl.Guid_status=:Guid_status AND sl.currentstatus='Y' ";
 
    if(!empty($searchData)){
        
        //adding filter conditions 
        if(isset($searchData['Guid_salesrep'])&&$searchData['Guid_salesrep']!=""){
            $usersQ .= 'AND sl.Guid_salesrep='.$searchData['Guid_salesrep'].' ';
        }
        if(isset($searchData['Guid_account'])&&$searchData['Guid_account']!=""){
            $usersQ .= 'AND sl.Guid_account='.$searchData['Guid_account'].' ';
        }
        if(isset($searchData['mdl_number'])&&$searchData['mdl_number']!=""){
            $usersQ .= 'AND mn.mdl_number='.$searchData['mdl_number'].' ';
        }
        if( isset($searchData['from_date']) && isset($searchData['to_date']) ){
            if ($searchData['from_date'] == $searchData['to_date']) {
                $usersQ .= " AND sl.Date LIKE '%" . date("Y-m-d", strtotime($searchData['from_date'])) . "%'";
            } else {
                $usersQ .= " AND sl.Date BETWEEN '" . date("Y-m-d", strtotime($searchData['from_date'])) . "' AND '" . date("Y-m-d", strtotime($searchData['to_date'])) . "'";
            }
        }
    }
    
    $users = $db->query($usersQ, array('Guid_status'=>$Guid_status, ));

    $patientTotal = 0;
    $insuranceTotal = 0;
    $total = 0;
    $revenueTotalsData = array();
    if(!empty($users)){
        
        foreach ($users as $user){
            
            $revenuDetails = getRevenueStat($db, $user['Guid_user']);
           
            if(!empty($revenuDetails)){
                $patientTotal += $revenuDetails['patient_paid'];
                $insuranceTotal += $revenuDetails['insurance_paid'];
                $total += $revenuDetails['total'];
            }
        }
    }
    $revenueTotalsData['patient_total'] = $patientTotal;
    $revenueTotalsData['insurance_total'] = $insuranceTotal;
    $revenueTotalsData['total'] = $total;
    
    return $revenueTotalsData;    
}

/**
 * Top Nav Links
 * @param type $role
 * @return string
 */
function topNavLinks($role=FALSE){
    $content = '<a href="'.SITE_URL.'/dashboard.php?logout=1" name="log_out" class="button red back logout"></a>';
    if($role=='Physician'){
        $content .= '<a href="'.SITE_URL.'/accounts.php" class="button homeIcon"></a>';
    }else{
       $content .= '<a href="'.SITE_URL.'/dashboard2.php" class="button homeIcon"></a>'; 
    }    
    $content .= '<a href="https://www.mdlab.com/questionnaire" target="_blank" class="button submit smaller_button"><strong>View Questionnaire</strong></a>';

    return $content;
}


function get_status_state($db, $parent = 0, $searchData=array(), $linkArr=array(), $today) {
    $statuses = array('28' => 'Registered Paient', '36' => 'Completed Questionnaire', '16' => 'Insufficient Informatin' , '29' => 'Medically Qualified' );
    $filterUrlStr = "";
    $content = '';    
    foreach ($statuses as $key => $status) {
        $stats1 = get_stats_info($db, $key, FALSE, $searchData);
        $stats2 = get_stats_info_today($db, $key, FALSE, $searchData, $today);
        $content .= "<tr class='parent'>";
        $content .= "<td class='text-left'><span>".$status."</span></td>";            
        $content .= '<td><a>'.$stats2['count'].'</a></td>';
        $content .= '<td><a>'.$stats1['count'].'</a></td>';
        $content .= "</tr>";    
    }    
    return $content;
}


function get_stats_info_today($db, $statusID, $hasChildren=FALSE, $searchData=array(), $today){    
    //exclude test users
    $markedTestUserIds = getMarkedTestUserIDs($db);
    $testUserIds = getTestUserIDs($db);
    $filterUrlStr = "";
    //$testUserIds = '';
    $q = "SELECT statuses.*, statuslogs.*,
            mdlnum.mdl_number as mdl_number
            FROM `tbl_mdl_status` statuses
            LEFT JOIN `tbl_mdl_status_log` statuslogs ON statuses.`Guid_status`= statuslogs.`Guid_status`
            LEFT JOIN `tbl_mdl_number` mdlnum ON statuslogs.Guid_user=mdlnum.Guid_user ";
    
    $q .=  "WHERE  statuslogs.`currentstatus`='Y' AND DATE(statuslogs.`Date`) =:today ";
    
    if(!empty($searchData)){ 
        if (isset($searchData['from_date']) && $searchData['from_date']!="" && isset($searchData['to_date']) && $searchData['to_date']!="") {
            if ($searchData['from_date'] == $searchData['to_date']) {
                $q .= " AND statuslogs.Date LIKE '%" . date("Y-m-d", strtotime($searchData['from_date'])) . "%'";
            } else {
                $q .= " AND statuslogs.Date BETWEEN '" . date("Y-m-d", strtotime($searchData['from_date'])) . "' AND '" . date("Y-m-d", strtotime($searchData['to_date'])) . "'";
            }
            $filterUrlStr .= "&from=".date("Y-m-d", strtotime($searchData['from_date']));
            $filterUrlStr .= "&to=".date("Y-m-d", strtotime($searchData['to_date']));
        }
        if(isset($searchData['mdl_number']) && $searchData['mdl_number']!=""){
            $q .= " AND mdl_number='".$searchData['mdl_number']."' ";
            $filterUrlStr .= "&mdnum=".$searchData['mdl_number'];
        }
        if(isset($searchData['Guid_salesrep']) && $searchData['Guid_salesrep']!=""){
            $q .= " AND statuslogs.Guid_salesrep='".$searchData['Guid_salesrep']."' ";
            $filterUrlStr .= "&salesrep=".$searchData['Guid_salesrep'];
        }
        if(isset($searchData['Guid_account']) && $searchData['Guid_account']!=""){
            $q .= " AND statuslogs.Guid_account='".$searchData['Guid_account']."' ";
            $filterUrlStr .= "&account=".$searchData['Guid_account'];
        }
    }
  
    $q .=  " AND statuslogs.`Guid_status_log`<>'' 
            AND statuslogs.Guid_status=$statusID ";
    if($markedTestUserIds!=""){
    $q .=  " AND statuslogs.Guid_user NOT IN(".$markedTestUserIds.") "; 
    }
    if($testUserIds!=""){
    $q .=  " AND statuslogs.Guid_user NOT IN(".$testUserIds.") ";   
    }
    $q .=  " AND statuslogs.Guid_patient<>'0' 
            ORDER BY statuslogs.`Date` DESC, statuses.`order_by` DESC";
    
    $stats = $db->query($q, array('today'=>$today));
    $result['count'] = 0;
    if(!empty($stats)){
        $result['count'] = count($stats);
        $result['filterUrlStr'] = $filterUrlStr;
        $result['info'] = $stats;
    }  
    
    return $result;
}

function checkedAccountData($db, $accountNum){
    $checkAccount = $db->row("SELECT * FROM tblaccount WHERE account=:account", array('account'=>$accountNum));
    $accountData = array();
    if(!empty($checkAccount)){ 
        $Guid_account = $checkAccount['Guid_account'];
        $whereAccount = array('Guid_account'=>$Guid_account);
        if(isset($checkAccount['account']) && $checkAccount['account']==''){
            if(isset($checkAccount['account'])){
                $accountData['account'] = $accountNum;
            }
        }
        if(isset($checkAccount['name']) && $checkAccount['name']==''){
            if(isset($data['account']['name'])){
                $accountData['account'] = $data['account']['name'];
            }
        }
        if(isset($checkAccount['address']) && $checkAccount['address']==''){
             if(isset($data['account']['addr1'])){
                $accountData['address'] = $data['account']['addr1'];
            }  
        }
        if(isset($checkAccount['address2']) && $checkAccount['address2']==''){
            if(isset($data['account']['addr2'])){
                $accountData['address2'] = $data['account']['addr2'];
            }
        }
        if(isset($checkAccount['city']) && $checkAccount['city']==''){
            if(isset($data['account']['city'])){
                $accountData['city'] = $data['account']['city'];
            }
        }
        if(isset($checkAccount['state']) && $checkAccount['state']==''){
            if(isset($data['account']['state'])){
                $accountData['state'] = $data['account']['state'];
            }
        }
        if(isset($checkAccount['zip']) && $checkAccount['zip']==''){
            if(isset($data['account']['zip'])){
                $accountData['zip'] = $data['account']['zip'];
            }
        }
        return $accountData;
    }
    
    return $accountData;
}

function updateOrInsertProvider($db,$accountNum, $Guid_account, $Guid_user, $apiProviderData){    
    //check provider   
    $checkProvider = $db->row("SELECT * FROM tblprovider WHERE account_id=:account_id", array('account_id'=>$accountNum)); 
    if(!empty($checkProvider)){ //update fields which are empty                                    
        
        $Guid_provider = $checkProvider['Guid_provider'];
        
        if(isset($checkProvider['first_name']) && $checkProvider['first_name']==''){
            $providerData['first_name'] = $apiProviderData['FirstName'];
        }
        if(isset($checkProvider['last_name']) && $checkProvider['last_name']==''){
            $providerData['last_name'] = $apiProviderData['LastName'];
        }
        if(isset($Guid_provider) && !empty($providerData)){
            $updateProvider = updateTable($db, 'tblprovider', $providerData, array('Guid_provider'=>$Guid_provider));
        }                                
    } else { //insert
        $providerData = array(
            'Guid_user'=>$Guid_user,
            'Guid_account'=>$Guid_account,
            'account_id'=>$accountNum,
            'Loaded'=>'Y'
        );
        if(isset($data['Physician']['FirstName'])){
            $providerData['first_name'] = $apiProviderData['FirstName'];
        }
        if(isset($data['Physician']['LastName'])){
            $providerData['last_name'] = $apiProviderData['LastName'];
        }
        $insertProvider = insertIntoTable($db, 'tblprovider', $providerData);
        $Guid_provider = $insertProvider['insertID'];
    }

    return $Guid_provider;
    
}

/**
 * API Date format month-day-year ex. 07-31-2017
 * Converted date year-month-day ex. 2017-07-31
 * @param type $date
 * @return string
 */
function convertDmdlDate($date){
    $dateExp  = explode("-", $date) ;            
    $convertedDate = $dateExp['2']."-".$dateExp['0']."-".$dateExp['1'];
    
    return $convertedDate;
}

function getPaientPossibleMatch($db,$firstname,$lastname,$Date_Of_Birth){
    $SQuery = "SELECT p.Guid_patient, p.Guid_user, p.dob,"
            . "AES_DECRYPT(p.firstname_enc, 'F1rstn@m3@_%') as firstname,"
            . "AES_DECRYPT(p.lastname_enc, 'L@stn@m3&%#') as lastname "
            . "FROM tblpatient p "
            . "LEFT JOIN tbluser u ON u.Guid_user = p.Guid_user "
            . "WHERE u.marked_test='0' AND u.Loaded='N' "
            . "AND (LOWER(CONVERT(AES_DECRYPT(p.firstname_enc, 'F1rstn@m3@_%') USING 'utf8')) LIKE '%".strtolower($firstname)."%' "
            . "OR LOWER(CONVERT(AES_DECRYPT(p.lastname_enc, 'L@stn@m3&%#') USING 'utf8')) LIKE '%".strtolower($lastname)."%' "
            . "OR p.dob='".convertDmdlDate($Date_Of_Birth)."' )";

    $SGetPatient = $db->query($SQuery);
    
    return $SGetPatient;
}

function dmdl_refresh($db){ 
    require_once 'classes/xmlToArrayParser.php';
    ini_set("soap.wsdl_cache_enabled", 0);
    try {
        $opts = array('ssl' => array('ciphers'=>'RC4-SHA'));
        $client = new SoapClient('https://patientpayment.mdlab.com/MDL.WebService/BillingWebService?wsdl',
        array ('stream_context' => stream_context_create($opts),"exceptions"=>0));
    } catch (Exception $e) { 
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $headers .= 'From: billingcustomerservice@mdlab.com' . "\r\n";
        $message = "faultcode: " . $e->faultcode . ", faultstring: " . $e->faultstring;
        $subject = "SOAP Fault";  
        mail('agokhale@mdlab.com', $subject, $message, $headers);
        trigger_error("SOAP Fault: (faultcode: {$e->faultcode}, faultstring: {$e->faultstring})", E_USER_ERROR);
        return;
    }    
    //skip ToUpdate='N' 
    //select [toupdate = Y] and [time of now - updatedatetime > 1 hour or is null
    $dmdlResult = $db->query("SELECT * FROM tbl_mdl_dmdl "
            . "WHERE ToUpdate='Y' "
            . "AND UpdateDatetime IS NULL "
            . "OR UpdateDatetime = '' "
            . "OR UpdateDatetime < NOW() - INTERVAL 60 MINUTE ");
    
    $content=""; $match=""; $possibleM ="";
    
    $content .= "<form action='' method='POST'>";
    
    $content .= "<div class='pB-15 text-right'>";
    $content .= "<button name='dmdlUpdate' type='submit' class='botton btn-inline'>Update</button>";
    //$content .= "<button name='dmdlCreateNew' type='submit' class='botton btn-inline'>Create New</button>";    
    $content .= "</div>";
    $content .= "<table id='refresh-log-table' class='table'>";
    $content .= "<thead>";
    $content .= "<tr class='tableTopInfo'>";
    $content .= "<th colspan='6' class='dmdl'>dMDL</th>";
    $content .= "<th colspan='3' class='tbl-borderR braca'>BRCA Admin</th>";
    $content .= "</tr>";
    $content .= "<tr class='tableHeader'>";
    $content .= "<th>Matched</th>";    
    $content .= "<th>Patient F Name</th>";
    $content .= "<th>Patient L Name</th>";
    $content .= "<th>DOB</th>";
    $content .= "<th>MDL#</th>";
    $content .= "<th>Account#</th>";
    $content .= "<th class='tbl-borderR'>Possible Match</th>";
    $content .= "<th>
                    <label class='switch'>
                        <input class='selectAllCheckboxes' type='checkbox'>
                        <span class='slider round'>
                            <span id='switchLabel' class='switchLabel'>Select All</span>
                        </span>
                    </label>
                </th>";
    $content .= "</tr></thead>";
    $content .= "<tbody>";
    foreach ( $dmdlResult as $k=>$v ){
        $param = array(
            "patientId" => $v['PatientID'], 
            "physicianId" => $v['PhysicianID']
        );
        $result = (array)$client->GetCombinedResults($param);
        
        $domObj = new xmlToArrayParser($result['GetCombinedResultsResult']); 
        $domArr = $domObj->array; 
        if($domObj->parse_error){ 
            echo $domObj->get_xml_error();            
        } else {             
            $res = $domArr['CombinedResults']['GeneticResults'];
            //var_dump($res);
            //admin db date format 1993-01-25           
            
            $Guid_MDLNumber = $res['Guid_MDLNumber'];
            $Date_Of_Birth = $res['Date_Of_Birth'];          
            $firstname = $res['Patient_FirstName'];
            $lastname = $res['Patient_LastName'];           
            $accountNumber = $res['ClientID'];           
            
            $dob = str_replace('-','/',$Date_Of_Birth);
            
            $where = array(
                'firstname' => $firstname,
                'lastname' => $lastname,
                'dob' => convertDmdlDate($Date_Of_Birth)
            );
            $dobConverted = convertDmdlDate($Date_Of_Birth);
            $query = "SELECT Guid_patient,Guid_user,dob,"
                    . "aes_decrypt(firstname_enc, 'F1rstn@m3@_%') as firstname,"
                    . "aes_decrypt(lastname_enc, 'L@stn@m3&%#') as lastname FROM tblpatient "
                    . "WHERE LOWER(CONVERT(AES_DECRYPT(firstname_enc, 'F1rstn@m3@_%') USING 'utf8'))='".strtolower($firstname)."' "
                    . "AND LOWER(CONVERT(AES_DECRYPT(lastname_enc, 'L@stn@m3&%#') USING 'utf8'))='".strtolower($lastname)."' "
                    . "AND dob='$dobConverted'";
            $getPatient = $db->query($query, $where );
            
            //$getPatient = getPaientPossibleMatch($db,$firstname,$lastname,$Date_Of_Birth);
         
            $content .= "<tr>";
            if(empty($getPatient)){ //patient not match with dmdl data => ststus=no
                $match = "<td class='mn no'>"
                        . "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][status]' value='no' />"
                        . "No</td>"; 
                $SGetPatient = getPaientPossibleMatch($db,$firstname,$lastname,$Date_Of_Birth);                
                $sContent = "";  $sOption = ""; $matchedPatient=array();
                if(!empty($SGetPatient)){
                    foreach ($SGetPatient as $k=>$v){
                        $matchedPatient = $v;
                        $this_Guid_user = $v['Guid_user'];
                        $sqlQualify = "SELECT q.account_number FROM tbl_ss_qualify q WHERE Guid_user=:Guid_user ORDER BY q.`Date_created` DESC LIMIT 1 ";
                        $qualifyResult = $db->row($sqlQualify, array('Guid_user'=>$this_Guid_user)); 
                        $mdlNumberResult = $db->query("SELECT `mdl_number` FROM `tbl_mdl_number` WHERE Guid_user=:Guid_user", array('Guid_user'=>$this_Guid_user));
                        $mdl_num = "";                         
                        if(!empty($mdlNumberResult)){
                            foreach ($mdlNumberResult as $key=>$val){
                                $mdl_num .= $val['mdl_number'].'';
                            }
                        }
                        $sOption .= "<option value='".$v['Guid_patient']."'>";                        
                        $sOption .= ucwords(strtolower($v['firstname']." ".$v['lastname']));
                        $sOption .= " (".date("m/d/Y", strtotime($matchedPatient['dob'])).") ";
                        $sOption .= "Acct#: ".$qualifyResult['account_number'].", ";
                        if($mdl_num!=''){
                            $sOption .= "MDL#: ".$mdl_num.", ";
                        }
                        $sOption .= "Patient ID: ".$v['Guid_patient'];
                        $sOption .= "</option>";
                    }
                    
                    $sContent .= "<select name='dmdl[".$Guid_MDLNumber."][Possible_Match]'>";
                    $sContent .= "<option value=''>Select From Possible Match</option>";
                    $sContent .= "<option value='create_new'>Create New</option>";
                    $sContent .= $sOption;                    
                    $sContent .= "</select>";
                } else {
                    //if there is not possible match it should create new records
                    $sContent .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][Possible_Match]' value='create_new' />";
                }
                $possibleM = "<td class='tbl-borderR'>".$sContent."</td>";
            } else {                
                if(count($getPatient)>1){ //duplicate records => status=duplicate                  
                    $match = "<td class='hasDuplicate'>"
                            . "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][status]' value='duplicate' />"
                            . "Duplicate</td>";
                   
                    $SGetPatient = getPaientPossibleMatch($db,$firstname,$lastname,$Date_Of_Birth);

                    $sContent=""; $sOption=""; $mdl_num = ""; 
                    if(!empty($SGetPatient)){
                        foreach ($SGetPatient as $k=>$v){
                            $matchedPatient=$v;
                            $this_Guid_user = $v['Guid_user'];
                            $sqlQualify = "SELECT q.account_number FROM tbl_ss_qualify q WHERE Guid_user=:Guid_user ORDER BY q.`Date_created` DESC LIMIT 1 ";
                            $qualifyResult = $db->row($sqlQualify, array('Guid_user'=>$this_Guid_user));
                            
                            $mdlNumberResult = $db->query("SELECT `mdl_number` FROM `tbl_mdl_number` WHERE Guid_user=:Guid_user", array('Guid_user'=>$this_Guid_user));
                            
                            if(!empty($mdlNumberResult)){
                                foreach ($mdlNumberResult as $key=>$val){
                                    $mdl_num .= $val['mdl_number'].'';
                                }
                            }
                            
                            $sOption .= "<option value='".$v['Guid_patient']."'>";                        
                            $sOption .= ucwords(strtolower($v['firstname']." ".$v['lastname']));
                            $sOption .= " (".date("m/d/Y", strtotime($matchedPatient['dob'])).") ";
                            $sOption .= "Acct#: ".$qualifyResult['account_number'].", ";
                            if($mdl_num!=''){
                                $sOption .= "MDL#: ".$mdl_num.", ";
                            }
                            $sOption .= "Patient ID: ".$v['Guid_patient'];
                            $sOption .= "</option>";
                        }
                        
                        $sContent .= "<select name='dmdl[".$Guid_MDLNumber."][Possible_Match]'>";
                        $sContent .= "<option value=''>Select From Possible Match</option>";
                        $sContent .= "<option value='create_new'>Create New</option>";
                        $sContent .= $sOption;
                        $sContent .= "</select>";
                    } else { //create new records if possibe match not found
                        $sContent .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][Possible_Match]' value='create_new' />";
                    }
                    $possibleM = "<td class='tbl-borderR'>".$sContent."</td>";
                }else{ 
                    //update mdl# for this perfect match => status=yes 
                    $matchedPatient = $getPatient['0'];
                    //update the Physician MDL ID Guid_dmdl_patient
                    $update_dmdl_patient = updateTable($db, 'tblpatient', array('Guid_dmdl_patient'=>$res['Guid_PatientId'], 'Guid_dmdl_physician'=>$res['GUID_PhysicianID']), array('Guid_patient'=>$matchedPatient['Guid_patient']));
                    $acctLink = ''; 
                    if(isset($matchedPatient['Guid_user'])){
                        $this_Guid_user = $matchedPatient['Guid_user'];
                        $sqlQualify = "SELECT q.account_number FROM tbl_ss_qualify q WHERE Guid_user=:Guid_user ORDER BY q.`Date_created` DESC LIMIT 1 ";
                        $qualifyResult = $db->row($sqlQualify, array('Guid_user'=>$this_Guid_user));
                        if(isset($qualifyResult['account_number'])&&$qualifyResult['account_number']!=''){
                            $acctLink = '&account='.$qualifyResult['account_number'];
                        }
                        $mdlNumberResult = $db->query("SELECT `mdl_number` FROM `tbl_mdl_number` WHERE Guid_user=:Guid_user", array('Guid_user'=>$this_Guid_user));
                        $mdl_num = '';
                        if(!empty($mdlNumberResult)){
                            $mdlNumMatch = False;
                            foreach ($mdlNumberResult as $key => $val) {
                                if($val['mdl_number']!=''){
                                    if($val['mdl_number']==$Guid_MDLNumber){
                                        $mdlNumMatch = True;
                                    }
                                    $mdl_num .= $val['mdl_number'].', ';
                                }
                            } 
                            $mdl_num = rtrim($mdl_num, ', ');
                        }
                    }
                    $patientInfoLink = "<a href='".SITE_URL."/patient-info.php?patient=".$matchedPatient['Guid_user'].$acctLink."'>";                        
                    $patientInfoLink .= ucwords(strtolower($matchedPatient['firstname']." ".$matchedPatient['lastname']));
                    $patientInfoLink .= " (".date("m/d/Y", strtotime($matchedPatient['dob'])).") ";
                    if(isset($qualifyResult['account_number'])&&$qualifyResult['account_number']!=''){
                        $patientInfoLink .= "Acct#: ".$qualifyResult['account_number'].", ";
                    }
                    if($mdl_num!=''){
                        if($mdlNumMatch){
                            $patientInfoLink .= "MDL#: ".$mdl_num.", ";
                            $patientInfoLink .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][Possible_Match]' value='".$matchedPatient['Guid_patient']."' />";
                        }else{
                            $patientInfoLink .= "<span class='color-red'>MDL#: ".$mdl_num."</span>, ";
                            $patientInfoLink .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][Possible_Match]' value='create_new' />";
                        }
                    }
                    $patientInfoLink .= "Patient ID: ".$matchedPatient['Guid_patient'];
                    $patientInfoLink .= "</a>";

                    
                    $match = "<td class='mn yes'>"
                            . "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][status]' value='yes' />"
                            . "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][Guid_patient]' value='".$getPatient['0']['Guid_patient']."' />"
                            . "Yes</td>";  
                    $possibleM = "<td  class='tbl-borderR'>".$patientInfoLink."</td>";
                }
            }
            
            $content .= $match; //match status=>yes,no,duplicate             
            $content .= "<td>".ucwords(strtolower($firstname))."</td>";
            $content .= "<td>".ucwords(strtolower($lastname))."</td>";
            $content .= "<td>$dob</td>"; 
            $content .= "<td>$Guid_MDLNumber</td>";
            $content .= "<td>$accountNumber</td>";            
            $content .= $possibleM;
            
            $content.= "<td class='text-center'>"
                    . "<input name='dmdl[selected][".$Guid_MDLNumber."]' type='checkbox' class='checkboxSelect' />"
                    . "</td>";            
            
            //hidden inputs            
            if(isset($res['Guid_MDLNumber']) && !empty($res['Guid_MDLNumber'])){
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][mdlnumber]' value='". $res['Guid_MDLNumber']."' />";
            }
            if(isset($res['Patient_FirstName']) && !empty($res['Patient_FirstName'])){
                $content .=  "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][firstname]' value='".$res['Patient_FirstName']."' />";
            }
            if(isset($res['Patient_LastName']) && !empty($res['Patient_LastName'])){
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][lastname]' value='".$res['Patient_LastName']."' />";
            }
            if(isset($res['Date_Of_Birth'])&&!empty($res['Date_Of_Birth'])){
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][dob]' value='".convertDmdlDate($Date_Of_Birth)."' />";
            }
            if(isset($res['Guid_PatientId']) && !empty($res['Guid_PatientId'])){
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][Guid_PatientId]' value='".$res['Guid_PatientId']."' />";
            }
            if(isset($res['GUID_PhysicianID'])&&!empty($res['GUID_PhysicianID'])){
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][Physician][GUID_PhysicianID]' value='".$res['GUID_PhysicianID']."' />";
            }
            if(isset($res['Physician_FirstName'])&&!empty($res['Physician_FirstName'])){
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][Physician][FirstName]' value='".$res['Physician_FirstName']."' />";
            }
            if(isset($res['Physician_LastName'])&&!empty($res['Physician_LastName'])){
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][Physician][LastName]' value='".$res['Physician_LastName']."' />";
            }
            if(isset($res['ClientID']) && !empty($res['ClientID'])){ //account number
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][account][number]' value='".$res['ClientID']."' />";
            }
            if(isset($res['ClientName']) && !empty($res['ClientName'])){ //account name
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][account][name]' value='".$res['ClientName']."' />";
            }
            if(isset($res['ClientAddress1'])&&!empty($res['ClientAddress1'])){
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][account][addr1]' value='".$res['ClientAddress1']."' />";
            }
            if(isset($res['ClientAddress2']) && !empty($res['ClientAddress2'])){
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][account][addr2]' value='".$res['ClientAddress2']."' />";
            }
            if(isset($res['ClientCity']) && !empty($res['ClientCity'])){
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][account][city]' value='".$res['ClientCity']."' />";
            }
            if(isset($res['ClientState']) && !empty($res['ClientState'])){
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][account][state]' value='".$res['ClientState']."' />";
            }
            if(isset($res['ClientZip'])&&!empty($res['ClientZip'])){
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][account][zip]' value='".$res['ClientZip']."' />";
            }
            if(isset($res['Insurance_Company']) && !empty($res['Insurance_Company'])){
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][insurance_full]' value='".$res['Insurance_Company']."' />";
            }
            //statuses
            if(isset($res['DOS']) && !empty($res['DOS'])){
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][statuses][SpecimenCollected][Date]' value='".convertDmdlDate($res['DOS'])."' />";
            }
            if(isset($res['Date_Accessioned']) && !empty($res['Date_Accessioned'])){
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][statuses][SpecimenAccessioned][Date]' value='".convertDmdlDate($res['Date_Accessioned'])."' />";
            }
            if(isset($res['TestCode']) && !empty($res['TestCode'])){
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][statuses][SpecimenAccessioned][Test]' value='".$res['TestCode']."' />";
            }
                        
            if(isset($res['Genetic_Counseling_Status']) && !empty($res['Genetic_Counseling_Status'])){
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][statuses][Genetic_Counseling][Status]' value='".$res['Genetic_Counseling_Status']."' />";
            }
            if(isset($res['Genetic_Counseling_Status_Date']) && !empty($res['Genetic_Counseling_Status_Date'])){
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][statuses][Genetic_Counseling][Date]' value='".convertDmdlDate($res['Genetic_Counseling_Status_Date'])."' />";
            }
            if(isset($res['Testing_Status']) && !empty($res['Testing_Status'])){
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][statuses][Laboratory_Testing][Status]' value='".$res['Testing_Status']."' />";
            }
            if(isset($res['Testing_Status_Date']) && !empty($res['Testing_Status_Date'])){
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][statuses][Laboratory_Testing][Date]' value='".convertDmdlDate($res['Testing_Status_Date'])."' />";
            }
            if(isset($res['DateTime_ResultStatus']) && !empty($res['DateTime_ResultStatus'])){
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][statuses][Laboratory_Testing_Status_Complete_Date]' value='".convertDmdlDate($res['DateTime_ResultStatus'])."' />";
            }
            //Revenue section on the screen
            if(isset($res['Payor']) && !empty($res['Payor'])){
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][revenue][Payor]' value='".$res['Payor']."' />";
            }
            if(isset($res['Patient_Responsibility']) && !empty($res['Patient_Responsibility'])){
                $content .= "<input type='hidden' name='dmdl[".$Guid_MDLNumber."][revenue][PatientAmount]' value='".$res['Patient_Responsibility']."' />";
            }
            
            $content .= "</tr>";
        }    
    }
    $content .= "</tbody>";
    $content .= "</table>";
    $content .= "</form>";
  
    return $content;       
}

function insertDmdlStatuses($db,$statuses,$data, $dmdl_mdl_number){    
    //update tbl_mdl_dmdl UpdateDatetime    
    updateTable($db, 'tbl_mdl_dmdl', array('UpdateDatetime'=> date('Y-m-d h:i:s')), array('MDLNumber'=>$dmdl_mdl_number));
    
    $statusLogData = array(
        'Loaded' => 'Y',
        'Guid_user' => $data['Guid_user'],
        'Guid_patient'=> $data['Guid_patient'],
        'Guid_account' => $data['Guid_account'],
        'account' => $data['account'],
        'Guid_salesrep' => $data['Guid_salesrep'],
        'salesrep_fname' => $data['salesrep_fname'],
        'salesrep_lname' => $data['salesrep_lname'],
        'Recorded_by' => $_SESSION['user']['id'],  
        'provider_id' => $data['provider_id'],
        'deviceid' => $data['deviceid'],        
        'Date_created'=>date('Y-m-d h:i:s')
    );
        
    if(isset($statuses['SpecimenCollected']['Date'])){
        $statusLogData['Date'] = $statuses['SpecimenCollected']['Date'];
        updateTable($db, 'tblpatient', array('specimen_collected'=>'Yes'), array('Guid_patient'=>$data['Guid_patient']));
        saveStatusLog($db, array('1'), $statusLogData);
        updateCurrentStatusID($db, $data['Guid_patient']);
    }    
    if(isset($statuses['SpecimenAccessioned']['Date'])){
        $statusLogData['Date'] = $statuses['SpecimenAccessioned']['Date'];
        saveStatusLog($db, array('2'), $statusLogData);
        updateCurrentStatusID($db, $data['Guid_patient']);
    }
    if(isset($statuses['Genetic_Counseling']['Status'])){
        $statusGCIDs[] = '34'; //Patient Responsibility
        $statusGCIDs[] = '3'; //Patient Responsibility --> Genetic Counseling
        $statusName = $statuses['Genetic_Counseling']['Status']; //Pending->4,Completed->5,Waived?
        $getGuidStatusRow = $db->row("SELECT Guid_status FROM tbl_mdl_status WHERE `parent_id`='3' AND `status`='$statusName'");
        if(!empty($getGuidStatusRow)){
            $statusGCIDs[] = $getGuidStatusRow['Guid_status'];
            $statusLogData['Date'] = $statuses['Genetic_Counseling']['Date'];
            saveStatusLog($db, $statusGCIDs, $statusLogData);
            updateCurrentStatusID($db, $data['Guid_patient']);
        }
    }
    if(isset($statuses['Laboratory_Testing']['Status'])){ //Laboratory Testing: Pending
        $statusLTIDs[] = '17'; //Laboratory Testing Status        
        $statusName = $statuses['Laboratory_Testing']['Status']; //Pending->18
        if($statusName=='Pending'){
            $statusLTIDs[] = '18';
        } else {
            $statusName = '';
        }        
        if($statusName!=''){
            $statusLogData['Date'] = $statuses['Laboratory_Testing']['Date'];
            saveStatusLog($db, $statusLTIDs, $statusLogData);
            updateCurrentStatusID($db, $data['Guid_patient']);            
        }
    }
    if(isset($statuses['Laboratory_Testing_Status_Complete_Date'])){ //Laboratory Testing: Complete
        $statusLTCIDs[] = '17'; //Laboratory Testing Status        
        $statusLTCIDs[] = '20'; //Complete->20
        $statusLogData['Date'] = $statuses['Laboratory_Testing_Status_Complete_Date'];
        saveStatusLog($db, $statusLTCIDs, $statusLogData);
        updateCurrentStatusID($db, $data['Guid_patient']);    
    }
    
    
    
}

function updatePatientData($db,$data,$where){   
    $updateFields = "";
    $whereStr = "";
    $executeArray = array();
    foreach ($data as $key => $val) {
        if($key=='firstname_enc'){           
            $updateFields .= "`$key`=AES_ENCRYPT('$val', 'F1rstn@m3@_%'), ";
        } elseif ($key=='lastname_enc') {            
            $updateFields .= "`$key`=AES_ENCRYPT('$val', 'L@stn@m3&%#'), ";
        } else {
            $updateFields .= "`$key`='$val', ";
        }
    }
    $updateFields = rtrim($updateFields,", ");   
    
    foreach ($where as $key => $val) {
        $whereStr = " WHERE `$key`=:$key";
        $executeArray["$key"] = $val;
    }   
    if($updateFields!=''){
        $query = "UPDATE `tblpatient` SET $updateFields $whereStr";
        $update = $db->query($query, $executeArray);

        return $update;  
    }
    return FALSE;
}