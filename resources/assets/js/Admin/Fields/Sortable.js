import "jquery-ui/ui/widgets/sortable";

export const CONFIG_JQUERY_SORTABLE = {
    items: '> .item',
};

export default class Sortable {
    constructor(element, config) {
        this.element = element;
        this.config = config;

        this.container = jQuery(this.element).find('.body:first')[0];

        this.registerEventHandlers();
    }

    registerEventHandlers() {
        let container = jQuery(this.container);
        let handlers = {};

        handlers.update = this.handleUpdate();

        handlers.stop = (event, ui) => {
            ui.item.find('textarea').trigger('richtextresume');
        };

        handlers.start = (event, ui) => {
            ui.item.find('textarea').trigger('richtextsuspend');
        };

        this.sortable = jQuery(this.container).sortable(Object.assign(
            handlers, this.config.vendor
        ));

        // Normal has many layout
        container.on('click', '> .item > .sortable-navigation .button', event => this.manualSort(event));
        // Panel layouts
        container.on('click', '> .item > header .sortable-navigation.button', event => this.manualSort(event));
        container.on('DOMNodeInserted DOMNodeRemoved', () => this.handleUpdate());
    }

    handleUpdate() {
        let items = this.getItems();

        items.each((index) => {
            this.setLocationInput(items.eq(index), index);
        });
    }

    manualSort(event) {
        const itemSelector = '.item';
        let target = jQuery(event.target);
        let button = target.is('button') ? target : target.closest('button');
        let item = button.closest(itemSelector);
        let text = item.find('textarea');

        text.trigger('richtextsuspend');

        if (button.hasClass('move-down')) {
            item.insertAfter(item.next(itemSelector));
        }

        if (button.hasClass('move-up')) {
            item.insertBefore(item.prev(itemSelector));
        }

        text.trigger('richtextresume');

        this.handleUpdate();
    }

    setLocationInput(item, locationIndex) {
        let sortByName = this.getSortByName();

        let contentSelector ='';

        if(item.is('.panel')) {
            // Expects that the position input in constructor is always a direct descendant of the fieldset.item entry
            contentSelector = '> .content >';
        }

        let positionInput = item.find(`${contentSelector} input[data-name="${sortByName}"]`).first();

        if(positionInput.length) {
            positionInput.val(locationIndex);
        }
    }

    getItems() {
        return jQuery(this.container).find('> .item');
    }

    getSortByName() {
        return jQuery(this.element).data('sortBy');
    }
}