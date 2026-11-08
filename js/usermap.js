//Base map with user location
const mapElement = document.getElementById('map');
const storedLat = mapElement.getAttribute('data-lat');
const storedLng = mapElement.getAttribute('data-lng');
const areaname = mapElement.getAttribute('area-name');
const latNum = parseFloat(storedLat);
const lngNum = parseFloat(storedLng);

var map = L.map('map', {
    zoomControl: false,
    scrollWheelZoom: false
}).setView([lngNum, latNum], 15);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

L.control.zoom({
    position: 'bottomright' 
}).addTo(map);

const userMarker = L.marker([lngNum, latNum]).addTo(map);
userMarker.bindPopup("You are located here!").openPopup();

//Areas Monitored menu
const monitoredAreas =[
    { name: "Celine Homes", coords: [10.6727, 122.9875], zoom: 17 },
    { name: "East Homes", coords: [10.67469166716797, 122.99009451560596], zoom: 17},
    { name: "Purok Carvic", coords:[10.690732242556788, 122.9602300962636], zoom: 19}
];

var areaControl = L.control({ position: 'topright' });
areaControl.onAdd = function(map) {
    var div = L.DomUtil.create('div', 'map-menu-container');
    div.innerHTML = '<h4 style="margin:0 0 10px 0;">Areas Secured</h4>';

    monitoredAreas.forEach(area => {
        let button = document.createElement('button');
        button.innerText = area.name;
        
        if (area.name === areaname) {
            button.className = 'menu-btn highlighted-btn';
        } else {
            button.className = 'menu-btn';
        }

        div.appendChild(button);
    });

    L.DomEvent.disableClickPropagation(div);
    return div;
};
areaControl.addTo(map);

//Routing Function
let onRoute = false;
let routeControl = null;
let emerlat = 0;
let emerlng = 0;
if (areaname == "Purok Carvic"){
    emerlng = 10.690976872339029;
    emerlat = 122.95843435535801;
}
else {
    emerlng = 10.670823793690534;
    emerlat = 122.98678411341392;
}
var nearestShelter = L.control({ position: 'topleft' });
nearestShelter.onAdd=function(map){
    var div = L.DomUtil.create('div', 'map-route-container');
    div.innerHTML = '<h4 style="margin:0 0 10px 0;">Emergency Shelter</h4>';

    let btn = document.createElement('button');
    btn.innerText = 'Route';
    btn.className = 'route-btn';

    btn.onclick = function() {
        if (onRoute === false){
            routeControl = L.Routing.control({
                waypoints: [
                    L.latLng(lngNum, latNum),
                    L.latLng(emerlng, emerlat)
                ],show: false,            
            addWaypoints: false,     
            draggableWaypoints: false, 
            fitSelectedRoutes: true
                }).addTo(map);
                onRoute = true;
                btn.innerText  = 'Clear Route'
                btn.style.backgroundColor = '#dc3545';
            }
        else {
            map.removeControl(routeControl);
            routeControl = null; 
            onRoute = false;
            btn.innerText = 'Route'; 
            btn.style.backgroundColor = '#4a6cf7'; 
        };
    };
    div.appendChild(btn); 
    L.DomEvent.disableClickPropagation(div);
    return div;
    }
nearestShelter.addTo(map);