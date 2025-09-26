<?php
session_start();

// Database connection
$host = "localhost";
$dbUser = "root";
$dbPass = "1234"; // your MySQL password
$dbName = "familysite";

$conn = new mysqli($host, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $newPassword = $_POST['newPassword'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    if (!$email || !$newPassword || !$confirmPassword) {
        $message = "Please fill in all fields!";
        $message_type = 'error';
    } elseif ($newPassword !== $confirmPassword) {
        $message = "Passwords do not match!";
        $message_type = 'error';
    } else {
        // Check if user exists
        $stmt = $conn->prepare("SELECT id FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            // Update password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmtUpdate = $conn->prepare("UPDATE user SET password=? WHERE email=?");
            $stmtUpdate->bind_param("ss", $hashedPassword, $email);
            if ($stmtUpdate->execute()) {
                $message = "Password reset successful! You can now login.";
                $message_type = 'success';
            } else {
                $message = "Error updating password!";
                $message_type = 'error';
            }
            $stmtUpdate->close();
        } else {
            $message = "Email not found!";
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
<title>Forgot Password - Family Website</title>
<style>
body {font-family: Arial,sans-serif; background:#f4f7f8; padding:20px;}
.auth-container {max-width:400px; margin:50px auto; background:#fff; padding:30px; border-radius:10px; box-shadow:0 8px 20px rgba(0,0,0,0.1); text-align:center;}
.auth-container h2 {margin-bottom:20px;}
input {width:100%; padding:12px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px;}
button {width:100%; padding:12px; background:#3498db; color:#fff; border:none; border-radius:5px; cursor:pointer;}
button:hover {background:#2980b9;}
#server-message {padding:12px; border-radius:6px; margin-bottom:15px;}
.success {background:#e6ffed; border:1px solid #12a454; color:#03592a;}
.error {background:#fff0f0; border:1px solid #d33; color:#600;}
a {color:#3498db; text-decoration:none;}
</style>
</head>
<body>

<div class="auth-container">
<h2>Forgot Password</h2>

<?php if($message): ?>
<div id="server-message" class="<?php echo $message_type; ?>"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<form action="forgot_password.php" method="post">
<label for="email">Email</label>
<input type="email" id="email" name="email" placeholder="Enter your registered email" required>

<label for="newPassword">New Password</label>
<input type="password" id="newPassword" name="newPassword" placeholder="Enter new password" required>

<label for="confirmPassword">Confirm Password</label>
<input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm new password" required>

<button type="submit">Reset Password</button>

<p>Back to <a href="login.php">Login</a></p>
</form>
</div>

</body>
</html>
