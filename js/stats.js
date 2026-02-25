var statmap = L.map('statmap', {
    zoomControl: false,
    scrollWheelZoom: false
}).setView([10.672768169260864, 122.98753057212929], 18);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(statmap);

var marker = L.marker([10.672768169260864, 122.98753057212929]).addTo(statmap);

