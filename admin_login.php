<?php
session_start();
include('db.php');

// Redirect to dashboard if already logged in
if (isset($_SESSION['admin'])) {
    header("Location: admin_dashboard.php");
    exit();
}

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Secure prepared statement
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin'] = $admin['username'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid username or password!";
        }
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login - Miss Tourism</title>
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
  color: #888;
}
button {
  width: 85%;
  padding: 10px;
  background: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}
button:hover {
  background: #0056b3;
}
.error {
  color: red;
  margin-bottom: 10px;
  text-align: center;
}
.register-link {
  margin-top: 15px;
}
.register-link a {
  text-decoration: none;
  color: #007bff;
  font-weight: bold;
}
.register-link a:hover {
  text-decoration: underline;
}
</style>
</head>
<body>
<form method="POST">
  <h2>Admin Login</h2>
  <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

  <div class="input-container">
    <input type="text" name="username" placeholder="Username" required>
  </div>

  <div class="input-container">
    <input type="password" name="password" id="password" placeholder="Password" required>
    <span class="show-password" onclick="togglePassword()">👁️</span>
  </div>

  <button type="submit" name="login">Login</button>

  <div class="register-link">
    <p>Don't have an account? <a href="admin_register.php">Register here</a></p>
  </div>
</form>

<script>
function togglePassword() {
  const passwordField = document.getElementById('password');
  const icon = document.querySelector('.show-password');
  if (passwordField.type === "password") {
    passwordField.type = "text";
  } else {
    passwordField.type = "password";
    icon.textContent = "👁️";
  }
}
</script>
</body>
</html>
