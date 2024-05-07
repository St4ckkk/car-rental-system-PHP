<?php
function isPasswordStrong($password)
{
  return preg_match('/[A-Z]/', $password)        // at least one upper case
    && preg_match('/[a-z]/', $password)       // at least one lower case
    && preg_match('/\d/', $password)          // at least one digit
    && preg_match('/\W/', $password)          // at least one special character
    && strlen($password) >= 8;                // at least 8 characters long
}
function logActivity($userId, $actionType, $description)
{
  global $dbh;
  $sql = "INSERT INTO tbllogs (user_id, action_type, description, action_time) VALUES (:userId, :actionType, :description, NOW())";
  $stmt = $dbh->prepare($sql);
  $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
  $stmt->bindParam(':actionType', $actionType, PDO::PARAM_STR);
  $stmt->bindParam(':description', $description, PDO::PARAM_STR);
  $stmt->execute();
}


if (isset($_POST['signup'])) {
  $fname = $_POST['fullname'];
  $email = $_POST['emailid'];
  $mobile = $_POST['mobileno'];
  $password = $_POST['password'];
  if (!isPasswordStrong($password)) {
    echo "<script>alert('Password does not meet the security requirements.');</script>";
    logActivity(null, "Signup Failed", "Weak password attempt for email: $email");
  } else {
    // Use MD5 for hashing the password
    $passwordHash = md5($password);

    // SQL statement to insert the new user into the database
    $sql = "INSERT INTO tblusers (FullName, EmailId, ContactNo, Password) VALUES (:fname, :email, :mobile, :passwordHash)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':fname', $fname, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $query->bindParam(':passwordHash', $passwordHash, PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();
    if ($lastInsertId) {
      echo "<script>alert('Registration successful. Now you can login.');</script>";
      logActivity($lastInsertId, "Signup Success", "New user registered: $email");
    } else {
      echo "<script>alert('Something went wrong. Please try again.');</script>";
      logActivity(null, "Signup Failed", "Registration failed for email: $email");
    }
  }
}
?>



<script>
  function checkAvailability() {
    $("#loaderIcon").show();
    jQuery.ajax({
      url: "check_availability.php",
      data: 'emailid=' + $("#emailid").val(),
      type: "POST",
      success: function(data) {
        $("#user-availability-status").html(data);
        $("#loaderIcon").hide();
      },
      error: function() {}
    });
  }
</script>
<style type="text/css">
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
</style>
<div class="modal fade" id="signupform">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="background-color: #04dbc0;"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Sign Up</h3>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="signup_wrap">
            <div class="col-md-12 col-sm-6">
              <form method="post" name="signup">
                <div class="form-group">
                  <input type="text" class="form-control" name="fullname" placeholder="Full Name" required="required">
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" name="mobileno" placeholder="Mobile Number" maxlength="11" required="required">
                </div>
                <div class="form-group">
                  <input type="email" class="form-control" name="emailid" id="emailid" onBlur="checkAvailability()" placeholder="Email " required="required">
                  <span id="user-availability-status" style="font-size:12px;"></span>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control" name="password" id="password" placeholder="Password" required="required" onKeyUp="checkPasswordStrength()">
                  <span id="password-strength" style="font-size:12px;"></span>
                </div>

                <div class="form-group checkbox">
                  <input type="checkbox" id="terms_agree" required="required" checked="">
                  <label for="terms_agree">I Agree with <a href="#" class="last">Terms and Conditions</a></label>
                </div>
                <div class="form-group">
                  <input type="submit" value="Sign Up" name="signup" id="submit" class="btn btn-block">
                </div>
              </form>
            </div>

          </div>
        </div>
      </div>
      <div class="modal-footer text-center">
        <p>Already got an account? <a href="#loginform" data-toggle="modal" data-dismiss="modal" class="last">Login
            Here</a></p>
      </div>
    </div>
  </div>
</div>
<script>
  function validatePassword(password) {
    const hasUpperCase = /[A-Z]/.test(password);
    const hasLowerCase = /[a-z]/.test(password);
    const hasNumbers = /\d/.test(password);
    const hasNonalphas = /\W/.test(password);
    const isLengthValid = password.length >= 8;

    return hasUpperCase && hasLowerCase && hasNumbers && hasNonalphas && isLengthValid;
  }

  function checkPasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthIndicator = document.getElementById('password-strength');
    if (validatePassword(password)) {
      strengthIndicator.textContent = 'Strong';
      strengthIndicator.style.color = 'green';
      document.getElementById('submit').disabled = false;
    } else {
      strengthIndicator.textContent = 'Weak. Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.';
      strengthIndicator.style.color = 'red';
      document.getElementById('submit').disabled = true;
    }
  }

  document.getElementById('password').addEventListener('keyup', checkPasswordStrength);
</script>