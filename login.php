<?php
session_start();

$host = "localhost";
$dbUser = "root";
$dbPass = "1234"; 
$dbName = "familysite";

$conn = new mysqli($host, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $message = "Please fill in all fields!";
        $message_type = 'error';
    } else {
        $stmt = $conn->prepare("SELECT id, fullName, email, password FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['fullName'];
                
                // Redirect to index.html
                header("Location: index.html");
                exit;
            } else {
                $message = "Incorrect password!";
                $message_type = 'error';
            }
        } else {
            $message = "User not found!";
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
<title>Login - Family Website</title>
<style>
/* Same CSS as before */
*{margin:0;padding:0;box-sizing:border-box;font-family:Arial,sans-serif;color:#333;}
body{background:#f4f7f8;min-height:100vh;}
nav{display:flex;justify-content:space-between;align-items:center;padding:15px 30px;background-color:#2c3e50;}
nav .logo{color:#ecf0f1;font-size:24px;font-weight:bold;text-transform:uppercase;letter-spacing:1px;}
.nav-links{list-style:none;display:flex;gap:15px;}
.nav-links li a{text-decoration:none;color:#ecf0f1;padding:8px 15px;border-radius:5px;transition:background-color 0.3s, transform 0.2s;}
.nav-links li a:hover{background-color:#3498db;transform:scale(1.05);}
.nav-links li a.btn{background-color:#e74c3c;color:#fff;font-weight:bold;}
.nav-links li a.btn:hover{background-color:#c0392b;}
.auth-container{max-width:400px;margin:50px auto;background-color:#fff;padding:40px 30px;border-radius:10px;box-shadow:0 8px 20px rgba(0,0,0,0.1);text-align:center;}
.auth-container h2{margin-bottom:25px;font-size:28px;color:#2c3e50;}
.auth-container label{display:block;text-align:left;margin-bottom:5px;font-weight:600;font-size:14px;}
.auth-container input{width:100%;padding:12px 15px;margin-bottom:20px;border:1px solid #ccc;border-radius:5px;font-size:14px;transition:border-color 0.3s, box-shadow 0.3s;}
.auth-container input:focus{border-color:#3498db;box-shadow:0 0 5px rgba(52,152,219,0.3);outline:none;}
.auth-container .btn{width:100%;padding:12px;background-color:#3498db;border:none;border-radius:5px;color:white;font-size:16px;font-weight:bold;cursor:pointer;transition:background-color 0.3s, transform 0.2s;}
.auth-container .btn:hover{background-color:#2980b9;transform:scale(1.03);}
.auth-container p{margin-top:15px;font-size:14px;}
.auth-container a{color:#3498db;text-decoration:none;transition:color 0.3s;}
.auth-container a:hover{color:#2980b9;}
#server-message{display:block;padding:12px;border-radius:6px;margin:12px 0;}
.success{background:#e6ffed;border:1px solid #12a454;color:#03592a;}
.error{background:#fff0f0;border:1px solid #d33;color:#600;}
@media(max-width:768px){nav{flex-direction:column;align-items:flex-start;}.nav-links{flex-direction:column;gap:10px;width:100%;}.nav-links li a{display:block;width:100%;}.auth-container{margin:30px 20px;padding:30px 20px;}}
</style>
</head>
<body>

<header>
<nav>
<h1 class="logo">Family Website</h1>
<ul class="nav-links">
<li><a href="index.html">Home</a></li>
<li><a href="login.php" class="btn">Login</a></li>
<li><a href="register.php" class="btn">Register</a></li>
</ul>
</nav>
</header>

<div class="auth-container">
<h2>Login</h2>

<?php if($message): ?>
<div id="server-message" class="<?php echo $message_type; ?>"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<form action="login.php" method="post">
  <label for="email">Email</label>
  <input type="email" id="email" name="email" placeholder="Enter your email" required>

  <label for="password">Password</label>
  <input type="password" id="password" name="password" placeholder="Enter your password" required>

  <button type="submit" class="btn">Login</button>

  <!-- Forgot password link -->
  <p><a href="forgot_password.php">Forgot Password?</a></p>

  <p>Don’t have an account? <a href="register.php">Register</a></p>
</form>

</div>

</body>
</html>
