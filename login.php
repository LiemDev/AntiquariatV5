<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/styles.css?v=2">
  <title>Login</title>
</head>
<body>
<nav>
  <ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="booklist.php">Booklist</a></li>
    <li><a href="customers.php">Customers</a></li>
    <li><a href="contact.php">Contact</a></li>
    <div class="auth-links">
      <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
        <li><?php echo htmlspecialchars($_SESSION['benutzername']); ?></li>
        <li><a href="logout.php">Logout</a></li>
      <?php else: ?>
        <li><a href="signup.php">Sign Up</a></li>
        <li><a href="login.php">Login</a></li>
      <?php endif; ?>
    </div>
  </ul>
</nav>
  <div class="login-form">
    <h1>Login</h1>
    <form action="authenticate.php" method="POST">
      <label for="benutzername">Username</label>
      <input type="text" id="benutzername" name="benutzername" required>
      
      <label for="passwort">Password</label>
      <input type="password" id="passwort" name="passwort" required>
      
      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
