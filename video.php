<?php
      define('HOST','localhost');
	   define('USER','id6388318_gotadsapp');
	   define('PASS','GOT_App_Team4');
	   define('DB','id6388318_adverti_project');
	
	$con = mysqli_connect(HOST,USER ,PASS);
        @mysqli_select_db($con,DB);
        mysqli_set_charset($con, 'utf8');
        
if($_SERVER['REQUEST_METHOD']=='POST'){
 $file_name = $_FILES['myFile']['name'];
 $file_size = $_FILES['myFile']['size'];
 $temp_name =$_FILES['myFile']['tmp_name'];
 $milliseconds = "GOT_VID_" . round(microtime(true) * 1000) .".mp4";
 
 $ServerURL = "https://gotadsapp4.000webhostapp.com/adverti_project/Upload/Ads/".$milliseconds;
 
 $location = "Upload/Ads/";
 
 $sqlSelect = "SELECT id FROM ads WHERE id =(SELECT MAX(id) FROM ads)";
 
	$r = mysqli_query($con,$sqlSelect);
    $row = mysqli_fetch_array($r);
	$id = $row['id'];

	$sql = "UPDATE ads SET video_path = '$ServerURL' WHERE id = $id";
	if(mysqli_query($con,$sql)){
		move_uploaded_file($temp_name, $location.$milliseconds);
		echo "Video Uploaded";
	}
	else{
		echo "Failed upload video";
	}
 }
        
        
