<script lang="ts">
import '@maplibre/maplibre-gl-geocoder/dist/maplibre-gl-geocoder.css';
import 'maplibre-gl/dist/maplibre-gl.css';

import MaplibreGeocoder, {
    CarmenGeojsonFeature,
    MaplibreGeocoderApi,
    MaplibreGeocoderApiConfig,
    MaplibreGeocoderFeatureResults,
} from '@maplibre/maplibre-gl-geocoder';
import maplibregl, { GeolocateControl, Map } from 'maplibre-gl';
import { onMounted, shallowRef } from 'vue';

export default {
    name: 'MapLibre',
    emits: ['result-click'],
    setup(_, { emit }) {
        const container = shallowRef(null);
        const element = shallowRef(null);

        onMounted(() => {
            const initialState = { lng: 4.4, lat: 50.534, zoom: 8 };

            const map = new Map({
                container: container.value,
                // style: 'https://tiles.openfreemap.org/styles/liberty',
                style: {
                    'version': 8,
                    'sources': {
                        'raster-tiles': {
                            'type': 'raster',
                            'tiles': ['https://tile.meteo.be/styles/Streets-v10/{z}/{x}/{y}.png'],
                            'tileSize': 256,
                            'attribution': '<a href="https://www.openmaptiles.org/" target="_blank">&copy; OpenMapTiles</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>'
                        }
                    },
                    'layers': [
                        {
                            'id': 'simple-tiles',
                            'type': 'raster',
                            'source': 'raster-tiles',
                            'minzoom': 0,
                            'maxzoom': 22
                        }
                    ]
                },
                center: [initialState.lng, initialState.lat],
                zoom: initialState.zoom,
            });

            const geocoderApi: MaplibreGeocoderApi = {
                forwardGeocode: async (config: MaplibreGeocoderApiConfig): Promise<MaplibreGeocoderFeatureResults> => {
                    const features: CarmenGeojsonFeature[] = [];
                    try {
                        const request = `https://nominatim.openstreetmap.org/search?q=${config.query}&format=geojson&polygon_geojson=1&addressdetails=1`;
                        const response = await fetch(request);
                        const geojson = await response.json();
                        for (const f of geojson.features) {
                            const center: GeoJSON.Position = [f.bbox[0] + (f.bbox[2] - f.bbox[0]) / 2, f.bbox[1] + (f.bbox[3] - f.bbox[1]) / 2];
                            const feature: CarmenGeojsonFeature = {
                                type: 'Feature',
                                id: f.properties.osm_id,
                                geometry: {
                                    type: 'Point',
                                    coordinates: center,
                                },
                                place_name: f.properties.display_name,
                                properties: f.properties,
                                text: f.properties.display_name,
                                place_type: ['place'],
                            };
                            features.push(feature);
                        }
                    } catch (e) {
                        console.error(`Failed to forwardGeocode with error: ${e}`);
                    }

                    return { type: 'FeatureCollection', features };
                },
            };

            const geocoder = new MaplibreGeocoder(geocoderApi, { maplibregl, enableEventLogging: false });
            geocoder.on('result', (e) => {
                const feature = e.result as CarmenGeojsonFeature;

                emit('result-click', feature.geometry.coordinates);
            });

            const geolocate = new GeolocateControl({ positionOptions: { enableHighAccuracy: true } });
            geolocate.on('geolocate', (e) => {
                emit('result-click', [e.coords.longitude, e.coords.latitude], e.coords.altitude);
            });

            map.addControl(geocoder);
            map.addControl(geolocate, 'top-left');
        });

        return {
            container,
            element,
        };
    },
};
</script>

<template>
    <div class="h-full w-full rounded-lg" ref="container"></div>
</template>
