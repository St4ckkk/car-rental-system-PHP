<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
} else {
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

		<title>Admin Dashboard</title>

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
	</head>

	<body>
		<?php include('includes/header.php'); ?>

		<div class="ts-main-content">
			<?php include('includes/leftbar.php'); ?>
			<div class="content-wrapper">
				<div class="container-fluid">

					<div class="row">
						<div class="col-md-12">

							<h2 class="page-title">Dashboard</h2>

							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-3">
											<div class="panel panel-default">
												<div class="panel-body bk-success text-light" style="background-color: #28a745; background-image: linear-gradient(315deg, #28a745 0%, #72d689 74%);">
													<div class="stat-panel text-center">
														<?php
														$sql_verified = "SELECT id FROM tblusers WHERE is_verified = 1";
														$query_verified = $dbh->prepare($sql_verified);
														$query_verified->execute();
														$verified_users_count = $query_verified->rowCount();
														?>
														<div class="stat-panel-number h1 "><?php echo htmlentities($verified_users_count); ?></div>
														<div class="stat-panel-title text-uppercase">Verified Users</div>
													</div>
												</div>
												<a href="verified-users.php" class="block-anchor panel-footer">Full Detail <i class="fa fa-arrow-right"></i></a>
											</div>
										</div>

										<div class="col-md-3">
											<div class="panel panel-default">
												<div class="panel-body bk-danger text-light" style="background-color: #dc3545; background-image: linear-gradient(315deg, #dc3545 0%, #f79292 74%);">
													<div class="stat-panel text-center">
														<?php
														$sql_non_verified = "SELECT id FROM tblusers WHERE is_verified = 0";
														$query_non_verified = $dbh->prepare($sql_non_verified);
														$query_non_verified->execute();
														$non_verified_users_count = $query_non_verified->rowCount();
														?>
														<div class="stat-panel-number h1 "><?php echo htmlentities($non_verified_users_count); ?></div>
														<div class="stat-panel-title text-uppercase">Non-Verified Users</div>
													</div>
												</div>
												<a href="non-verified-users.php" class="block-anchor panel-footer">Full Detail <i class="fa fa-arrow-right"></i></a>
											</div>
										</div>

										<div class="col-md-3">
											<div class="panel panel-default">
												<div class="panel-body bk-info text-light" style="background-color: #6b0f1a;
background-image: linear-gradient(315deg, #6b0f1a 0%, #b91372 74%);
">
													<div class="stat-panel text-center">
														<?php
														$sql2 = "SELECT id from tblbooking ";
														$query2 = $dbh->prepare($sql2);
														$query2->execute();
														$results2 = $query2->fetchAll(PDO::FETCH_OBJ);
														$bookings = $query2->rowCount();
														?>

														<div class="stat-panel-number h1 ">
															<?php echo htmlentities($bookings); ?>
														</div>
														<div class="stat-panel-title text-uppercase">Total Bookings</div>
													</div>
												</div>
												<a href="manage-bookings.php" class="block-anchor panel-footer text-center">Full Detail &nbsp; <i class="fa fa-arrow-right"></i></a>
											</div>
										</div>
										<div class="col-md-3">
											<div class="panel panel-default">
												<div class="panel-body bk-warning text-light" style="background-color: #1fd1f9;
background-image: linear-gradient(315deg, #1fd1f9 0%, #b621fe 74%);
">
													<div class="stat-panel text-center">
														<?php
														$sql3 = "SELECT id from tblbrands ";
														$query3 = $dbh->prepare($sql3);
														$query3->execute();
														$results3 = $query3->fetchAll(PDO::FETCH_OBJ);
														$brands = $query3->rowCount();
														?>
														<div class="stat-panel-number h1 ">
															<?php echo htmlentities($brands); ?>
														</div>
														<div class="stat-panel-title text-uppercase">Listed Brands</div>
													</div>
												</div>
												<a href="manage-brands.php" class="block-anchor panel-footer text-center">Full Detail &nbsp; <i class="fa fa-arrow-right"></i></a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>



					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-3">
											<div class="panel panel-default">
												<div class="panel-body bk-success text-light" style="background-color: #d5adc8;
background-image: linear-gradient(315deg, #d5adc8 0%, #ff8489 74%);">
													<div class="stat-panel text-center">
														<?php
														$sql1 = "SELECT id from tblvehicles ";
														$query1 = $dbh->prepare($sql1);;
														$query1->execute();
														$results1 = $query1->fetchAll(PDO::FETCH_OBJ);
														$totalvehicle = $query1->rowCount();
														?>
														<div class="stat-panel-number h1 ">
															<?php echo htmlentities($totalvehicle); ?>
														</div>
														<div class="stat-panel-title text-uppercase">Listed Vehicles</div>
													</div>
												</div>
												<a href="manage-vehicles.php" class="block-anchor panel-footer text-center">Full Detail &nbsp; <i class="fa fa-arrow-right"></i></a>
											</div>
										</div>
										<div class="col-md-3">
											<div class="panel panel-default">
												<div class="panel-body bk-success text-light" style="background-color: #fbb034;
background-image: linear-gradient(315deg, #fbb034 0%, #ffdd00 74%);">
													<div class="stat-panel text-center">
														<?php
														$sql6 = "SELECT id from tblcontactusquery ";
														$query6 = $dbh->prepare($sql6);;
														$query6->execute();
														$results6 = $query6->fetchAll(PDO::FETCH_OBJ);
														$query = $query6->rowCount();
														?>
														<div class="stat-panel-number h1 ">
															<?php echo htmlentities($query); ?>
														</div>
														<div class="stat-panel-title text-uppercase">Queries</div>
													</div>
												</div>
												<a href="manage-conactusquery.php" class="block-anchor panel-footer text-center">Full Detail &nbsp; <i class="fa fa-arrow-right"></i></a>
											</div>
										</div>
										<div class="col-md-3">
											<div class="panel panel-default">
												<div class="panel-body bk-info text-light" style="background-color: #fce043;
background-image: linear-gradient(315deg, #fce043 0%, #fb7ba2 74%);">
													<div class="stat-panel text-center">
														<?php
														$sql5 = "SELECT id from tbltestimonial ";
														$query5 = $dbh->prepare($sql5);
														$query5->execute();
														$results5 = $query5->fetchAll(PDO::FETCH_OBJ);
														$testimonials = $query5->rowCount();
														?>

														<div class="stat-panel-number h1 ">
															<?php echo htmlentities($testimonials); ?>
														</div>
														<div class="stat-panel-title text-uppercase">Testimonials</div>
													</div>
												</div>
												<a href="testimonials.php" class="block-anchor panel-footer text-center">Full Detail &nbsp; <i class="fa fa-arrow-right"></i></a>
											</div>
										</div>

									</div>
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

		<script>
			window.onload = function() {

				// Line chart from swirlData for dashReport
				var ctx = document.getElementById("dashReport").getContext("2d");
				window.myLine = new Chart(ctx).Line(swirlData, {
					responsive: true,
					scaleShowVerticalLines: false,
					scaleBeginAtZero: true,
					multiTooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>",
				});

				// Pie Chart from doughutData
				var doctx = document.getElementById("chart-area3").getContext("2d");
				window.myDoughnut = new Chart(doctx).Pie(doughnutData, {
					responsive: true
				});

				// Dougnut Chart from doughnutData
				var doctx = document.getElementById("chart-area4").getContext("2d");
				window.myDoughnut = new Chart(doctx).Doughnut(doughnutData, {
					responsive: true
				});

			}
		</script>
	</body>

	</html>
<?php } ?>