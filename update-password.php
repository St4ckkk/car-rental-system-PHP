<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Function to log activity
function logActivity($email, $actionType, $description)
{
  global $dbh;
  $sql = "INSERT INTO tbllogs (user_id, action_type, description, action_time) VALUES ((SELECT id FROM tblusers WHERE EmailId = :email), :actionType, :description, NOW())";
  $stmt = $dbh->prepare($sql);
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->bindParam(':actionType', $actionType, PDO::PARAM_STR);
  $stmt->bindParam(':description', $description, PDO::PARAM_STR);
  $stmt->execute();
}

if (strlen($_SESSION['login']) == 0) {
  header('location:index.php');
} else {
  if (isset($_POST['update'])) {
    $password = md5($_POST['password']);  // Consider using password_hash in production
    $newpassword = $_POST['newpassword'];
    $email = $_SESSION['login'];

    if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}$/", $newpassword)) {
      $error = "Password must be strong: at least one uppercase letter, one lowercase letter, one number, and one special character.";
    } else {
      $newpassword = md5($newpassword);  // MD5 is not recommended for password hashing in production

      $sql = "SELECT Password FROM tblusers WHERE EmailId=:email and Password=:password";
      $query = $dbh->prepare($sql);
      $query->bindParam(':email', $email, PDO::PARAM_STR);
      $query->bindParam(':password', $password, PDO::PARAM_STR);
      $query->execute();

      if ($query->rowCount() > 0) {
        $con = "UPDATE tblusers SET Password=:newpassword WHERE EmailId=:email";
        $chngpwd1 = $dbh->prepare($con);
        $chngpwd1->bindParam(':email', $email, PDO::PARAM_STR);
        $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
        $chngpwd1->execute();
        $msg = "Your Password successfully changed";

        logActivity($email, "Password Changed", "Password change successful for user.");
      } else {
        $error = "Your current password is wrong";
        logActivity($email, "Failed Password Change", "Failed password change attempt due to incorrect current password.");
      }
    }
  }
