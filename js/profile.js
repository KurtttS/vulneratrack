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
        
        // Optional: Change icon from bars to an 'X'
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

//PROFILE!

document.addEventListener('DOMContentLoaded', function () {

    const saveBtn = document.getElementById('main-save-btn');
    const modal = document.getElementById('save-confirm-modal');
    const form = document.getElementById('profileForm');
    const masterBtn = document.getElementById('master-edit-btn');
    const allInputs = document.querySelectorAll(
    '.profiles-input-field, .profiles-select-field');

    // OPEN MODAL
    if (saveBtn) {
        saveBtn.addEventListener('click', function (e) {
            e.preventDefault();
            modal.style.display = 'flex';
        });
    }

    // CLOSE MODAL
    window.closeSaveModal = function () {
        modal.style.display = 'none';
    }

    // CONFIRM SAVE
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


    modal.style.display = 'none';


    setTimeout(() => {
        profmodup("Profile updated successfully.", "success");
    }, 150);

 
    setTimeout(() => {
        form.submit();
    }, 800);
}



    // EDIT TOGGLE
    window.enableGlobalEdit = function () {

        const isReadOnly = allInputs[0].hasAttribute('readonly');

        if (isReadOnly) {

            allInputs.forEach(input => {
                input.removeAttribute('readonly');
                input.removeAttribute('disabled');
                input.classList.add('editable-active');
            });

            masterBtn.innerHTML = '<i class="fa-solid fa-xmark"></i> Cancel Edit';
            masterBtn.style.backgroundColor = "#8c3535";
            saveBtn.style.display = "block";

        } else {

            allInputs.forEach(input => {
                input.setAttribute('readonly', true);
                input.setAttribute('disabled', true);
                input.classList.remove('editable-active');
            });

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
