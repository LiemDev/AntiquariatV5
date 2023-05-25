<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/styles.css?v=2">
  <title>Contact</title>
</head>
<body>
<nav>
 
  <ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="booklist.php">Booklist</a></li>
    <li><a href="customers.php">Customers</a></li>
    <li><a href="contact.php">Contact</a></li>
    <div class="auth-links">
    <?php
      // Überprüfung, ob der Benutzer angemeldet ist oder nicht
      if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): 
        // Wenn der Benutzer angemeldet ist, wird sein Benutzername und eine Option zum Ausloggen angezeigt
    ?>
      <li><a href="profile.php"><?php echo htmlspecialchars($_SESSION['benutzername']); ?></a></li>
      <li><a href="logout.php">Logout</a></li>
      <li><a href="admin.php">Admin</a></li>
    <?php else: ?>
      <!-- Wenn der Benutzer nicht angemeldet ist, werden Optionen zum Anmelden oder Registrieren angezeigt -->
      <li><a href="signup.php">Sign Up</a></li>
      <li><a href="login.php">Login</a></li>
    <?php endif; ?>

    </div>
  </ul>
</nav>
  <div class="contact-form">
    <h1>Contact Us</h1>
    <form action="send_email.php" method="POST">
      <label for="name">Name</label>
      <input type="text" id="name" name="name" required>
      
      <label for="vorname">Prename</label>
      <input type="text" id="vorname" name="vorname" required>
      
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>
      
      <label for="country">Country</label>
      <select id="country" name="country">
        <option value="Afghanistan">Afghanistan</option>
        <option value="Albania">Albania</option>
        <option value="Algeria">Algeria</option>
      </select>
      
      <button type="submit">Submit</button>
    </form>
  </div>
  
</body>
</html>
