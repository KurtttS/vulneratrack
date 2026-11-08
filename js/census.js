let map;
let marker;

// Standard Add/Edit Modal
window.toggleModal = function () {
    const modal = document.getElementById('censusModal');
    if (!modal) return;

    const isCurrentlyVisible = modal.style.display === 'flex';
    modal.style.display = isCurrentlyVisible ? 'none' : 'flex';

    if (isCurrentlyVisible) {
        const form = document.getElementById('censusForm');
        if (form) {
            form.reset();
            form.action = "census.php?action=add";
        }

        const editId = document.getElementById('editUserId');
        if (editId) editId.value = "";

        const householdId = document.getElementById('household_id');
        if (householdId) householdId.value = "";

        toggleHouseholdUI();
    }
};

// Delete modal
window.toggleDeleteModal = function () {
    const deleteModal = document.getElementById('deleteModal');
    if (!deleteModal) return;

    const isVisible = deleteModal.style.display === 'flex';
    deleteModal.style.display = isVisible ? 'none' : 'flex';
};

window.toggleHouseholdUI = function () {
    const actionElement = document.getElementById('householdAction');
    const action = actionElement ? actionElement.value : 'join';
    const joinSection = document.getElementById('joinSection');
    const createSection = document.getElementById('createSection');
    const areaCreate = document.getElementById('area');
    const houseJoin = document.getElementById('household_id');

    if (action === 'create') {
        if (joinSection) joinSection.style.display = 'none';
        if (createSection) createSection.style.display = 'block';

        areaCreate.required = true;
        houseJoin.required = false;
        setTimeout(initMap, 100);
    } else {
        if (joinSection) joinSection.style.display = 'block';
        if (createSection) createSection.style.display = 'none';
        houseJoin.required = true;
        areaCreate.required = false;
    }
}

// Map logic
function initMap() {
    if (!map) {
        map = L.map('map').setView([10.672768169260864, 122.98753057212929], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        map.on('click', function (e) {
            if (marker) map.removeLayer(marker);
            marker = L.marker(e.latlng).addTo(map);

            const lngInput = document.getElementById('coordX');
            const latInput = document.getElementById('coordY');

            if (latInput) latInput.value = e.latlng.lat.toFixed(8);
            if (lngInput) lngInput.value = e.latlng.lng.toFixed(8);
        });

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
                                    122.961271703607, 10.690550652289303
                                ],
                                [
                                    122.96173375332958, 10.691743998621845
                                ],
                                [
                                    122.96048418553572, 10.692099604574281
                                ],
                                [
                                    122.96009707638427, 10.6916892486108
                                ],
                                [
                                    122.95998710013262, 10.690947835109384
                                ],
                                [
                                    122.95897378677768, 10.69056497620359
                                ],
                                [
                                    122.95868640968718, 10.689889041574105
                                ],
                                [
                                    122.95851018788741, 10.689369918283525
                                ],
                                [
                                    122.95872436515168, 10.689170482065052
                                ],
                                [
                                    122.95978022801003, 10.688875659136855
                                ],
                                [
                                    122.96029358748734, 10.68923455830948
                                ],
                                [
                                    122.961271703607, 10.690550652289303
                                ]
                            ]
                        ],
                        "type": "Polygon"
                    }
                }
            ]
        }; L.geoJSON(purokcarvic).addTo(map);

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
                                    122.98431417418584, 10.680103713635134
                                ],
                                [
                                    122.98423614067383, 10.679973084853511
                                ],
                                [
                                    122.98412362093796, 10.6798594004947
                                ],
                                [
                                    122.98434391012503, 10.679442615828425
                                ],
                                [
                                    122.98435728196637, 10.679051247836227
                                ],
                                [
                                    122.98504177128706, 10.678244925334878
                                ],
                                [
                                    122.98539864147436, 10.677946938134522
                                ],
                                [
                                    122.98611502902759, 10.677448751883803
                                ],
                                [
                                    122.98691210105726, 10.676566761836764
                                ],
                                [
                                    122.98758536470822, 10.67555267801064
                                ],
                                [
                                    122.9888572213526, 10.673102515960043
                                ],
                                [
                                    122.9891187353636, 10.672910796905269
                                ],
                                [
                                    122.98947011900057, 10.672860381740954
                                ],
                                [
                                    122.989807230633, 10.672452421447412
                                ],
                                [
                                    122.9902704998166, 10.672657812013195
                                ],
                                [
                                    122.99065409054634, 10.672732702365067
                                ],
                                [
                                    122.99083542437482, 10.673785923286616
                                ],
                                [
                                    122.99081756207298, 10.674917444028068
                                ],
                                [
                                    122.99062709494238, 10.675372896935585
                                ],
                                [
                                    122.9906780313841, 10.675692792395694
                                ],
                                [
                                    122.9898630100054, 10.67606685839037
                                ],
                                [
                                    122.98923633687326, 10.676982494127486
                                ],
                                [
                                    122.98817411941528, 10.677766873758891
                                ],
                                [
                                    122.98747138046167, 10.678657229594094
                                ],
                                [
                                    122.98688097853997, 10.679300308359927
                                ],
                                [
                                    122.98609222544722, 10.679514867707091
                                ],
                                [
                                    122.98523881068581, 10.679784496597136
                                ],
                                [
                                    122.98451060917503, 10.680181831716553
                                ],
                                [
                                    122.98431417418584, 10.680103713635134
                                ]
                            ]
                        ],
                        "type": "Polygon"
                    }
                }
            ]
        };
        L.geoJSON(easthomes3).addTo(map);

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
                                    122.97965325453322, 10.673535225684105
                                ],
                                [
                                    122.98121952728673, 10.670700579632936
                                ],
                                [
                                    122.98285835892347, 10.670294299926695
                                ],
                                [
                                    122.98415306420193, 10.669816412095749
                                ],
                                [
                                    122.98487462630646, 10.669585797594792
                                ],
                                [
                                    122.98609954509529, 10.6698078425263
                                ],
                                [
                                    122.986006059853, 10.670688158958539
                                ],
                                [
                                    122.98582870000175, 10.671444839371254
                                ],
                                [
                                    122.9875988037581, 10.671175774740956
                                ],
                                [
                                    122.98778926901662, 10.671387517190198
                                ],
                                [
                                    122.98910028128046, 10.672284615400542
                                ],
                                [
                                    122.98859352686688, 10.673332228284316
                                ],
                                [
                                    122.98821492760679, 10.674179821894866
                                ],
                                [
                                    122.98681564109202, 10.676455344113517
                                ],
                                [
                                    122.98638625519007, 10.676493871239895
                                ],
                                [
                                    122.98357390391814, 10.678569664111508
                                ],
                                [
                                    122.98296326116521, 10.676771606689321
                                ],
                                [
                                    122.98205839134809, 10.675101652623951
                                ],
                                [
                                    122.98093969755193, 10.674078037953599
                                ],
                                [
                                    122.97965325453322, 10.673535225684105
                                ]
                            ]
                        ],
                        "type": "Polygon"
                    }
                }
            ]
        };
        L.geoJSON(celinehomes).addTo(map);
        //Area border styles
        var residentialAreas = L.featureGroup().addTo(map);
        L.geoJSON(purokcarvic, {
            style: {
                fillColor: "#9870FC",
                color: "#9870FC",
                fillOpacity: 0.5,
                weight: 2
            }
        }).addTo(residentialAreas);
        L.geoJSON(celinehomes, {
            style: {
                fillColor: "#0C9CEE",
                color: "#0C9CEE",
                fillOpacity: 0.5,
                weight: 2
            }
        }).addTo(residentialAreas);
        L.geoJSON(easthomes3, {
            style: {
                fillColor: "#00CF95",
                color: "#00CF95",
                fillOpacity: 0.5,
                weight: 2
            }
        }).addTo(residentialAreas);
        const dropdown = document.getElementById('area');
        dropdown.addEventListener('change', function () {
            const selectedArea = this.value;
            if (selectedArea === "Celine Homes") {
                map.setView([10.672768169260864, 122.98753057212929], 14);
            }
            else if (selectedArea === "East Homes") {
                map.setView([10.675115474735781, 122.98933560563219], 14);
            }
            else if (selectedArea === "Purok Carvic") {
                map.setView([10.690732242556788, 122.9602300962636], 16);
            }
        });
    }
    setTimeout(() => { if (map) map.invalidateSize(); }, 200);
}

