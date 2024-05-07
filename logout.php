<?php
session_start();
include('includes/config.php'); // Ensure the config file is included if needed for database connection

// Function to log activity
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

if (isset($_SESSION['userid'])) {
    // Log the logout activity before clearing and destroying the session
    logActivity($_SESSION['userid'], 'Logout', 'User logged out successfully');
}

// Clear the session array
$_SESSION = array();

// Delete the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();
header("location:index.php");
exit;
