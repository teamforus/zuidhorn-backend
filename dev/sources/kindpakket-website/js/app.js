$(function() {
    return ;

    var locations = [];
    var markers = [];

    $('.blocks-item').unbind('click').bind('click', function() {
        $(this).toggleClass('checked');

        if ($(this).hasClass("checked") && !$(this).hasClass("newItem")) {

            $(this).addClass('newItem');

            $(this).removeClass('checked');

            var lat = $(this).parent().attr('data-lg');

            var lg = $(this).parent().attr('data-lt');

            locations.push([lat, lg]);

            var element = $(this).parent().clone();

            $('.blocks.selected').append(element);

            $(this).parent().fadeTo("slow", 0.5, function() { //fade
                $(this).slideDown("slow", function() { //slide up
                    $(this).remove(); //then remove from the DOM
                });
            });

            /*$(this).parent().remove();*/

            initialize('map-canvas-contact');

            $('.map').addClass('map-show');
        } else if ($(this).hasClass("newItem") && $(".blocks.selected").html().length > 0) {

            $(this).removeClass('newItem');

            $(this).removeClass('checked');

            var element = $(this).parent().clone();

            $('.blocks.initial').append(element);

            $(this).parent().remove();

            var lat = $(this).parent().attr('data-lg');

            var lg = $(this).parent().attr('data-lt');

            initialize('map-canvas-contact');

            if ($(".blocks.selected").is(':empty')) {
                $('.map').removeClass('map-show');
            }
        }
    });

    $(function() {
        if ($('#map-canvas-contact').length == 1) {
            initialize('map-canvas-contact');
        }
    });

    function initialize(obj) {
        var stylesArray = {
            'style-1': {
                'style': [{
                    "featureType": "water",
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#e9e9e9"
                    }, {
                        "lightness": 17
                    }]
                }, {
                    "featureType": "landscape",
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#f5f5f5"
                    }, {
                        "lightness": 20
                    }]
                }, {
                    "featureType": "road.highway",
                    "elementType": "geometry.fill",
                    "stylers": [{
                        "color": "#ffffff"
                    }, {
                        "lightness": 17
                    }]
                }, {
                    "featureType": "road.highway",
                    "elementType": "geometry.stroke",
                    "stylers": [{
                        "color": "#ffffff"
                    }, {
                        "lightness": 29
                    }, {
                        "weight": 0.2
                    }]
                }, {
                    "featureType": "road.arterial",
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#ffffff"
                    }, {
                        "lightness": 18
                    }]
                }, {
                    "featureType": "road.local",
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#ffffff"
                    }, {
                        "lightness": 16
                    }]
                }, {
                    "featureType": "poi",
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#f5f5f5"
                    }, {
                        "lightness": 21
                    }]
                }, {
                    "featureType": "poi.park",
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#dedede"
                    }, {
                        "lightness": 21
                    }]
                }, {
                    "elementType": "labels.text.stroke",
                    "stylers": [{
                        "visibility": "on"
                    }, {
                        "color": "#ffffff"
                    }, {
                        "lightness": 16
                    }]
                }, {
                    "elementType": "labels.text.fill",
                    "stylers": [{
                        "saturation": 36
                    }, {
                        "color": "#333333"
                    }, {
                        "lightness": 40
                    }]
                }, {
                    "elementType": "labels.icon",
                    "stylers": [{
                        "visibility": "off"
                    }]
                }, {
                    "featureType": "transit",
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#f2f2f2"
                    }, {
                        "lightness": 19
                    }]
                }, {
                    "featureType": "administrative",
                    "elementType": "geometry.fill",
                    "stylers": [{
                        "color": "#fefefe"
                    }, {
                        "lightness": 20
                    }]
                }, {
                    "featureType": "administrative",
                    "elementType": "geometry.stroke",
                    "stylers": [{
                        "color": "#fefefe"
                    }, {
                        "lightness": 17
                    }, {
                        "weight": 1.2
                    }]
                }]
            }
        }

        /*var lat = $('#'+obj).attr("data-lat");
        var lng = $('#'+obj).attr("data-lng");*/
        var contentString = $('#' + obj).attr("data-string");
        /*var myLatlng = new google.maps.LatLng(lat,lng);*/
        var map, marker, infowindow;
        var image = $('#' + obj).attr("data-marker");
        var zoomLevel = parseInt($('#' + obj).attr("data-zoom"), 18);
        var styles = stylesArray[$('#map-canvas-contact').attr("data-style")]['style'];
        var styledMap = new google.maps.StyledMapType(styles, {
            name: "Styled Map"
        });

        var mapOptions = {
            zoom: zoomLevel,
            disableDefaultUI: true,
            center: new google.maps.LatLng(-33.92, 151.25),
            scrollwheel: false,
            mapTypeControlOptions: {
                mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
            }
        }

        map = new google.maps.Map(document.getElementById(obj), mapOptions);

        map.mapTypes.set('map_style', styledMap);
        map.setMapTypeId('map_style');

        infowindow = new google.maps.InfoWindow();

        for (var i = 0; i < locations.length; i++) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][0], locations[i][1]),
                map: map,
                icon: image
            });

            markers.push(marker);

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infowindow.setContent('<div class="map-info">' +
                        '<div class="map-info__img">' +
                        '<img class="img-responsive" src="assets/img/personal-shop.png" alt="" />' +
                        '</div>' +
                        '<div class="map-info__text">' +
                        '<div class="map-info__h">' +
                        '<h3> Lidl Groningen </h3>' +
                        '</div>' +
                        '<div class="map-info__desc">' +
                        '<p> Address: Zuiderweg 48, 9745 AE Groningen </p>' +
                        '<p> Openingstijden: 8–21 </p>' +
                        '<p> Telephone: +31 20 709 5039 </p>' +
                        '<a href="" class="btn-2"> Apply </a>' +
                        '</div>' +
                        '</div>' +
                        '</div>');
                    infowindow.open(map, marker);
                }
            })(marker, i));
        }
    }

    // Modals
    $('[data-toggle="login"]').on('click', function() {
        $('body').addClass('has-modal');
        $('.popup').addClass('act');
        $('.popup-block').hide();
        $('.login').show();
    });

    $('[data-toggle="voucher"]').on('click', function() {
        $('body').addClass('has-modal');
        $('.popup').addClass('act');
        $('.popup-block').hide();
        $('.voucher').show();
    });

    $('[data-toggle="close-popup"]').on('click', function() {
        $('body').removeClass('has-modal');
        $('.popup').removeClass('act');
        $('.popup-block').hide();
    });
});