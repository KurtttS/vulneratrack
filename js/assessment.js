//Area Assessment
let currentArea = 1;
document.addEventListener('DOMContentLoaded', () => {
    fetchAssessment(currentArea); 

    const nextBtn = document.querySelector('.btn-next');

    nextBtn.addEventListener('click', () => {
        if (currentArea<3){
        currentArea++; 
        fetchAssessment(currentArea);
        }
        else if (currentArea==3){
            currentArea = 1;
            fetchAssessment(currentArea);
        }
        if (currentArea==1){
            areamap.setView([10.672768169260864, 122.98753057212929], 15);
        }
        else if (currentArea==2){
            areamap.setView([10.675115474735781, 122.98933560563219], 15);
        }
        else if (currentArea==3){
            areamap.setView([10.690732242556788, 122.9602300962636], 15);
        }
    });
});

//fetch area assessment
function fetchAssessment(areaID) {
    currentArea = areaID;
    fetch(`../router/pointsystemrouter.php?action=getAreaAssessment&areaID=${areaID}`)
        .then(response => response.json())
        .then(data => {
            if(data.error) {
                console.error("Area not found");
                return;
            }
            updateUI(data);
        });
}

//update area ui
function updateUI(data) {
    document.querySelector('.assessment-data h3').innerText = data.name;

    const list = document.querySelector('.risk-list');
    const scoreDisplay = document.querySelector('.risk-score');
    const scoreGrade = document.querySelector('.risk-label');

    list.innerHTML = ''; 
    let totalPoints = 0; 
    const activeRiskNames = data.risks.map(r => r.Type_of_Vulnerablity);

    data.risks.forEach(risk => {
        list.innerHTML += `
            <li class="risk-item">
                <button class="vul-delete-btn" onclick="deleteVulnerability(${risk.VulnerabilityID})">X</button>
                ${risk.Type_of_Vulnerablity}
                <span>+ ${risk.Vulnerability_Points}</span>
            </li>
        `;

        const points = parseInt(risk.Vulnerability_Points) || 0;
        totalPoints += points; 
    });
    
    scoreDisplay.innerText = totalPoints;

    if (totalPoints >= 20){
        scoreGrade.innerText = 'High Risk';
    }
    if (totalPoints < 20 && totalPoints > 10){
        scoreGrade.innerText = 'Medium Risk';
    }
    if (totalPoints < 10){
        scoreGrade.innerText = 'Low Risk';
    }

    const tagGrid = document.querySelector('.tag-grid');
    tagGrid.innerHTML = ''; 
    AVAILABLE_TAGS.forEach(tag => {
        if (!activeRiskNames.includes(tag.name)) {
            const btn = document.createElement('button');
            btn.className = 'tag';
            btn.innerHTML = `${tag.name} <span>+</span>`;
            
            btn.onclick = function() {
                console.log("Tag clicked:", tag.name);
                addAreaVulnerability(tag.name, tag.points);
                if (tag.name ==  "Storm Surge" || tag.name == "Red Rainfall" || tag.name  == "Typhoon Signal 3" 
                    || tag.name == "Typhoon Signal 4" || tag.name == "Typhoon Signal 5") {
                    vulnerabilityname =  tag.name;
                    sendAreaAlert(currentArea, vulnerabilityname);
                }
            };
            
            tagGrid.appendChild(btn);
        }
    });
}

const AVAILABLE_TAGS = [
    { name: "Drainage failure", points: 2 },
    { name: "Within Floodzone", points: 6 },
    { name: "Storm Surge", points: 10 },
    { name: "Yellow Rainfall", points: 2 },
    { name: "Orange Rainfall", points: 5 },
    { name: "Red Rainfall", points: 10 },
    { name: "Typhoon Signal 1", points: 2 },
    { name: "Typhoon Signal 2", points: 4 },
    { name: "Typhoon Signal 3", points: 10 },
    { name: "Typhoon Signal 4", points: 20 },
    { name: "Typhoon Signal 5", points: 20 }
];

//Add area vulnerabilities
function addAreaVulnerability(name, points) {
    console.log(`Adding ${name} to Area ${currentArea}`);

    fetch(`../router/pointsystemrouter.php?action=addAreaVulnerability&areaID=${currentArea}&name=${encodeURIComponent(name)}&points=${points}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                fetchAssessment(currentArea); 
                if (currentHouse !== 0) {
                    fetchHouseholdAssessment(currentHouse);
                }
            } else {
                alert("Error adding risk: " + (data.message || "Unknown error"));
            }
        })
        .catch(err => console.error("Fetch Error:", err));
}

//Delete vulnerabilities
function deleteVulnerability(VulnerabilityID) {
    fetch(`../router/pointsystemrouter.php?action=deleteVulnerability&id=${VulnerabilityID}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                fetchAssessment(currentArea); 
                if (currentHouse !== 0) {
                    fetchHouseholdAssessment(currentHouse);
                }
            } else {
                alert("Delete failed: " + data.message);
            }
        })
        .catch(err => console.error("Error deleting:", err));
}

