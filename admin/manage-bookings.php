<?php
session_start();
error_reporting(0);
include('includes/config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

// Function to send email
function sendEmail($to, $subject, $body)
{
	$mail = new PHPMailer(true);
	try {
		$mail->isSMTP();
		$mail->Host = 'smtp.gmail.com';
		$mail->SMTPAuth = true;
		$mail->Username = 'keyanandydelgado@gmail.com';
		$mail->Password = 'gcrcajkxfpcidqpe';
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
		$mail->Port = 465;

		$mail->setFrom('keyanandydelgado@gmail.com', 'QuicKars');
		$mail->addAddress($to);

		$mail->isHTML(true);
		$mail->Subject = $subject;
		$mail->Body    = $body;

		$mail->send();
		echo 'Message has been sent';
	} catch (Exception $e) {
		echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
	}
}

if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
} else {
	// Handle booking confirmation
	if (isset($_REQUEST['aeid'])) {
		$aeid = intval($_GET['aeid']);
		$status = 1;

		$sql = "SELECT userEmail FROM tblbooking WHERE id=:aeid";
		$query = $dbh->prepare($sql);
		$query->bindParam(':aeid', $aeid, PDO::PARAM_STR);
		$query->execute();
		$user = $query->fetch(PDO::FETCH_OBJ);

		$sql = "UPDATE tblbooking SET Status=:status WHERE id=:aeid";
		$query = $dbh->prepare($sql);
		$query->bindParam(':status', $status, PDO::PARAM_STR);
		$query->bindParam(':aeid', $aeid, PDO::PARAM_STR);
		$query->execute();

		if ($query->rowCount() > 0) {
			$msg = "Booking Successfully Confirmed";
			$subject = "Booking Confirmation";
			$body = "Your booking has been successfully confirmed.";
			sendEmail($user->userEmail, $subject, $body);
		}
	}

	// Handle booking cancellation
	if (isset($_REQUEST['eid'])) {
		$eid = intval($_GET['eid']);
		$status = 2;

		$sql = "SELECT userEmail FROM tblbooking WHERE id=:eid";
		$query = $dbh->prepare($sql);
		$query->bindParam(':eid', $eid, PDO::PARAM_STR);
		$query->execute();
		$user = $query->fetch(PDO::FETCH_OBJ);

		$sql = "UPDATE tblbooking SET Status=:status WHERE id=:eid";
		$query = $dbh->prepare($sql);
		$query->bindParam(':status', $status, PDO::PARAM_STR);
		$query->bindParam(':eid', $eid, PDO::PARAM_STR);
		$query->execute();

		if ($query->rowCount() > 0) {
			$msg = "Booking Successfully Cancelled";
			$subject = "Booking Cancellation";
			$body = "Your booking has been successfully cancelled.";
			sendEmail($user->userEmail, $subject, $body);
		}
	}

	// Handle vehicle return
	if (isset($_REQUEST['beid'])) {
		$beid = intval($_GET['beid']);
		$status = 3;

		$sql = "SELECT userEmail FROM tblbooking WHERE id=:beid";
		$query = $dbh->prepare($sql);
		$query->bindParam(':beid', $beid, PDO::PARAM_STR);
		$query->execute();
		$user = $query->fetch(PDO::FETCH_OBJ);

		$sql = "UPDATE tblbooking SET Status=:status WHERE id=:beid";
		$query = $dbh->prepare($sql);
		$query->bindParam(':status', $status, PDO::PARAM_STR);
		$query->bindParam(':beid', $beid, PDO::PARAM_STR);
		$query->execute();

		if ($query->rowCount() > 0) {
			$msg = "Returned";
			$subject = "Vehicle Return Confirmation";
			$body = "The vehicle has been successfully returned. Thank you for using our service.";
			sendEmail($user->userEmail, $subject, $body);
		}
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

	<title>QuicKars</title>

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
			-webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
			box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
		}

		.succWrap {
			padding: 10px;
			margin: 0 0 20px 0;
			background: #fff;
			border-left: 4px solid #5cb85c;
			-webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
			box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
		}

		.btn {
			background: #04dbc0;
			width: 100%;
		}

		.btn:hover {
			background: #9b51e0;
			transition: 1s;

		}
	</style>

</head>

<body>
	<?php include('includes/header.php'); ?>

	<div class="ts-main-content">
		<?php include('includes/leftbar.php'); ?>
		<div class="content-wrapper">
			<div class="container-fluid">

				<div class="row">
					<div class="col-md-12">

						<h2 class="page-title">Manage Bookings</h2>

						<!-- Zero Configuration Table -->
						<div class="panel panel-default">
							<div class="panel-heading">Bookings Info</div>
							<div class="panel-body">
								<?php if ($error) { ?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>
								<table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>#</th>
											<th>Name</th>
											<th>Vehicle</th>
											<th>From Date</th>
											<th>To Date</th>
											<th>Message</th>
											<th>Status</th>
											<th>Posting date</th>
											<th>Action</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>#</th>
											<th>Name</th>
											<th>Vehicle</th>
											<th>From Date</th>
											<th>To Date</th>
											<th>Message</th>
											<th>Status</th>
											<th>Posting date</th>
											<th>Action</th>
										</tr>
									</tfoot>
									<tbody>

										<?php $sql = "SELECT tblusers.FullName,tblbrands.BrandName,tblvehicles.VehiclesTitle,tblbooking.FromDate,tblbooking.ToDate,tblbooking.message,tblbooking.VehicleId as vid,tblbooking.Status,tblbooking.PostingDate,tblbooking.id  from tblbooking join tblvehicles on tblvehicles.id=tblbooking.VehicleId join tblusers on tblusers.EmailId=tblbooking.userEmail join tblbrands on tblvehicles.VehiclesBrand=tblbrands.id  ";
										$query = $dbh->prepare($sql);
										$query->execute();
										$results = $query->fetchAll(PDO::FETCH_OBJ);
										$cnt = 1;
										if ($query->rowCount() > 0) {
											foreach ($results as $result) {				?>
												<tr>
													<td><?php echo htmlentities($cnt); ?></td>
													<td><?php echo htmlentities($result->FullName); ?></td>
													<td><a href="edit-vehicle.php?id=<?php echo htmlentities($result->vid); ?>"><?php echo htmlentities($result->BrandName); ?> , <?php echo htmlentities($result->VehiclesTitle); ?></td>
													<td><?php echo htmlentities($result->FromDate); ?></td>
													<td><?php echo htmlentities($result->ToDate); ?></td>
													<td><?php echo htmlentities($result->message); ?></td>
													<td><?php
														if ($result->Status == 0) {
															echo htmlentities('Not Confirmed yet');
														} else if ($result->Status == 1) {
															echo htmlentities('Confirmed');
														} else if ($result->Status == 3) {
															echo htmlentities('Returned');
														} else {
															echo htmlentities('Cancelled');
														}
														?></td>
													<td><?php echo htmlentities($result->PostingDate); ?></td>
													<td><a href="manage-bookings.php?aeid=<?php echo htmlentities($result->id); ?>" onclick="return confirm('Do you really want to Confirm this booking')"> Confirm</a> /


														<a href="manage-bookings.php?eid=<?php echo htmlentities($result->id); ?>" onclick="return confirm('Do you really want to Cancel this Booking')"> Cancel</a>/
														<a href="manage-bookings.php?beid=<?php echo htmlentities($result->id); ?>" onclick="return confirm('Did customer return Vehicle?')">return</a>

													</td>

												</tr>
										<?php $cnt = $cnt + 1;
											}
										} ?>

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