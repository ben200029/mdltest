<?php
ob_start();
require_once('config.php');
require_once('settings.php');
require_once('header.php');
if (!login_check($db)) {
    Leave(SITE_URL);
}
if (isset($_GET['logout'])) {
    logout();
    Leave(SITE_URL);
}
$userID = $_SESSION['user']["id"];
$roleInfo = getRole($db, $userID);
$role = $roleInfo['role'];

if($role!="Admin"){
    Leave(SITE_URL."/no-permission.php");
}

$userTables = array(
    'admin'=>'tbladmins',
    'patient'=>'tblpatient',
    'salesrep'=>'tblsalesrep',
    'tblprovider'=>'tblprovider'
);
$selectQ = "";$adminQ="";$patientsQ="";$salesrepsQ="";$providersQ="";
foreach ($userTables as $k=>$v){
    $selectQ = "SELECT u.Guid_user, u.status, uInfo.first_name, uInfo.last_name, u.email, r.role";

    if($k=='patient'){
        $selectQ = "SELECT u.Guid_user, u.status, uInfo.Guid_patient, uInfo.firstname AS first_name, uInfo.lastname AS last_name, u.email, r.role";
    }
    if($k=='admin'){
        $roleID = '1'; 
    } elseif ($k=='patient') {
        $roleID = '3'; 
    } elseif ($k=='salesrep') {
       $roleID = '4 OR urole.Guid_role=5'; 
    } elseif ($k=='tblprovider') {
        $roleID = '2'; 
    }    
    
    $selectQ .= " FROM $v uInfo
                LEFT JOIN `tbluser` u ON uInfo.Guid_user=u.Guid_user
                LEFT JOIN `tbluserrole` urole ON uInfo.Guid_user=urole.Guid_user AND urole.Guid_role=$roleID
                LEFT JOIN `tblrole` r ON r.Guid_role=urole.Guid_role";
    
    if($k=='admin'){
        $adminQ = $selectQ;
    } elseif ($k=='patient') {
        $patientsQ = $selectQ;
    } elseif ($k=='salesrep') {
        $salesrepsQ = $selectQ;
    } elseif ($k=='tblprovider') {
        $providersQ = $selectQ;
    }
}


$query1 = ($adminQ!="")?$db->query($adminQ):array();
$query2 = ($patientsQ!="")?$db->query($patientsQ):array();
$query3 = ($salesrepsQ!="")?$db->query($salesrepsQ):array();
$query4 = ($providersQ!="")?$db->query($providersQ):array();

$users = array_merge($query1,$query2,$query3,$query4);

$thisMessage="";

if(isset($_GET['delete-user']) && $_GET['delete-user']!=""){
    deleteUserByID($db, $_GET['delete-user']);    
    Leave(SITE_URL."/user-management.php");
}

require_once ('navbar.php');
?>

<!--SEARCH FORM BLOCK Start-->
<aside id="action_palette" >		
    <div class="box full">
        <h4 class="box_top">Filters</h4>
        
        <div class="boxtent scroller ">
            <form id="filter_form" action="" method="post">             
