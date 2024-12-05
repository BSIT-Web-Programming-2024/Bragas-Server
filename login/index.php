<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "user_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"])) {
        $username = htmlspecialchars($_POST["username"]);
        $email = htmlspecialchars($_POST["email"]);
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

        $check_sql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $result = $conn->query($check_sql);

        if ($result->num_rows > 0) {
            $message = "User is already registered.";
        } else {
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

            if ($conn->query($sql) === TRUE) {
                $message = "Registration successful!";
            } else {
                $message = "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } elseif (isset($_POST["username_or_email"]) && isset($_POST["password"])) {
        $username_or_email = htmlspecialchars($_POST["username_or_email"]);
        $password = $_POST["password"];

        $sql = "SELECT * FROM users WHERE username = '$username_or_email' OR email = '$username_or_email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row["password"])) {
                $message = "Login successful! Welcome, " . $row["username"];
            } else {
                $message = "Invalid password.";
            }
        } else {
            $message = "User not found.";
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Animated Login & Registration Form</title>
  <link rel="stylesheet" href="style.css">
  <style>
  .message {
    position: absolute;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #ff4d4d;
    color: #fff;
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    z-index: 1;
  }
  </style>
</head>
<body>
  <?php if (!empty($message)): ?>
    <div class="message"><?php echo $message; ?></div>
  <?php endif; ?>
  <div class="wrapper">
    <div class="form-wrapper sign-in">
        <form action="" method="POST">
            <h2>Sign-in</h2>
            <div class="input-group">
              <input type="text" name="username_or_email" required>
              <label for="">Email or Username</label>
            </div>
            <div class="input-group">
              <input type="password" name="password" required>
              <label for="">Password</label>
            </div>
            <div class="remember">
              <label><input type="checkbox"> Remember me</label>
            </div>
            <button type="submit">Login</button>
            <div class="signUp-link">
              <p>Don't have an account? <a href="#" class="signUpBtn-link">Sign Up</a></p>
            </div>
          </form>            
    </div>
    <div class="form-wrapper sign-up">
        <form action="" method="POST">
            <h2>Sign Up</h2>
            <div class="input-group">
              <input type="text" name="username" required>
              <label for="">Username</label>
            </div>
            <div class="input-group">
              <input type="email" name="email" required>
              <label for="">Email</label>
            </div>
            <div class="input-group">
              <input type="password" name="password" required>
              <label for="">Password</label>
            </div>
            <button type="submit">Sign Up</button>
            <div class="signUp-link">
              <p>Already have an account? <a href="#" class="signInBtn-link">Sign In</a></p>
            </div>
          </form>          
    </div>
  </div>
  
  <script>
    const signInBtnLink = document.querySelector('.signInBtn-link');
    const signUpBtnLink = document.querySelector('.signUpBtn-link');
    const wrapper = document.querySelector('.wrapper');
    signUpBtnLink.addEventListener('click', () => {
        wrapper.classList.toggle('active');
    });
    signInBtnLink.addEventListener('click', () => {
        wrapper.classList.toggle('active');
    });
    window.addEventListener('load', () => {
        const message = document.querySelector('.message');
        if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 3000); 
        }
    });

    
  </script>

</body>
</html>