//Map base settings
var map = L.map('map', {
    zoomControl: false,
    scrollWheelZoom: false
}).setView([10.672768169260864, 122.98753057212929], 18);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

L.control.zoom({
    position: 'bottomright' 
}).addTo(map);


//Areas Monitored menu
const monitoredAreas =[
    { name: "Celine Homes", coords: [10.6727, 122.9875], zoom: 17 },
    { name: "East Homes Phase 3", coords: [10.67469166716797, 122.99009451560596], zoom: 17},
    { name: "Buena Royale", coords:[10.658594502768942, 122.99730998650621], zoom: 17}
];

var areaControl = L.control({ position: 'bottomright' });
areaControl.onAdd = function(map) {
    var template = document.getElementById('area-menu-template').cloneNode(true);
    var div = template.querySelector('.map-menu-container');
    div.style.display = 'block'; 

    const list = div.querySelector('#button-list');
    
    monitoredAreas.forEach(area => {
        let button = document.createElement('button');
        button.innerText = area.name;
        button.className = 'menu-btn';

        button.onclick = function() {
            map.flyTo(area.coords, area.zoom, {
                animate: true,
                duration: 0.5 
            });
        };
        list.appendChild(button);
    });

    L.DomEvent.disableClickPropagation(div);
    return div;
};
areaControl.addTo(map);

//Risk control
var riskControl = L.control({ position: 'topright' });

riskControl.onAdd = function(map) {
    var template = document.getElementById('risk-summary-template').cloneNode(true);
    var div = template.querySelector('.risk-summary-container');
    div.style.display = 'flex'; 

    L.DomEvent.disableClickPropagation(div);
    
    return div;
};riskControl.addTo(map);

document.addEventListener('DOMContentLoaded', () => {
    loadAllHouseholdMarkers();
});

var highrisk = 0;

function loadAllHouseholdMarkers() {
    fetch(`../router/pointsystemrouter.php?action=getAllHouseholdsAssessment`)
        .then(response => response.json())
        .then(data => {
            const { households, vulnerabilities } = data;

            households.forEach(hh => {
                // 1. Get vulnerabilities specific to this house OR its area
                const houseRelatedVuls = vulnerabilities.filter(v => 
                    v.Household_ID == hh.HouseholdID || (v.Area_ID == hh.Area_ID && v.Household_ID == null)
                );

                // 2. Calculate scores and build the HTML rows for the popup
                let totalScore = 0;
                let rowsHtml = '';

                // Add Member Scores (Senior, Child, PWD)
                if (parseInt(hh.elderly_count) > 0) {
                    const pts = hh.elderly_count * 3;
                    totalScore += pts;
                    rowsHtml += `<div class="assessment-row"><span>${hh.elderly_count} Senior Citizens</span> <strong>+${pts}</strong></div>`;
                }
                if (parseInt(hh.child_count) > 0) {
                    const pts = hh.child_count * 2;
                    totalScore += pts;
                    rowsHtml += `<div class="assessment-row"><span>${hh.child_count} Children</span> <strong>+${pts}</strong></div>`;
                }
                if (parseInt(hh.pwd_count) > 0) {
                    const pts = hh.pwd_count * 3;
                    totalScore += pts;
                    rowsHtml += `<div class="assessment-row"><span>${hh.pwd_count} PWDs</span> <strong>+${pts}</strong></div>`;
                }

                // Add Vulnerability Table Scores (Flood, Rainfall, etc.)
                houseRelatedVuls.forEach(v => {
                    const pts = parseInt(v.Vulnerability_Points);
                    totalScore += pts;
                    rowsHtml += `<div class="assessment-row"><span>${v.Type_of_Vulnerablity}</span> <strong>+${pts}</strong></div>`;
                });

                // 3. Determine Risk Label
                let riskLabel = "Low Risk";
                let markerColor = "";
                if (totalScore >= 20) { 
                    riskLabel = "High Risk"; markerColor = "#dc3545"; 
                    highrisk++;
                }
                else if (totalScore >= 10) { 
                    riskLabel = "Medium Risk"; markerColor = "#ffc107"; 
                }
                else if (totalScore < 10) { 
                    riskLabel = "Low Risk"; markerColor = "#28a745"; 
                }

                // 4. Create Marker and Popup
                const marker = L.circleMarker([hh.CoordinateY, hh.CoordinateX], {
                    radius: 10,
                    fillColor: markerColor,
                    color: "#fff",
                    weight: 2,
                    fillOpacity: 0.9
                }).addTo(map);

                marker.bindPopup(`
                    <div class="risk-container">
                        <h3> Household ${hh.HouseholdID} Assessment </h3><br>
                        ${rowsHtml}
                        <div class="popup-footer">
                            <span class="risk-label">${riskLabel}</span>
                            <span class="risk-score">${totalScore}</span>
                        </div>
                        <button class="details-btn" onclick="displaypointsystem(${hh.HouseholdID})">View Full Report</button>
                    </div>
                `, { maxWidth: 300 });
                
            const highriskcount = document.querySelector('.big-number-red');
            highriskcount.innerHTML = highrisk; 
            });
        });
}
    