document.addEventListener('DOMContentLoaded', () => {
    const selectAll = document.getElementById('selectAll');
    const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const censusModal = document.getElementById('censusModal');
    const deleteModal = document.getElementById('deleteModal');

    //bulk delete
    function toggleBulkDeleteButton() {
        const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
        if (!deleteSelectedBtn) return;

        const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;

        if (checkedCount > 0) {
            deleteSelectedBtn.classList.add('enabled');
        } else {
            deleteSelectedBtn.classList.remove('enabled');
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
            toggleBulkDeleteButton();
        });
    }

    // Ensure individual checkboxes call the toggle
    document.addEventListener('change', (e) => {
        if (e.target.classList.contains('row-checkbox')) {
            toggleBulkDeleteButton();
        }
    });

    // SELECT ALL 
    if (selectAll) {
        selectAll.addEventListener('change', function () {
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

    // OPEN DELETE MODAL
    if (deleteSelectedBtn) {
        deleteSelectedBtn.addEventListener('click', () => {
            toggleDeleteModal();
        });
    }

    // CONFIRM DELETE ACTION
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', () => {
            const selected = document.querySelectorAll('.row-checkbox:checked');
            const ids = Array.from(selected).map(cb => cb.value);

            if (ids.length > 0) {
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
            const userId = editBtn.getAttribute('data-id');

            const fullName = row.cells[1].innerText.trim();
            const address = row.cells[2].innerText.trim();
            const email = row.cells[3].innerText.trim();
            const contact = row.cells[4].innerText.trim();
            const dob = row.cells[5].innerText.trim();
            const status = row.cells[6].innerText.trim();
            const householdId = row.cells[7].innerText.trim();

            document.getElementById('editUserId').value = userId;

            const names = fullName.split(' ');
            document.getElementById('firstName').value = names[0] || "";
            document.getElementById('lastName').value = names.slice(1).join(' ') || "";

            document.getElementById('contactnum').value = contact;
            document.getElementById('address').value = address;
            document.getElementById('email').value = email;
            document.getElementById('dob').value = dob;

            // Set status dropdown
            const statusSelect = document.getElementById('status');
            if (statusSelect) statusSelect.value = status;

            const hhInput = document.getElementById('household_id');
            if (hhInput) {
                hhInput.value = (householdId === 'None' || householdId === 'N/A') ? "" : householdId;
            }

            const form = document.getElementById('censusForm');
            if (form) {
                form.action = "census.php?action=update";
                document.getElementById('birth-cert').required = false;
            }

            document.getElementById('modalTitle').innerText = "Edit Member";
            toggleModal();
        }
    });

    window.addEventListener('click', (e) => {
        if (e.target === censusModal) toggleModal();
        if (e.target === deleteModal) toggleDeleteModal();
    });

});

// UPLOAD BIRTH CERTIFICATE NAME DISPLAY
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