<?php
// Connect to database
$host = "localhost";
$dbUser = "root";
$dbPass = "1234"; // your MySQL password
$dbName = "familysite";

$conn = new mysqli($host, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all unemployed users
$sql = "SELECT * FROM category WHERE occupation='Unemployed' ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Unemployed Members - Family Website</title>
<style>
body {font-family: Arial, sans-serif; background: #f4f7f8; padding: 20px;}
table {width: 100%; border-collapse: collapse; margin-top: 20px;}
th, td {padding: 12px; border: 1px solid #ccc; text-align: left;}
th {background-color: #3498db; color: white;}
tr:nth-child(even) {background-color: #f2f2f2;}
h2 {text-align:center; color: #2c3e50;}
</style>
<link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
  <nav>
    <h1 class="logo">FAMILY WEBSITE</h1>
    <ul>
      <li><a href="index.html">Home</a></li>
      <li><a href="about.html">About</a></li>
      <li><a href="services.html">Services</a></li>
      <li><a href="employment.php">Employers</a></li>
      <li><a href="student.php">Students</a></li>
      <li><a href="unemployed.php">Unemployed</a></li>
      <li><a href="farmer.php">Farmer</a></li>
      <li><a href="contact.html">Contact Us</a></li>
      <li><a href="login.php">Login</a></li>
    </ul>
  </nav>
</header>

<h2>Unemployed Members</h2>

<?php if($result && $result->num_rows > 0): ?>
<table>
<tr>
    <th>ID</th>
    <th>Full Name</th>
    <th>Family Name</th>
    <th>Gender</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Marital Status</th>
    <th>Registered At</th>
</tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?php echo htmlspecialchars($row['id']); ?></td>
    <td><?php echo htmlspecialchars($row['fullName']); ?></td>
    <td><?php echo htmlspecialchars($row['familyName']); ?></td>
    <td><?php echo htmlspecialchars($row['gender']); ?></td>
    <td><?php echo htmlspecialchars($row['email']); ?></td>
    <td><?php echo htmlspecialchars($row['phone']); ?></td>
    <td><?php echo htmlspecialchars($row['maritalStatus']); ?></td>
    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
</tr>
<?php endwhile; ?>
</table>
<?php else: ?>
<p style="text-align:center; color:red;">No unemployed members found.</p>
<?php endif; ?>

<?php $conn->close(); ?>

</body>
</html>
