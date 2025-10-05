<?php
session_start();
include('db.php');

// Security headers
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Redirect if not logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Secure DB query
$stmt = $conn->prepare("SELECT id, photo, fullname, email, phone, region, gender, created_at FROM registrations ORDER BY id DESC");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Miss Tourism - Admin Dashboard</title>
<style>
body {
  font-family: 'Segoe UI', Arial, sans-serif;
  background: #f8f8f8;
  margin: 0;
  padding: 0;
}

/* ===== HEADER SECTION ===== */
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #007bff;
  color: white;
  padding: 10px 30px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.2);
  position: relative;
}

/* Centered title */
.header h2 {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  margin: 0;
  font-size: 22px;
  letter-spacing: 0.5px;
}

/* Buttons on the right corner */
.header .buttons {
  display: flex;
  gap: 8px;
  margin-left: auto;
}
.header .buttons a {
  background: white;
  color: #007bff;
  padding: 5px 12px;
  font-size: 13px;
  border-radius: 20px;
  text-decoration: none;
  transition: 0.3s;
  font-weight: bold;
}
.header .buttons a:hover {
  background: #e6e6e6;
}
.header .buttons a.logout {
  color: red;
}

/* ===== TABLE SECTION ===== */
table {
  border-collapse: collapse;
  width: 90%;
  margin: 30px auto;
  background: white;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
th, td {
  padding: 12px 10px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}
th {
  background: #007bff;
  color: white;
  font-size: 15px;
}
tr:hover {
  background: #f2f2f2;
}
img {
  width: 60px;
  height: 60px;
  border-radius: 8px;
  object-fit: cover;
  cursor: pointer;
  transition: transform 0.2s;
}
img:hover {
  transform: scale(1.05);
}
.no-data {
  text-align: center;
  color: #888;
  padding: 20px;
}

/* ===== IMAGE PREVIEW MODAL ===== */
#previewContainer {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.7);
  justify-content: center;
  align-items: center;
  z-index: 1000;
}
#previewContainer img {
  max-width: 80%;
  max-height: 80%;
  border-radius: 10px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.5);
  border: 3px solid white;
}
#previewContainer span {
  position: absolute;
  top: 30px;
  right: 50px;
  font-size: 30px;
  color: white;
  cursor: pointer;
  font-weight: bold;
}
</style>
</head>
<body>

<div class="header">
  <div class="left-space"></div> <!-- Invisible spacer -->
  <h2>Miss & Mr Tourism - Registered Contestants</h2>
  <div class="buttons">
    <a href="change_password.php">🔑 Change Password</a>
    <a href="logout.php" class="logout">⎋ Logout</a>
  </div>
</div>

<table>
  <tr>
    <th>Photo</th>
    <th>Full Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Region</th>
    <th>Gender</th>
    <th>Registered At</th>
  </tr>
  <?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td>
        <img src="<?= htmlspecialchars($row['photo']); ?>" 
             alt="photo" 
             onclick="showPreview('<?= htmlspecialchars($row['photo']); ?>')">
      </td>
      <td><?= htmlspecialchars($row['fullname']); ?></td>
      <td><?= htmlspecialchars($row['email']); ?></td>
      <td><?= htmlspecialchars($row['phone']); ?></td>
      <td><?= htmlspecialchars($row['region']); ?></td>
      <td><?= htmlspecialchars($row['gender']); ?></td>
      <td><?= htmlspecialchars($row['created_at']); ?></td>
    </tr>
    <?php endwhile; ?>
  <?php else: ?>
    <tr><td colspan="7" class="no-data">No contestants registered yet.</td></tr>
  <?php endif; ?>
</table>

<!-- ===== IMAGE PREVIEW OVERLAY ===== -->
<div id="previewContainer" onclick="closePreview()">
  <span onclick="closePreview()">✖</span>
  <img id="previewImage" src="" alt="Full Image">
</div>

<script>
function showPreview(src) {
  const container = document.getElementById('previewContainer');
  const image = document.getElementById('previewImage');
  image.src = src;
  container.style.display = 'flex';
}

function closePreview() {
  document.getElementById('previewContainer').style.display = 'none';
}
</script>

</body>
</html>
