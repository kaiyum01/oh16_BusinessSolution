<?php 
// session 
session_start();
include('../../include/db_connection.php');
//session start
$user_check=$_SESSION['login_user'];
// SQL Query To Fetch Complete Information Of User
$ses_sql=mysql_query("select * from admin_user_create where user_name='$user_check'");
$row = mysql_fetch_assoc($ses_sql);
$login_session_user_id 		=$row['id'];
$login_session_username 	=$row['user_name'];
$login_session_useremail 	=$row['user_email'];
$login_session_usertype 	=$row['user_type'];
if(!isset($login_session_user_id)){
mysql_close($connection); // Closing Connection
header('Location: ../../index.php'); // Redirecting To Home Page
}
//end session
include('../../include/common_function.php');
include('../../include/array_function.php');
include('../../include/message_function.php');

//action=save_data,Fetching Values from URL
if(	isset($_POST['action']) &&
 	isset($_POST['name1']) && 
 	isset($_POST['email1']) && 
 	isset($_POST['password1'])&&
	isset($_POST['usertype1']) &&
	isset($_POST['status1'])
 	//isset($_POST['contact1'])
 	){
	$action_save	=$_POST['action'];
	$name2			=$_POST['name1'];
	$email2			=$_POST['email1'];
	$password2		=$_POST['password1'];
	$usertype2		=$_POST['usertype1'];
	$status2		=$_POST['status1'];

	if($action_save=="save_data")
	{
	  	$query = mysql_query("insert into admin_user_create(user_name,pass_word,user_email,user_type,status_active,insert_date,inserted_by) values ('$name2','$password2','$email2','$usertype2','$status2','$insert_and_update_date','$login_session_user_id')");
	  	if($query==1){
	  	echo $msg_save;
		//echo "<h4>Success:</h4> You have created <b>user successfully</b>!";
	  	}
	  	else{
	  		echo $msg_save_fail;
			//echo "<h4>Failed:</h4> You have not created <b>user</b>!";
		  }
	}
}

// show list view action start here
if(isset($_GET['action'])){
	 $action_view_data= $_GET['action'];
	// echo $actions;
	if($action_view_data=="list_view")
	{
		$i=0;
		$result=mysql_query("select * from admin_user_create where is_deleted=0 order by id DESC");
		?>
		<table class="table table-hover">
		<thead>
		  <tr>
			<th>sl</th>
			<th>user name</th>
			<th>user type</th>
			<th>Email</th>
			<th>status</th>
			<th>Action</th>
		  </tr>
		</thead>
    <tbody>
    <?php
    while($data = mysql_fetch_row($result))
	{   
		$i++;
	?>
      <tr style="cursor: pointer;">
      	<td><?php echo $i; ?></td>
        <td><?php echo $data[1]; ?></td>
        <td><?php echo $user_type[$data[4]]; ?></td>
        <td><?php echo $data[3]; ?></td>
		<td><?php echo $status[$data[6]]; ?></td>
        <td><span class="glyphicon glyphicon-edit" onclick="get_data_from_list(<?php echo $data[0]; ?>)";></span> | <span class="glyphicon glyphicon-trash" onclick="fnc_delete(<?php echo $data[0]; ?>)";></span></td>
      </tr>
   <?php
  	}
  	?>
    </tbody>
  </table>
<?php
 	
	}
}

// Get data from database and populate data to form for update
if(isset($_GET['action'])){
	 $action_data_populate_to_form = $_GET['action'];
	 if($action_data_populate_to_form =="getdata")
	 {
		 
		$idd=$_GET['idd'];
		$sql= mysql_query("select * from admin_user_create where id=$idd");
		while($result = mysql_fetch_assoc($sql)) {
			echo json_encode($result);
		}
		 
	 }
}
//Update data
//Fetching Values from URL
if(	isset($_POST['action']) &&
 	isset($_POST['name1']) && 
 	isset($_POST['email1']) && 
 	isset($_POST['password1'])&&
	isset($_POST['usertype1']) &&
	isset($_POST['status1']) && 
	isset($_POST['update_id1'])   
 	//isset($_POST['contact1'])
 	){
	$action_update	=$_POST['action'];
	$name2			=$_POST['name1'];
	$email2			=$_POST['email1'];
	$password2		=$_POST['password1'];
	$usertype2		=$_POST['usertype1'];
	$status2		=$_POST['status1']; 
	$update_id2		=$_POST['update_id1'];
	
	if($action_update=="update_data")
	{
	//update query 
	  $query_update = mysql_query("update admin_user_create SET user_name='$name2', pass_word='$password2', user_email='$email2', user_type='$usertype2', status_active='$status2',update_date='$insert_and_update_date',updated_by='$login_session_user_id' where id='$update_id2'");
	  if($query_update==1){
	  	echo $msg_update;
		//echo "<h4>Success:</h4> Data update <b>user successfully</b>!";
	  }
	  else{
	  		echo $msg_update_fail;
			//echo "<h4>Failed:</h4> data update <b>field</b>!";
		  }
	}
}
//-------------------
	// Delete action
if(isset($_POST['action'])){
	 $action_delete= $_POST['action'];//$_POST['action'];
	 if($action_delete=="delete_data_action")
	 {
		$delete_id=$_POST['delete_id'];
		$sql_delete= mysql_query("update admin_user_create SET status_active=0, is_deleted=1,update_date='$insert_and_update_date',updated_by='$login_session_user_id' where id='$delete_id'");
		if($sql_delete==1)
		{
			echo $msg_delete;
		}
		else
		{
			echo $msg_delete_fail;
		}
	 }
}
// Search  list view  data table action start here
if(isset($_GET['action']) && isset($_GET['search_value1'])){
	 $action_search	= $_GET['action'];
	 $search_value2	= $_GET['search_value1'];//$_GET['search_value1'];
	// echo $actions;
	if($action_search=="search_list_view")
	{
		$i=0;
		$result=mysql_query("select * from admin_user_create where user_name='$search_value2' and is_deleted=0 order by id DESC");
		?>
		<table class="table table-hover">
    <thead>
      <tr>
      	<th>sl</th>
        <th>user name</th>
        <th>user type</th>
        <th>Email</th>
		<th>status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php
    while($data = mysql_fetch_row($result))
	{   
		$i++;
	?>
      <tr style="cursor: pointer;">
      	<td><?php echo $i; ?></td>
        <td><?php echo $data[1]; ?></td>
        <td><?php echo $user_type[$data[4]]; ?></td>
        <td><?php echo $data[3]; ?></td>
		<td><?php echo $status[$data[6]]; ?></td>
        <td><span class="glyphicon glyphicon-edit" onclick="get_data_from_list(<?php echo $data[0]; ?>)";></span> | <span class="glyphicon glyphicon-trash" onclick="fnc_delete(<?php echo $data[0]; ?>)";></span></td>
      </tr>
   <?php
  	}
  	?>
    </tbody>
  </table>
<?php
 	
	}
}

?>