function sendAreaAlert(currentArea, vulnerabilityname) {
    console.log(`Triggering SMS Alerts for Area Residents`);
    
    fetch(`../router/pointsystemrouter.php?action=notifyAreaRisk&areaID=${currentArea}&alertname=${vulnerabilityname}`)
        .then(response => response.json())
        .then(result => {
            console.log("Full PhilSMS Response:", result.details); 
            if (result.status === 'success') {
                profmodup("Emergency SMS alerts sent to residents!", "success");
            }
        })
        .catch(err => console.error("SMS Trigger Error:", err));
}

let currentHouse = 0;


   //Household Search


function searchByHousehold() {
    const householdID = document.getElementById('householdSearch').value.trim();
    if (!householdID) {
        profmodup("Please enter a Household ID", "error");
        return;
    }
    fetch(`../router/pointsystemrouter.php?action=getHouseholdAssessment&householdID=${encodeURIComponent(householdID)}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                profmodup(data.error, "error");
                return;
            }
            if (!data.household || !data.household.HouseholdID) {
                profmodup("Invalid household data received", "error");
                return;
            }
            currentHouse = data.household.HouseholdID;
            if (typeof updateHouseholdUI === "function") {
                updateHouseholdUI(data);
            }
            profmodup("Household loaded successfully");
        })
        .catch(err => {
            console.error("Search Error:", err);
            profmodup("Server error while searching household", "error");
        });
}



   //Optional fetch by ID


function fetchHouseholdAssessment(householdID) {

    if (!householdID) return;

    fetch(`../router/pointsystemrouter.php?action=getHouseholdAssessment&householdID=${encodeURIComponent(householdID)}`)
        .then(response => response.json())
        .then(data => {

            if (data.error) {
                profmodup("Household not found", "error");
                return;
            }

            if (!data.household || !data.household.HouseholdID) {
                profmodup("Invalid household data received", "error");
                return;
            }

            currentHouse = data.household.HouseholdID;

            if (typeof updateHouseholdUI === "function") {
                updateHouseholdUI(data);
            }

        })
        .catch(err => {
            console.error("Fetch Error:", err);
            profmodup("Server error while loading household", "error");
        });
}



   //Color box alert


function profmodup(message, type = "success") {

    const box  = document.getElementById("customAlert");
    const text = document.getElementById("customAlertText");

    if (!box || !text) return;

    text.textContent = message;

    box.classList.remove("success", "error", "show");

    box.classList.add(type);
    box.classList.add("show");

    setTimeout(() => {
        box.classList.remove("show");
    }, 2200);
}





//UpdateUI
    function updateHouseholdUI(data) {

        housemap.panTo([data.household.CoordinateY, data.household.CoordinateX], 19);
        housemarker.setLatLng([data.household.CoordinateY, data.household.CoordinateX]);

        const container = document.querySelector('.household-list');
        const scoreHouseDisplay = document.querySelector('.household-score');
        const scoreHouseGrade = document.querySelector('.household-label');

        if (!container) return; 
        container.innerHTML = '';

        const tally = data.household; 
        const pwdPoints = (parseInt(tally.pwd_count) * 3);
        const elderlyPoints = (parseInt(tally.elderly_count) * 3);
        const childPoints = (parseInt(tally.child_count) * 2);

        document.querySelector('.houseassessment-data h3').innerText = "Household ID " + tally.HouseholdID + ": located at " + data.area_name;

        if (tally.pwd_count>0){
            container.innerHTML += `
            <li class="household-item">
                <button class="vul-delete-btn">X</button>
                    ${tally.pwd_count} PWDs
                    <span>+ ${pwdPoints}</span>
                </li>`;
        }
        if (tally.elderly_count>0){
            container.innerHTML += `
            <li class="household-item">
                <button class="vul-delete-btn">X</button>
                    ${tally.elderly_count} Elderly
                    <span>+ ${elderlyPoints}</span>
                </li>`;
        }
        if (tally.child_count>0){
            container.innerHTML += `
            <li class="household-item">
                <button class="vul-delete-btn">X</button>
                    ${tally.child_count} Children
                    <span>+ ${childPoints}</span>
                </li>`;
        }

            data.house_risks.forEach(house_risks => {
            container.innerHTML += `
                <li class="household-item house-risk">
                    <button class="vul-delete-btn" onclick="deleteVulnerability(${house_risks.VulnerabilityID})">X</button>
                    ${house_risks.Type_of_Vulnerablity}
                    <span>+ ${house_risks.Vulnerability_Points}</span>
                </li>`;
        });

            data.area_risks.forEach(area_risks => {
            container.innerHTML += `
                <li class="household-item">
                    <button class="vul-delete-btn" onclick="deleteVulnerability(${area_risks.VulnerabilityID})">X</button>
                    ${area_risks.Type_of_Vulnerablity}
                    <span>+ ${area_risks.Vulnerability_Points}</span>
                </li>
            `;
        });
        let areaPoints = 0;
        let housePoints = 0;
        data.area_risks.forEach(r => areaPoints += parseInt(r.Vulnerability_Points) || 0);
        data.house_risks.forEach(r => housePoints += parseInt(r.Vulnerability_Points) || 0);
        totalHousePoints = pwdPoints + elderlyPoints + childPoints + areaPoints + housePoints;
        scoreHouseDisplay.innerText = totalHousePoints;
        let previousGrade = scoreHouseGrade.innerText;

        if (totalHousePoints >= 20){
            scoreHouseGrade.innerText = 'High Risk';
            if (previousGrade !== 'High Risk' && currentHouse !== 0) {
            sendHighRiskAlert(currentHouse, totalHousePoints);
            }
        }
        if (totalHousePoints < 20 && totalHousePoints > 10){
            scoreHouseGrade.innerText = 'Medium Risk';
        }
        if (totalHousePoints < 10){
            scoreHouseGrade.innerText = 'Low Risk';
        }

        const activeHouseRiskNames = data.house_risks.map(r => r.Type_of_Vulnerablity);
        const housetagGrid = document.querySelector('.housetag-grid');
        housetagGrid.innerHTML = ''; 
        HOUSEAVAILABLE_TAGS.forEach(housetag => {
            if (!activeHouseRiskNames.includes(housetag.name)) {
                const btn = document.createElement('button');
                btn.className = 'housetag';
                btn.innerHTML = `${housetag.name} <span>+</span>`;
                
                btn.onclick = function() {
                    console.log("Tag clicked:", housetag.name);
                    addHouseholdVulnerability(housetag.name, housetag.points);
                };
                
                housetagGrid.appendChild(btn);
            }
        });
    }

    //List of avail tags for household
    const HOUSEAVAILABLE_TAGS = [
    { name: "Road potholes", points: 3 },
    { name: "Clogged Canals", points: 2 },
    { name: "Standing Water", points: 2 },
    { name: "Structural Damage", points: 3 },
	{ name: "Other", points: 3 },
    { name: "Severe Structural Damage", points: 5 },
    { name: "Single entry/exit", points: 4 }
];

function sendHighRiskAlert(householdID) {
    console.log(`Triggering SMS Alerts for Household ${householdID}`);
    
    fetch(`../router/pointsystemrouter.php?action=notifyHighRisk&householdID=${householdID}`)
        .then(response => response.json())
        .then(result => {
            console.log("Full PhilSMS Response:", result.details); 
            if (result.status === 'success') {
                profmodup("Emergency SMS alerts sent to residents!", "success");
            }
        })
        .catch(err => console.error("SMS Trigger Error:", err));
}

//add house vulnerabilities
function addHouseholdVulnerability(name, points) {
    console.log(`Adding ${name} to Household ${currentHouse}`);

    fetch(`../router/pointsystemrouter.php?action=addHouseholdVulnerability&householdID=${currentHouse}&name=${encodeURIComponent(name)}&points=${points}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                fetchHouseholdAssessment(currentHouse)
            } else {
                alert("Error adding risk: " + (data.message || "Unknown error"));
            }
        })
        .catch(err => console.error("Fetch Error:", err));
}

//Map displays

//Area map
var areamap = L.map('areamap', {
    zoomControl: false,
    scrollWheelZoom: false
}).setView([10.672768169260864, 122.98753057212929], 15);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(areamap);

var celinemarker = L.marker([10.672768169260864, 122.98753057212929]).addTo(areamap);
var easthomesmarker = L.marker([10.675115474735781, 122.98933560563219]).addTo(areamap);
var purokcarvic = L.marker([10.690732242556788, 122.9602300962636]).addTo(areamap);

//Household map
var housemap = L.map('housemap', {
    zoomControl: false,
    scrollWheelZoom: false
}).setView([10.67308295803972, 122.98535977039953], 19);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(housemap);

var housemarker = L.marker([10.67308295803972, 122.98535977039953]).addTo(housemap);

