<?php
session_start();
include("db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if appointment ID is provided
if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];

    // Prepare deletion query
    $query = "DELETE FROM appoinment_data WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $appointment_id, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);

    // Check if deletion was successful
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "<script>
                window.location.href = 'appoinment_dashboard.php'; // Redirect after successful delete
              </script>";
    } else {
        echo "<script>
                alert('Failed to delete the appointment.');
                window.location.href = 'appoinment_dashboard.php'; // Redirect even if failed
              </script>";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "<script>
            alert('No appointment ID specified.');
            window.location.href = 'appoinment_dashboard.php'; // Redirect if no ID
          </script>";
}
?>
