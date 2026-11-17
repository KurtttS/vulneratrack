document.addEventListener("DOMContentLoaded", function() {
    const latInput = document.getElementById("latInput");
    const lngInput = document.getElementById("lngInput");
    const mapDiv = document.getElementById('addressmap');

    if (mapDiv) {
        const defaultCoords = [10.684118576293782, 122.9767912810966];
        const addressmap = L.map('addressmap', { scrollWheelZoom: false }).setView(defaultCoords, 12);

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
        var purokcarvic = {
            "type": "FeatureCollection",
            "features": [
                {
                "type": "Feature",
                "properties": {},
                "geometry": {
                    "coordinates": [
                    [
                        [
                        122.961271703607,10.690550652289303
                        ],
                        [
                        122.96173375332958,10.691743998621845
                        ],
                        [
                        122.96048418553572,10.692099604574281
                        ],
                        [
                        122.96009707638427,10.6916892486108
                        ],
                        [
                        122.95998710013262,10.690947835109384
                        ],
                        [
                        122.95897378677768,10.69056497620359
                        ],
                        [
                        122.95868640968718,10.689889041574105
                        ],
                        [
                        122.95851018788741,10.689369918283525
                        ],
                        [
                        122.95872436515168,10.689170482065052
                        ],
                        [
                        122.95978022801003,10.688875659136855
                        ],
                        [
                        122.96029358748734,10.68923455830948
                        ],
                        [
                        122.961271703607,10.690550652289303
                        ]
                    ]
                    ],
                    "type": "Polygon"
                }
                }
            ]
            };L.geoJSON(purokcarvic).addTo(addressmap);

        var easthomes3 = {
            "type": "FeatureCollection",
            "features": [
                {
                "type": "Feature",
                "properties": {},
                "geometry": {
                    "coordinates": [
                    [
                        [
                        122.98431417418584,10.680103713635134
                        ],
                        [
                        122.98423614067383,10.679973084853511
                        ],
                        [
                        122.98412362093796,10.6798594004947
                        ],
                        [
                        122.98434391012503,10.679442615828425
                        ],
                        [
                        122.98435728196637,10.679051247836227
                        ],
                        [
                        122.98504177128706,10.678244925334878
                        ],
                        [
                        122.98539864147436,10.677946938134522
                        ],
                        [
                        122.98611502902759,10.677448751883803
                        ],
                        [
                        122.98691210105726,10.676566761836764
                        ],
                        [
                        122.98758536470822,10.67555267801064
                        ],
                        [
                        122.9888572213526,10.673102515960043
                        ],
                        [
                        122.9891187353636,10.672910796905269
                        ],
                        [
                        122.98947011900057,10.672860381740954
                        ],
                        [
                        122.989807230633,10.672452421447412
                        ],
                        [
                        122.9902704998166,10.672657812013195
                        ],
                        [
                        122.99065409054634,10.672732702365067
                        ],
                        [
                        122.99083542437482,10.673785923286616
                        ],
                        [
                        122.99081756207298,10.674917444028068
                        ],
                        [
                        122.99062709494238,10.675372896935585
                        ],
                        [
                        122.9906780313841,10.675692792395694
                        ],
                        [
                        122.9898630100054,10.67606685839037
                        ],
                        [
                        122.98923633687326,10.676982494127486
                        ],
                        [
                        122.98817411941528,10.677766873758891
                        ],
                        [
                        122.98747138046167,10.678657229594094
                        ],
                        [
                        122.98688097853997,10.679300308359927
                        ],
                        [
                        122.98609222544722,10.679514867707091
                        ],
                        [
                        122.98523881068581,10.679784496597136
                        ],
                        [
                        122.98451060917503,10.680181831716553
                        ],
                        [
                        122.98431417418584,10.680103713635134
                        ]
                    ]
                    ],
                    "type": "Polygon"
                }
                }
            ]
            };
            L.geoJSON(easthomes3).addTo(addressmap);

        var celinehomes = {
            "type": "FeatureCollection",
            "features": [
                {
                "type": "Feature",
                "properties": {},
                "geometry": {
                    "coordinates": [
                    [
                        [
                        122.97965325453322,10.673535225684105
                        ],
                        [
                        122.98121952728673,10.670700579632936
                        ],
                        [
                        122.98285835892347,10.670294299926695
                        ],
                        [
                        122.98415306420193,10.669816412095749
                        ],
                        [
                        122.98487462630646,10.669585797594792
                        ],
                        [
                        122.98609954509529,10.6698078425263
                        ],
                        [
                        122.986006059853,10.670688158958539
                        ],
                        [
                        122.98582870000175,10.671444839371254
                        ],
                        [
                        122.9875988037581,10.671175774740956
                        ],
                        [
                        122.98778926901662,10.671387517190198
                        ],
                        [
                        122.98910028128046,10.672284615400542
                        ],
                        [
                        122.98859352686688,10.673332228284316
                        ],
                        [
                        122.98821492760679,10.674179821894866
                        ],
                        [
                        122.98681564109202,10.676455344113517
                        ],
                        [
                        122.98638625519007,10.676493871239895
                        ],
                        [
                        122.98357390391814,10.678569664111508
                        ],
                        [
                        122.98296326116521,10.676771606689321
                        ],
                        [
                        122.98205839134809,10.675101652623951
                        ],
                        [
                        122.98093969755193,10.674078037953599
                        ],
                        [
                        122.97965325453322,10.673535225684105
                        ]
                    ]
                    ],
                    "type": "Polygon"
                }
                }
            ]
            };
            L.geoJSON(celinehomes).addTo(addressmap);

            //Area border styles
            var residentialAreas = L.featureGroup().addTo(addressmap);
            L.geoJSON(purokcarvic, {
                style: { fillColor: "#9870FC", 
                color: "#9870FC",
                fillOpacity: 0.5,
                weight: 2 }
            }).addTo(residentialAreas);

            L.geoJSON(celinehomes, {
                style: { fillColor: "#0C9CEE",
                color: "#0C9CEE",
                fillOpacity: 0.5,
                weight: 2 }
            }).addTo(residentialAreas);

            L.geoJSON(easthomes3, {
                style: { fillColor: "#00CF95",
                color: "#00CF95",
                fillOpacity: 0.5,
                weight: 2 }
            }).addTo(residentialAreas);
            const dropdown = document.getElementById('areaSelect');
            dropdown.addEventListener('change', function() {
            const selectedArea = this.value;
                if (selectedArea === "Celine Homes") {
                    addressmap.setView([10.672768169260864, 122.98753057212929], 14);
                    marker.setLatLng([10.672768169260864, 122.98753057212929]);
                }
                else if (selectedArea === "East Homes") {
                    addressmap.setView([10.675115474735781, 122.98933560563219], 14);
                    marker.setLatLng([10.675115474735781, 122.98933560563219]);
                }
                else if (selectedArea === "Purok Carvic") {
                    addressmap.setView([10.690732242556788, 122.9602300962636], 16);
                    marker.setLatLng([10.690732242556788, 122.9602300962636]);
                }
            });
    }
    
    //form val
    const forms = document.querySelectorAll('.signupstart-form, .signup-form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isEmpty = false;
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
        if (errorType === 'invalidphone') {
            showModal("Registration Error", "Please enter a valid PH mobile number (e.g. 09171234567).");
        }
        if (errorType === 'otprequired') {
            showModal("Verification Required", "Please tap Send OTP and verify the mobile number you entered.");
        }
        if (errorType === 'otpexpired') {
            showModal("OTP Expired", "Your OTP has expired. Please request a new one.");
        }
        if (errorType === 'otpwrong') {
            showModal("Incorrect OTP", "The code you entered is incorrect. Please try again.");
        }
        if (errorType === 'otptoomany') {
            showModal("Too Many Attempts", "Too many incorrect attempts. Please request a new OTP.");
        }
    }
});

