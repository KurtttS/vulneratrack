<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulneratrack</title>
    <link rel="icon" href="../style/imgs/VULNETITLE.png" type="image/png" >
    <link rel="stylesheet" href="../style/body.css">
    <link rel="stylesheet" href="../style/scrollbar.css"> 
    <link rel="stylesheet" href="../style/userstyle/index.css">
    <link rel="stylesheet" href="../style/nav.css">
    <link rel="stylesheet" href="../style/userstyle/mobile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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
              <a href="javascript:history.back()" class="back-btn" aria-label="Go back">
    <i class="fa-solid fa-arrow-left"></i>
</a>
          <h2>Create an account</h2>
          <p>Fill up the fields.</p>
          <form class="signup-form" action="../router/loginregrouter.php" method="post">
            <input type="text" name="firstname" placeholder="First Name" required>
            <input type="text" name="lastname" placeholder="Last Name" required>
            <input type="date" name="dateofbirth" required>
            <select name="status" required>
                <option value="" disabled selected>Status</option>
                <option>Adult</option>
                <option>Child</option>
                <option>PWD</option>
                <option>Senior Citizen</option>
            </select>
            <div class="file-container area">
              <label for="birth-cert" class="file-label"  id="birthCertLabel"> Upload Birth Certificate <span>+</span></label>
              <input type="file" name="birthcert" id="birth-cert" required>
            </div>    
            <select id="areaSelect" name="areaname" class="area" required>
                <option value="" disabled selected>Which area do you belong?</option>
                <option value="Celine Homes">Celine Homes</option>
                <option value="East Homes">East Homes</option>
                <option value="Mandalagan, Bacolod City">Mandalagan</option>
            </select>
            <div class="address-wrapper area">
                <input type="text" name="address" id="addressInput" placeholder="Full Address" autocomplete="off" required>
                <input type="hidden" name="latitude" id="latInput">
                <input type="hidden" name="longitude" id="lngInput">
                <div class="map-placeholder" id="mapDropdown">
                    <div id="addressmap"></div>
                </div>
            </div>
            <button type="submit" name="signup_step1" class="btn login full">Sign up</button>
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