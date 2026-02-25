document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('errorModal');
    const modalMessage = document.getElementById('modalMessage');
    const modalTitle = document.getElementById('modalTitle');
    const loginForm = document.getElementById('mainForm');

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementsByName('logemail')[0].value.trim();
            const pass = document.getElementsByName('logpass')[0].value.trim();

            if (email === "" || pass === "") {
                e.preventDefault();
                modalTitle.innerText = "Empty Fields!";
                modalMessage.innerText = "Please fill in all required textboxes before proceeding.";
                modal.style.display = 'flex';
            }
        });
    }

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('error')) {
        const errorType = urlParams.get('error');
        
        modalTitle.innerText = "Login Failed";
        if (errorType === 'wrongpassword') {
            modalMessage.innerText = "The password you entered is incorrect. Please try again.";
        } else {
            modalMessage.innerText = "The email or password you entered is incorrect. Please try again.";
        }
        
        modal.style.display = 'flex';
    }
});

function closeModal() {
    const modal = document.getElementById('errorModal');
    if (modal) {
        modal.style.display = 'none';
        window.history.replaceState({}, document.title, window.location.pathname);
    }
}



//PASSWORD
const togglePassword = document.getElementById("togglePassword");
const passwordInput = document.getElementById("logpass");

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
