<?php
session_start();
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bookingId = $_POST['booking_id'];

    // Perform the deletion query
    $sql = "DELETE FROM tblbooking WHERE id = :booking_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':booking_id', $bookingId, PDO::PARAM_INT);
    $query->execute();

    // Redirect to the page where bookings are displayed
    header('Location: my-booking.php');
    exit();
} else {
    // Handle the case where the request method is not POST
    echo "Invalid request!";
}
?>