// Mobile number + OTP (PhilSMS) + Continue button state on the first signup step
const signupStartForm = document.querySelector(".signupstart-form");
const signupPhone = document.getElementById("signupPhone");
const contactnumFull = document.getElementById("contactnumFull");
const sendOtpBtn = document.getElementById("sendOtpBtn");
const otpInput = document.getElementById("otpInput");
const otpStatus = document.getElementById("otpStatus");
const continueBtn = document.getElementById("continueBtn");

if (signupStartForm && signupPhone && contactnumFull && sendOtpBtn && otpInput && otpStatus && continueBtn) {
    const ROUTER_URL = "../router/loginregrouter.php";
    let otpTimer = null;
    let cooldownActive = false;
    let sending = false;
    let verifying = false;
    let sentPhone = null;      // number an OTP was last sent to
    let verifiedPhone = null;  // number whose OTP was confirmed by the server

    const isPhoneValid = () => /^9\d{9}$/.test(signupPhone.value);
    const isSent = () => sentPhone !== null && sentPhone === signupPhone.value;
    const isVerified = () => verifiedPhone !== null && verifiedPhone === signupPhone.value;

    function setOtpStatus(message, isError) {
        otpStatus.textContent = message;
        otpStatus.classList.toggle("error", !!isError);
    }

    // What is still needed before Continue can be used
    function getMissing() {
        const missing = [];
        const email = signupStartForm.querySelector('[name="email"]');
        const pass = signupStartForm.querySelector('[name="password"]');
        const confirm = signupStartForm.querySelector('[name="confirmpass"]');

        if (!email.value.trim() || !email.checkValidity()) missing.push("a valid email address");
        if (!pass.value) missing.push("a password");
        if (!confirm.value) missing.push("password confirmation");
        else if (pass.value !== confirm.value) missing.push("matching passwords");

        if (!isPhoneValid()) {
            missing.push("a valid 10-digit mobile number (e.g. 9171234567)");
        } else if (!isVerified()) {
            if (!isSent()) missing.push("tap Send OTP to receive your code");
            else if (otpInput.value.length < 6) missing.push("the 6-digit OTP code");
            else missing.push("a correct OTP code");
        }

        const joinRadioEl = document.getElementById("joinRadio");
        const createRadioEl = document.getElementById("createRadio");
        const joinCodeEl = document.getElementById("joinCodeInput");
        if (!joinRadioEl.checked && !createRadioEl.checked) missing.push("choose Join or Create household");
        else if (joinRadioEl.checked && !joinCodeEl.value.trim()) missing.push("a household join code");

        return missing;
    }

    function updateContinue() {
        const missing = getMissing();
        continueBtn.classList.toggle("is-disabled", missing.length > 0);
        continueBtn.setAttribute("aria-disabled", missing.length > 0 ? "true" : "false");
        continueBtn.title = missing.length > 0 ? "Still needed:\n• " + missing.join("\n• ") : "";
    }

    function updateSendBtn() {
        const valid = isPhoneValid();
        sendOtpBtn.disabled = !valid || cooldownActive || sending || verifying || isVerified();
        sendOtpBtn.title = !valid ? "Enter your 10-digit mobile number first (e.g. 9171234567)" : "";
    }

    function refreshOtpUI() {
        otpInput.readOnly = isVerified();
        if (isVerified()) setOtpStatus("✓ Mobile number verified.", false);
        updateSendBtn();
        updateContinue();
    }

    function startOtpCooldown(seconds) {
        clearInterval(otpTimer);
        cooldownActive = true;
        const tick = () => {
            if (seconds <= 0) {
                clearInterval(otpTimer);
                cooldownActive = false;
                sendOtpBtn.textContent = isSent() ? "Resend OTP" : "Send OTP";
            } else {
                sendOtpBtn.textContent = "Resend in " + seconds + "s";
                seconds--;
            }
            updateSendBtn();
        };
        tick();
        otpTimer = setInterval(tick, 1000);
    }

    // Phone: digits only, +63 is shown as a fixed prefix
    signupPhone.addEventListener("input", function () {
        let digits = this.value.replace(/\D/g, "");
        if (digits.startsWith("63") && digits.length > 10) digits = digits.slice(2); // pasted 63917...
        if (digits.startsWith("0")) digits = digits.slice(1);                        // pasted 0917...
        digits = digits.slice(0, 10);
        this.value = digits;
        this.style.border = "";
        contactnumFull.value = digits ? "+63" + digits : "";
        setOtpStatus("", false);
        refreshOtpUI();
    });

    // OTP code: digits only, checked against the server as soon as 6 digits are entered
    otpInput.addEventListener("input", async function () {
        this.value = this.value.replace(/\D/g, "").slice(0, 6);
        this.style.border = "";
        updateContinue();

        if (this.value.length < 6) return;
        if (!isSent()) {
            setOtpStatus("Tap Send OTP to receive a code first.", true);
            return;
        }
        if (isVerified() || verifying) return;

        verifying = true;
        updateSendBtn();
        setOtpStatus("Checking code...", false);
        try {
            const response = await fetch(ROUTER_URL, {
                method: "POST",
                body: new URLSearchParams({ verify_otp: "1", contactnum: contactnumFull.value, otp: this.value })
            });
            const result = await response.json();
            if (result.status === "success") {
                verifiedPhone = signupPhone.value;
            } else {
                setOtpStatus(result.message || "Incorrect code.", true);
                this.style.border = "2px solid #ff4d4d";
                if (result.reset) {          // expired / too many tries: a new code is needed
                    sentPhone = null;
                    this.value = "";
                    if (!cooldownActive) sendOtpBtn.textContent = "Send OTP";
                }
            }
        } catch (err) {
            console.error("OTP Verify Error:", err);
            setOtpStatus("Network error. Please try again.", true);
        }
        verifying = false;
        refreshOtpUI();
    });

    sendOtpBtn.addEventListener("click", async function () {
        if (!isPhoneValid() || sendOtpBtn.disabled) return;

        sending = true;
        updateSendBtn();
        sendOtpBtn.textContent = "Sending...";
        setOtpStatus("", false);
        const phone = signupPhone.value;

        try {
            const response = await fetch(ROUTER_URL, {
                method: "POST",
                body: new URLSearchParams({ send_otp: "1", contactnum: "+63" + phone })
            });
            const result = await response.json();
            sending = false;

            if (result.status === "success") {
                sentPhone = phone;
                verifiedPhone = null;
                otpInput.value = "";
                otpInput.style.border = "";
                setOtpStatus(result.message, false);
                startOtpCooldown(result.retry_after || 60);
                otpInput.focus();
            } else {
                setOtpStatus(result.message || "Could not send the OTP.", true);
                if (result.retry_after) {
                    startOtpCooldown(result.retry_after);
                } else {
                    sendOtpBtn.textContent = isSent() ? "Resend OTP" : "Send OTP";
                }
            }
        } catch (err) {
            console.error("OTP Error:", err);
            sending = false;
            setOtpStatus("Network error. Please try again.", true);
            sendOtpBtn.textContent = isSent() ? "Resend OTP" : "Send OTP";
        }
        refreshOtpUI();
    });

    // Continue is greyed out until everything is valid; clicking it early says what is missing
    continueBtn.addEventListener("click", function (e) {
        const missing = getMissing();
        if (missing.length > 0) {
            e.preventDefault();
            showModal("Almost there", "Still needed: " + missing.join("; ") + ".");
        }
    });

    signupStartForm.addEventListener("input", updateContinue);
    signupStartForm.addEventListener("change", updateContinue);
    refreshOtpUI();
    setTimeout(updateContinue, 300); // pick up browser autofill
}

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


//password
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