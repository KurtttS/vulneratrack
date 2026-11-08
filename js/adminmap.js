//Map base settings
var map = L.map('map', {
    zoomControl: false,
    scrollWheelZoom: false,
}).setView([10.680948934801846, 122.97515859836543], 15);

map.on('zoomend', function () {
    if (map.getZoom() <= 14) {   
        map.removeLayer(householdLayer);
        map.addLayer(residentialAreas);

        residentialAreas.eachLayer(layer => {
            layer.setStyle({ fillOpacity: 0.5 });
        });

    } else {
        map.addLayer(householdLayer);
        map.addLayer(residentialAreas);   
        residentialAreas.eachLayer(layer => {
            layer.setStyle({ fillOpacity: 0.25 });
        });

    }

});

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

L.control.zoom({
    position: 'bottomright' 
}).addTo(map);

//Areas Monitored menu
const monitoredAreas =[
    { name: "Celine Homes", coords: [10.673800294511398, 122.98515213899572], zoom: 17 },
    { name: "East Homes", coords: [10.676663915143108, 122.98827369553024], zoom: 17},
    { name: "Purok Carvic", coords:[10.690732242556788, 122.9602300962636], zoom: 18}
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
var householdLayer = L.layerGroup();
function loadAllHouseholdMarkers() {
    fetch(`../router/pointsystemrouter.php?action=getAllHouseholdsAssessment`)
        .then(response => response.json())
        .then(data => {
            const { households, vulnerabilities } = data;

            households.forEach(hh => {
                const houseRelatedVuls = vulnerabilities.filter(v => 
                    v.Household_ID == hh.HouseholdID || (v.Area_ID == hh.Area_ID && v.Household_ID == null)
                );

                let totalScore = 0;
                let rowsHtml = '';

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

                houseRelatedVuls.forEach(v => {
                    const pts = parseInt(v.Vulnerability_Points);
                    totalScore += pts;
                    rowsHtml += `<div class="assessment-row"><span>${v.Type_of_Vulnerablity}</span> <strong>+${pts}</strong></div>`;
                });

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

                const infoIcon = L.divIcon({
                html: '<i class="fa-solid fa-circle-info" style="color: ' + markerColor + '; font-size: 20px;"></i>',
                className: 'custom-leaflet-icon',
                iconSize: [20, 20],        
                iconAnchor: [10, 10]             
            });

            const marker = L.marker([hh.CoordinateY, hh.CoordinateX], {
                icon: infoIcon
            }).addTo(householdLayer);
                marker.bindPopup(`
                    <div class="risk-container">
                        <h3> Household ${hh.HouseholdID} Assessment </h3><br>
                        ${rowsHtml}
                        <div class="popup-footer">
                            <span class="risk-label">${riskLabel}</span>
                            <span class="risk-score">${totalScore}</span>
                        </div>
                        <button class="details-btn" onclick="window.location.href = '../admin-module/assessment.php';">View Full Report</button>
                    </div>
                `, { maxWidth: 300 });
                
            const highriskcount = document.querySelector('.big-number-red');
            highriskcount.innerHTML = highrisk; 
            });
        });
}

//area borders
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
};L.geoJSON(purokcarvic).addTo(map);

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
L.geoJSON(celinehomes).addTo(map);

//Area border styles
var residentialAreas = L.featureGroup().addTo(map);
L.geoJSON(purokcarvic, {
    style: { fillColor: "#9870FC", 
    color: "#9870FC",
    fillOpacity: 0.5,
    weight: 2 },
    onEachFeature: function (feature, layer) { layer.bindPopup(`
        <div class="area-container">
            <h3>Purok Carvic</h3>
        </div>
    `); }
}).addTo(residentialAreas);

L.geoJSON(celinehomes, {
    style: { fillColor: "#0C9CEE",
    color: "#0C9CEE",
    fillOpacity: 0.5,
    weight: 2 },
    onEachFeature: function (feature, layer) { layer.bindPopup(`
        <div class="area-container">
            <h3>Celine Homes</h3>
        </div>
    `); }
}).addTo(residentialAreas);

L.geoJSON(easthomes3, {
    style: { fillColor: "#00CF95",
    color: "#00CF95",
    fillOpacity: 0.5,
    weight: 2 },
    onEachFeature: function (feature, layer) { layer.bindPopup(`
        <div class="area-container">
            <h3>East Homes Phase 3</h3>
        </div>
    `); }
}).addTo(residentialAreas);

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

