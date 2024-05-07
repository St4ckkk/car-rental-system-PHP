<?php
session_start();
include('includes/config.php');

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = md5($_POST['password']); // Consider upgrading to password_hash() and password_verify()

  // Query to check if the email and password are correct
  $sql = "SELECT id, EmailId, Password, FullName, is_verified FROM tblusers WHERE EmailId = :email AND Password = :password";
  $query = $dbh->prepare($sql);
  $query->bindParam(':email', $email, PDO::PARAM_STR);
  $query->bindParam(':password', $password, PDO::PARAM_STR);
  $query->execute();
  $result = $query->fetch(PDO::FETCH_OBJ);

  if ($result) {
    if ($result->is_verified == 1) {
      $_SESSION['login'] = $email;
      $_SESSION['fname'] = $result->FullName;
      $_SESSION['userid'] = $result->id; // Store user ID in session
      logActivity($result->id, "Login Success", "User logged in successfully");
      $currentpage = $_SERVER['REQUEST_URI'];
      echo "<script type='text/javascript'> document.location = '$currentpage'; </script>";
    } else {
      echo "<script>alert('Please wait for confirmation of your account. A verification email will be sent to you');</script>";
      logActivity($result->id, "Login Failed", "Attempt to log in to an unverified account");
    }
  } else {
    echo "<script>alert('Invalid email or password. Please try again.');</script>";
    logActivity(null, "Login Failed", "Invalid email or password attempt");
  }
}

function logActivity($userId, $actionType, $description)
{
  global $dbh;
  $sql = "INSERT INTO tbllogs (action_type, description, user_id, action_time) VALUES (:actionType, :description, :userId, NOW())";
  $stmt = $dbh->prepare($sql);
  $stmt->bindParam(':actionType', $actionType, PDO::PARAM_STR);
  $stmt->bindParam(':description', $description, PDO::PARAM_STR);
  $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
  $stmt->execute();
}
?>


<style type="text/css">
  .form-group .btn {
    background: #04dbc0;
  }

  .modal-footer {
    margin-top: -25px;
  }

  .btn:hover {
    background: #9b51e0;
    transition: 1s;

  }

  .last {
    color: #04dbc0;

  }

  .last:hover {
    color: red;
  }

  .close {
    color: #04dbc0;

  }
</style>
<div class="modal fade" id="loginform">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="background-color: #04dbc0;"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Login</h3>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="login_wrap">
            <div class="col-md-12 col-sm-6">
              <form method="post">
                <div class="form-group">
                  <input type="email" class="form-control" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
                <div class="form-group checkbox">
                  <input type="checkbox" id="remember">

                </div>
                <div class="form-group">
                  <input type="submit" name="login" value="Login" class="btn btn-block">
                </div>
              </form>
            </div>

          </div>
        </div>
      </div>
      <div class="modal-footer text-center">
        <p>Don't have an account? <a href="#signupform" data-toggle="modal" data-dismiss="modal" class="last">Signup Here</a></p>
        <p><a href="forgotpassword.php" data-toggle="modal" data-dismiss="modal" class="last">Forgot Password ?</a></p>
      </div>
    </div>
  </div>
</div>