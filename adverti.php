<?php
 
function serverConnection(){
	   define('HOST','localhost');
	   define('USER','id6388318_gotadsapp');
	   define('PASS','GOT_App_Team4');
	   define('DB','id6388318_adverti_project');
	
	    $con = mysqli_connect(HOST,USER ,PASS,DB)or die ("Unable to connect to server");
	    mysqli_set_charset($con, 'utf8');
	    return $con;
 }
 
 	function getAllCompany($con){
		
		//Creating sql query
		$sql = "SELECT id , name FROM user WHERE type = 'Company'";
			//$con=serverConnection();
		//getting result 
		$r = mysqli_query($con,$sql);
		//creating a blank array 
		$result = array();
		//looping through all the records fetched
		while($row = mysqli_fetch_array($r)){
			//Pushing name and id in the blank array created 
			array_push($result,array(
				"id"=>$row['id'],
				"name"=>$row['name']
			));
		}
		
		return $result;
	}
	
    
	function getAllCategory($con){
		//Creating sql query
		$sql = "SELECT * FROM category where visibility<>0";
		//	$con=serverConnection();
		//getting result 
		$r = mysqli_query($con,$sql);
		//creating a blank array 
		$result = array();
		//looping through all the records fetched
		
		while($row = mysqli_fetch_array($r)){
			
			$category_id=$row['id'];
			$sqlSelect ="SELECT * FROM ads_category WHERE ads_id=ANY(SELECT id FROM ads WHERE visible='1') AND (category_id = '$category_id' OR category_id = Any ( SELECT id FROM category WHERE parent_id='$category_id'))";
			$r_category = mysqli_query($con,$sqlSelect);
			$row_category = mysqli_num_rows($r_category);
			//Pushing name and id in the blank array created 
			array_push($result,array(
				"id"=>$row['id'],
				'name'=>$row['name'],
				'parent_id'=>$row['parent_id'],
				'arabicName'=>$row['arabicName'],
				'visibility'=>$row['visibility'],
				'numberAds'=>$row_category
			));
		}	
		
		return $result;
			
	}
	
	function getAllCategorySelected($con,$id){
    
        $sql = "SELECT category_id FROM ads_category WHERE ads_id=$id";
        $r = mysqli_query($con,$sql);
      
        $result = array();
    
        while($row = mysqli_fetch_array($r)){
            array_push($result,array(
              "category_id"=>$row['category_id'], 
            ));
        }
      
        return $result;
	}
	

	function getParentCategories($con,$id){
		
		$sql = "SELECT * FROM category WHERE id=$id";
		$r = mysqli_query($con,$sql);		
		$result = array();		
		$row = mysqli_fetch_array($r);
		array_push($result,array(
				"id"=>$row['id'],
				"name"=>$row['name'],
				"parent_id"=>$row['parent_id'],
				"arabicName"=>$row['arabicName'],
				"visibility"=>$row['visibility']
		));
		return $result;
	
	}
	
	function getNewUpdateAds($con){
		$current_date=date("Y-m-d H:i");
        
        $sqlStartAds = "UPDATE ads SET visible = 1 WHERE expire_date >= '$current_date' AND start_date <= '$current_date'";
        mysqli_query($con,$sqlStartAds);
        
        $sqlUpdateDate = "UPDATE ads SET visible = 0 WHERE expire_date < '$current_date'  ";
        mysqli_query($con,$sqlUpdateDate);
        
        //Creating sql query
		$sql = "SELECT * FROM ads WHERE visible = 1 ORDER BY start_date DESC";
        
        //getting result 
        $r = mysqli_query($con,$sql);
        
        //creating a blank array 
        $result = array();
        
        $user_id="";
        //looping through all the records fetched
        while($row = mysqli_fetch_array($r)){
            
            $user_id=$row['user_id'];
            $sql_user = "SELECT * FROM user WHERE id = $user_id";
            $r_user = mysqli_query($con,$sql_user);
            $row_user = mysqli_fetch_array($r_user);
               
            
            $ad_id=$row['id'];
            $sql_rate = "SELECT * FROM rates WHERE ads_id = $ad_id";
            $r_rate = mysqli_query($con,$sql_rate);
            $row_rate = mysqli_num_rows($r_rate);
            //Pushing name and id in the blank array created 
            array_push($result,array(
                      "id"=>$row['id'],
                      "details"=>$row['details'],
                      "start_date"=>$row['start_date'],
                      "expire_date"=>$row['expire_date'],
                      "rate"=>$row['rate'],
                      "views_num"=>$row['views_num'],
                      "user_id"=>$row['user_id'],
                      "image_path"=>$row['image_path'],
                      "video_path"=>$row['video_path'],
                      "name"=>$row_user['name'],
                      "email"=>$row_user['email'],
                      "phone_number"=>$row_user['phone_number'],
                      "count_rate"=>$row_rate
            ));
        
        }
		return $result;
		
	}
	
	function getSuggestedAds($con,$user_id){
		$current_date=date("Y-m-d H:i");
        
        $sqlStartAds = "UPDATE ads SET visible = 1 WHERE expire_date >= '$current_date' AND start_date <= '$current_date'";
        mysqli_query($con,$sqlStartAds);
        
        $sqlUpdateDate = "UPDATE ads SET visible = 0 WHERE expire_date < '$current_date'  ";
        mysqli_query($con,$sqlUpdateDate);
        
        //Creating sql query
       $sql = "SELECT * FROM ads WHERE id = ANY (SELECT ads_id FROM ads_category WHERE category_id = ANY
       (SELECT category_id FROM ads_category WHERE ads_id = ANY (SELECT ads_id FROM visits WHERE user_id = $user_id))) AND visible = 1 ORDER BY views_num DESC";
        
        //mysqli_set_charset($con, 'utf8');
        
        //getting result 
        $r = mysqli_query($con,$sql);
        
        //creating a blank array 
        $result = array();
        
        $user_id="";
        //looping through all the records fetched
        while($row = mysqli_fetch_array($r)){
            
            $user_id=$row['user_id'];
            $sql_user = "SELECT * FROM user WHERE id = $user_id";
            $r_user = mysqli_query($con,$sql_user);
            $row_user = mysqli_fetch_array($r_user);
               
            
            $ad_id=$row['id'];
            $sql_rate = "SELECT * FROM rates WHERE ads_id = $ad_id";
            $r_rate = mysqli_query($con,$sql_rate);
            $row_rate = mysqli_num_rows($r_rate);
            //Pushing name and id in the blank array created 
            array_push($result,array(
                      "id"=>$row['id'],
                      "details"=>$row['details'],
                      "start_date"=>$row['start_date'],
                      "expire_date"=>$row['expire_date'],
                      "rate"=>$row['rate'],
                      "views_num"=>$row['views_num'],
                      "user_id"=>$row['user_id'],
                      "image_path"=>$row['image_path'],
                      "video_path"=>$row['video_path'],
                      "name"=>$row_user['name'],
                      "email"=>$row_user['email'],
                      "phone_number"=>$row_user['phone_number'],
                      "count_rate"=>$row_rate
            ));
        }
		return $result;
        
	}
	
	function getTopViewsAds($con){
		$current_date=date("Y-m-d H:i");
        
        $sqlStartAds = "UPDATE ads SET visible = 1 WHERE expire_date >= '$current_date' AND start_date <= '$current_date'";
        mysqli_query($con,$sqlStartAds);
        
        $sqlUpdateDate = "UPDATE ads SET visible = 0 WHERE expire_date < '$current_date' ";
        mysqli_query($con,$sqlUpdateDate);
        
        //Creating sql query
        $sql = "SELECT * FROM ads WHERE visible = 1 ORDER BY views_num DESC";
        
        //getting result 
        $r = mysqli_query($con,$sql);
        
        //creating a blank array 
        $result = array();
        
        $user_id="";
        //looping through all the records fetched
        while($row = mysqli_fetch_array($r)){
            
        $user_id=$row['user_id'];
           $sql_user = "SELECT * FROM user WHERE id = $user_id";
        $r_user = mysqli_query($con,$sql_user);
        $row_user = mysqli_fetch_array($r_user);
           
        $ad_id=$row['id'];
        $sql_rate = "SELECT * FROM rates WHERE ads_id = $ad_id";
        $r_rate = mysqli_query($con,$sql_rate);
        $row_rate = mysqli_num_rows($r_rate);
            //Pushing name and id in the blank array created 
            array_push($result,array(
                 "id"=>$row['id'],
                      "details"=>$row['details'],
                      "start_date"=>$row['start_date'],
                      "expire_date"=>$row['expire_date'],
                      "rate"=>$row['rate'],
                      "views_num"=>$row['views_num'],
                      "user_id"=>$row['user_id'],
                      "image_path"=>$row['image_path'],
                      "video_path"=>$row['video_path'],
                      "name"=>$row_user['name'],
                      "email"=>$row_user['email'],
                      "phone_number"=>$row_user['phone_number'],
                      "count_rate"=>$row_rate
            ));
        }
        
		return $result;
	}
	
	function getTopRatesAds($con){
	
		$current_date=date("Y-m-d H:i");
        
        $sqlStartAds = "UPDATE ads SET visible = 1 WHERE expire_date >= '$current_date' AND start_date <= '$current_date'";
        mysqli_query($con,$sqlStartAds);
        
        $sqlUpdateDate = "UPDATE ads SET visible = 0 WHERE expire_date < '$current_date'  ";
        mysqli_query($con,$sqlUpdateDate);
        
        //Creating sql query
        $sql = "SELECT * FROM ads WHERE visible = 1 ORDER BY rate DESC";
        
        //getting result 
        $r = mysqli_query($con,$sql);
        
        //creating a blank array 
        $result = array();
        $user_id="";
        //looping through all the records fetched
        while($row = mysqli_fetch_array($r)){
            $user_id=$row['user_id'];
            $sql_user = "SELECT * FROM user WHERE id = $user_id";
            $r_user = mysqli_query($con,$sql_user);
            $row_user = mysqli_fetch_array($r_user);
            
            $ad_id=$row['id'];
            $sql_rate = "SELECT * FROM rates WHERE ads_id = $ad_id";
            $r_rate = mysqli_query($con,$sql_rate);
            $row_rate = mysqli_num_rows($r_rate);
           
            //Pushing name and id in the blank array created 
            array_push($result,array(
                      "id"=>$row['id'],
                      "details"=>$row['details'],
                      "start_date"=>$row['start_date'],
                      "expire_date"=>$row['expire_date'],
                      "rate"=>$row['rate'],
                      "views_num"=>$row['views_num'],
                      "user_id"=>$row['user_id'],
                      "image_path"=>$row['image_path'],
                      "video_path"=>$row['video_path'],
                      "name"=>$row_user['name'],
                      "email"=>$row_user['email'],
                      "phone_number"=>$row_user['phone_number'],
                      "count_rate"=>$row_rate
            ));
       
        }
		return $result;
	}
	
	function getComment($con,$userId,$adsId){

        $sqlSelect = "SELECT * FROM comments WHERE user_id = $userId AND ads_id = $adsId";
            
		
        $result_query = mysqli_query($con,$sqlSelect);
        $result = array();
                
        while($row = mysqli_fetch_array($result_query)){
        
            //Pushing name and id in the blank array created 
            array_push($result,array(
                        "id"=>$row['id'],
                        "text"=>$row['text']
            ));
   
        }
		
		return $result;
	}
	
	function getAllComments($con,$adsId){
        
        $sqlSelect="SELECT user.name, user.image_path, comments.text,comments.time,comments.user_id FROM user INNER JOIN comments ON user.id=comments.user_id
                      WHERE ads_id = $adsId;";
          //getting result 
        $result_query = mysqli_query($con,$sqlSelect);
          
        $result = array();
          
        while($row = mysqli_fetch_array($result_query) ){
              
              //Pushing name and id in the blank array created 
              array_push($result,array(
                  "name"=>$row['name'],
                  "image_path"=>$row['image_path'],
                  "text"=>$row['text'],
                  "time"=>$row['time'],
                  "user_id"=>$row['user_id']
              ));
        }
		
		return $result;
	}
	
	function getAllCommentsAds($con){
        
        $sqlSelect="SELECT user.name, user.image_path, comments.text,comments.time,comments.user_id FROM user INNER JOIN comments ON user.id=comments.user_id;";
          //getting result 
        $result_query = mysqli_query($con,$sqlSelect);
          
        $result = array();
          
        // while($row = mysqli_fetch_array($result_query) ){
              
        //       //Pushing name and id in the blank array created 
        //       array_push($result,array(
        //           "name"=>$row['name'],
        //           "image_path"=>$row['image_path'],
        //           "text"=>$row['text'],
        //           "time"=>$row['time'],
        //           "user_id"=>$row['user_id']
        //       ));
        // }
        
    	//looping through all the records fetched
    	while($row = mysqli_fetch_array($result_query)){
    
    		//Pushing name and id in the blank array created 
    		array_push($result,array(
    			"text"=>$row['text'],
    			"user_id"=>$row['user_id'],
    			"status"=>$row['status']
    
    		));
    	}
		
		return $result;
	}
	
	function getRate($con,$user_id,$ads_id){
		
        $sqlSelect = "SELECT * FROM rates WHERE user_id = $user_id AND  ads_id = $ads_id";
        $total_row=mysqli_query($con,$sqlSelect);
        $result=array();
        
        while($row=mysqli_fetch_array($total_row))
        {
            array_push($result,array(
                "value_rate"=>$row['value_rate'],
                "time"=>$row['time']
            ));
        }
		
		return $result;
		
	}
	function getSavedAd($con,$userId){
        $current_date=date("Y-m-d H:i");
        
        $sqlStartAds = "UPDATE ads SET visible = 1 WHERE expire_date >= '$current_date' AND start_date <= '$current_date'";
        mysqli_query($con,$sqlStartAds);
        
        $sqlUpdateDate = "UPDATE ads SET visible = 0 WHERE expire_date < '$current_date'  ";
        mysqli_query($con,$sqlUpdateDate);
        // Create connection
    
        //Creating sql query
        $sql = "SELECT * FROM ads WHERE visible = '1' AND id = ANY (SELECT ads_id from user_save_ads WHERE user_id = $userId)";
        
        //getting result 
        $r = mysqli_query($con,$sql);
        
        //creating a blank array 
        $result = array();
        $user_id="";
        //looping through all the records fetched
        while($row = mysqli_fetch_array($r)){
            
            $user_id=$row['user_id'];
            $sql_user = "SELECT * FROM user WHERE id = $user_id";
            $r_user = mysqli_query($con,$sql_user);
            $row_user = mysqli_fetch_array($r_user);
               
            
            $ad_id=$row['id'];
            $sql_rate = "SELECT * FROM rates WHERE ads_id = $ad_id";
            $r_rate = mysqli_query($con,$sql_rate);
            $row_rate = mysqli_num_rows($r_rate);
            //Pushing name and id in the blank array created 
            array_push($result,array(
                      "id"=>$row['id'],
                      "details"=>$row['details'],
                      "start_date"=>$row['start_date'],
                      "expire_date"=>$row['expire_date'],
                      "rate"=>$row['rate'],
                      "views_num"=>$row['views_num'],
                      "user_id"=>$row['user_id'],
                      "image_path"=>$row['image_path'],
                      "video_path"=>$row['video_path'],
                      "name"=>$row_user['name'],
                      "email"=>$row_user['email'],
                      "phone_number"=>$row_user['phone_number'],
                      "count_rate"=>$row_rate
    
    
            ));
        }
		
		return $result;
	}
	
	function addVisit($con, $userId, $adsId){
  	
        $current_date=date("Y-m-d H:i");
        
		$sqlSelect = "SELECT type FROM user WHERE id = '$userId'";
		$result_query = mysqli_query($con,$sqlSelect);
   
		$row = mysqli_fetch_array($result_query);
		if($row['type'] == "Admin"){
			return "Admin";
		}
		else{
        
            //Creating an sql query
            $sql = "INSERT INTO visits (time_visit,user_id,ads_id) VALUES ('$current_date','$userId','$adsId')";
            
            //Executing query to database
            if(mysqli_query($con,$sql)){
                $sqlUpdate = "UPDATE ads SET views_num = (SELECT COUNT(id) FROM visits WHERE ads_id = $adsId) WHERE id = $adsId;";
                if(mysqli_query($con,$sqlUpdate)){
					return "Done";
                }
				return "False";
                
            }else{
				return "False";
            }
        }  
	}
	
 
