<div id="wineMap">
    <script type="text/javascript">
        function initialize() {
            var mapOptions =  {
                    center: new google.maps.LatLng(43.5061463,  8.7826302),
                    zoom: 2,
                    mapTypeId: google.maps.MapTypeId.TERRAIN,
                    mapTypeControl: false,
                    streetViewControl: false,
                    panControl: true,
                    panControlOptions: {
                        position: google.maps.ControlPosition.RIGHT_CENTER
                    },
                    zoomControl: true,
                    zoomControlOptions: {
                        style: google.maps.ZoomControlStyle.LARGE,
                        position: google.maps.ControlPosition.LEFT_CENTER
                    },
                },
                map = new google.maps.Map(document.getElementById("wineRegionMap"),
                    mapOptions);

            map.data.loadGeoJson('/wp-content/themes/reporter-child/widgets/wine-map/land.json');
            map.data.loadGeoJson('/wp-content/themes/reporter-child/widgets/wine-map/australia.json');
            map.data.loadGeoJson('/wp-content/themes/reporter-child/widgets/wine-map/california.json');
            map.data.loadGeoJson('/wp-content/themes/reporter-child/widgets/wine-map/chile-argentina.json');
            map.data.loadGeoJson('/wp-content/themes/reporter-child/widgets/wine-map/france.json');
            map.data.loadGeoJson('/wp-content/themes/reporter-child/widgets/wine-map/italy.json');
            map.data.loadGeoJson('/wp-content/themes/reporter-child/widgets/wine-map/spain.json');

            map.data.setStyle(function(feature) {
                var loc = feature.getProperty('loc'),
                    color = loc === 'land' ? '#EFDEC6' : '#D12F2F',
                    opacity = loc === 'land' ? 0.1 : 0.8,
                    stroke = loc === 'land' ? '#ffffff' : '#D12F2F',
                    weight = loc === 'land' ? 0 : 2;
                return {
                    fillColor: color,
                    fillOpacity: opacity,
                    "clickable": true,
                    "visible": true,
                    strokeColor: stroke,
                    strokeWeight: weight
                };
            });

            map.data.addListener('mouseover', function (event) {
                document.getElementById('wineCountry').textContent =
                    'Wines from ' + event.feature.getProperty('name');
            });

            map.data.addListener('mouseout', function (event) {
                document.getElementById('wineCountry').textContent =
                    'Country';
            });

            map.data.addListener('click', function (event) {
                var href = event.feature.getProperty('loc') === 'land' ? 'other-regions' : event.feature.getProperty('name');
                window.location = '/tag/' + href + '/';
            });
        }
        function loadScript() {
            var script = document.createElement('script');
            script.type = 'text/javascript';
            script.src = '//maps.googleapis.com/maps/api/js?v=3.15p&key=AIzaSyDOMHtHWjoAUj490saftIGY4UPz8XcvXiM&sensor=false&' +
                'callback=initialize';
            document.body.appendChild(script);
        }
        window.onload = loadScript;
    </script>

    <h3 id="wineCountry">Country</h3>
    <div id="wineRegionMap"></div>
</div>