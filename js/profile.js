document.addEventListener('DOMContentLoaded', () => {
    const profileIcon = document.getElementById('profileIcon');
    const profileDropdown = document.getElementById('profileDropdown');

    profileIcon.addEventListener('click', (e) => {
        e.stopPropagation();
        
        const isShowing = profileDropdown.classList.contains('show');
        
        if (isShowing) {
            profileDropdown.classList.remove('show');
            profileIcon.classList.remove('active');
        } else {
            profileDropdown.classList.add('show');
            profileIcon.classList.add('active');
        }
    });

    document.addEventListener('click', (e) => {
        if (!profileDropdown.contains(e.target)) {
            profileDropdown.classList.remove('show');
            profileIcon.classList.remove('active');
        }
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.getElementById('hamburger');
    const navLinksContainer = document.getElementById('navLinks');

    // Toggle Mobile Menu
    hamburger.addEventListener('click', () => {
        navLinksContainer.classList.toggle('mobile-active');
        
        // change icon from bars to an 'X'
        const icon = hamburger.querySelector('i');
        icon.classList.toggle('fa-bars');
        icon.classList.toggle('fa-xmark');
    });

    // Keep your existing active link logic...
    const currentUrl = window.location.href;
    const navLinks = document.querySelectorAll('.homeheader-links a');
    navLinks.forEach(link => {
        if (currentUrl.includes(link.getAttribute('href'))) {
            link.classList.add('active');
        }
    });
});

//report
document.addEventListener("DOMContentLoaded", function () {

    const input = document.getElementById("file-input");
    const label = document.getElementById("reportFilesLabel");

    if (!input || !label) return;

    input.addEventListener("change", function () {

        if (!this.files || this.files.length === 0) return;

        if (this.files.length === 1) {
            label.textContent = this.files[0].name;
        } else {
            label.textContent = this.files.length + " files selected";
        }

    });

});

//profile

document.addEventListener('DOMContentLoaded', function () {

    const saveBtn = document.getElementById('main-save-btn');
    const modal = document.getElementById('save-confirm-modal');
    const form = document.getElementById('profileForm');
    const masterBtn = document.getElementById('master-edit-btn');
    const allInputs = document.querySelectorAll(
    '.profiles-input-field, .profiles-select-field');

    // open modal
    if (saveBtn) {
        saveBtn.addEventListener('click', function (e) {
            e.preventDefault();
            modal.style.display = 'flex';
        });
    }

    // close modal
    window.closeSaveModal = function () {
        modal.style.display = 'none';
    }

    // confirm save
window.submitProfileForm = function () {

    const password = document.querySelector('input[name="password"]');
    const confirmPassword = document.querySelector('input[name="confirmpass"]');

    // validate first
    if (password && confirmPassword) {

        if (password.value !== confirmPassword.value) {
            modal.style.display = 'none'; 
            profmodup("Password and confirm password do not match.", "error");
            return;
        }
    }

    saveBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

    const mapEdit = document.getElementById('locEdit');
    if (locEdit) locEdit.style.display = 'none'; 
    modal.style.display = 'none';


    setTimeout(() => {
        profmodup("Profile updated successfully.", "success");
    }, 150);

 
    setTimeout(() => {
        form.submit();
    }, 800);
}



    // edit toggle
    window.enableGlobalEdit = function () {

        const isReadOnly = allInputs[0].hasAttribute('readonly');
        const areaSelect = document.getElementById('areaEdit');
        const areaHidden = document.getElementById('areaHidden');

        if (isReadOnly) {

            allInputs.forEach(input => {
                input.removeAttribute('readonly');
                input.removeAttribute('disabled');
                input.classList.add('editable-active');
            });
            if (areaSelect && areaHidden) {
                areaSelect.addEventListener('change', function() {
                    areaHidden.value = this.value;
                });
            }
            masterBtn.innerHTML = '<i class="fa-solid fa-xmark"></i> Cancel Edit';
            masterBtn.style.backgroundColor = "#8c3535";
            saveBtn.style.display = "block";
            
        } else {

            allInputs.forEach(input => {
                input.setAttribute('readonly', true);
                input.setAttribute('disabled', true);
                input.classList.remove('editable-active');
            });
            if (areaSelect && areaHidden) {
                areaSelect.addEventListener('change', function() {
                    areaHidden.value = this.value;
                });
            }
            if (locEdit) {
            locEdit.style.display = "none";
            }

            masterBtn.innerHTML = '<i class="fa-solid fa-pencil"></i> Edit Profile';
            masterBtn.style.backgroundColor = "#6a79d3";
            saveBtn.style.display = "none";
        }
    }

});


/*notification*/
const bellIcon = document.getElementById('bellIcon');
const notificationDropdown = document.getElementById('notificationDropdown');

bellIcon.addEventListener('click', function(e) {
    e.stopPropagation();
    notificationDropdown.classList.toggle('show');
    bellIcon.classList.toggle('active');
    
    // Close profile dropdown if it's open
    document.getElementById('profileDropdown').classList.remove('show');
    document.getElementById('profileIcon').classList.remove('active');
});

// Close when clicking outside
window.addEventListener('click', function() {
    notificationDropdown.classList.remove('show');
    bellIcon.classList.remove('active');
});

//history profile

document.addEventListener("DOMContentLoaded", function () {

    const headers = document.querySelectorAll(".report-header");

    headers.forEach(header => {
        header.addEventListener("click", function () {

            const card = this.closest(".report-card");

            card.classList.toggle("active");

        });
    });

});

function profmodup(message, type = "success") {

    const box  = document.getElementById("customAlert");
    const text = document.getElementById("customAlertText");

    if (!box || !text) return;

    text.textContent = message;

    box.classList.remove("success", "error");
    box.classList.add(type, "show");

    setTimeout(() => {
        box.classList.remove("show");
    }, 2200);
}


//risk color change
document.addEventListener("DOMContentLoaded", function () {

    const riskText = document.getElementById("riskText");
    if (!riskText) return;

    const risk = riskText.textContent.trim().toLowerCase();

    if (risk.includes("low")) {
        riskText.className = "risk-low";
    }
    else if (risk.includes("medium")) {
        riskText.className = "risk-medium";
    }
    else if (risk.includes("high")) {
        riskText.className = "risk-high";
    }

});

//profile map
document.addEventListener("DOMContentLoaded", function () {
const areaSelect = document.getElementById('areaEdit');
const mapEdit = document.getElementById('locEdit');
const profmapElement = document.getElementById('profile-map');
let profmap = null;
let marker = null;

function initMap() {
        if (profmap) return;
        const profstoredLat = profmapElement.getAttribute('data-lat');
        const profstoredLng = profmapElement.getAttribute('data-lng');
        const proflatNum = parseFloat(profstoredLat);
        const proflngNum = parseFloat(profstoredLng);
        const latInput = document.getElementById("latInput");
        const lngInput = document.getElementById("lngInput");

        //Map
        profmap = L.map('profile-map', {
            zoomControl: false,
            scrollWheelZoom: false
        }).setView([proflngNum, proflatNum], 18);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(profmap);

        L.control.zoom({
            position: 'bottomright' 
        }).addTo(profmap);

        marker = L.marker([proflngNum, proflatNum], { draggable: true }).addTo(profmap)
            .bindPopup("Please pin your new location!").openPopup();
        
        //Get longlat
        function updateCoords(lng, lat) {
            if (latInput && lngInput) {
                lngInput.value = lng.toFixed(6);
                latInput.value = lat.toFixed(6);
            }
        }
        updateCoords(proflatNum, proflngNum);

        marker.on('dragend', function(e) {
            const pos = marker.getLnglat();
            updateCoords(pos.lng, pos.lat);
            console.log("Inputs Updated -> Lat: " + latInput.value + " | Lng: " + lngInput.value);
        });

        profmap.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateCoords(e.latlng.lat, e.latlng.lng);
            console.log("Inputs Updated -> Lat: " + latInput.value + " | Lng: " + lngInput.value);
        });

    }

    areaSelect.addEventListener('change', function() {
        mapEdit.style.display = 'block';
        if (!profmap) {
        initMap();
        }
        setTimeout(() => {
            if (profmap) {
            profmap.invalidateSize();
        }
        }, 100);

    const selectedArea = this.value;
    let newCoords;

    // Set the coordinates based on selection
    if (selectedArea === "Celine Homes") {
        newCoords = [10.672768169260864, 122.98753057212929];
    } else if (selectedArea === "East Homes") {
        newCoords = [10.675115474735781, 122.98933560563219];
    } else if (selectedArea === "Purok Carvic") {
        newCoords = [10.690732242556788, 122.9602300962636];
    }

    // 3. Use the unified profmap and marker variables to update the view
    if (newCoords && profmap && marker) {
        profmap.setView(newCoords, 16);
        marker.setLatLng(newCoords);
        
        // Also update your hidden inputs immediately
        document.getElementById("latInput").value = newCoords[0].toFixed(6);
        document.getElementById("lngInput").value = newCoords[1].toFixed(6);
    }
    });

});