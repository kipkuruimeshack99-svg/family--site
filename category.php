<?php
session_start();

// Database connection
$host = "localhost";
$dbUser = "root";
$dbPass = "1234"; // Your MySQL password
$dbName = "familysite";

$conn = new mysqli($host, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['fullName'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $familyName = trim($_POST['familyName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $maritalStatus = $_POST['maritalStatus'] ?? '';
    $occupation = $_POST['occupation'] ?? '';
    $workplace = trim($_POST['workplace'] ?? null);
    $schoolName = trim($_POST['schoolName'] ?? null);

   

    // Basic validation
    if (!$fullName || !$gender || !$familyName || !$phone || !$maritalStatus || !$occupation) {
        $message = "Please fill in all required fields!";
        $message_type = 'error';
    } elseif ($occupation === "Employed" && !$workplace) {
        $message = "Please enter your workplace!";
        $message_type = 'error';
    } elseif ($occupation === "Student" && (!$schoolName)) {
        $message = "Please enter all student details!";
        $message_type = 'error';
    } else {
        // Insert into database
        $stmt = $conn->prepare("
            INSERT INTO category 
            (fullName, familyName, gender, email, phone, maritalStatus, occupation, workplace, schoolName) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "sssssssss",
            $fullName,
            $familyName,
            $gender,
            $email,
            $phone,
            $maritalStatus,
            $occupation,
            $workplace,
            $schoolName,
       
        );

        if ($stmt->execute()) {
            $message = "Registration successful!";
            $message_type = 'success';

            // Redirect based on occupation
            switch ($occupation) {
                case "Employed": header("Location: employment.php"); break;
                case "Unemployed": header("Location: unemployed.php"); break;
                case "Farmer": header("Location: farmer.php"); break;
                case "Student": header("Location: student.php"); break;
            }
            exit;
        } else {
            $message = "Database error: " . $stmt->error;
            $message_type = 'error';
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Category Registration - Family Website</title>
<style>
body {font-family: Arial,sans-serif; background:#f4f7f8; padding:20px;}
form {max-width:500px; margin:auto; background:#fff; padding:30px; border-radius:10px; box-shadow:0 8px 20px rgba(0,0,0,0.1);}
label {display:block; margin:10px 0 5px;}
input, select {width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px;}
button {padding:12px 20px; background:#3498db; color:#fff; border:none; border-radius:5px; cursor:pointer;}
button:hover {background:#2980b9;}
#server-message {padding:12px; border-radius:6px; margin-bottom:15px;}
.success {background:#e6ffed; border:1px solid #12a454; color:#03592a;}
.error {background:#fff0f0; border:1px solid #d33; color:#600;}
.hidden {display:none;}
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
         <li><a href="category.php">Category</a></li>
        <li><a href="employment.php">Employed</a></li>
        <li><a href="student.php">Students</a></li>
        <li><a href="unemployed.php">Unemployed</a></li>
        <li><a href="farmer.php">Farmer</a></li>
        <li><a href="contact.html">Contact Us</a></li>
        <li><a href="login.php" class="btn">Login</a></li>
    </ul>
  </nav>
</header>

<h2 style="text-align:center;">Family Category Registration</h2>

<?php if($message): ?>
<div id="server-message" class="<?php echo $message_type; ?>">
<?php echo htmlspecialchars($message); ?>
</div>
<?php endif; ?>

<form action="" method="post" id="categoryForm">
  <label for="fullName">Full Name</label>
  <input type="text" id="fullName" name="fullName" required>

  <label for="familyName">Family Name</label>
  <input type="text" id="familyName" name="familyName" required>

  <label for="gender">Gender</label>
  <select id="gender" name="gender" required>
    <option value="">Select Gender</option>
    <option value="Male">Male</option>
    <option value="Female">Female</option>
  </select>

  <label for="email">Email (optional)</label>
  <input type="email" id="email" name="email">

  <label for="phone">Phone Number (optional)</label>
  <input type="text" id="phone" name="phone">

  <label for="maritalStatus">Marital Status</label>
  <select id="maritalStatus" name="maritalStatus" required>
    <option value="">Select Status</option>
    <option value="Single">Single</option>
    <option value="Married">Married</option>
  </select>

  <label for="occupation">Occupation Status</label>
  <select id="occupation" name="occupation" required onchange="showExtraFields()">
    <option value="">Select Occupation</option>
    <option value="Employed">Employed</option>
    <option value="Unemployed">Unemployed</option>
    <option value="Farmer">Farmer</option>
    <option value="Student">Student</option>
  </select>

  <div id="employedFields" class="hidden">
    <label for="workplace">Workplace</label>
    <input type="text" id="workplace" name="workplace">
  </div>

  <div id="studentFields" class="hidden">
    <label for="schoolName">School Name</label>
    <input type="text" id="schoolName" name="schoolName">
  </div>

  <button type="submit">Submit</button>
</form>

<script>
function showExtraFields(){
  var occ = document.getElementById('occupation').value;
  document.getElementById('employedFields').style.display = occ === 'Employed' ? 'block' : 'none';
  document.getElementById('studentFields').style.display = occ === 'Student' ? 'block' : 'none';
}
</script>

</body>
</html>
