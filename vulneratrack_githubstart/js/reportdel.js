document.addEventListener("DOMContentLoaded", () => {

    const deleteModal = document.getElementById("deleteModal");
    const cancelBtn   = document.getElementById("cancelDelete");
    const confirmBtn  = document.getElementById("confirmDelete");

    if (!deleteModal || !cancelBtn || !confirmBtn) return;

    let deleteId = null;
    let deleteRow = null;

    document.querySelectorAll(".delete-report-btn").forEach(btn => {

        btn.addEventListener("click", function (e) {

            // stop the row click
            e.preventDefault();
            e.stopPropagation();

            deleteId  = this.dataset.id;
            deleteRow = this.closest("tr");

            // disable the inline onclick on that row
            if (deleteRow) deleteRow.onclick = null;

            deleteModal.style.display = "flex";

        }, true);

    });

    cancelBtn.addEventListener("click", () => {

        deleteModal.style.display = "none";
        deleteId = null;
        deleteRow = null;

    });

    confirmBtn.addEventListener("click", () => {

        if (!deleteId) return;
                        
        fetch("../processes/deletereportctrl.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "report_id=" + encodeURIComponent(deleteId)
        })
        .then(r => r.text())
        .then(t => {

            console.log("DELETE:", t);

            if (t.trim() === "success") {
                if (deleteRow) deleteRow.remove();
            } else {
                alert(t);
            }

            deleteModal.style.display = "none";
            deleteId = null;
            deleteRow = null;

        });

    });

});

//MODAL FOR PICTURE

function openImageModal(filename)
{
    const modal = document.getElementById("imageModal");
    const img   = document.getElementById("modalImage");

    img.src = "../uploads/reports/" + filename;
    modal.style.display = "flex";
}

function closeImageModal()
{
    document.getElementById("imageModal").style.display = "none";
}

