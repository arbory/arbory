
const COOKIE_NAME_MENU = 'menu';
const SELECTOR_MENU_ITEM = 'li[data-name]';

class Menu {
    constructor(element) {
        this.element = element;
    }

    getItemElements() {
        return this.element.find(SELECTOR_MENU_ITEM);
    }

    getItems() {
        let items = [];

        jQuery.each(this.getItemElements(),  (key, element) => {
            items.push(new MenuItem(this, jQuery(element)));
        });

        return items;
    }

    collapseItems() {
        jQuery.each(this.getItems(), (key, menuItem) => {
            menuItem.collapseItems();
        });
    }

    isCompact() {
        return this.element.closest('body').hasClass('side-compact');
    }
}

class MenuItem {
    constructor(menu, element) {
        this.menu = menu;
        this.element = element;
        this.name = element.data('name');
        this.stored = MenuStore.get(this.name);
    }

    getChildBlockElement() {
        return this.element.find('.block:first');
    }

    getChildElements() {
        return this.getChildBlockElement().children(SELECTOR_MENU_ITEM);
    }

    hasChildren() {
        return this.getChildElements().length;
    }

    getIconElement() {
        // TODO: fix typo
        return this.element.children('.trigger').find('.collapser i');
    }

    toggleItems() {
        if (! this.hasChildren()) {
            return;
        }

        if (this.menu.isCompact()) {
            this.menu.collapseItems();
        }
        
        this.isCollapsed() ? this.expandItems() : this.collapseItems();
    }

    isCollapsed() {
        return this.menu.isCompact() ?
                !this.element.hasClass('open') :
                this.element.hasClass('collapsed');
    }

    collapseItems() {
        this.menu.isCompact() ?
            this.element.removeClass('open') :
            this.element.addClass('collapsed');
    }

    expandItems() {
        this.menu.isCompact() ?
            this.element.addClass('open') :
            this.element.removeClass('collapsed');
    }

    updateIcon() {
        let icon = this.getIconElement();

        icon.toggleClass('fa-chevron-right', this.menu.isCompact());

        if (! this.menu.isCompact()) {
            icon.toggleClass('fa-chevron-down', this.isCollapsed());
            icon.toggleClass('fa-chevron-up', !this.isCollapsed());
        }
    }
}

class MenuStore {
    static getStored() {
        if (typeof jQuery.cookie(COOKIE_NAME_MENU) === 'undefined') {
            MenuStore.save({});
        }

        return JSON.parse(jQuery.cookie(COOKIE_NAME_MENU));
    }

    static get(id) {
        let stored = this.getStored();

        if (typeof stored[id] === 'undefined') {
            stored[id] = new MenuStoreItem({id: id});
            MenuStore.save(stored);
        }

        return new MenuStoreItem(stored[id]);
    }

    static saveItem(store) {
        let stored = this.getStored();

        stored[store.id] = store;

        MenuStore.save(stored);
    }

    static save(data) {
        jQuery.cookie(COOKIE_NAME_MENU, JSON.stringify(data));
    }
}

class MenuStoreItem {
    constructor(stored) {
        this.id = stored.id;
        this.collapsed = stored.collapsed;
    }

    isCollapsed() {
        return this.collapsed;
    }

    setCollapsed(state) {
        this.collapsed = state;
        this.save();
    }

    save() {
        MenuStore.saveItem(this);
    }
}

jQuery(document).ready(function () {
    let menu = new Menu(jQuery('aside nav > ul'));

    jQuery.each(menu.getItems(), (key, menuItem) => {
        menuItem.updateIcon();

        menuItem.element.find('.trigger:first').on('click', () => {
            menuItem.toggleItems();
            menuItem.updateIcon();

            if (! menu.isCompact()) {
                menuItem.stored.setCollapsed(menuItem.isCollapsed());
            }
        });
    });
});