
class Sortable {
    constructor(field, container) {
        this.field = field;
        this.container = container;
    }

    update() {
        let items = this.getItems();

        items.each((index) => {
            this.setLocationInput(items.eq(index), index);
        });
    }

    manualSort(event) {
        const itemSelector = 'fieldset.item';
        let button = $(event.target);
        let item = button.closest(itemSelector);

        if (button.hasClass('move-down')) {
            item.insertAfter(item.next(itemSelector));
        }

        if (button.hasClass('move-up')) {
            item.insertBefore(item.prev(itemSelector));
        }

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

jQuery(document).ready($ => {
    let fields = $('.sortable');

    fields.each(function () {
        let field = $(this);
        let container = $(this).find('.body:first');
        let sortable = new Sortable(field, container);

        container.on('click', '.sortable-navigation .button', event => sortable.manualSort(event));

        container.on('DOMNodeInserted DOMNodeRemoved', () => sortable.update());

        container.sortable({
            items: '> .item',
            update: () => sortable.update(),
            stop: (event, ui) => {
                ui.item.find('textarea').trigger('richtextresume');
            },
            start: (event, ui) => {
                ui.item.find('textarea').trigger('richtextsuspend');
            }
        });
    });
});