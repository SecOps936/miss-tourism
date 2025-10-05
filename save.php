<?php
include('db.php');

if (isset($_POST['submit'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $region = $_POST['region'];
    $gender = $_POST['gender'];

    $targetDir = "uploads/";
    $photoName = basename($_FILES["photo"]["name"]);
    $targetFilePath = $targetDir . time() . "_" . $photoName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    $allowedTypes = ["jpg", "jpeg", "png", "gif"];

    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
            $sql = "INSERT INTO registrations (fullname, email, phone, region, gender, photo)
                    VALUES ('$fullname', '$email', '$phone', '$region', '$gender', '$targetFilePath')";

            if ($conn->query($sql) === TRUE) {
                echo "<h2 style='text-align:center;'>🎉 Registration Successful!</h2>";
                echo "<p style='text-align:center;'>Thank you for registering for Miss Tourism. Your information has been submitted successfully.</p>";
            } else {
                echo "Database Error: " . $conn->error;
            }
        } else {
            echo "Error uploading photo. Check folder permissions.";
        }
    } else {
        echo "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
    }
}
$conn->close();
?>
