
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
        let container = $(this).find('.body');
        let sortable = new Sortable(field, container);

        container.on('DOMNodeInserted DOMNodeRemoved', () => sortable.update());
        container.sortable({update: () => sortable.update()});
    });
});