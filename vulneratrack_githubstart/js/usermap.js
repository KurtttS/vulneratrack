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

var areaControl = L.control({ position: 'topright' });
areaControl.onAdd= function(map){
    var div = L.DomUtil.create('div', 'map-menu-container');
    
    div.innerHTML = '<h4 style="margin:0 0 10px 0; font-size: 14px;">Areas Secured</h4>';

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

        div.appendChild(button);
    });

    // Stop map clicks from "bleeding through" the buttons
    L.DomEvent.disableClickPropagation(div);
    
    return div;
};
areaControl.addTo(map);

//PLACEHOLDER FOR USER LOCATION
var marker = L.marker([10.6727, 122.9875]).addTo(map);
marker.bindPopup("<b>You are here</b>").openPopup();

//Routing Function
let onRoute = false;
let routeControl = null;

var nearestShelter = L.control({ position: 'topleft' });
nearestShelter.onAdd=function(map){
    var div = L.DomUtil.create('div', 'map-route-container');
    div.innerHTML = '<h4 style="margin:0 0 10px 0; font-size: 14px;">Emergency Shelter</h4>';

    let btn = document.createElement('button');
    btn.innerText = 'Route';
    btn.className = 'route-btn';

    btn.onclick = function() {
        if (onRoute === false){
            routeControl = L.Routing.control({
                waypoints: [
                    L.latLng(10.6727, 122.9875),
                    L.latLng(10.670823793690534, 122.98678411341392)
                ],show:false
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
