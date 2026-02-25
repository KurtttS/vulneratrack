document.addEventListener("DOMContentLoaded", function() {
    // --- MAP INITIALIZATION ---
    const latInput = document.getElementById("latInput");
    const lngInput = document.getElementById("lngInput");
    const mapDiv = document.getElementById('addressmap');

    if (mapDiv) {
        const defaultCoords = [10.6727, 122.9875];
        const addressmap = L.map('addressmap', { scrollWheelZoom: false }).setView(defaultCoords, 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(addressmap);

        const marker = L.marker(defaultCoords, { draggable: true }).addTo(addressmap);

        function updateCoords(lat, lng) {
            if (latInput && lngInput) {
                latInput.value = lat.toFixed(6);
                lngInput.value = lng.toFixed(6);
            }
        }

        updateCoords(defaultCoords[0], defaultCoords[1]);

        marker.on('dragend', function(e) {
            updateCoords(marker.getLatLng().lat, marker.getLatLng().lng);
        });

        addressmap.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateCoords(e.latlng.lat, e.latlng.lng);
        });

        setTimeout(() => { addressmap.invalidateSize(); }, 100);
    }

    // --- FORM VALIDATION & MODAL ---
    const forms = document.querySelectorAll('.signupstart-form, .signup-form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isEmpty = false;
            // Select all required fields
            const requiredFields = form.querySelectorAll('[required]');

            requiredFields.forEach(field => {
                if (field.value.trim() === "") {
                    isEmpty = true;
                    field.style.border = "2px solid #ff4d4d";
                } else {
                    field.style.border = "";
                }
            });

            if (isEmpty) {
                e.preventDefault();
                showModal("Empty Fields!", "Please fill in all required textboxes before proceeding.");
            }
        });
    });

    
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('error')) {
        const errorType = urlParams.get('error');
        if (errorType === 'emailtaken') {
            showModal("Registration Error", "This email is already registered. Please use a different one.");
        }
        if (errorType === 'passwordmismatch') {
            showModal("Registration Error", "Passwords do not match. Please use a different one.");
        }
        if (errorType === 'emptyinput') {
            showModal("Registration Error", "Please fill up all fields.");
        }
        if (errorType === 'incorrectusername') {
            showModal("Registration Error", "Please use only letters for your name (2-50 characters).");
        }
    }
});

// Household Toggle Logic
const joinRadio = document.getElementById("joinRadio");
const createRadio = document.getElementById("createRadio");
const joinDropdown = document.getElementById("joinDropdown");
const joinCodeInput = document.getElementById('joinCodeInput');

if (joinRadio && createRadio && joinDropdown) {
    function updateHouseholdUI() {
        if (joinRadio.checked) {
            joinDropdown.classList.add("show");
            joinCodeInput.setAttribute('required', 'true');
        } else {
            joinDropdown.classList.remove("show");
            joinCodeInput.removeAttribute('required', 'true');
            joinCodeInput.value = ''; 
        }
    }
    joinRadio.addEventListener("change", updateHouseholdUI);
    createRadio.addEventListener("change", updateHouseholdUI);
    updateHouseholdUI();
}

// Global Modal Functions
function showModal(title, message) {
    document.getElementById('modalTitle').innerText = title;
    document.getElementById('modalMessage').innerText = message;
    document.getElementById('errorModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('errorModal').style.display = 'none';
    
    window.history.replaceState({}, document.title, window.location.pathname);
}


//PASSWORD
const togglePassword = document.getElementById("togglePassword");
const passwordInput = document.getElementById("signupPass");

if (togglePassword && passwordInput) {
    togglePassword.addEventListener("click", function () {

        const type = passwordInput.getAttribute("type") === "password"
            ? "text"
            : "password";

        passwordInput.setAttribute("type", type);

        this.classList.toggle("fa-eye");
        this.classList.toggle("fa-eye-slash");
    });
}

// UPLOAD BIRTH CERTIFICATE
document.addEventListener("DOMContentLoaded", function () {

    const birthInput = document.getElementById("birth-cert");
    const birthLabel = document.getElementById("birthCertLabel");

    if (!birthInput || !birthLabel) return;

    birthInput.addEventListener("change", function () {

        if (!this.files || !this.files[0]) return;

        const file = this.files[0];

        birthLabel.innerHTML = file.name;

    });

});