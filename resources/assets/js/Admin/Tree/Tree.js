import Item from "./Item";
import Store from "./Store";

export default class Tree {
    /**
     * @param {HTMLElement} collectionElement
     * @param {String} repositionUrl
     * @param {String} storeName
     */
    constructor(collectionElement, repositionUrl = '', storeName = null) {
        this.repositionUrl = repositionUrl;
        this.collection = jQuery(collectionElement);
        this.store = storeName ? new Store(storeName) : null;

        this.initialize();
    }

    initialize() {
        this.collection.data('tree', this);

        jQuery('body').on('click', '.dialog .node-cell label', this.onDialogLabelClick.bind(this));

        jQuery(document).ready(() => {
            let items = this.collection.find('ul[data-level] > li');

            items.each((index, element) => {
                let item = new Item(jQuery(element), this.store);
                item.element.on('click', '> .collapser-cell > .collapser', () => item.toggleChildVisibility());

                // Note: this approach does not allow changing parent for nodes, need to add connectWith
                item.makeSortable({
                    items: '> li',
                    stop: (event, ui) => {
                        item = new Item(ui.item, this.store);

                        this.send(item);
                    }
                });
            });

        });
    }

    onDialogLabelClick(e) {
        jQuery('.dialog .node-cell label').removeClass('selected');
        jQuery(e.target).addClass('selected');
    }

    send(item) {
        jQuery.post(this.repositionUrl, {
            _token: Tree.getToken(),
            id: item.id,
            toLeftId: item.getLeftSibling().id,
            toRightId: item.getRightSibling().id
        });
    }

    /**
     * @returns {String}
     */
    static getToken() {
        return jQuery('[name=_token]:first').val();
    }
}