
class Sortable {
    constructor(field, params) {
        this.field = field;
        this.container = field.find('.body:first');
        this.sortable = this.container.sortable(params);
    }

    update() {
        let items = this.getItems();

        items.each((index) => {
            this.setLocationInput(items.eq(index), index);
        });
    }

    manualSort(event) {
        const itemSelector = 'fieldset.item';
        let button = jQuery(event.target);
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

        this.update();
    }

    setLocationInput(item, locationIndex) {
        let inputs = item.find('input');

        inputs.each((index) => {
            let input = inputs.eq(index);

            if (input.attr('id').includes(this.getSortByName())) {
                input.val(locationIndex);
            }
        });
    }

    getItems() {
        return this.container.find('> .item');
    }

    getSortByName() {
        return this.field.data('sortBy');
    }
}

jQuery(document).ready(() => {
    let fields = jQuery('.sortable');

    fields.each(function () {
        let field = jQuery(this);
        let sortable = new Sortable(field, {
            items: '> .item',
            update: () => sortable.update(),
            stop: (event, ui) => {
                ui.item.find('textarea').trigger('richtextresume');
            },
            start: (event, ui) => {
                ui.item.find('textarea').trigger('richtextsuspend');
            }
        });

        sortable.container.on('click', '.sortable-navigation .button', event => sortable.manualSort(event));

        sortable.container.on('DOMNodeInserted DOMNodeRemoved', () => sortable.update());
    });
});