if($_SERVER['REQUEST_METHOD']=='POST'){
	define('IP','https://gotadsapp4.000webhostapp.com');
	date_default_timezone_set("Egypt");
	$function = $_POST['function'];
    
		//$con=serverConnection();
        
    if($function=="addUser"){
            $userName = $_POST['userName'];
            $userBirthday = $_POST['userBirthday'];
            $userAddress = $_POST['userAddress'];
            $userPhone = $_POST['userPhone'];
            $userEmail = $_POST['userEmail'];
            $userPassword = $_POST['userPassword'];
            $userType = $_POST['userType'];
            $userImageProfileName = $_POST['userImageProfileName'];
            $userImageProfilePath = $_POST['userImageProfilePath'];
        
            $profilePath = "";
            $id="";
            $profilePath = IP . "/adverti_project/Upload/Users/" . $userImageProfileName . ".png";
            $con = serverConnection();
            
            $sql = "SELECT id FROM user WHERE email = '$userEmail' ";
            $r = mysqli_query($con,$sql);
            $row = mysqli_fetch_array($r);
            $id = $row['id'];
            
            if($id != ""){
               echo 'This account is used previously';
            }
            else{
                $sqlInsert = "INSERT INTO user (image_path,name,birthday,address,phone_number,email,password,type) VALUES ('$profilePath','$userName','$userBirthday','$userAddress','$userPhone','$userEmail','$userPassword','$userType')";
                
                if(mysqli_query($con,$sqlInsert)){
                        file_put_contents("Upload/Users/" . $userImageProfileName . ".png",base64_decode($userImageProfilePath));
                        $sqlSelect = 'SELECT id FROM user WHERE id=(SELECT MAX(id) FROM user)';
                        $r = mysqli_query($con,$sqlSelect);
                        $row = mysqli_fetch_array($r);
                        $id = $row['id'];
                        echo $id;
                        //echo TRUE;
                }
                else{
                         echo 'FALSE';
                        //echo 'Could Not Inserted, Please Try Again';
                         
                }
            }
    }
    
    
    if($function=="userLogin"){
		
		$user_email=$_POST['login_email'];
		$user_password=$_POST['login_password'];
		
		$con=serverConnection();
	  
		$sqlSelect = "SELECT * FROM user WHERE email = '$user_email' AND password = '$user_password'";
		$total_row=mysqli_query($con,$sqlSelect);
		$result=array();
		
		while($row=mysqli_fetch_array($total_row)){
			  array_push($result,array(
				"id"=>$row['id'],
				"image_path"=>$row['image_path'],
				"name"=>$row['name'],
				"type"=>$row['type']));
		}    
		echo json_encode(array('result'=>$result));
		mysqli_close($con);   
    }
    
    if($function=="viewProfile"){
        $user_id=$_POST['user_id'];
    
        $con=serverConnection();
            
        $sqlSelect = "SELECT * FROM user WHERE id = '$user_id'";
        $total_row=mysqli_query($con,$sqlSelect);
        $result=array();
        
        while($row=mysqli_fetch_array($total_row))
       {
              array_push($result,array(
                "name"=>$row['name'],
                "birthday"=>$row['birthday'],
                "address"=>$row['address'],
                "phone_number"=>$row['phone_number'],
                "email"=>$row['email'],
                "password"=>$row['password'],
                "type"=>$row['type'],
                "image_path"=>$row['image_path'])
                );
       }
        
        echo json_encode(array('user_result'=>$result));
        mysqli_close($con);
    }
    
    if($function=="editProfileWithOutImage"){
	
        $user_id=$_POST['user_id'];
        $user_name=$_POST['user_name'];
        $user_birthday=$_POST['user_birthday'];
        $user_address=$_POST['user_address'];
        $user_phone=$_POST['user_phone'];
        $user_email=$_POST['user_email'];

		$con=serverConnection();
		
        $sql = "SELECT email FROM user WHERE id = '$user_id' ";
        $r = mysqli_query($con,$sql);
        $row = mysqli_fetch_array($r);
        $email = $row['email'];
        
        if($email != $user_email){
            $sql = "SELECT id FROM user WHERE email = '$user_email' ";
            $r = mysqli_query($con,$sql);
            $row = mysqli_fetch_array($r);
            $id = $row['id'];
            if($id != ""){
                echo 'This account is used previously';
            }
            else{
                $sqlSelect = "UPDATE user SET name = '$user_name' , birthday = '$user_birthday' , address = '$user_address' ,phone_number='$user_phone' ,
                        email='$user_email' WHERE id = '$user_id' ";
                
                    if(mysqli_query($con,$sqlSelect))
                    {
                        echo "Success";
                    }
                    else
                    {
                        echo "Failed";
                    }
                }
        }
        else{
            $sqlSelect = "UPDATE user SET name = '$user_name' , birthday = '$user_birthday' , address = '$user_address' ,phone_number='$user_phone' ,
                    email='$user_email' WHERE id = '$user_id' ";
            
                if(mysqli_query($con,$sqlSelect))
                {
                echo "Success";
                }
                else
                {
                echo "Failed";
                }
        }
        mysqli_close($con);
    }
    
    
        
    if($function=="editProfileWithImage"){
        
        $user_id=$_POST['user_id'];
        $user_name=$_POST['user_name'];
        $user_birthday=$_POST['user_birthday'];
        $user_address=$_POST['user_address'];
        $user_phone=$_POST['user_phone'];
        $user_email=$_POST['user_email'];
        $userImageProfileName = $_POST['userImageProfileName'];
        $userImageProfilePath = $_POST['userImageProfilePath'];
    
        $profilePath = IP . "/adverti_project/Upload/Users/" . $userImageProfileName . ".png";
        
        $con=serverConnection();
        $sql = "SELECT email FROM user WHERE id = '$user_id' ";
        $r = mysqli_query($con,$sql);
        $row = mysqli_fetch_array($r);
        $email = $row['email'];
        
        if($email != $user_email){	
            $sql = "SELECT id FROM user WHERE email = '$user_email' ";
            $r = mysqli_query($con,$sql);
            $row = mysqli_fetch_array($r);
            $id = $row['id'];
            if($id != ""){
                echo 'This account is used previously';
            }
            else{
                $sqlSelect = "UPDATE user SET image_path = '$profilePath' , name = '$user_name' , birthday = '$user_birthday' , address = '$user_address' ,phone_number='$user_phone' ,
                    email='$user_email'  WHERE id = '$user_id' ";
            
                if(mysqli_query($con,$sqlSelect))
                {
                    file_put_contents("Upload/Users/" . $userImageProfileName . ".png",base64_decode($userImageProfilePath));
                echo "Success";
                }
                else
                {
                echo "Failed";
                }
            }
        }
        else{
            $sqlSelect = "UPDATE user SET image_path = '$profilePath' , name = '$user_name' , birthday = '$user_birthday' , address = '$user_address' ,phone_number='$user_phone' ,
                    email='$user_email'  WHERE id = '$user_id' ";
            
                if(mysqli_query($con,$sqlSelect))
                {
                    file_put_contents("Upload/Users/" . $userImageProfileName . ".png",base64_decode($userImageProfilePath));
                echo "Success";
                }
                else
                {
                echo "Failed";
                }
        }
        mysqli_close($con);
    }
    
    
    if($function=="changePassword"){
        $user_id=$_POST['user_id'];
        $user_password=$_POST['user_password'];
        
        $con=serverConnection();
                
        $sqlSelect = "UPDATE user SET password = '$user_password' WHERE id = '$user_id' ";
        
        if(mysqli_query($con,$sqlSelect))
        {
            echo "Success";
        }
        else
        {
           echo "Failed";
        }
        mysqli_close($con);
    }
    
    
    
    if($function=="getTopViewsAds")
    {
		$user_id=$_POST['user_id'];
        $con=serverConnection();
		
		$resultTopViews = getTopViewsAds($con);
		$resultSavedAd = getSavedAd($con, $user_id);	
		
        //Displaying the array in json format 
        echo json_encode(array('result'=>$resultTopViews,'resultSavedAd'=>$resultSavedAd));
		mysqli_close($con);
		
		/*
        $con=serverConnection();
        
        $current_date=date("Y-m-d H:i");
        
        $sqlStartAds = "UPDATE ads SET visible = 1 WHERE expire_date >= '$current_date' AND start_date <= '$current_date'";
        mysqli_query($con,$sqlStartAds);
        
        $sqlUpdateDate = "UPDATE ads SET visible = 0 WHERE expire_date < '$current_date' ";
        mysqli_query($con,$sqlUpdateDate);
        
        //Creating sql query
        $sql = "SELECT * FROM ads WHERE visible = 1 ORDER BY views_num DESC";
        
        //getting result 
        $r = mysqli_query($con,$sql);
        
        //creating a blank array 
        $result = array();
        
        $user_id="";
        //looping through all the records fetched
        while($row = mysqli_fetch_array($r)){
            
        $user_id=$row['user_id'];
           $sql_user = "SELECT * FROM user WHERE id = $user_id";
        $r_user = mysqli_query($con,$sql_user);
        $row_user = mysqli_fetch_array($r_user);
           
        $ad_id=$row['id'];
        $sql_rate = "SELECT * FROM rates WHERE ads_id = $ad_id";
        $r_rate = mysqli_query($con,$sql_rate);
        $row_rate = mysqli_num_rows($r_rate);
            //Pushing name and id in the blank array created 
            array_push($result,array(
                 "id"=>$row['id'],
                      "details"=>$row['details'],
                      "start_date"=>$row['start_date'],
                      "expire_date"=>$row['expire_date'],
                      "rate"=>$row['rate'],
                      "views_num"=>$row['views_num'],
                      "user_id"=>$row['user_id'],
                      "image_path"=>$row['image_path'],
                      "video_path"=>$row['video_path'],
                      "name"=>$row_user['name'],
                      "email"=>$row_user['email'],
                      "phone_number"=>$row_user['phone_number'],
                      "count_rate"=>$row_rate
            ));
        }
        
        //Displaying the array in json format 
        echo json_encode(array('result'=>$result));
		*/
        
    }
    
    
    

    if($function=="getTopRatesAds")
    {
		$user_id=$_POST['user_id'];
        $con=serverConnection();
		
		$resultTopRates = getTopRatesAds($con);
		$resultSavedAd = getSavedAd($con, $user_id);	
		
        //Displaying the array in json format 
        echo json_encode(array('result'=>$resultTopRates,'resultSavedAd'=>$resultSavedAd));
		mysqli_close($con);
		
		/*
        $con=serverConnection();
    
        $current_date=date("Y-m-d H:i");
        
        $sqlStartAds = "UPDATE ads SET visible = 1 WHERE expire_date >= '$current_date' AND start_date <= '$current_date'";
        mysqli_query($con,$sqlStartAds);
        
        $sqlUpdateDate = "UPDATE ads SET visible = 0 WHERE expire_date < '$current_date'  ";
        mysqli_query($con,$sqlUpdateDate);
        
        //Creating sql query
        $sql = "SELECT * FROM ads WHERE visible = 1 ORDER BY rate DESC";
        
        //getting result 
        $r = mysqli_query($con,$sql);
        
        //creating a blank array 
        $result = array();
        $user_id="";
        //looping through all the records fetched
        while($row = mysqli_fetch_array($r)){
            $user_id=$row['user_id'];
            $sql_user = "SELECT * FROM user WHERE id = $user_id";
            $r_user = mysqli_query($con,$sql_user);
            $row_user = mysqli_fetch_array($r_user);
            
            $ad_id=$row['id'];
            $sql_rate = "SELECT * FROM rates WHERE ads_id = $ad_id";
            $r_rate = mysqli_query($con,$sql_rate);
            $row_rate = mysqli_num_rows($r_rate);
           
            //Pushing name and id in the blank array created 
            array_push($result,array(
                      "id"=>$row['id'],
                      "details"=>$row['details'],
                      "start_date"=>$row['start_date'],
                      "expire_date"=>$row['expire_date'],
                      "rate"=>$row['rate'],
                      "views_num"=>$row['views_num'],
                      "user_id"=>$row['user_id'],
                      "image_path"=>$row['image_path'],
                      "video_path"=>$row['video_path'],
                      "name"=>$row_user['name'],
                      "email"=>$row_user['email'],
                      "phone_number"=>$row_user['phone_number'],
                      "count_rate"=>$row_rate
            ));
       
        }
        
        //Displaying the array in json format 
        echo json_encode(array('result'=>$result));   
		*/
    }
    
    

    if($function=="getSuggestedAds")
    {
		$user_id=$_POST['user_id'];
        $con=serverConnection();
		
		$resultSuggested = getSuggestedAds($con,$user_id);
		$resultSavedAd = getSavedAd($con, $user_id);	
		
        //Displaying the array in json format 
        echo json_encode(array('result'=>$resultSuggested,'resultSavedAd'=>$resultSavedAd));
		mysqli_close($con);
		
		/*
        $user_id=$_POST['user_id'];
        $con=serverConnection();
    
        $current_date=date("Y-m-d H:i");
        
        $sqlStartAds = "UPDATE ads SET visible = 1 WHERE expire_date >= '$current_date' AND start_date <= '$current_date'";
        mysqli_query($con,$sqlStartAds);
        
        $sqlUpdateDate = "UPDATE ads SET visible = 0 WHERE expire_date < '$current_date'  ";
        mysqli_query($con,$sqlUpdateDate);
        
        //Creating sql query
       $sql = "SELECT * FROM ads WHERE id = ANY (SELECT ads_id FROM ads_category WHERE category_id = ANY
       (SELECT category_id FROM ads_category WHERE ads_id = ANY (SELECT ads_id FROM visits WHERE user_id = $user_id))) AND visible = 1 ORDER BY views_num DESC";
        
        //mysqli_set_charset($con, 'utf8');
        
        //getting result 
        $r = mysqli_query($con,$sql);
        
        //creating a blank array 
        $result = array();
        
        $user_id="";
        //looping through all the records fetched
        while($row = mysqli_fetch_array($r)){
            
            $user_id=$row['user_id'];
            $sql_user = "SELECT * FROM user WHERE id = $user_id";
            $r_user = mysqli_query($con,$sql_user);
            $row_user = mysqli_fetch_array($r_user);
               
            
            $ad_id=$row['id'];
            $sql_rate = "SELECT * FROM rates WHERE ads_id = $ad_id";
            $r_rate = mysqli_query($con,$sql_rate);
            $row_rate = mysqli_num_rows($r_rate);
            //Pushing name and id in the blank array created 
            array_push($result,array(
                      "id"=>$row['id'],
                      "details"=>$row['details'],
                      "start_date"=>$row['start_date'],
                      "expire_date"=>$row['expire_date'],
                      "rate"=>$row['rate'],
                      "views_num"=>$row['views_num'],
                      "user_id"=>$row['user_id'],
                      "image_path"=>$row['image_path'],
                      "video_path"=>$row['video_path'],
                      "name"=>$row_user['name'],
                      "email"=>$row_user['email'],
                      "phone_number"=>$row_user['phone_number'],
                      "count_rate"=>$row_rate
            ));
        }
        
        //Displaying the array in json format 
        echo json_encode(array('result'=>$result));
		*/
        
    }
    
    
	 if($function=="getNewUpdate_Suggested_TopViews_TopRates")
    {
		$user_id=$_POST['user_id'];
        $con=serverConnection();
		
		$resultNewUpdate = getNewUpdateAds($con);
		$resultSuggested = getSuggestedAds($con,$user_id);
		$resultTopViews = getTopViewsAds($con);
		$resultTopRates = getTopRatesAds($con);
		$resultSavedAd = getSavedAd($con, $user_id);	
		
        //Displaying the array in json format 
        echo json_encode(array('resultNewUpdate'=>$resultNewUpdate, 'resultSuggested'=>$resultSuggested, 'resultTopViews'=>$resultTopViews, 'resultTopRates'=>$resultTopRates,
		'resultSavedAd'=>$resultSavedAd));
		mysqli_close($con);
	}
    if($function=="getNewUpdateAds")
    {
		
		$user_id=$_POST['user_id'];
        $con=serverConnection();
		
		$resultNewUpdate = getNewUpdateAds($con);
		$resultSavedAd = getSavedAd($con, $user_id);	
		
        //Displaying the array in json format 
        echo json_encode(array('result'=>$resultNewUpdate, 'resultSavedAd'=>$resultSavedAd));
		mysqli_close($con);
		
		/*
        //$user_id=$_POST['user_id'];
        $con=serverConnection();
    
        $current_date=date("Y-m-d H:i");
        
        $sqlStartAds = "UPDATE ads SET visible = 1 WHERE expire_date >= '$current_date' AND start_date <= '$current_date'";
        mysqli_query($con,$sqlStartAds);
        
        $sqlUpdateDate = "UPDATE ads SET visible = 0 WHERE expire_date < '$current_date'  ";
        mysqli_query($con,$sqlUpdateDate);
        
        //Creating sql query
       $sql = "SELECT * FROM ads WHERE visible = 1 ORDER BY start_date DESC";
        
        //getting result 
        $r = mysqli_query($con,$sql);
        
        //creating a blank array 
        $result = array();
        
        $user_id="";
        //looping through all the records fetched
        while($row = mysqli_fetch_array($r)){
            
            $user_id=$row['user_id'];
            $sql_user = "SELECT * FROM user WHERE id = $user_id";
            $r_user = mysqli_query($con,$sql_user);
            $row_user = mysqli_fetch_array($r_user);
               
            
            $ad_id=$row['id'];
            $sql_rate = "SELECT * FROM rates WHERE ads_id = $ad_id";
            $r_rate = mysqli_query($con,$sql_rate);
            $row_rate = mysqli_num_rows($r_rate);
            //Pushing name and id in the blank array created 
            array_push($result,array(
                      "id"=>$row['id'],
                      "details"=>$row['details'],
                      "start_date"=>$row['start_date'],
                      "expire_date"=>$row['expire_date'],
                      "rate"=>$row['rate'],
                      "views_num"=>$row['views_num'],
                      "user_id"=>$row['user_id'],
                      "image_path"=>$row['image_path'],
                      "video_path"=>$row['video_path'],
                      "name"=>$row_user['name'],
                      "email"=>$row_user['email'],
                      "phone_number"=>$row_user['phone_number'],
                      "count_rate"=>$row_rate
            ));
        
        }
        
        //Displaying the array in json format 
        echo json_encode(array('result'=>$result));
		*/
        
    }
    
    if($function=="getAllAds")
    {
        //mysqli_set_charset($con, 'utf8');
        $categoryId = $_POST['categoryId'];
		$userId=$_POST['user_id'];
        
        $con =serverConnection();
        $current_date=date("Y-m-d H:i");
            
        $sqlStartAds = "UPDATE ads SET visible = 1 WHERE expire_date >= '$current_date' AND start_date <= '$current_date'";
        mysqli_query($con,$sqlStartAds);
            
        $sqlUpdateDate = "UPDATE ads SET visible = 0 WHERE expire_date < '$current_date'  ";
        mysqli_query($con,$sqlUpdateDate);
        // Create connection
        
        //Creating sql query
        $sql = "SELECT * FROM ads WHERE visible = '1' AND id = ANY (SELECT ads_id from ads_category WHERE category_id = $categoryId)";
            
        //getting result 
        $r = mysqli_query($con,$sql);
            
        //creating a blank array 
        $result = array();
        $user_id="";
        //looping through all the records fetched
        while($row = mysqli_fetch_array($r)){
                
            $user_id=$row['user_id'];
            $sql_user = "SELECT * FROM user WHERE id = $user_id";
            $r_user = mysqli_query($con,$sql_user);
            $row_user = mysqli_fetch_array($r_user);
                   
            $ad_id=$row['id'];
            $sql_rate = "SELECT * FROM rates WHERE ads_id = $ad_id";
            $r_rate = mysqli_query($con,$sql_rate);
            $row_rate = mysqli_num_rows($r_rate);
            //Pushing name and id in the blank array created 
            array_push($result,array(
                          "id"=>$row['id'],
                          "details"=>$row['details'],
                          "start_date"=>$row['start_date'],
                          "expire_date"=>$row['expire_date'],
                          "rate"=>$row['rate'],
                          "views_num"=>$row['views_num'],
                          "user_id"=>$row['user_id'],
                          "image_path"=>$row['image_path'],
			     "video_path"=>$row['video_path'],
                          "name"=>$row_user['name'],
                          "email"=>$row_user['email'],
                          "phone_number"=>$row_user['phone_number'],
                          "count_rate"=>$row_rate
            ));
        }
		
		
		$resultSavedAd = getSavedAd($con, $userId);
            
            //Displaying the array in json format 
         echo json_encode(array('result'=>$result,'resultSavedAd'=>$resultSavedAd));
		 mysqli_close($con);
            
    }
        
        
    if($function=="getCompanyAds")
    {
        $idUserCompany=$_POST['idUserCompany'];
        $con=serverConnection();
    
        $current_date=date("Y-m-d H:i");
        
        $sqlStartAds = "UPDATE ads SET visible = 1 WHERE expire_date >= '$current_date' AND start_date <= '$current_date'";
        mysqli_query($con,$sqlStartAds);
        
        $sqlUpdateDate = "UPDATE ads SET visible = 0 WHERE expire_date < '$current_date'  ";
        mysqli_query($con,$sqlUpdateDate);
        
        //Creating sql query
       $sql = "SELECT * FROM ads WHERE user_id = '$idUserCompany' AND visible = 1 ORDER BY start_date DESC";
        
        //getting result 
        $r = mysqli_query($con,$sql);
        
        //creating a blank array 
        $result = array();
        
        $user_id="";
        //looping through all the records fetched
        while($row = mysqli_fetch_array($r)){
            
            $user_id=$row['user_id'];
            $sql_user = "SELECT * FROM user WHERE id = $user_id";
            $r_user = mysqli_query($con,$sql_user);
            $row_user = mysqli_fetch_array($r_user);
               
            
            $ad_id=$row['id'];
            $sql_rate = "SELECT * FROM rates WHERE ads_id = $ad_id";
            $r_rate = mysqli_query($con,$sql_rate);
            $row_rate = mysqli_num_rows($r_rate);
            //Pushing name and id in the blank array created 
            array_push($result,array(
                      "id"=>$row['id'],
                      "details"=>$row['details'],
                      "start_date"=>$row['start_date'],
                      "expire_date"=>$row['expire_date'],
                      "rate"=>$row['rate'],
                      "views_num"=>$row['views_num'],
                      "user_id"=>$row['user_id'],
                      "image_path"=>$row['image_path'],
                      "video_path"=>$row['video_path'],
                      "name"=>$row_user['name'],
                      "email"=>$row_user['email'],
                      "phone_number"=>$row_user['phone_number'],
                      "count_rate"=>$row_rate
            ));
        
        }
        
        //Displaying the array in json format 
        echo json_encode(array('result'=>$result)); 
    }
    
    if($function=="getAllAds_Admin")
    {
        $con = serverConnection();
        
        $current_date=date("Y-m-d H:i");
        
        $sqlStartAds = "UPDATE ads SET visible = 1 WHERE expire_date >= '$current_date' AND start_date <= '$current_date'";
        mysqli_query($con,$sqlStartAds);
        
        $sqlUpdateDate = "UPDATE ads SET visible = 0 WHERE expire_date < '$current_date'  ";
        mysqli_query($con,$sqlUpdateDate);
        // Create connection
    
        //Creating sql query
        $sql = "SELECT * FROM ads";
        
        //getting result 
        $r = mysqli_query($con,$sql);
        
        //creating a blank array 
        $result = array();
        
        //looping through all the records fetched
        
        while($row = mysqli_fetch_array($r)){
            
            $user_id=$row['user_id'];
            $sql_user = "SELECT * FROM user WHERE id = $user_id";
            $r_user = mysqli_query($con,$sql_user);
            $row_user = mysqli_fetch_array($r_user);
    
            //Pushing name and id in the blank array created 
            array_push($result,array(
                      "id"=>$row['id'],
                      "details"=>$row['details'],
                      "start_date"=>$row['start_date'],
                      "expire_date"=>$row['expire_date'],
                      "rate"=>$row['rate'],
                      "views_num"=>$row['views_num'],
                      "user_id"=>$row['user_id'],
                      "image_path"=>$row['image_path'],
                      "video_path"=>$row['video_path'],
                      "visible"=>$row['visible'],
                      "name"=>$row_user['name']
            ));
        }
        
        //Displaying the array in json format 
        echo json_encode(array('result'=>$result)); 
    }
    
    if($function=="getSavedAds")
    {
        $con =serverConnection();
        $userId = $_POST['userId'];
        $resultSavedAd = getSavedAd($con, $userId);	
		
        //Displaying the array in json format 
        echo json_encode(array('resultSavedAd'=>$resultSavedAd));
		mysqli_close($con);
        
    }
    
    if($function=="saveAd")
    {
        $user_id=$_POST['userId'];
        $ad_id=$_POST['adId'];
        $con=serverConnection();
        
        $InsertSQL = "insert into user_save_ads (user_id,ads_id) values ('$user_id','$ad_id')";
        mysqli_query($con, $InsertSQL);
		
		
		$resultSavedAd = getSavedAd($con, $user_id);	
		
        //Displaying the array in json format 
        echo json_encode(array('resultSavedAd'=>$resultSavedAd));
		mysqli_close($con);
    }

    if($function=="deleteSaveAd")
    {
        $user_id=$_POST['userId'];
        $ad_id=$_POST['adId'];
        $con=serverConnection();
        
         $InsertSQL = "DELETE FROM user_save_ads WHERE user_id = $user_id AND ads_id = $ad_id; ";
         mysqli_query($con, $InsertSQL);
		 
		 
		$resultSavedAd = getSavedAd($con, $user_id);	
		
        //Displaying the array in json format 
        echo json_encode(array('resultSavedAd'=>$resultSavedAd));
		mysqli_close($con);
    }
    
    if($function=="addVisit"){
        $userId = $_POST['userId'];
        $adsId = $_POST['adsId'];
  	
        $current_date=date("Y-m-d H:i");
		$con=serverConnection();
		$sqlSelect = "SELECT type FROM user WHERE id = '$userId'";
		$result_query = mysqli_query($con,$sqlSelect);
   
		$row = mysqli_fetch_array($result_query);
		if($row['type'] == "Admin"){
		}
		else{
        
            //Creating an sql query
            $sql = "INSERT INTO visits (time_visit,user_id,ads_id) VALUES ('$current_date','$userId','$adsId')";
            
            //Importing our db connection script
            
            
            //Executing query to database
            if(mysqli_query($con,$sql)){
                $sqlUpdate = "UPDATE ads SET views_num = (SELECT COUNT(id) FROM visits WHERE ads_id = $adsId) WHERE id = $adsId;";
                if(mysqli_query($con,$sqlUpdate)){
                    echo 'Inserted Successfully';
                }
                
            }else{
                echo 'Could Not Inserted, Try Again';
            }
        }
            //Closing the database 
            mysqli_close($con);     
    }
    
    if($function=="addComment"){
        $textComment = $_POST['textComment'];
        $idUser = $_POST['idUser'];
        $idAds = $_POST['idAds'];

        $current_date=date("Y-m-d H:i");
        
		$con=serverConnection();
		$sql = "INSERT INTO comments (text,time,user_id,ads_id) VALUES ('$textComment','$current_date','$idUser' ,'$idAds')";
    
        if(mysqli_query($con,$sql)){
         //echo 'Inserted Successfully';
		 		
			$resultComment = getComment($con, $idUser, $idAds);
			$resultAllComments = getAllComments($con, $idAds);
			
			echo json_encode(array('resultComment'=>$resultComment, 'resultAllComments'=>$resultAllComments));
        }
        else{
            echo 'Could Not Inserted, Please Try Again'; 
        }
    }


    if($function=="updateComment"){
        $textComment = $_POST['textComment'];
        $idUser = $_POST['idUser'];
        $idAds = $_POST['idAds'];
        
	    $current_date=date("Y-m-d H:i");
        
        $con=serverConnection();
        
		$sqlUpdate = "UPDATE comments SET text = '$textComment',time = '$current_date' WHERE user_id = $idUser AND ads_id = $idAds";
    
        if(mysqli_query($con,$sqlUpdate)){
            //echo 'Updated Successfully';
			$resultComment = getComment($con, $idUser, $idAds);
			$resultAllComments = getAllComments($con, $idAds);
			
			echo json_encode(array('resultComment'=>$resultComment, 'resultAllComments'=>$resultAllComments));
        }
        else{
            echo 'Could Not Updated, Please Try Again';            
        }
		mysqli_close($con);
    }
   
   if($function=="deleteComment"){
        $userId = $_POST['idUser'];
        $adsId = $_POST['idAds'];
  
        $con=serverConnection();
        $sqlDelete = "DELETE FROM comments WHERE user_id = $userId AND ads_id = $adsId";
      
	    if(mysqli_query($con,$sqlDelete)){       
            //echo 'Deleted Successfully';
					
			$resultComment = getComment($con, $userId, $adsId);
			$resultAllComments = getAllComments($con, $adsId);
			
			echo json_encode(array('resultComment'=>$resultComment, 'resultAllComments'=>$resultAllComments));
        }
        else{
			echo 'Could Not Deleted, Please Try Again';             
        }
		mysqli_close($con);
	
    }
	
    if($function=="getComment_Rate"){
		$userId = $_POST['userId'];
        $adsId = $_POST['adsId'];
		
		$con=serverConnection();
		
		$resultVisit = addVisit($con, $userId, $adsId);
		if($resultVisit == "False"){	
		}
		else{
			$resultComment = getComment($con, $userId, $adsId);
			$resultAllComments = getAllComments($con, $adsId);
			$resultRate = getRate($con, $userId, $adsId);
			$resultSavedAd = getSavedAd($con, $userId);
			
			echo json_encode(array('resultComment'=>$resultComment, 'resultAllComments'=>$resultAllComments, 'resultRate'=>$resultRate, 'resultSavedAd'=>$resultSavedAd));
		}
		mysqli_close($con);
	}
	
    
    if($function=="viewComment"){
        $userId = $_POST['userId'];
        $adsId = $_POST['adsId'];

        $sqlSelect = "SELECT * FROM comments WHERE user_id = $userId AND ads_id = $adsId";
               
        $con=serverConnection();
		
        $result_query = mysqli_query($con,$sqlSelect);
        $result = array();
                
        while($row = mysqli_fetch_array($result_query)){
        
            //Pushing name and id in the blank array created 
            array_push($result,array(
                        "id"=>$row['id'],
                        "text"=>$row['text']
            ));
   
        }
		echo json_encode(array('result'=>$result));
		mysqli_close($con);
               
    }

    
    if($function=="viewAllComments"){
        $adsId = $_POST['adsId'];
    
        $con=serverConnection();
        //mysqli_set_charset($con, 'utf8');
        
        $sqlSelect="SELECT user.name, user.image_path, comments.text,comments.time,comments.user_id FROM user INNER JOIN comments ON user.id=comments.user_id
                      WHERE ads_id = $adsId;";
          //getting result 
        $result_query = mysqli_query($con,$sqlSelect);
          
        $result = array();
          
        while($row = mysqli_fetch_array($result_query) ){
              
              //Pushing name and id in the blank array created 
              array_push($result,array(
                  "name"=>$row['name'],
                  "image_path"=>$row['image_path'],
                  "text"=>$row['text'],
                  "time"=>$row['time'],
                  "user_id"=>$row['user_id']
              ));
        }
       
        echo json_encode(array('result'=>$result));
        mysqli_close($con);
    }

    
    
    if($function=="viewRate"){
		$user_id = $_POST['user_id'];
		$ads_id = $_POST['ads_id'];

		$con=serverConnection();
		
        $sqlSelect = "SELECT * FROM rates WHERE user_id = $user_id AND  ads_id = $ads_id";
        $total_row=mysqli_query($con,$sqlSelect);
        $result=array();
        
        while($row=mysqli_fetch_array($total_row))
        {
            array_push($result,array(
                "value_rate"=>$row['value_rate'],
                "time"=>$row['time']
            ));
        }
            
        echo json_encode(array('result'=>$result));
        
        mysqli_close($con);
    }

    if($function=="addRate"){
    
        //Getting values
		$value_rate = $_POST['value_rate'];
		$user_id = $_POST['user_id'];
		$ads_id = $_POST['ads_id'];
		
		$current_date=date("Y-m-d H:i");
		
		//Creating an sql query
		$sql = "INSERT INTO rates (value_rate,time,user_id,ads_id) VALUES ('$value_rate','$current_date','$user_id' ,'$ads_id')";
		
		//Importing our db connection script
        $con=serverConnection();
		
		//Executing query to database
		if(mysqli_query($con,$sql)){
			$sqlUpdate = "UPDATE ads SET rate = (SELECT AVG(value_rate) FROM rates WHERE ads_id = $ads_id ) WHERE id = $ads_id;";
			if(mysqli_query($con,$sqlUpdate)){
				//echo 'Rate Added Successfully';
				
				$resultRate = getRate($con, $user_id, $ads_id);
				
				echo json_encode(array('resultRate'=>$resultRate));
			}
			else{
				echo 'Could Not Add Rate';
			}
			
		}else{
			echo 'Could Not Add Rate';
		}
		
		//Closing the database 
		mysqli_close($con);
    }


    if($function=="updateRate"){
		
		$value_rate = $_POST['value_rate'];
		$user_id = $_POST['user_id'];
		$ads_id = $_POST['ads_id'];
			
		$current_date=date("Y-m-d H:i");
		
		$con=serverConnection();
		
		$sql = "UPDATE rates SET value_rate = '$value_rate',time = '$current_date' WHERE user_id = $user_id AND ads_id = $ads_id;";		
		if(mysqli_query($con,$sql)){
			$sqlUpdate = "UPDATE ads SET rate = (SELECT AVG(value_rate) FROM rates WHERE ads_id = $ads_id ) WHERE id = $ads_id;";
			if(mysqli_query($con,$sqlUpdate)){
				//echo 'Rate Update Successfully';
				
				$resultRate = getRate($con, $user_id, $ads_id);
				
				echo json_encode(array('resultRate'=>$resultRate));
			}
			else{
				echo 'Could Not Update Rate';
			}
			
		}else{
				echo 'Could Not Update Rate';
		}			
		mysqli_close($con);
    }

    if($function=="deleteRate"){
		$user_id = $_POST['user_id'];
		$ads_id = $_POST['ads_id'];
		
		$con=serverConnection();

		$sql = "DELETE FROM rates WHERE user_id = $user_id AND ads_id = $ads_id;";
		
		if(mysqli_query($con,$sql)){
			$sqlUpdate = "UPDATE ads SET rate = (SELECT AVG(value_rate) FROM rates WHERE ads_id = $ads_id ) WHERE id = $ads_id;";
			if(mysqli_query($con,$sqlUpdate)){
				//echo 'Rate Delete Successfully';
				
				$resultRate = getRate($con, $user_id, $ads_id);
				
				echo json_encode(array('resultRate'=>$resultRate));
			}
			else{
				echo 'Could Not Delete Rate';
			}
		}else{
				echo 'Could Not Delete Rate';
		}
		mysqli_close($con);
    }
    
    
    if($function=="add_ad"){
	
        $con = serverConnection();
         
        $adPictureName = $_POST['adPictureName'];
        $adPicturePath = $_POST['adPicturePath'];
        $details = $_POST['details'];
        $startDate = $_POST['startDate'];
        $expireDate = $_POST['expireDate'];
        $categoryIds = $_POST['categoryIds'];
        $companyId = $_POST['companyId'];
        
        $current_date=date("Y-m-d H:i");
                
        if(strtotime($startDate) > strtotime($current_date)){
            $visible = "0";
        }
        else{
            $visible = "1";
        }
         $ImagePath="";
         $ImagePath = IP . "/adverti_project/Upload/Ads/" . $adPictureName . ".png";
         
         $InsertSQL = "insert into ads (image_path,details,user_id,start_date,expire_date,visible) values ('$ImagePath','$details','$companyId','$startDate','$expireDate','$visible')";
         if(mysqli_query($con, $InsertSQL)){
                file_put_contents("Upload/Ads/" . $adPictureName . ".png",base64_decode($adPicturePath));
                $GetNewIdSQL ="SELECT id FROM ads WHERE id =(SELECT MAX(id) FROM ads)";
                $Query = mysqli_query($con,$GetNewIdSQL);
                if($row = mysqli_fetch_array($Query)){
                   $ads_Id = $row['id'];
                }
         }
         
        $categories = explode(' ', $categoryIds);
        
        foreach ($categories as $value) {
            $id = ((int)$value);
            $sql="INSERT INTO ads_category (ads_id,category_id) VALUES ('$ads_Id','$id')";
             if(mysqli_query($con,$sql)){
                echo 'Done';
             }else{
                echo 'Failed';
             }  
          }
    }

    if($function=="edit_ad"){
	
        $con = serverConnection();
         
        $adId = $_POST['adId'];
        $details = $_POST['details'];
        $startDate = $_POST['startDate'];
        $expireDate = $_POST['expireDate'];
        $categoryIds = $_POST['categoryIds'];
        $companyId = $_POST['companyId'];
        $Visibility = $_POST['Visibility'];
        
        $current_date=date("Y-m-d H:i");
                
                
        if(strtotime($startDate) > strtotime($current_date)){
            $visible = "0";
        }
        else{
            $visible = "1";
        }
         
        $UpdateSQL = "UPDATE ads SET details = '$details',user_id = '$companyId',start_date = '$startDate',expire_date = '$expireDate',
          visible = '$Visibility' WHERE id = '$adId' ";
        if(mysqli_query($con, $UpdateSQL)){
            
            $sqlDELETE = "DELETE FROM ads_category WHERE ads_id = '$adId' ";
            mysqli_query($con,$sqlDELETE);
            $categories = explode(' ', $categoryIds);
              //$catsIdsSeparation = join("','",$categories);
              
            foreach ($categories as $value) {
                $id = ((int)$value);
                $sql="INSERT INTO ads_category (ads_id,category_id) VALUES ('$adId','$id')";
                if(mysqli_query($con,$sql)){
                      echo 'Done';
                }else{
                      echo 'Failed';
                }  
            }
        }
    }

    if($function=="edit_adWithPicture"){
        
        $con = serverConnection();
            
        $adId = $_POST['adId'];
        $adPictureName = $_POST['adPictureName'];
        $adPicturePath = $_POST['adPicturePath'];
        $details = $_POST['details'];
        $startDate = $_POST['startDate'];
        $expireDate = $_POST['expireDate'];
        $categoryIds = $_POST['categoryIds'];
        $companyId = $_POST['companyId'];
        $Visibility = $_POST['Visibility'];
        
        $current_date=date("Y-m-d H:i");
                        
        if(strtotime(substr($startDate,0,10)) > strtotime($current_date)){
            $visible = "0";
        }
        else{
            $visible = "1";
        }
        
        $ImagePath="";
        $ImagePath = IP . "/adverti_project/Upload/Ads/" . $adPictureName . ".png";
         
        $UpdateSQL = "UPDATE ads SET image_path = '$ImagePath',details = '$details',user_id = '$companyId',start_date = '$startDate',
          expire_date = '$expireDate',visible = '$Visibility' WHERE id = '$adId' ";
        if(mysqli_query($con, $UpdateSQL)){
                file_put_contents("Upload/Ads/" . $adPictureName . ".png",base64_decode($adPicturePath));
        }
         
        $sqlDELETE = "DELETE FROM ads_category WHERE ads_id = '$adId' ";
        mysqli_query($con,$sqlDELETE);
        $categories = explode(' ', $categoryIds);
        
        foreach ($categories as $value) {
            $id = ((int)$value);
            $sql="INSERT INTO ads_category (ads_id,category_id) VALUES ('$adId','$id')";
            if(mysqli_query($con,$sql)){
                echo 'Done';
            }else{
                echo 'Failed';
            }  
        }
    }

    if($function=="deleteAd"){
	
        $con = serverConnection();
        $adId = $_POST['adId'];
	
        $DeleteSQL = "DELETE FROM ads WHERE id = '$adId' ";
        if(mysqli_query($con, $DeleteSQL)){
            $sqlDELETE = "DELETE FROM ads_category WHERE ads_id = '$adId' ";
            if(mysqli_query($con,$sqlDELETE)){
               echo 'Done';
            }else{
               echo 'Failed';
            }  
        }
    }
    
    
    if($function=="getAllCompany"){
	
		//Creating sql query
		$sql = "SELECT id , name FROM user WHERE type = 'Company'";
		
		$con=serverConnection();
		//getting result 
		$r = mysqli_query($con,$sql);
		//creating a blank array 
		$result = array();
		//looping through all the records fetched
		while($row = mysqli_fetch_array($r)){
			//Pushing name and id in the blank array created 
			array_push($result,array(
				"id"=>$row['id'],
				"name"=>$row['name']
			));
		}
		//Displaying the array in json format 
		echo json_encode(array('result'=>$result));
    }
    
    
    if($function=="getAllCategory"){
		//Importing Database Script 
		$con=serverConnection();
		//Creating sql query
		$sql = "SELECT * FROM category where visibility<>0";
		//getting result 
		$r = mysqli_query($con,$sql);
		//creating a blank array 
		$result = array();
		//looping through all the records fetched
		
		while($row = mysqli_fetch_array($r)){
			
			$category_id=$row['id'];
			$sqlSelect ="SELECT * FROM ads_category WHERE ads_id=ANY(SELECT id FROM ads WHERE visible='1') AND (category_id = '$category_id' OR category_id = Any ( SELECT id FROM category WHERE parent_id='$category_id'))";
			$r_category = mysqli_query($con,$sqlSelect);
			$row_category = mysqli_num_rows($r_category);
			//Pushing name and id in the blank array created 
			array_push($result,array(
				"id"=>$row['id'],
				'name'=>$row['name'],
				'parent_id'=>$row['parent_id'],
				'arabicName'=>$row['arabicName'],
				'visibility'=>$row['visibility'],
				'numberAds'=>$row_category
			));
		}
		//Displaying the array in json format 
		echo json_encode(array('result'=>$result));
		mysqli_close($con);
    }
    	
  if($function=="getAllOwnComments"){
        $con=serverConnection();

	    $sql = "SELECT * FROM comments";
	    $r = mysqli_query($con,$sql); 
        $result = array();
          
              
            

        while($row = mysqli_fetch_array($r)){
		
			  array_push($result,array(
	                 # "name"=>$row['name'],
	                 # "image_path"=>$row['image_path'],
	                  "text"=>$row['text'],
	                 # "time"=>$row['time'],
	                 # "user_id"=>$row['user_id']
	              ));

	
        }
       
        echo json_encode(array('result'=>$result));
      #  mysqli_close($conn);
    }
	
    
      if($function=="getAllCategory_Company"){
		//Importing Database Script 
		$con=serverConnection();
		$resultCategory = getAllCategory($con);
		$resultCompany = getAllCompany($con);
		//Displaying the array in json format 
		echo json_encode(array('resultCategory'=>$resultCategory,'resultCompany'=>$resultCompany));
		mysqli_close($con);
    }
    
     if($function=="getAllCategory_Selected_Company"){
         $id = $_POST['id'];
		//Importing Database Script 
		$con=serverConnection();
		$resultCategory = getAllCategory($con);
		$resultCategorySelected = getAllCategorySelected($con,$id);
		$resultCompany = getAllCompany($con);
		//Displaying the array in json format 
		echo json_encode(array('resultCategory'=>$resultCategory,'resultCategorySelected'=>$resultCategorySelected,'resultCompany'=>$resultCompany));
		mysqli_close($con);
    }

    if($function=="getAllCategory_Admin"){
		//Importing Database Script 
		$con=serverConnection();
		//mysqli_set_charset($con, 'utf8');	
		//Creating sql query
		$sql = "SELECT * FROM category";
		//getting result 
		$r = mysqli_query($con,$sql);
		//creating a blank array 
		$result = array();
		//looping through all the records fetched
		while($row = mysqli_fetch_array($r)){
			//Pushing name and id in the blank array created 
			array_push($result,array(
				"id"=>$row['id'],
				'name'=>$row['name'],
				'parent_id'=>$row['parent_id'],
				'arabicName'=>$row['arabicName'],
				'visibility'=>$row['visibility'],
			));
		}
		//Displaying the array in json format 
		echo json_encode(array('result'=>$result));
		mysqli_close($con);
    }
    
	if($function=="getAllCategories_ParentCategories"){
		$id = $_POST['id'];

		$con=serverConnection();
		$resultParent=getParentCategories($con,$id);
		$resultCategory = getAllCategory($con);
		
		echo json_encode(array('resultParent'=>$resultParent, 'resultCategory'=>$resultCategory));
		mysqli_close($con);
    }
	
    if($function=="getCategory"){
		$id = $_POST['id'];

		$con=serverConnection();
		
		$sql = "SELECT * FROM category WHERE id=$id";
		$r = mysqli_query($con,$sql);		
		$result = array();		
		$row = mysqli_fetch_array($r);
		array_push($result,array(
				"id"=>$row['id'],
				"name"=>$row['name'],
				"parent_id"=>$row['parent_id'],
				"arabicName"=>$row['arabicName'],
				"visibility"=>$row['visibility']
		));
		echo json_encode(array('result'=>$result));
		mysqli_close($con);
    }
    
    if($function=="addCategory"){
    
        //Getting values
		$name = $_POST['name'];
		$parent_id = $_POST['parent_id'];
		$arabicName = $_POST['arabicName'];
		$visibility = $_POST['visibility'];
		
		//Importing our db connection script
        $con=serverConnection();
		
		$sql = "SELECT id FROM category WHERE name = '$name'";
        $r = mysqli_query($con,$sql);
        $row = mysqli_fetch_array($r);
        $id = $row['id'];
            
        if($id != ""){
           echo 'This category is used previously';
		   mysqli_close($con);
        }
		else{
		//Creating an sql query
		$sql = "INSERT INTO category (name,parent_id,arabicName,visibility) VALUES ('$name','$parent_id','$arabicName' ,'$visibility')";
       
		//Executing query to database
		if(mysqli_query($con,$sql)){
			echo 'Category Added Successfully';
		}else{
			echo 'Could Not Add Category';
		}
		
		//Closing the database 
		mysqli_close($con);
		}
	}
    
    if($function=="updateCategory"){
		
		$id = $_POST['id'];
		$name = $_POST['name'];
		$parent_id = $_POST['parent_id'];
		$arabicName = $_POST['arabicName'];
		$visibility = $_POST['visibility'];
			
		$con=serverConnection();
		
		$sql = "SELECT id FROM category WHERE name = '$name' and parent_id = '$parent_id' ";
        $r = mysqli_query($con,$sql);
        $row = mysqli_fetch_array($r);
        $idRow = $row['id'];
            
        if($idRow != "" && $idRow != $id){
           echo 'This category is used previously';
		   mysqli_close($con);
        }
		else{
		$sql = "UPDATE category SET name = '$name', parent_id = '$parent_id', arabicName = '$arabicName', visibility = '$visibility' WHERE id = $id;";		
		if(mysqli_query($con,$sql)){
			echo 'Category Updated Successfully';
		}else{
			echo 'Could Not Update Category Try Again';
		}			
		mysqli_close($con);
		}
    }
    
    if($function=="getAllCategorySelected")
    {
        $con = serverConnection();
        $id = $_POST['id'];
    
        $sql = "SELECT category_id FROM ads_category WHERE ads_id=$id";
        $r = mysqli_query($con,$sql);
      
        $result = array();
    
        while($row = mysqli_fetch_array($r)){
            array_push($result,array(
              "category_id"=>$row['category_id'], 
            ));
        }
      
        echo json_encode(array('result'=>$result));
      
    }
    
    if($function=="deleteCategory"){
		$id = $_POST['id'];
		$con=serverConnection();
		
		$sql = "DELETE FROM category WHERE id=$id;";
		
		if(mysqli_query($con,$sql)){
			echo 'Category Deleted Successfully';
		}else{
			echo 'Could Not Delete Category Try Again';
		}
		mysqli_close($con);
    }
    
     if($function=="getAllCommentsAds"){
     
		$con=serverConnection();
		$result = getAllCommentsAds($con);
		
    	//Displaying the array in json format 
    	echo json_encode(array('result'=>$result));
     }
}