var geojson = {
  "type": "FeatureCollection",
  "features": [
    {
      "type": "Feature",
      "properties": {},
      "geometry": {
        "coordinates": [
          [
            [
              122.98217831397784,
              10.674302767745615
            ],
            [
              122.9815850730719,
              10.673583273121023
            ],
            [
              122.98134668458692,
              10.672753658493416
            ],
            [
              122.98143513385727,
              10.672116754583486
            ],
            [
              122.98095641808295,
              10.671853363190085
            ],
            [
              122.98096328983223,
              10.671002662219188
            ],
            [
              122.98155265313119,
              10.670332997611055
            ],
            [
              122.98233984258573,
              10.670208127972122
            ],
            [
              122.98359697154949,
              10.670115780438575
            ],
            [
              122.98466877225695,
              10.669438034211739
            ],
            [
              122.98597223328736,
              10.66941073137955
            ],
            [
              122.98740743956314,
              10.66992818433997
            ],
            [
              122.98887902154473,
              10.67179209777143
            ],
            [
              122.98891637443057,
              10.672568379490855
            ],
            [
              122.98835893263359,
              10.674467946886281
            ],
            [
              122.98787010174894,
              10.674981837763326
            ],
            [
              122.98742958365179,
              10.675238782766229
            ],
            [
              122.98693303622652,
              10.675257135993533
            ],
            [
              122.9854259473241,
              10.674816658683667
            ],
            [
              122.98354532955715,
              10.674770775644944
            ],
            [
              122.98217831397784,
              10.674302767745615
            ]
          ]
        ],
        "type": "Polygon"
      }
    }
  ]
};
L.geoJSON(geojson).addTo(map);
L.geoJSON(geojson, {
    style: {
        color: "#ff7800",     
        weight: 3,           
        fillColor: "#ff0000",
        fillOpacity: 0.3       
    }
}).addTo(map);

function createRiskAssessment(data){
    return `
        <div class="risk-container">
            <h3> Vulnerability Assessment </h3><br>
            <div class="assessment-row"><span>Within Flood Zone</span> <strong>+5</strong></div>
            <div class="assessment-row"><span>2 Senior Citizens</span> <strong>+4</strong></div>
            <div class="assessment-row"><span>3 Children</span> <strong>+6</strong></div>
            <div class="assessment-row"><span>Yellow Rainfall Alert</span> <strong>+5</strong></div>
            <div class="popup-footer">
                <span class="risk-label">High Risk</span>
                <span class="risk-score">20</span>
            </div>
        <button class="details-btn" onclick="viewDetailedReport('${data.id}')">View Full Report</button>
        </div>
    `;
}

