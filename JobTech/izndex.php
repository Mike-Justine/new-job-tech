<?php
session_start();

$errors = [
    'login' => $_SESSION['login_error'] ??'',
    'register' => $_SESSION['register_error'] ??''
];
$activeform = $_SESSION['active_form'] ?? 'login';

session_unset();

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}
function isActiveform($formName,$activeform)  {
    return $formName ===$activeform? 'active' : '';
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
    <title>jobtech login & registration form with user and admin page | mike</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container">
        <div class="form-box <?= isActiveform('login', $activeform); ?> " id="login-form">
            <form action="login_register.php" method="post">
                <h2>Login</h2>
                <?= showError($errors['login']); ?>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">login</button>
                <p>Don't have an account? <a href="#" onclick="showform('register-form')">Register</a></p>
            </form>
        </div>

 <div class="form-box <?= isActiveform('register', $activeform); ?>" id="register-form">
  <form action="login_register.php" method="post">
    <h2>Register</h2>
    <?= showError($errors['register']); ?>

    <input type="text" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="phone_number" placeholder="Phone_Number" required>
    <input type="password" name="password" placeholder="Password" required>

    <select name="role" id="role" onchange="toggleSecretCode()" required>
      <option value="">--select role--</option>
      <option value="user">User</option>
      <option value="admin">Admin</option>
    </select>

    
    <div id="secret-code-field" style="display: none; margin-top: 10px;">
      <input type="text" name="secret_code" placeholder="Admin Secret Code">
    </div>

    <button type="submit" name="register">Register</button>
    <p>Already have an account? <a href="#" onclick="showform('login-form')">Login</a></p>
  </form>
</div>

    </div>
</div>
    <script src="script.js"></script>
     <div style="text-align: right; margin: 10px;">
    <a href="logout.php" style="
        background-color:rgb(5, 148, 17);
        color: white;
        padding: 6px 12px;
        text-decoration: none;
        border-radius: 4px;
        font-family: sans-serif;
        font-weight: bold;
    ">HOME? CLICK HERE!</a>
    <script>
function toggleSecretCode() {
  var role = document.getElementById('role').value;
  var secretField = document.getElementById('secret-code-field');
  secretField.style.display = (role === 'admin') ? 'block' : 'none';
}
</script>

</body>
</html>