import L from 'leaflet';

export function initLeafletMaps() {
    const destinationMap = document.getElementById('destination-map');
    if (destinationMap && !destinationMap.dataset.initialized) {
        const lat = Number(destinationMap.dataset.lat || -8.6753);
        const lng = Number(destinationMap.dataset.lng || 115.2625);
        const map = L.map(destinationMap).setView([lat, lng], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);
        L.marker([lat, lng]).addTo(map);
        destinationMap.dataset.initialized = 'true';
    }

    const plannerMap = document.getElementById('planner-map');
    if (plannerMap && !plannerMap.dataset.initialized) {
        const map = L.map(plannerMap).setView([-8.6753, 115.2625], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);
        const stops = [[-8.6775,115.2620],[-8.6737,115.2640],[-8.6690,115.2670]];
        stops.forEach((position,index) => L.marker(position).addTo(map).bindPopup(`Destinasi ${index+1}`));
        L.polyline(stops,{color:'#087f78',weight:3,dashArray:'5 7'}).addTo(map);
        plannerMap.dataset.initialized = 'true';
    }
}
