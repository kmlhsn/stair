
<?php
session_start();
include('include/config.php');
if(strlen($_SESSION['alogin'])==0)
	{	
header('location:index.php');
}
else{
date_default_timezone_set('Asia/Kolkata');// change according timezone
$currentTime = date( 'd-m-Y h:i:s A', time () );


if(isset($_POST['submit']))
{
	$type=$_POST['type'];
	$email=$_POST['email'];
	$name=$_POST['name'];
	$emailpass=$_POST['emailpass'];
	if($type=="sender"){
		if(empty($emailpass)){
			 $_SESSION['delmsg']="Enter Sender Email Password !!";
		}else{
		$sql1=mysqli_query($con,"SELECT * FROM `emailsettings` WHERE `emailtype`='$type'");
		if(mysqli_num_rows($sql1)>0){
				$sql=mysqli_query($con,"UPDATE `emailsettings` SET `email`='$email',`name`='$name', `emailpassword`='$emailpass' WHERE `emailtype`='$type'");
$_SESSION['msg']="Record Updated !!";
		}else{
				$sql=mysqli_query($con,"INSERT INTO `emailsettings`( `emailtype`, `email`, `name`,  `emailpassword`) VALUES ('$type','$email','$name', '$emailpass')");
$_SESSION['msg']="Record Inserted !!";
		}
	}
	}else{
		$sql=mysqli_query($con,"INSERT INTO `emailsettings`( `emailtype`, `email`, `name`) VALUES ('$type','$email','$name')");
$_SESSION['msg']="Record Inserted !!";
	}


}

if(isset($_GET['del']))
		  {
		          mysqli_query($con,"delete from emailsettings where id = '".$_GET['id']."'");
                  $_SESSION['delmsg']="Record deleted !!";
		  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin| EmailSettings</title>
	<link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link type="text/css" href="css/theme.css" rel="stylesheet">
	<link type="text/css" href="images/icons/css/font-awesome.css" rel="stylesheet">
	<link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
<script>


function getDesign(val) {
	$.ajax({
	type: "POST",
	url: "getphpfiles/get_design.php",
	data:'des_id='+val,
	success: function(data){
		$("#stairdesign").html(data);
	}
	});
}

function getType() {
	var type=$('#typeemail').val();
	if(type=="sender"){
		$('#passinput').css('display', 'block');
	}else{
		$('#passinput').css('display', 'none');
	}
	//alert(type);
}
</script>
</head>
<body>
<?php include('include/header.php');?>

	<div class="wrapper">
		<div class="container">
			<div class="row">
<?php include('include/sidebar.php');?>				
			<div class="span9">
					<div class="content">

						<div class="module">
							<div class="module-head">
								<h3>Email</h3>
							</div>
							<div class="module-body">

									<?php if(isset($_POST['submit']))
{ if($_SESSION['msg'] !=""){?>
									<div class="alert alert-success">
										<button type="button" class="close" data-dismiss="alert">×</button>
									<strong>Well done!</strong>	<?php echo htmlentities($_SESSION['msg']);?><?php echo htmlentities($_SESSION['msg']="");?>
									</div>
<?php }else if($_SESSION['delmsg'] !=""){?>   
	<div class="alert alert-error">
										<button type="button" class="close" data-dismiss="alert">×</button>
									<strong>Oh snap!</strong> 	<?php echo htmlentities($_SESSION['delmsg']);?><?php echo htmlentities($_SESSION['delmsg']="");?>
									</div>
<?php } } ?>


									<?php if(isset($_GET['del']))
{?>
									<div class="alert alert-error">
										<button type="button" class="close" data-dismiss="alert">×</button>
									<strong>Oh snap!</strong> 	<?php echo htmlentities($_SESSION['delmsg']);?><?php echo htmlentities($_SESSION['delmsg']="");?>
									</div>
<?php } ?>

									<br />

			<form class="form-horizontal row-fluid" name="Category" method="post" >
				<div class="control-group">
<label class="control-label" for="basicinput">Email Type</label>
<div class="controls">
<select name="type" class="span8 tip" id="typeemail" onchange="getType()" required>
<option value="" selected disabled>Choose...</option>
<option value="sender" >Sender</option>
<option value="admin receiver" >Admin Receiver</option>
<option value="other receiver" >Other Receiver</option>
</select>
</div>
</div>				
<div class="control-group">
<label class="control-label" for="basicinput">Email</label>
<div class="controls">
<input type="email" placeholder="Enter email"  name="email" class="span8 tip" required>
</div>
</div>			
<div class="control-group" style="display:none;" id="passinput" >
<label class="control-label" for="basicinput">Email Password</label>
<div class="controls">
<input type="password" placeholder="Enter email password"  name="emailpass" class="span8 tip" >
</div>
</div>			
<div class="control-group">
<label class="control-label" for="basicinput">Sender/Receiver Name</label>
<div class="controls">
<input type="text" placeholder="Enter Name"  name="name" class="span8 tip" required>
</div>
</div>


	<div class="control-group">
											<div class="controls">
												<button type="submit" name="submit" class="btn">Insert</button>
											</div>
										</div>
									</form>
							</div>
						</div>


	<div class="module">
							<div class="module-head">
								<h3>Manage Emails</h3>
							</div>
							<div class="module-body table">
								<table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped	 display" width="100%">
									<thead>
										<tr>
											<th>#</th>
											<th>Email Type</th>
											<th>Email</th>
											<th>Sender/Receiver Name</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>

<?php $query=mysqli_query($con,"SELECT * FROM `emailsettings`");
$cnt=1;
while($row=mysqli_fetch_array($query))
{
?>									
										<tr>
											<td><?php echo htmlentities($cnt);?></td>
											<td><?php echo htmlentities($row['emailtype']);?></td>
											<td><?php echo htmlentities($row['email']);?></td>
											<td><?php echo htmlentities($row['name']);?></td>
											<td>
											<?php if($row['emailtype']=="sender"){}else{?>
											<a href="emailsettings.php?id=<?php echo $row['id']?>&del=delete" onClick="return confirm('Are you sure you want to delete?')"><i class="icon-remove-sign"></i></a></td>
											<?php } ?>
										</tr>
										<?php $cnt=$cnt+1; } ?>
										
								</table>
							</div>
						</div>						

						
						
					</div><!--/.content-->
				</div><!--/.span9-->
			</div>
		</div><!--/.container-->
	</div><!--/.wrapper-->

<?php include('include/footer.php');?>

	<script src="scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
	<script src="scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
	<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="scripts/flot/jquery.flot.js" type="text/javascript"></script>
	<script src="scripts/datatables/jquery.dataTables.js"></script>
	<script>
		$(document).ready(function() {
			getDesign('1');
			$('.datatable-1').dataTable();
			$('.dataTables_paginate').addClass("btn-group datatable-pagination");
			$('.dataTables_paginate > a').wrapInner('<span />');
			$('.dataTables_paginate > a:first-child').append('<i class="icon-chevron-left shaded"></i>');
			$('.dataTables_paginate > a:last-child').append('<i class="icon-chevron-right shaded"></i>');
		} );
	</script>
</body>
<?php } ?>