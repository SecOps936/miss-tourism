<?php
session_start();
include('db.php');

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin'])) {
    header("Location: admin_dashboard.php");
    exit();
}

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long!";
    } else {
        // Check if username already exists
        $check = $conn->prepare("SELECT * FROM admin WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $error = "Username already exists!";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert into database
            $stmt = $conn->prepare("INSERT INTO admin (username, password_hash) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                $success = "Registration successful! You can now log in.";
            } else {
                $error = "Error while registering. Try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Registration - Miss Tourism</title>
<style>
body {
  background: #e8f0fe;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  font-family: Arial, sans-serif;
  margin: 0;
}
form {
  background: white;
  padding: 30px 25px;
  border-radius: 10px;
  width: 350px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.3);
  text-align: center;
}
h2 {
  margin-bottom: 20px;
  color: #333;
}
.input-container {
  position: relative;
  width: 85%;
  margin: 0 auto 15px auto;
}
.input-container input {
  width: 100%;
  padding: 10px 35px 10px 10px;
  border-radius: 5px;
  border: 1px solid #ccc;
  font-size: 15px;
  box-sizing: border-box;
}
.show-password {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  font-size: 18px;
  color: #777;
}
button {
  width: 85%;
  padding: 10px;
  background: blue;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}
button:hover {
  background: blue;
}
.error {
  color: red;
  margin-bottom: 10px;
  text-align: center;
}
.success {
  color: green;
  margin-bottom: 10px;
  text-align: center;
}
a {
  text-decoration: none;
  color: #007bff;
}
a:hover {
  text-decoration: underline;
}
</style>
</head>
<body>
<form method="POST">
  <h2>Admin Registration</h2>
  <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
  <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>

  <div class="input-container">
    <input type="text" name="username" placeholder="Choose Username" required>
  </div>

  <div class="input-container">
    <input type="password" name="password" id="password" placeholder="Enter Password" required>
    <span class="show-password" onclick="togglePassword('password', this)">👁️</span>
  </div>

  <div class="input-container">
    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
    <span class="show-password" onclick="togglePassword('confirm_password', this)">👁️</span>
  </div>

  <button type="submit" name="register">Register</button>
  <p>Already have an account? <a href="admin_login.php">Login here</a></p>
</form>

<script>
function togglePassword(fieldId, icon) {
  const field = document.getElementById(fieldId);
  if (field.type === "password") {
    field.type = "text";
  } else {
    field.type = "password";
    icon.textContent = "👁️";
  }
}
</script>
</body>
</html>
