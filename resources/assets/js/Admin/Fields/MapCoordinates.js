export default class MapCoordinates {
    /**
     * @param element
     * @param {Object} config
     */
    constructor(element, config) {
        this.element = element;
        this.config = config;

        this.registerEventHandlers();
    }

    /**
     * @return {void}
     */
    registerEventHandlers() {
        let field = this.getField();

        this.canvas = field.find('.canvas');
        this.coordinatesInput = this.getInput();

        this.map = new google.maps.Map(this.canvas[0], {
            zoom: this.coordinatesInput.data('zoom'),
            center: this.getCenterPosition()
        });

        google.maps.event.addDomListener(this.canvas[0], 'mousedown', function(e) {
            e.stopPropagation();
        });

        this.marker = new google.maps.Marker({
            map: this.map,
            draggable: this.isInteractive() ,
        });

        this.search = new google.maps.places.SearchBox(this.getSearchField()[0]);

        this.moveMarkerToInputValue();
        this.bindEvents();
    }

    bindEvents() {
        google.maps.event.addListener(this.map, 'click', event => {
            if(!this.isInteractive()) {
                return false;
            }

            this.clearSearch();
            this.marker.setPosition(event.latLng);
            this.writeCoordinates(this.marker);
        });

        google.maps.event.addListener(this.marker, 'dragend', () => {
            this.clearSearch();
            this.writeCoordinates(this.marker);
        });

        this.coordinatesInput.on('change', () => {
            this.moveMarkerToInputValue();
        });

        this.map.addListener('bounds_changed', () => {
            this.search.setBounds(this.map.getBounds());
        });

        this.search.addListener('places_changed', () => {
            if(!this.isInteractive()) {
                return false;
            }

            let places = this.search.getPlaces();

            if (places.length === 0) {
                return;
            }

            let place = places[0];

            this.map.setCenter(place.geometry.location);
            this.marker.setPosition(place.geometry.location);

            this.writeCoordinates(this.marker);
        });
    }

    getCoordinates() {
        return this.convertToSemicolon(this.coordinatesInput.val());
    }

    convertToSemicolon(value) {
        return value.replace(' ', '').replace(',', ';');
    }

    hasValidInputLatLng() {
        let coordinates = this.getCoordinates();

        return coordinates.length >= 3 && coordinates.includes(';');
    }

    getInputLatLng() {
        let coordinates = this.getCoordinates().split(';');

        return [
            parseFloat(coordinates[0]),
            parseFloat(coordinates[1])
        ]
    }

    getCenterPosition() {
        let field = this.getField();
        let input = this.getInput();

        if (this.hasValidInputLatLng()) {
            let coordinates = this.getInputLatLng();

            return {
                lat: coordinates[0],
                lng: coordinates[1]
            };
        }

        return {
            lat: input.data('latitude'),
            lng: input.data('longitude')
        };
    }

    moveMarkerToInputValue() {
        if (! this.hasValidInputLatLng()) {
            return;
        }

        let coordinates = this.getInputLatLng();
        this.marker.setPosition(new google.maps.LatLng(coordinates[0], coordinates[1]));
    }

    writeCoordinates(marker) {
        this.coordinatesInput.val([
            marker.position.lat(),
            marker.position.lng()
        ].join(';'));
    }

    clearSearch() {
        this.getSearchField().val(null);
    }

    getSearchField() {
        return this.getField().find('.search-input');
    }

    getField() {
        return jQuery(this.element);
    }

    getInput() {
        let field = this.getField();

        return field.find(`input.coordinates-input`);
    }

    isInteractive() {
        let disabled = this.getInput().prop('disabled');
        let interactive = $(this.element).data('interactive');

        return interactive && !disabled;
    }
}
