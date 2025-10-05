<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Miss Tourism Registration</title>
<style>
body {
  font-family: Arial, sans-serif;
  background: #f4f4f4;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  margin: 0;
}

form {
  background: white;
  padding: 30px 25px;
  border-radius: 10px;
  width: 400px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  text-align: center;
}

h2 {
  margin-bottom: 20px;
  color: #333;
}

input, select {
  display: block;
  width: 90%;
  margin: 0 auto 15px auto;
  padding: 10px;
  font-size: 15px;
  border: 1px solid #ccc;
  border-radius: 5px;
  box-sizing: border-box;
}

input[type="file"] {
  border: none;
  background: #f9f9f9;
  padding: 5px;
}

button {
  width: 90%;
  margin: 10px auto 0 auto;
  background: #007bff;
  color: white;
  border: none;
  padding: 10px;
  cursor: pointer;
  border-radius: 5px;
  font-size: 16px;
}

button:hover {
  background: #0056b3;
}
</style>
</head>
<body>
<form action="save.php" method="POST" enctype="multipart/form-data">
  <h2>Miss & Mr Tourism Registration</h2>
  <input type="text" name="fullname" placeholder="Full Name" required>
  <input type="email" name="email" placeholder="Email" required>
  <input type="text" name="phone" placeholder="Phone Number" required>
  <input type="text" name="region" placeholder="Region" required>
  <select name="gender" required>
    <option value="">Select Gender</option>
    <option>Female</option>
    <option>Male</option>
  </select>
  <label for="photo">Upload Photo:</label>
  <input type="file" name="photo" id="photo" required>
  <button type="submit" name="submit">Submit</button>
</form>
</body>
</html>
