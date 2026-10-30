import './bootstrap';
import { initLeafletMaps } from './modules/leaflet.js';

initLeafletMaps();
import { createApp } from 'vue';

const mapElement = document.getElementById('trip-planner-map');

if (mapElement) {
    import('./components/vue/TripPlannerMap.vue').then(({ default: TripPlannerMap }) => {
        createApp(TripPlannerMap).mount(mapElement);
    });
}
