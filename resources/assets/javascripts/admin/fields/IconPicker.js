
export default class IconPicker {
    constructor(element) {
        this.element = element;

        if (!jQuery(this.element).data('initialized')) {
            this.registerEventHandlers();
        }
    }

    registerEventHandlers() {
        let field = jQuery(this.element);
        let select = field.find('select');
        let picker = field.find('.value > .contents');
        let selected = picker.find('.selected');
        let items = picker.find('.items');

        selected.on('click', () => {
            items.toggleClass('active');
        });

        items.on('click', 'li', event => {
            let target = jQuery(event.target);
            let item = target.is('li') ? target : target.closest('li');
            let index = item.index();

            select.val(select.find('option').eq(index).val());

            selected.html(item.html());

            items.removeClass('active');
        });

        field.data('initialized', true);
    }
}