<!DOCTYPE html>
<?php session_start(); ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulneratrack</title>
    <link rel="icon" href="style/imgs/VULNETITLE.png" type="image/png">
    <link rel="stylesheet" href="style/body.css">
    <link rel="stylesheet" href="style/scrollbar.css">
    <link rel="stylesheet" href="style/userstyle/index.css"> 
    <link rel="stylesheet" href="style/nav.css">
    <link rel="stylesheet" href="style/userstyle/mobile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
</head>
<body>

    <div class="container">
        <div class="left">
            <div class="overlay">
                <div class="content">
                    <div class="logo-section">
                        <img class="main-logo" src="style/imgs/VULNERATRACK.png" alt="VULNERATRACK Logo">
                    </div>
                </div>
            </div>
        </div>

        <div class="right">
            <h2>Hello User!</h2>
            <p>Log in for your safety.</p>
            <form class="login-form" id="mainForm" action="router/loginregrouter.php" method="post">
                <input type="email" name="logemail" placeholder="Email" required>
                <div class="password-wrapper">
    <input type="password" name="logpass" id="logpass" placeholder="Password" required>
    <i class="fa-solid fa-eye" id="togglePassword"></i>
</div>

                <button type="submit" name="login" class="btn login">Log In</button>
            </form>
            <p class="login-text">
                New account?<a href="loginreg-module/signup.php">Sign up</a> Here.
            </p>
        </div>
    </div>

      <div id="errorModal" class="modal-overlay">
      <div class="modal-content">
          <i class="fas fa-exclamation-circle" id="modalIcon"></i>
          <h3 id="modalTitle">Notice</h3>
          <p id="modalMessage">Please fill in all required textboxes before proceeding.</p>
          <button type="button" onclick="closeModal()" class="btn login" style="width: 100%;">Understood</button>
      </div>
  </div>

  
    <script src="js/index.js"></script>
</body>
</html>