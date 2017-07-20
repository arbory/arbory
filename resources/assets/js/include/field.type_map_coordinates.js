'use strict';

jQuery(document).ready($ => {
    let body = $('body');
    let pickers = $('.coordinate_picker');

    body.on('mapCoordinatesInit', () => {
        pickers.each(function() {
            let picker = {
                element: $(this)
            };

            picker.getCoordinates = function () {
                return this.convertToSemicolon(this.coordinatesInput.val());
            };

            picker.convertToSemicolon = function (value) {
                return value.replace(' ', '').replace(',', ';');
            };

            picker.hasValidInputLatLng = function () {
                let coordinates = this.getCoordinates();

                return coordinates.length >= 3 && coordinates.includes(';');
            };

            picker.getInputLatLng = function () {
                let coordinates = this.getCoordinates().split(';');

                return [
                    parseFloat(coordinates[0]),
                    parseFloat(coordinates[1])
                ]
            };

            picker.getCenterPosition = function () {
                if (this.hasValidInputLatLng()) {
                    let coordinates = this.getInputLatLng();

                    return {
                        lat: coordinates[0],
                        lng: coordinates[1]
                    };
                }

                return {
                    lat: this.element.data('latitude'),
                    lng: this.element.data('longitude')
                };
            };

            picker.moveMarkerToInputValue = function () {
                if (! picker.hasValidInputLatLng()) {
                    return;
                }

                let coordinates = this.getInputLatLng();
                this.marker.setPosition(new google.maps.LatLng(coordinates[0], coordinates[1]));
            };

            picker.writeCoordinates = function(marker) {
                this.coordinatesInput.val([
                    marker.position.lat(),
                    marker.position.lng()
                ].join(';'));
            };

            picker.bindEvents = function () {
                google.maps.event.addListener(this.map, 'click', event => {
                    this.marker.setPosition(event.latLng);
                    this.writeCoordinates(this.marker);
                });

                google.maps.event.addListener(this.marker, 'dragend', () => {
                    this.writeCoordinates(this.marker);
                });

                this.coordinatesInput.on('change', () => {
                    this.moveMarkerToInputValue();
                });
            };

            picker.initializeCanvas = function() {
                this.canvas = this.element.find('.canvas');
                this.coordinatesInput = this.element.find('.value input');

                this.map = new google.maps.Map(this.canvas[0], {
                    zoom: this.element.data('zoom'),
                    center: this.getCenterPosition()
                });

                this.marker = new google.maps.Marker({map: this.map, draggable: true});

                this.moveMarkerToInputValue();
                this.bindEvents();
            };

            picker.initializeCanvas();
        });
    });

    body.on('contentloaded', function () {
        $(this).trigger('mapCoordinatesInit');
    });
});