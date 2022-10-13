<?php
if(isset($_POST['catname'])){
include_once "../includes/db_conn.php";
include_once "../includes/func.inc.php";

    $catname  = htmlentities($_POST['catname']);
    $catstatus = htmlentities($_POST['catstatus']);
    
    
    //file upload initialization------------------
    $filecheckstat = true;
    $image_temp_file = $_FILES["catimage"]["tmp_name"];
    $baseitem_img = basename($_FILES["catimage"]["name"]);
    $ext = strtolower(pathinfo($baseitem_img,PATHINFO_EXTENSION));
    $target_dir = '../images';
    $target_filename = strtolower($catname).".".$ext; 
    
  
  $check = getimagesize($image_temp_file);
  $filecheckstat = $check !== false ? true : false;
  echo "<br>";
    
  $file_stat = checkImage($_FILES["catimage"], $target_dir, $target_filename);
    $file_err_count=0;
    $error_msg = null;
    foreach($file_stat as $key => $stat){
        if($stat != ''){
            $error_msg .= ($file_err_count+1). ": ". $stat ."<br>";
            $file_err_count++;
        }
    }
    if($error_msg !== null){
        header("location: control-dashboard.php?error={$error_msg}");
        exit();
    }
    //file upload initialization------------------
    $sql_check = "SELECT `cat_id`
                    FROM `category`
                   WHERE `cat_desc` = ? ; ";
    $stmt_chk = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt_chk, $sql_check)){
        header("location: control-dashboard.php?error=3"); //statement failed
        exit();
    }
    mysqli_stmt_bind_param($stmt_chk,"s",$catname);
    mysqli_stmt_execute($stmt_chk);
    $chk_result=mysqli_stmt_get_result($stmt_chk);
    $arr=array();
    while($row = mysqli_fetch_assoc($chk_result)){
        array_push($arr,$row);
    }
    if(!empty($arr)){
        header("location: control-dashboard.php?error=1&catname={$catname}"); //item exist
        exit();
    }
    else{
        $sql_ins = "INSERT INTO `category`
                  (`cat_desc`, `cat_icon`, `cat_status`) 
                   VALUES (?,?,?);";
        $stmt_ins = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt_ins, $sql_ins)){
			header("location: control-dashboard.php?error=2"); //insert failed
			exit();
        }
       
		mysqli_stmt_bind_param($stmt_ins,"sss",$catname,$target_filename,$catstatus);
		mysqli_stmt_execute($stmt_ins);
                
        if(!$file_err_count){
             //upload file.
            if (move_uploaded_file($image_temp_file, $target_dir."/".$target_filename)) {
                echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
              } else {
                header("location: control-dashboard.php?error=99"); //file upload failed
                exit();
              }
            
             header("location: control-dashboard.php?error=0&catname={$catname}"); 
        }
        
    }
}