?>
  <!DOCTYPE HTML>
  <html lang="en">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <title>Quickars</title>
    <!--Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
    <!--Custome Style -->
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <!--OWL Carousel slider-->
    <link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
    <link rel="stylesheet" href="assets/css/owl.transitions.css" type="text/css">
    <!--slick-slider -->
    <link href="assets/css/slick.css" rel="stylesheet">
    <!--bootstrap-slider -->
    <link href="assets/css/bootstrap-slider.min.css" rel="stylesheet">
    <!--FontAwesome Font Style -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">

    <!-- SWITCHER -->
    <link rel="stylesheet" id="switcher-css" type="text/css" href="assets/switcher/css/switcher.css" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/red.css" title="red" media="all" data-default-color="true" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/orange.css" title="orange" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/blue.css" title="blue" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/pink.css" title="pink" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/green.css" title="green" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/purple.css" title="purple" media="all" />

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/favicon-icon/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/favicon-icon/apple-touch-icon-114-precomposed.html">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/favicon-icon/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="assets/images/favicon-icon/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="assets/images/mainlogo.png">
    <!-- Google-Font-->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
  </head>

  <body>

    <!-- Start Switcher -->
    <?php include('includes/colorswitcher.php'); ?>
    <!-- /Switcher -->

    <!--Header-->
    <?php include('includes/header.php'); ?>
    <!-- /Header -->
    <!--Page Header-->
    <section class="page-header profile_page">
      <div class="container">
        <div class="page-header_wrap">
          <div class="page-heading">
            <h1>Update Password</h1>
          </div>
          <ul class="coustom-breadcrumb">
            <li><a href="#">Home</a></li>
            <li>Update Password</li>
          </ul>
        </div>
      </div>
      <!-- Dark Overlay-->
      <div class="dark-overlay"></div>
    </section>
    <!-- /Page Header-->

    <?php
    $useremail = $_SESSION['login'];
    $sql = "SELECT * from tblusers where EmailId=:useremail";
    $query = $dbh->prepare($sql);
    $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    $cnt = 1;
    if ($query->rowCount() > 0) {
      foreach ($results as $result) { ?>
        <section class="user_profile inner_pages">
          <div class="container">
            <div class="user_profile_info gray-bg padding_4x4_40" style="display:none">
              <div class="upload_user_logo"> <img src="assets/images/dealer-logo.jpg" alt="image">
              </div>

              <div class="dealer_info">
                <h5><?php echo htmlentities($result->FullName); ?></h5>
                <p><?php echo htmlentities($result->Address); ?><br>
                  <?php echo htmlentities($result->City); ?>&nbsp;<?php echo htmlentities($result->Country);
                                                                }
                                                              } ?></p>
              </div>

            </div>
            <div class="row">
              <div class="col-md-3 col-sm-3">
                <?php include('includes/sidebar.php'); ?>
                <div class="col-md-6 col-sm-8">
                  <div class="profile_wrap">
                    <form name="chngpwd" method="post" onSubmit="return valid();">

                      <div class="gray-bg field-title">
                        <h6 style="text-decoration: none;">Update password</h6>
                      </div>
                      <?php if ($error) { ?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>
                      <div class="form-group">
                        <label class="control-label">Current Password</label>
                        <input class="form-control white_bg" id="password" name="password" type="password" required>
                      </div>
                      <div cl>
                        <div class="form-group">
                          <label class="control-label">New Password</label>
                          <input class="form-control white_bg" id="newpassword" type="password" name="newpassword" required onKeyUp="checkPasswordStrength();">
                          <div id=" password-strength-status">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label">Confirm Password</label>
                        <input class="form-control white_bg" id="confirmpassword" type="password" name="confirmpassword" required>
                      </div>

                      <div class="form-group">
                        <input type="submit" value="Update" name="update" id="submit" class="btn btn-block">
                      </div>
                      <?php if ($error) { ?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?></div><?php } else if ($msg) { ?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?></div><?php } ?>
                    </form>
                  </div>
                </div>
              </div>
            </div>
        </section>
        <!--/Profile-setting-->

        <<!--Footer -->
          <?php include('includes/footer.php'); ?>
          <!-- /Footer-->

          <!--Back to top-->
          <div id="back-top" class="back-top"> <a href="#top" style="background-color: #04dbc0;"><i class="fa fa-angle-up" aria-hidden="true"></i> </a> </div>
          <!--/Back to top-->

          <!--Login-Form -->
          <?php include('includes/login.php'); ?>
          <!--/Login-Form -->

          <!--Register-Form -->
          <?php include('includes/registration.php'); ?>

          <!--/Register-Form -->

          <!--Forgot-password-Form -->
          <?php include('includes/forgotpassword.php'); ?>
          <!--/Forgot-password-Form -->

          <!-- Scripts -->
          <script src="assets/js/jquery.min.js"></script>
          <script src="assets/js/bootstrap.min.js"></script>
          <script src="assets/js/interface.js"></script>
          <!--Switcher-->
          <script src="assets/switcher/js/switcher.js"></script>
          <!--bootstrap-slider-JS-->
          <script src="assets/js/bootstrap-slider.min.js"></script>
          <!--Slider-JS-->
          <script src="assets/js/slick.min.js"></script>
          <script src="assets/js/owl.carousel.min.js"></script>
          <script type="text/javascript">
            function checkPasswordStrength() {
              var number = /([0-9])/;
              var alphabets = /([a-zA-Z])/;
              var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;

              var password = document.getElementById("newpassword").value;
              var strength = 0;
              if (password.length < 6) {
                document.getElementById('password-strength-status').innerHTML = "Weak (should be at least 6 characters.)";
                document.getElementById('password-strength-status').style.color = 'red';
              } else {
                if (password.match(number) && password.match(alphabets) && password.match(special_characters)) {
                  strength += 3;
                } else if (password.match(number) && password.match(alphabets)) {
                  strength += 2;
                } else if (password.match(number)) {
                  strength += 1;
                }
                switch (strength) {
                  case 1:
                    document.getElementById('password-strength-status').innerHTML = "Weak";
                    document.getElementById('password-strength-status').style.color = 'red';
                    break;
                  case 2:
                    document.getElementById('password-strength-status').innerHTML = "Moderate";
                    document.getElementById('password-strength-status').style.color = 'orange';
                    break;
                  case 3:
                    document.getElementById('password-strength-status').innerHTML = "Strong";
                    document.getElementById('password-strength-status').style.color = 'green';
                    break;
                }
              }
            }
          </script>


  </body>

  </html>
<?php } ?>