document.addEventListener('DOMContentLoaded', function() {
    try {
        // Defaults (coordenadas solicitadas)
        var defaultLat = -2.190182685852051;
        var defaultLng = -79.8813247680664;
        var defaultZoom = 17;

        // Initialize a Leaflet map for every element with class 'consult-map'
        var nodes = document.querySelectorAll('.consult-map');
        if (!nodes || nodes.length === 0) return;

        // keep references so other code can request invalidation after layout changes
        window.consultMaps = window.consultMaps || [];

        nodes.forEach(function(el, idx) {
            // allow overriding coords via data attributes
            var lat = parseFloat(el.getAttribute('data-lat')) || defaultLat;
            var lng = parseFloat(el.getAttribute('data-lng')) || defaultLng;
            var zoom = parseInt(el.getAttribute('data-zoom')) || defaultZoom;

            // Create the map on the element with interactions disabled (no scroll zoom)
            var map = L.map(el, {
                scrollWheelZoom: false,
                doubleClickZoom: false,
                boxZoom: false,
                touchZoom: false,
                dragging: false,
                zoomControl: true
            }).setView([lat, lng], zoom);

            // Use Carto Voyager tiles for a richer basemap appearance
            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/">CARTO</a>'
            }).addTo(map);

            var marker = L.marker([lat, lng]).addTo(map);
                        var popupHtml = el.getAttribute('data-popup') || (
                                '<div class="map-popup">' +
                                    '<div class="map-popup-left">' +
                                        '<img src="images/ISOTIPO.jpg" alt="Logo" />' +
                                    '</div>' +
                                    '<div class="map-popup-right">' +
                                        '<h4>Dirección</h4>' +
                                        '<div class="map-popup-address">Malecón 2000, Guayaquil, Ecuador</div>' +
                                        '<div class="map-popup-coords">Coordenadas: ' + lat.toFixed(6) + ', ' + lng.toFixed(6) + '</div>' +
                                    '</div>' +
                                '</div>'
                        );
            marker.bindPopup(popupHtml, {className: 'small-map-popup'});

            // Bind a small tooltip (label) so users can 'aplastar' la etiqueta (click) and open popup
            var tooltipText = el.getAttribute('data-tooltip') || 'Nuestra ubicación';
            marker.bindTooltip(tooltipText, {direction: 'top', offset: [0, -10], opacity: 0.95});

            // Open popup when tooltip/marker is clicked
            marker.on('click', function(e){
                try { marker.openPopup(); } catch (err) { console.warn(err); }
            });

            // Also open popup on hover and close on mouseout for a small preview
            marker.on('mouseover', function() {
                try { marker.openPopup(); } catch (err) { console.warn(err); }
            });
            marker.on('mouseout', function() {
                try { marker.closePopup(); } catch (err) { console.warn(err); }
            });

            // Añadir overlay que invita a activar el mapa; al hacer clic se habilitan las interacciones
            try {
                if (!el.style.position || el.style.position === '') el.style.position = 'relative';
                var overlay = document.createElement('div');
                overlay.className = 'map-activate-overlay';
                overlay.setAttribute('role', 'button');
                overlay.innerText = 'Haz clic para activar el mapa';
                overlay.style.cssText = 'position:absolute;top:10px;right:10px;background:rgba(0,0,0,0.55);color:#fff;padding:8px 12px;border-radius:4px;cursor:pointer;z-index:1000;font-size:14px;';
                el.appendChild(overlay);

                function enableInteractions() {
                    try {
                        if (map.dragging && map.dragging.disable) map.dragging.enable();
                        if (map.scrollWheelZoom && map.scrollWheelZoom.disable) map.scrollWheelZoom.enable();
                        if (map.doubleClickZoom && map.doubleClickZoom.disable) map.doubleClickZoom.enable();
                        if (map.boxZoom && map.boxZoom.disable) map.boxZoom.enable();
                        if (map.touchZoom && map.touchZoom.disable) map.touchZoom.enable();
                        if (map.tap && map.tap.disable) map.tap.enable();
                    } catch (e) {
                        console.error('Error enabling map interactions', e);
                    }
                }

                overlay.addEventListener('click', function (ev) {
                    ev.stopPropagation();
                    enableInteractions();
                    if (overlay && overlay.parentNode) overlay.parentNode.removeChild(overlay);
                });

                // También habilitar al hacer clic en cualquier parte del mapa
                map.on('click', function () {
                    enableInteractions();
                    if (overlay && overlay.parentNode) overlay.parentNode.removeChild(overlay);
                });
            } catch (e) {
                console.warn('No se pudo añadir overlay de activación del mapa', e);
            }

            // store map reference for later resize/invalidate calls
            try { window.consultMaps.push(map); } catch (e) { window.consultMaps = [map]; }

            // Ensure map renders when inside hidden/animated containers
            setTimeout(function() { try { map.invalidateSize(); } catch (e){} }, 300);
        });

        // Listen for custom event to invalidate maps when layout changes
        document.addEventListener('consult_resize', function () {
            try {
                (window.consultMaps || []).forEach(function(m) { try { m.invalidateSize(); } catch(e){} });
            } catch (e) {}
        });

    } catch (e) {
        console.error('Leaflet map error:', e);
    }
});
