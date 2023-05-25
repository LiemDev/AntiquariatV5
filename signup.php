<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/styles.css?v=2">
  <title>Sign Up</title>
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
<div class="signup-form">
  <h1>Sign Up</h1>
  <form action="register.php" method="POST">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>
    
    <label for="vorname">Prename:</label>
    <input type="text" id="vorname" name="vorname" required>
    
    <label for="benutzername">Username:</label>
    <input type="text" id="benutzername" name="benutzername" required>
    
    <label for="passwort">Password:</label>
    <input type="password" id="passwort" name="passwort" required>
    
    <label for="passwort_wiederholung">Repeat Password:</label>
    <input type="password" id="passwort_wiederholung" name="passwort_wiederholung" required>
    
    <button type="submit">Sign Up</button>
  </form>
</div>

</body>
</html>
