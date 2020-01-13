import Store from "./Store";

export default class Item {
    /**
     * @param {jQuery<HTMLElement>}element
     * @param {Store} store
     */
    constructor(element, store) {
        this.element = element;
        this.store = store;

        this.id = element.data('id');
        this.level = element.data('level');
    }

    /**
     * @returns {Store|null}
     */
    getStorage() {
        return this.store ? this.store.get(this.id) : null;
    }

    /**
     * @param {Object} params
     *
     * @returns {jQuery}
     */
    makeSortable(params) {
        return this.element.parent().sortable(params);
    }

    toggleChildVisibility() {
        let storage = this.getStorage();

        if(storage) {
            storage.setCollapsed(!storage.isCollapsed());
        }

        if (this.isCollapsed()) {
            this.element.removeClass('collapsed');
        } else {
            this.element.addClass('collapsed');
        }
    }

    /**
     * @returns {Boolean}
     */
    isCollapsed() {
        return this.element.hasClass('collapsed');
    }

    /**
     * @returns {Item}
     */
    getParent() {
        const parent = 'li[data-level=' + --this.level + ']';
        return new Item(this.element.closest(parent), this.store);
    }

    /**
     * @returns {Item}
     */
    getLeftSibling() {
        return new Item(this.element.prev('li[data-id]'), this.store);
    }

    /**
     * @returns {Item}
     */
    getRightSibling() {
        return new Item(this.element.next('li[data-id]'), this.store);
    }
}