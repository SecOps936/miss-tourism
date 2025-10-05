<?php
session_start();
include('db.php');

// Redirect if admin not logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$username = $_SESSION['admin'];
$message = "";

// CSRF Protection — generate token
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update'])) {
    // Validate CSRF token
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("<p style='color:red;'>Invalid CSRF token. Please reload the page.</p>");
    }

    // Sanitize input
    $old_pass = $_POST['old_pass'] ?? '';
    $new_pass = $_POST['new_pass'] ?? '';
    $confirm_pass = $_POST['confirm_pass'] ?? '';

    // Fetch current hash
    $stmt = $conn->prepare("SELECT password_hash FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($current_hash);
    $stmt->fetch();
    $stmt->close();

    // Verify current password
    if (!$current_hash || !password_verify($old_pass, $current_hash)) {
        $message = "<p style='color:red;'>❌ Old password is incorrect!</p>";
    } elseif ($new_pass !== $confirm_pass) {
        $message = "<p style='color:red;'>⚠️ New passwords do not match!</p>";
    } elseif (strlen($new_pass) < 8) {
        $message = "<p style='color:red;'>⚠️ Password must be at least 8 characters long!</p>";
    } else {
        // Hash and update password
        $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE admin SET password_hash=? WHERE username=?");
        $update->bind_param("ss", $new_hash, $username);
        if ($update->execute()) {
            $message = "<p style='color:green;'>✅ Password updated successfully!</p>";
        } else {
            $message = "<p style='color:red;'>❌ Error updating password!</p>";
        }
        $update->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Change Password</title>
<style>
body {
  background: #f2f2f2;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  font-family: Arial, sans-serif;
}
form {
  background: white;
  padding: 25px;
  border-radius: 10px;
  width: 350px;
  box-shadow: 0 3px 8px rgba(0,0,0,0.3);
  text-align: center;
}
input {
  width: 85%;
  padding: 10px;
  margin: 0 auto 15px auto;
  display: block;
  border-radius: 5px;
  border: 1px solid #ccc;
  font-size: 15px;
}
.password-container {
  position: relative;
  width: 85%;
  margin: 0 auto 15px auto;
}
.password-container input {
  width: 100%;
  padding-right: 35px;
}
.toggle-password {
  position: absolute;
  right: 10px;
  top: 10px;
  cursor: pointer;
  color: #007bff;
  font-size: 16px;
}
button {
  width: 85%;
  padding: 10px;
  background: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
button:hover {
  background: #0056b3;
}
a {
  display: block;
  margin-top: 10px;
  color: #007bff;
  text-decoration: none;
}
</style>
</head>
<body>
<form method="POST">
  <h2>Change Password</h2>
  <?= $message ?>
  <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token']) ?>">

  <div class="password-container">
    <input type="password" name="old_pass" id="old_pass" placeholder="Current Password" required>
    <span class="toggle-password" onclick="togglePassword('old_pass', this)">👁️</span>
  </div>

  <div class="password-container">
    <input type="password" name="new_pass" id="new_pass" placeholder="New Password" required>
    <span class="toggle-password" onclick="togglePassword('new_pass', this)">👁️</span>
  </div>

  <div class="password-container">
    <input type="password" name="confirm_pass" id="confirm_pass" placeholder="Confirm New Password" required>
    <span class="toggle-password" onclick="togglePassword('confirm_pass', this)">👁️</span>
  </div>

  <button type="submit" name="update">Update Password</button>
  <a href="admin_dashboard.php">Back to Dashboard</a>
</form>

<script>
function togglePassword(fieldId, icon) {
  const input = document.getElementById(fieldId);
  if (input.type === "password") {
    input.type = "text";
  } else {
    input.type = "password";
    icon.textContent = "👁️";
  }
}
</script>
</body>
</html>
