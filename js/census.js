let map;
let marker;

// --- Modal and UI Logic ---
window.toggleModal = function() {
    const modal = document.getElementById('censusModal');
    if (!modal) return;
    
    const isCurrentlyVisible = modal.style.display === 'flex';
    modal.style.display = isCurrentlyVisible ? 'none' : 'flex';
    
    if (isCurrentlyVisible) {
        const form = document.getElementById('censusForm');
        if(form) {
            form.reset();
            form.action = "census.php?action=add";
        }
        
        const editId = document.getElementById('editUserId');
        if(editId) editId.value = "";
        
        const householdId = document.getElementById('household_id');
        if(householdId) householdId.value = "";
        
        toggleHouseholdUI();
    }
};

window.toggleHouseholdUI = function() {
    const actionElement = document.getElementById('householdAction');
    const action = actionElement ? actionElement.value : 'join';
    const joinSection = document.getElementById('joinSection');
    const createSection = document.getElementById('createSection');
    const areaCreate = document.getElementById('area');
    const houseJoin = document.getElementById('household_id');

    if (action === 'create') {
        if(joinSection) joinSection.style.display = 'none';
        if(createSection) createSection.style.display = 'block';

        areaCreate.required = true;
        houseJoin.required = false;
        setTimeout(initMap, 100);
    } else {
        if(joinSection) joinSection.style.display = 'block';
        if(createSection) createSection.style.display = 'none';
        houseJoin.required = true;
        areaCreate.required = false;
    }
}

// --- Leaflet Map Logic ---
function initMap() {
    if (!map) {
        map = L.map('map').setView([10.6765, 122.9509], 15); 
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        map.on('click', function(e) {
            if (marker) map.removeLayer(marker);
            marker = L.marker(e.latlng).addTo(map);
            
            const lngInput = document.getElementById('coordX'); 
            const latInput = document.getElementById('coordY');
            
            if(latInput) latInput.value = e.latlng.lat.toFixed(8);
            if(lngInput) lngInput.value = e.latlng.lng.toFixed(8);
        });
    }
    setTimeout(() => { if(map) map.invalidateSize(); }, 200);
}

document.addEventListener('DOMContentLoaded', () => {
    const selectAll = document.getElementById('selectAll');
    const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
    const modal = document.getElementById('censusModal');

    function toggleBulkDeleteButton() {
        if(!deleteSelectedBtn) return;
        const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
        deleteSelectedBtn.style.display = checkedCount > 0 ? 'inline-block' : 'none';
    }

    // SELECT ALL 
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
            toggleBulkDeleteButton();
        });
    }

    document.addEventListener('change', (e) => {
        if (e.target.classList.contains('row-checkbox')) {
            toggleBulkDeleteButton();
        }
    });

    // BULK DELETE FIX
    if(deleteSelectedBtn) {
        deleteSelectedBtn.addEventListener('click', () => {
            const selected = document.querySelectorAll('.row-checkbox:checked');
            const ids = Array.from(selected).map(cb => cb.value);

            if (ids.length > 0 && confirm(`Are you sure you want to delete ${ids.length} records?`)) {
                const urlParams = new URLSearchParams(window.location.search);
                const page = urlParams.get('page') || 1;
                window.location.href = `census.php?action=bulk_delete&ids=${ids.join(',')}&page=${page}`;
            }
        });
    }

    // EDIT RECORD
    document.addEventListener('click', (e) => {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            const row = editBtn.closest('tr');
            
            const userId = row.cells[1].innerText.trim();
            const fullName = row.cells[2].innerText.trim();
            const address = row.cells[3].innerText.trim();
            const email = row.cells[4].innerText.trim();
            const dob = row.cells[5].innerText.trim();
            const status = row.cells[6].innerText.trim();
            const householdId = row.cells[7].innerText.trim();

            document.getElementById('editUserId').value = userId;
            
            const names = fullName.split(' ');
            document.getElementById('firstName').value = names[0] || "";
            document.getElementById('lastName').value = names.slice(1).join(' ') || "";

            document.getElementById('address').value = address;
            document.getElementById('email').value = email;
            document.getElementById('dob').value = dob;
            document.getElementById('status').value = status;
            
            const hhInput = document.getElementById('household_id');
            if(hhInput) {
                hhInput.value = (householdId === 'None' || householdId === 'N/A') ? "" : householdId;
            }

            const form = document.getElementById('censusForm');
            if(form) form.action = "census.php?action=update";

            toggleModal();
        }
    });

    if(modal) {
        window.addEventListener('click', (e) => { if (e.target === modal) toggleModal(); });
    }
});