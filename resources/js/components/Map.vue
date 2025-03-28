<script>
import { Map, GeolocateControl, NavigationControl, ScaleControl, Popup } from 'maplibre-gl';
import { shallowRef, onMounted, onUnmounted, markRaw, defineEmits } from 'vue';
import 'maplibre-gl/dist/maplibre-gl.css';

export default {
  name: "Map",
  emits: ['feature-click'],
  setup(props, { emit }) {
    const mapContainer = shallowRef(null);
    const map = shallowRef(null);

    onMounted(() => {
      const initialState = { lng: 4.400, lat: 50.534, zoom: 8 };

      const maplibre = new Map({
        container: mapContainer.value,
        style: 'https://tiles.openfreemap.org/styles/liberty',
        center: [initialState.lng, initialState.lat],
        zoom: initialState.zoom
      });

      maplibre.addControl(new NavigationControl(), 'top-left');
      maplibre.addControl(new GeolocateControl({ positionOptions: { enableHighAccuracy: true } }), 'top-left');
      maplibre.addControl(new ScaleControl(), 'bottom-left');

      maplibre.on('load', async () => {
        maplibre.addSource('wow-live', {
          type: 'geojson',
          data: route('api.live')
        });
        maplibre.addLayer({
          id: 'wow-live',
          type: 'circle',
          source: 'wow-live',
          paint: {
            'circle-radius': 5,
            'circle-color': [
              'case',
              ['==', ['get', 'timestamp'], null], 'rgba(80,80,80,0.25)', // Gray if timestamp is null
              ['interpolate', ['linear'], ['get', 'dt', ['get', 'primary']],
                0, 'blue',
                50, 'red'
              ]
            ]
          }
        });
      });

      const popup = new Popup({
        closeButton: false,
        closeOnClick: false
      });

      let currentFeatureCoordinates = undefined;
      maplibre.on('mousemove', 'wow-live', (e) => {
        const features = e.features.filter(f => typeof f.properties.timestamp !== 'undefined');
        if (features.length === 0) {
          return;
        }

        const featureCoordinates = features[0].geometry.coordinates.toString();
        if (currentFeatureCoordinates !== featureCoordinates) {
          currentFeatureCoordinates = featureCoordinates;

          // Change the cursor style as a UI indicator.
          maplibre.getCanvas().style.cursor = 'pointer';

          const coordinates = features[0].geometry.coordinates.slice();
          const datetime = new Date(features[0].properties.timestamp);
          const name = features[0].properties.name;
          const reading = JSON.parse(features[0].properties.primary);

          // Ensure that if the map is zoomed out such that multiple
          // copies of the feature are visible, the popup appears
          // over the copy being pointed to.
          while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
            coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
          }

          // Populate the popup and set its coordinates
          // based on the feature found.
          popup.setLngLat(coordinates).setHTML(
            `<h1>${name}</h1>` +
            `<h2>${datetime.toLocaleString()}</h2>` +
            `<ul>` +
            `<li>Temperature: ${reading.dt}°C</li>` +
            `<li>Wind speed: ${reading.dws}m/s</li>` +
            `<li>Wind direction: ${reading.dwd}°</li>` +
            `<li>Humidity: ${reading.dh}%</li>` +
            `<li>Pressure: ${reading.dm}hPa</li>` +
            `</ul>`
          ).addTo(maplibre);
        }
      });
      maplibre.on('mouseleave', 'wow-live', () => {
        currentFeatureCoordinates = undefined;
        maplibre.getCanvas().style.cursor = '';
        popup.remove();
      });

      maplibre.on('click', 'wow-live', (e) => {
        const features = e.features.filter(f => typeof f.properties.timestamp !== 'undefined');
        if (features.length === 0) {
          return;
        }

        const feature = features[0];

        maplibre.zoomTo(12, { center: feature.geometry.coordinates });

        emit('feature-click', feature);
      });

      map.value = markRaw(maplibre);
    }),
      onUnmounted(() => {
        map.value?.remove();
      })

    return {
      map, mapContainer
    };
  }
};
</script>

<template>
  <div class="h-full w-full rounded-lg" ref="mapContainer"></div>
</template>
