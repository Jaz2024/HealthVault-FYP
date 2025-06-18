<?php
session_start();
include("db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if the ID is provided in the URL
if (isset($_GET['id'])) {
    $record_id = $_GET['id'];

    // Delete the record from the health_data table
    $query = "DELETE FROM health_data WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $record_id, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "<script>
                window.location.href = 'dashboard.php'; // Redirect to the dashboard after delete
              </script>";
    } else {
        echo "<script>
                alert('Failed to delete the record.');
                window.location.href = 'dashboard.php'; // Redirect to the dashboard if failed
              </script>";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "<script>
            alert('No record ID specified.');
            window.location.href = 'dashboard.php'; // Redirect to the dashboard if no ID
          </script>";
}
?>

