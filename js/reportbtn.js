function showAlert(message, type = "success") {

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


document.addEventListener("DOMContentLoaded", function () {

    const stepper = document.querySelector(".status-stepper");
    if (!stepper) {
        console.log("NO STEPPER FOUND");
        return;
    }

    const reportId = stepper.dataset.id;

    document.querySelectorAll(".status-btn").forEach(btn => {

        btn.addEventListener("click", function () {

            const status = this.dataset.status;

            fetch("../processes/updatestatusctrl.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body:
                    "report_id=" + encodeURIComponent(reportId) +
                    "&status=" + encodeURIComponent(status)
            })
            .then(res => res.text())
            .then(data => {

                if (data.trim() === "success") {

                    showAlert("Status updated successfully", "success");

                    // active state
                    document.querySelectorAll(".step-number")
                        .forEach(n => n.classList.remove("active"));

                    this.querySelector(".step-number")
                        .classList.add("active");

                } else {

                    showAlert("Update failed", "error");
                    console.log(data);

                }

            })
            .catch(err => {
                console.error(err);
                showAlert("Server error", "error");
            });

        });

    });


    // highlight only (merged – no second listener anymore)
    document.querySelectorAll('.status-btn').forEach(btn => {

        btn.addEventListener('click', () => {

            document.querySelectorAll('.step-number').forEach(n => {
                n.classList.remove('selected');
            });

            btn.querySelector('.step-number').classList.add('selected');

        });

    });

});
