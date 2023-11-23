<?php
session_start();
error_reporting(0);
include('includes/config.php');
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


if(strlen($_SESSION['alogin'])==0)
	{	
header('location:index.php');
}
else{
if(isset($_REQUEST['eid']))
	{
$eid=intval($_GET['eid']);
$status="Cancelled";
$sql = "UPDATE item_appointment SET status=:status WHERE itembook_id=:eid";
$query = $dbh->prepare($sql);
$query -> bindParam(':status',$status, PDO::PARAM_STR);
$query-> bindParam(':eid',$eid, PDO::PARAM_INT);
$query -> execute();

try {
    $mail = new PHPMailer(true);

    // SMTP configuration for canceled booking
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'rezeyan127@gmail.com';
    $mail->Password = 'xsqrxtgggczblehv';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    // Fetch the user's email from the database based on booking details
    echo $getEmailQuery = "SELECT user.user_id, user.email, item_appointment.user_id, item_appointment.itembook_id FROM user JOIN item_appointment ON user.user_id = item_appointment.user_id WHERE item_appointment.itembook_id = :eid";
    $getEmailStmt = $dbh->prepare($getEmailQuery);
    $getEmailStmt->bindParam(':eid', $eid, PDO::PARAM_INT);
    $getEmailStmt->execute();
    $row = $getEmailStmt->fetch(PDO::FETCH_ASSOC);
    $email = $row['email'];

    // Email content for canceled booking
    $mail->setFrom('rezeyan127@gmail.com', 'Admin');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Item Booking Cancellation';
    $mail->Body = 'Your booking for item has been canceled.';

    // Send the email for canceled booking
    $mail->send();
    $msg = "Booking Successfully Canceled and Email Sent";
} catch (Exception $e) {
    $msg = "Booking Canceled but Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
}


if(isset($_REQUEST['aeid']))
	{
$aeid=intval($_GET['aeid']);
$status="Approve";

$sql = "UPDATE item_appointment SET status=:status WHERE itembook_id=:aeid";
$query = $dbh->prepare($sql);
$query -> bindParam(':status',$status, PDO::PARAM_STR);
$query-> bindParam(':aeid',$aeid, PDO::PARAM_INT);
$query -> execute();

try {
    $mail = new PHPMailer(true);

    // SMTP configuration for confirmeded booking
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'rezeyan127@gmail.com';
    $mail->Password = 'xsqrxtgggczblehv';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    // Fetch the user's email from the database based on booking details
    $getEmailQuery1 = "SELECT user.user_id, user.email, item_appointment.user_id, item_appointment.itembook_id FROM user JOIN item_appointment ON user.user_id = item_appointment.user_id WHERE item_appointment.itembook_id = :aeid";
    $getEmailStmt1 = $dbh->prepare($getEmailQuery1);
	$getEmailStmt1->bindParam(':aeid', $aeid, PDO::PARAM_INT);
    $getEmailStmt1->execute();
    $row = $getEmailStmt1->fetch(PDO::FETCH_ASSOC);
    $email = $row['email'];

    // Email content for confirmed booking
    $mail->setFrom('rezeyan127@gmail.com', 'Admin');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Item Booking Confirmation';
    $mail->Body = 'Your booking for item has been confirmed.';

    // Send the email for confirmed booking
    $mail->send();
    $msg = "Booking Successfully Confirmed and Email Sent";
} catch (Exception $e) {
    $msg = "Booking Confirmed but Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
}



 ?>

<!doctype html>
<html lang="en" class="no-js">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="theme-color" content="#3e454c">
	
	<title>College |Admin Manage Item Booking </title>

	<!-- Font awesome -->
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<!-- Sandstone Bootstrap CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<!-- Bootstrap Datatables -->
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<!-- Bootstrap social button library -->
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<!-- Bootstrap select -->
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<!-- Bootstrap file input -->
	<link rel="stylesheet" href="css/fileinput.min.css">
	<!-- Awesome Bootstrap checkbox -->
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<!-- Admin Stye -->
	<link rel="stylesheet" href="css/style.css">
  <style>
		.errorWrap {
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #dd3d36;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
.succWrap{
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #5cb85c;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
		</style>

</head>

<body>
	<?php include('includes/header.php');?>

	<div class="ts-main-content">
		<?php include('includes/leftbar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">

				<div class="row">
					<div class="col-md-12">

						<h2 class="page-title">Manage Item Bookings</h2>

						<!-- Zero Configuration Table -->
						<div class="panel panel-default">
							<div class="panel-heading">Bookings Info</div>
							<div class="panel-body">
							<?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>
								<table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
										<th>#</th>
											<th>Name</th>
											<th>Item</th>
											<th>From Time</th>
											<th>To Time</th>
											<th>Date</th>
											<th>Status</th>
											<th>Posting date</th>
											<th>Action</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
										<th>#</th>
										<th>Name</th>
											<th>Item</th>
											<th>From Time</th>
											<th>To Time</th>
											<th>Date</th>
											<th>Status</th>
											<th>Posting date</th>
											<th>Action</th>
										</tr>
									</tfoot>
									<tbody>

<?php $sql = "SELECT item.item_id, user.name, item.item_name, item_appointment.start_time, item_appointment.end_time, item_appointment.booking_date, item_appointment.status, item_appointment.bookingtime, item_appointment.itembook_id FROM item_appointment JOIN item ON item.item_id = item_appointment.item_id JOIN user ON user.user_id = item_appointment.user_id;";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{				?>	
										<tr>
											<td><?php echo htmlentities($cnt);?></td>
											<td><?php echo htmlentities($result->name);?></td>
											<td><a href="edit_item.php?item_id=<?php echo $result->item_id;?>"><?php echo htmlentities($result->item_name);?></td>
											<td><?php echo htmlentities($result->start_time);?></td>
											<td><?php echo htmlentities($result->end_time);?></td>
											<td><?php echo htmlentities($result->booking_date);?></td>
											<td><?php echo htmlentities($result->status);

										?></td>
											<td><?php echo htmlentities($result->bookingtime);?></td>
										<td><a href="booking_item.php?aeid=<?php echo htmlentities($result->itembook_id);?>" onclick="return confirm('Do you really want to Confirm this booking')"> Confirm</a> /


<a href="booking_item.php?eid=<?php echo htmlentities($result->itembook_id);?>" onclick="return confirm('Do you really want to Cancel this Booking')"> Cancel</a>
</td>

										</tr>
										<?php $cnt=$cnt+1; }} ?>
										
									</tbody>
								</table>

						

							</div>
						</div>

					

					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- Loading Scripts -->
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>
</body>
</html>
<?php } ?>