<!--                <div class="f2<?php echo ((!isset($_POST['clear'])) && (isset($_POST['first_name'])) && (strlen(trim($_POST['first_name'])))) ? " show-label valid" : ""; ?>">
                    <label class="dynamic" for="first_name"><span>First Name</span></label>

                    <div class="group">
                        <input id="first_name" name="first_name" type="text" value="<?php echo ((!isset($_POST['clear'])) && isset($_POST['first_name']) && strlen(trim($_POST['first_name']))) ? trim($_POST['first_name']) : ""; ?>" placeholder="First Name">

                        <p class="f_status">
                            <span class="status_icons"><strong></strong></span>
                        </p>
                    </div>
                </div>-->
                    <div class="f2<?php echo ((!isset($_POST['clear'])) && (isset($_POST['Guid_role'])) && (strlen($_POST['Guid_role']))) ? " show-label valid" : ""; ?>">
                        <label class="dynamic" for="Guid_role"><span>User Role</span></label>

                        <div class="group">
                            <select id="Guid_role" name="Guid_role" class="<?php echo ((!isset($_POST['clear'])) && (isset($_POST['location'])) && (strlen($_POST['location']))) ? "" : "no-selection"; ?>">
                                <option value="">User Role</option>							
                                <?php
                                $roles = $db->query("SELECT * FROM tblrole");

                                foreach ($roles as $k=>$v) {
                                    ?>
                                    <option value="<?php echo $v['Guid_role']; ?>"<?php echo ((!isset($_POST['clear'])) && (isset($_POST['Guid_role']) && ($_POST['Guid_role'] == $v['Guid_role'])) ? " selected" : ""); ?>><?php echo $v['role']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>

                            <p class="f_status">
                                <span class="status_icons"><strong></strong></span>
                            </p>
                        </div>
                    </div>
                
                    <div class="f2<?php echo ((!isset($_POST['clear'])) && (isset($_POST['Guid_role'])) && (strlen($_POST['Guid_role']))) ? " show-label valid" : ""; ?>">
                        <label class="dynamic" for="status"><span>Status</span></label>

                        <div class="group">
                            <select id="Guid_role" name="status" class="<?php echo ((!isset($_POST['clear'])) && (isset($_POST['status'])) && (strlen($_POST['status']))) ? "" : "no-selection"; ?>">
                                <option value="">Status</option>                                
                                <option value="1" <?php echo ((!isset($_POST['clear'])) && (isset($_POST['status']) && ($_POST['status'] == $v['status'])) ? " selected" : ""); ?>>Active</option>
                                <option value="0" <?php echo ((!isset($_POST['clear'])) && (isset($_POST['status']) && ($_POST['status'] == $v['status'])) ? " selected" : ""); ?>>Inactive</option>
                              
                            </select>

                            <p class="f_status">
                                <span class="status_icons"><strong></strong></span>
                            </p>
                        </div>
                    </div>
               

                
                <button id="filter" value="1" name="search" type="submit" class="button filter half"><strong>Search</strong></button>
                <button type="submit" name="clear" class="button cancel half"><strong>Clear</strong></button>
            </form>
            <!--********************   SEARCH BY PALETTE END    ******************** -->

        </div>
    </div>    
</aside>
<!--SEARCH FORM BLOCK END-->


<main >
    <?php 
    
    if(isset($_GET['update']) ){ 
        $thisMessage = "Changes have been saved";
    }
    if($thisMessage != ""){  ?>
    <section id="msg_display" class="show success">
        <h4><?php echo $thisMessage;?></h4>
    </section>
    <?php } ?> 
    <div class="box full visible ">  
        <section id="palette_top">
            <h4>  
                <ol class="breadcrumb">
                    <li><a href="<?php echo SITE_URL; ?>">Home</a></li>
                    <li class="active">User Management</li>                   
                </ol>      
            </h4>
            <a href="<?php echo SITE_URL; ?>/dashboard.php?logout=1" name="log_out" class="button red back logout"></a>
            <a href="https://www.mdlab.com/questionnaire" target="_blank" class="button submit"><strong>View Questionnaire</strong></a>
        </section>
        <div class="scroller">  
            <div class="row">                   
                <div class="col-md-7">
                  
                </div>               
                <div class="col-md-5">                    
                    <a class="add-new-button pull-right" href="<?php echo SITE_URL; ?>/user-management.php?action=add">
                        <span class="fas fa-user-plus" aria-hidden="true"></span> Add 
                    </a>
<!--                    <form action="" method="POST">
                        <button name="show-duplicates" type="submit" value="1" class="pull-right button  add-new-button"><i class="fas fa-clone"></i> Show User Duplicates</button>
                    </form>-->
                </div>                 
            </div>               
            <div class="row">
                <div class="col-md-12">
                    <table id="dataTable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th class="actions">#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th class="noFilter actions text-center">Status</th>
                                <th class="noFilter actions text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $k=>$user){  ?>
                            <tr>
                                <td><?php echo $user['Guid_user']; ?></td>
                                <td><?php echo $user['first_name']; ?> </td>
                                <td><?php echo $user['last_name']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td><?php echo isset($user['role'])?$user['role']:'Patient'; ?></td>
                                <td class="text-center fs-20">
                                    <?php if($user['status']=='1') {
                                       echo "<span class='fas fa-user-check mn yes'></span>"; 
                                    } else {
                                        echo "<span class='fas fa-user-alt-slash mn no'></span>"; 
                                    }?>
                                </td>
                                <td class="text-center">   
                                    <?php 
                                        $editUrl = "";
                                        if($user['Guid_user'] != ""){
                                            $editUrl = '&id='.$user['Guid_user'];
                                        } else {
                                            if(isset($user['Guid_patient'])){
                                                $editUrl = '&id='.$user['Guid_user'].'&patient_id='.$user['Guid_patient'];
                                            }
                                        }
                                    ?>
                                    <a href="<?php echo SITE_URL; ?>/user-management.php?action=update<?php echo $editUrl; ?>">
                                        <span class="fas fa-pencil-alt" aria-hidden="true"></span>
                                    </a>                                       
                                    <a onclick="javascript:confirmationDeleteUser($(this));return false;" href="<?php echo SITE_URL; ?>/user-management.php?delete-user=<?php echo $user['Guid_user']; ?>&id=<?php echo $user['Guid_user']; ?>">
                                        <span class="far fa-trash-alt" aria-hidden="true"></span> 
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>           
        </div>
    </div>
</main>
<button id="action_palette_toggle" class=""><i class="fa fa-2x fa-angle-left"></i></button>

<?php     
    $first_name = "";
    $last_name = "";
    $photo_filename = "";
    $result = false;
    $message = "";
    if(isset($_POST['save_user'])){
        extract($_POST);
        $userData['email'] = $email;
        $userData['status'] = $status;
        
        if($_POST['password'] != ""){
            $userData['password'] = encode_password($password);
        }        
        if($Guid_role != ""){
            if($Guid_role=='1'){
                $userData['user_type'] = 'admin';
                $roleName = 'Admin';
            } elseif ($Guid_role=='2') {
                $userData['user_type'] = 'provider';
                $roleName = 'Physician';
            } elseif ($Guid_role=='3') {
                $userData['user_type'] = 'patient';
                $roleName = 'Patient';
            } elseif ($Guid_role=='4') {
                $userData['user_type'] = 'salesrep';
                $roleName = 'Sales Rep';
            } elseif ($Guid_role=='5') {
                $userData['user_type'] = 'salesmgr';
                $roleName = 'Sales Manager';
            }            
        }else{
            $userData['user_type'] = 'patient';
            $roleName = 'Patient';
        }
        
        if($Guid_user == ""){ //insert User 
            //checking for email unique
            $isMailExists = $db->row("SELECT `email` FROM tbluser WHERE `email`='".$email."' ");
            if(!$isMailExists){
                unset($userData['Guid_user']);
                $userData['Date_created'] = date('Y-m-d H:i:s');             
                $inserUser = insertIntoTable($db, 'tbluser', $userData);
                if($inserUser['insertID']){
                    $Guid_user = $inserUser['insertID'];
                    $saveRoles = saveUserRole($db, $Guid_user, $Guid_role); 
                    $result = TRUE;
                }        
            } else {
                $message = "User with this email already exists.";
            }
        }else{ //update
            $isMailExists = $db->row("SELECT `email` FROM tbluser WHERE `email`='".$email."' AND `Guid_user`<>$Guid_user");
            if(!$isMailExists){
                $userData['Date_modified'] = date('Y-m-d H:i:s');
                $whereUser = array('Guid_user'=>$Guid_user);
                
                $updateUser = updateTable($db, 'tbluser', $userData, $whereUser);            
                saveUserRole($db, $Guid_user, $Guid_role);
                
                $result = TRUE;
            } else {
                $message = "User with this email already exists.";
            }
        }
        
        if($result && $message==""){
            $fName = isset($_POST['first_name']) ? $_POST['first_name'] : "";
            $lName = isset($_POST['last_name']) ? $_POST['last_name'] : "";
            if($Guid_role=='3'){
                $userDetails = array('firstname'=>$fName, 'lastname'=>$lName);                    
            } else {
                $userDetails = array('first_name'=>$fName, 'last_name'=>$lName);
                if($_FILES["photo_filename"]["name"] != ""){
                    $fileName = $_FILES["photo_filename"]["name"];        
                    $userDetails['photo_filename'] = $fileName;
                    $uploadMsg = uploadFile('photo_filename', 'images/users/');
                }
            }
            saveUserDetails($db, $Guid_user, $Guid_role, $userDetails);            
            Leave(SITE_URL."/user-management.php?update");
        }
    }
  
    
    if(isset($_GET['action']) && $_GET['action'] !="" ){ 
        $userID = $_GET['id'];
        $user = getUserAndRole($db, $userID);        
        $allRoles = $db->selectAll('tblrole', ' ORDER BY role ASC'); 
        $patientID = isset($_GET['patient_id'])?$_GET['patient_id']:"";
        $userDetails = getUserDetails($db, $user['role'], $userID, $patientID);
        
        if($userDetails){
            extract($userDetails);
        }
        if(isset($_POST)){
            $userDetails = $_POST;
            if($userDetails){
                extract($userDetails);
                $user['email'] = $email;
                $user['role'] = $roleName;
                
            }            
        }
    
        
    $modalTitle = ($_GET['action']=="update")? "Update User" : "Add New User";
?>
<div id="patient-info-box" class="modalBlock">
    <div class="contentBlock">
        <a class="close" href="<?php echo SITE_URL."/user-management.php"; ?>">X</a>        
        <h2 class="text-center"><?php echo $modalTitle; ?></h2>
        <?php if($message!=""){ ?>
            <div class="error text-center" id="message"><?php echo $message; ?></div>
        <?php } ?>
        <form action="" method="POST" enctype="multipart/form-data"> 
            <div class="row">
                
                <input type="hidden" name="Guid_user" value="<?php echo isset($user['Guid_user'])?$user['Guid_user']:''; ?>" />
                
                <div class="col-md-6 col-md-offset-3">
                   
                    <div class="f2 <?php echo ($first_name!="")?"valid show-label":"";?>">
                        <label class="dynamic" for="first_name"><span>First Name</span></label>
                        <div class="group">
                            <input name="first_name" value="<?php echo $first_name; ?>" type="text" class="form-control" id="first_name" placeholder="First Name">
                            <p class="f_status">
                                <span class="status_icons"><strong>*</strong></span>
                            </p>
                        </div>
                    </div>
                    <div class="f2 <?php echo ($last_name!="")?"valid show-label":"";?>">
                        <label class="dynamic" for="last_name"><span>Last Name</span></label>
                        <div class="group">
                            <input name="last_name" value="<?php echo $last_name; ?>" type="text" class="form-control" id="last_name" placeholder="Last Name">
                            <p class="f_status">
                                <span class="status_icons"><strong>*</strong></span>
                            </p>
                        </div>
                    </div>
                    <div class="f2 required <?php echo ($user['role']!="")?"valid show-label":"";?>">
                        <label class="dynamic" for="reason_not"><span>User Roles</span></label>
                        <div class="group">
                            <?php $selUserRole = isset($_POST['user_type'])?$_POST['user_type']:$user['role'];?>
                            <select required id="user_type" name="Guid_role" class="<?php echo ($user['role']=="")?'no-selection':''; ?> ">
                                <option value="">User Roles</option>
                                <?php foreach ($allRoles as $role){ ?>
                                    <option <?php echo ($selUserRole==$role['role']) ? " selected": ""; ?> value="<?php echo $role['Guid_role']; ?>"><?php echo $role['role']; ?></option>   
                                <?php } ?>
                            </select>
                            <p class="f_status">
                                <span class="status_icons"><strong></strong></span>
                            </p>
                        </div>
                    </div>
                    <div class="f2 required <?php echo ($user['status']!="")?"valid show-label":"";?>">
                        <label class="dynamic" for="status"><span>Status</span></label>
                        <div class="group">
                            <?php $userStatus = isset($_POST['status'])?$_POST['status']:$user['status'];?>
                            <select required id="status" name="status">
                                <option value="">Status</option>
                                <option <?php echo ($userStatus=='1') ? " selected": ""; ?> value="1">Active</option>   
                                <option <?php echo ($userStatus=='0') ? " selected": ""; ?> value="0">Inactive</option>   
                            </select>
                            <p class="f_status">
                                <span class="status_icons"><strong></strong></span>
                            </p>
                        </div>
                    </div>
                    <div class="f2 required <?php echo ($user['email']!="")?"valid show-label":"";?>">
                        <label class="dynamic" for="email"><span>Email</span></label>
                        <div class="group">
                            <input required="" name="email" value="<?php echo $user['email']; ?>" type="text" class="form-control" id="email" placeholder="Email">
                            <p class="f_status">
                                <span class="status_icons"><strong>*</strong></span>
                            </p>
                        </div>
                    </div>                  
                    
                    <?php $passRequred = isset($_GET['add'])?' required':''; ?>
                    <div class="f2 <?php echo $passRequred; ?> ">
                        <label class="dynamic" for="password"><span>Password</span></label>
                        <div class="group">
                            <input <?php echo $passRequred; ?> name="password" type="password" class="form-control" id="password" placeholder="Password">
                            <p class="f_status">
                                <span class="status_icons"><strong>*</strong></span>
                            </p>
                        </div>
                    </div>
                    
                    <?php if($user['role']==NULL && $role != 'Patient') { //For patients we dont have photo field in DB ?>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="f2 <?php echo ($photo_filename!="")?"valid show-label":"";?>">
                                <label class="dynamic" for="photo"><span>Photo</span></label>
                                <div class="group">
                                    <input id="file" value="<?php echo $photo_filename; ?>" name="photo_filename" class="form-control pT-5" type="file" placeholder="Photo"/>
                                    <p class="f_status">
                                        <span class="status_icons"><strong>*</strong></span>
                                    </p>
                                </div>
                            </div>                    
                        </div>
                        <?php $image = (!isset($photo_filename) || $photo_filename=="")?"/assets/images/default.png":"/images/users/".$photo_filename; ?>
                        <div id="profile-pic" class="col-md-2 pT-30">
                            <img id="image" width="40" src="<?php echo SITE_URL.$image; ?>" />
                        </div>
                    </div>    
                    <?php } ?>
                    
                    
                </div>                
            </div>
            <div class="row actionButtons">
                <div class="col-md-6 col-md-offset-3 pT-20">
                    <button name="save_user" type="submit" class="button btn-inline">Save</button>
                </div>
            </div>
            
        </form>   
    </div>    
</div>
<?php } ?>

<?php require_once('scripts.php');?>
<script type="text/javascript">  
    
        var table = $('#dataTable').DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            //searching: false,
            //lengthChange: false,
            "pageLength": 25,
            "order": [[ 4, "asc" ]],
            "aoColumnDefs": [
              { 
                  
                  //"bSortable": false, 
                  "aTargets": [ 4,5 ] } 
            ]
        });   
 
</script>
<?php require_once('footer.php');?>