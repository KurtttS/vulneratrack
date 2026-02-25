<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulneratrack</title>
    <link rel="icon" href="../style/imgs/VULNETITLE.png" type="image/png">
    <link rel="stylesheet" href="../style/body.css">
    <link rel="stylesheet" href="../style/scrollbar.css"> 
    <link rel="stylesheet" href="../style/userstyle/index.css"> 
    <link rel="stylesheet" href="../style/nav.css">
    <link rel="stylesheet" href="../style/userstyle/mobile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
   
    <div class="container">
        <div class="left">
            <div class="overlay">
                <div class="content">
                    <div class="logo-section">
                        <img class="main-logo" src="../style/imgs/VULNERATRACK.png" alt="VULNERATRACK Logo">
                    </div>
                </div>
            </div>
        </div>

         <div class="right">
           <a href="../index.php" class="back-btn" aria-label="Go back">
    <i class="fa-solid fa-arrow-left"></i>
</a>

            <h2>Create an account</h2>  
            <p>Fill up the fields.</p>
            <form class="signupstart-form" action="../router/loginregrouter.php" method="POST">
                <input type="Email" name="email" placeholder="Email" required>
       <div class="password-wrapper">
    <input type="password" id="signupPass" name="password" placeholder="Password" required>
    <i class="fa-solid fa-eye" id="togglePassword"></i>
</div>
               
                <input type="password" name="confirmpass" placeholder="Confirm Password" required>
                <div class="household-choice">
                    <p class="login-text" style="margin-bottom:8px;">Join or Create Household?</p>
                    <div class="choice-row">
                        <input type="radio" name="householdmode" value="join" id="joinRadio" required>
                        <p>Join</p>
                        <input type="radio" name="householdmode" value="create" id="createRadio" required>  
                        <p>Create</p>
                    </div>
                    <div class="join-dropdown" id="joinDropdown">
                        <input type="text" id="joinCodeInput" name="joincode" placeholder="Insert code to join (ex. 2)">
                    </div>
                </div>
                <button type="submit" name="signup_step0" class="btn login">Continue</button>
            </form>
        </div>
    </div>

    <div id="errorModal" class="modal-overlay">
        <div class="modal-content">
            <i class="fas fa-exclamation-triangle"></i>
            <h3 id="modalTitle">Notice</h3>
            <p id="modalMessage"></p>
            <button type="button" onclick="closeModal()" class="btn login" style="width:100%">Understood</button>
        </div>
    </div>

    <script src="../js/signup.js"></script>

</body>
</html>