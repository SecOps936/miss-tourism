<?php
include('db.php');
$result = $conn->query("SELECT * FROM registrations ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Registered Contestants</title>
<style>
table {
  border-collapse: collapse;
  width: 90%;
  margin: 30px auto;
}
th, td {
  border: 1px solid #ccc;
  padding: 10px;
  text-align: left;
}
th {
  background-color: #007bff;
  color: white;
}
img {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 5px;
}
</style>
</head>
<body>
<h2 style="text-align:center;">Registered Contestants</h2>
<table>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Region</th>
    <th>Gender</th>
    <th>Photo</th>
    <th>Registered At</th>
  </tr>
  <?php while ($row = $result->fetch_assoc()) { ?>
  <tr>
    <td><?= $row['id']; ?></td>
    <td><?= htmlspecialchars($row['fullname']); ?></td>
    <td><?= htmlspecialchars($row['email']); ?></td>
    <td><?= htmlspecialchars($row['phone']); ?></td>
    <td><?= htmlspecialchars($row['region']); ?></td>
    <td><?= htmlspecialchars($row['gender']); ?></td>
    <td><img src="uploads/<?= htmlspecialchars($row['photo']); ?>" alt="photo"></td>
    <td><?= $row['created_at']; ?></td>
  </tr>
  <?php } ?>
</table>
</body>
</html>
