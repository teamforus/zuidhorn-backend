kindpakketApp.directive('googleMap', [
    'GoogleMapService',
    function(
        GoogleMapService
    ) {
        // Runs during compile
        return {
            // name: '',
            // priority: 1,
            // terminal: true,
            // scope: {}, // {} = isolate, true = child, false/undefined = no change
            // controller: function($scope, $element, $attrs, $transclude) {},
            // require: 'ngModel', // Array = multiple requires, ? = optional, ^ = check parent elements
            restrict: 'A', // E = Element, A = Attribute, C = Class, M = Comment
            // template: '',
            templateUrl: './tpl/directives/google-map.html',
            replace: true,
            transclude: true,
            // compile: function(tElement, tAttrs, function transclude(function(scope, cloneLinkingFn){ return function linking(scope, elm, attrs){}})),
            link: function($scope, iElm, iAttrs, controller) {

                $scope.style = [];
                $scope.locations = [];
                $scope.markers = [];

                var initialize = function(obj) {
                        var office = $scope.locations.length ? $scope.locations[0] : false;
                        var $element = $(iElm).find('.map-canvas');
                        var contentString = $element.attr("data-string");
                        var map, marker, infowindow;
                        var image = $element.attr("data-marker");
                        var zoomLevel = parseInt($element.attr("data-zoom"), 8);
                        var styledMap = new google.maps.StyledMapType($scope.style, {
                            name: "Styled Map"
                        });

                        var mapOptions = {
                            zoom: zoomLevel,
                            disableDefaultUI: true,
                            center: office ? new google.maps.LatLng(office.lat, office.lon) : new google.maps.LatLng(-33.92, 151.25),
                            scrollwheel: true,
                            mapTypeControlOptions: {
                                mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
                            }
                        }

                        map = new google.maps.Map(document.getElementById(obj), mapOptions);

                        map.mapTypes.set('map_style', styledMap);
                        map.setMapTypeId('map_style');

                        infowindow = new google.maps.InfoWindow();


                        $scope.locations.forEach(function(office) {
                            marker = new google.maps.Marker({
                                position: new google.maps.LatLng(office.lat, office.lon),
                                map: map,
                                icon: image
                            });

                            $scope.markers.push(marker);

                            google.maps.event.addListener(marker, 'click', (function(marker, office) {
                                var description = [
                                'Address: ' + office.address, 
                                'Telephone: ' + office.shopkeeper.phone, 
                                'Categories: ' + office.shopkeeper.categories, 
                                ];

                                return function() {
                                    infowindow.setContent(
                                        '<div class="map-card">\
                                    <img class="map-card-img" src="' + (office.preview || 'assets/img/office-photo-sample.jpg') + '" alt=""/>\
                                    <div class="map-card-title">' + office.shopkeeper.name + '</div>\
                                    <div class="map-card-description">' + description.join('<br />') + '</div>\
                                    <div class="map-card-actions">\
                                    <!--<a class="button button-success" href="#">Apply</a>-->\
                                    </div>\
                                    </div>');
                                    infowindow.open(map, marker);
                                }
                            })(marker, office));
                        });
                    }

                $scope.updatePoints = function() {
                    $scope.locations = {};

                    var categories = $scope.categories.filter(function(category) {
                        return category.selected && category.shopkeepers.length;
                    });

                    if (categories.length == 0)
                        categories = $scope.categories;

                    categories.forEach(function(category) {
                        category.shopkeepers.forEach(function(shopkeeper) {
                            shopkeeper.offices.forEach(function(office) {
                                if ($scope.locations[office.id])
                                    return;

                                $scope.locations[office.id] = JSON.parse(JSON.stringify(office));
                                $scope.locations[office.id].shopkeeper = JSON.parse(JSON.stringify(shopkeeper));
                                $scope.locations[office.id].category = JSON.parse(JSON.stringify(category));
                            });
                        });
                    });

                    $scope.locations = Object.values($scope.locations);

                    initialize('map-canvas-contact');
                };

                GoogleMapService.getStyle().then(function(style) {
                    $scope.style = style.style;
                    initialize('map-canvas-contact');
                });
            }
        };
    }
]);