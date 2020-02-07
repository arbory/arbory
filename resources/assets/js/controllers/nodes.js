
const COOKIE_NAME_NODES = 'nodes';

class Node {
    constructor(element) {
        this.element = element;

        this.id = element.data('id');
        this.level = element.data('level');
    }

    getStorage() {
        return NodeStore.get(this.id);
    }

    makeSortable(params) {
        return this.element.parent().sortable(params);
    }

    toggleChildVisibility() {
        this.getStorage().setCollapsed(! this.getStorage().isCollapsed());

        if (this.isCollapsed()) {
            this.element.removeClass('collapsed');
        } else {
            this.element.addClass('collapsed');
        }
    }

    isCollapsed() {
        return this.element.hasClass('collapsed');
    }

    getParent() {
        const parent = 'li[data-level=' + --this.level + ']';
        return new Node(this.element.closest(parent));
    }

    getLeftSibling() {
        return new Node(this.element.prev('li[data-id]'));
    }

    getRightSibling() {
        return new Node(this.element.next('li[data-id]'));
    }
}

class NodeStore {
    static getStored() {
        if (typeof jQuery.cookie(COOKIE_NAME_NODES) === 'undefined') {
            NodeStore.save({});
        }

        return JSON.parse(jQuery.cookie(COOKIE_NAME_NODES));
    }

    static get(id) {
        let stored = this.getStored();

        if (typeof stored[id] === 'undefined') {
            stored[id] = true;

            NodeStore.save(stored);
        }

        return new NodeStoreItem(id, stored[id]);
    }

    static saveItem(store) {
        let stored = this.getStored();

        stored[store.id] = store.getContents();

        NodeStore.save(stored);
    }

    static save(data) {
        jQuery.cookie(COOKIE_NAME_NODES, JSON.stringify(data));
    }
}

class NodeStoreItem {
    constructor(id, contents = {}) {
        this.id = id;
        this.contents = contents;
    }

    getContents() {
        return this.contents;
    }

    isCollapsed() {
        return this.contents;
    }

    setCollapsed(state) {
        this.contents = state;

        this.save();
    }

    save() {
        NodeStore.saveItem(this);
    }
}

jQuery(document).ready(() => {
    let body = jQuery('body.controller-nodes');
    let collection = jQuery('.collection');

    body.on('click', '.dialog .node-cell label', function() {
        jQuery('.dialog .node-cell label').removeClass('selected');
        jQuery(this).addClass('selected');
    });

    collection.ready(() => {
        const token = jQuery('[name=_token]:first').val();

        let nodes = collection.find('ul[data-level] > li');

        nodes.each(function () {
            let node = new Node(jQuery(this));

            node.element.on('click', '> .collapser-cell > .collapser', () => node.toggleChildVisibility());

            node.makeSortable({
                items: '> li',
                stop: (event, ui) => {
                    node = new Node(ui.item);

                    jQuery.post('/admin/nodes/api/node_reposition', {
                        _token: token,
                        id: node.id,
                        toLeftId: node.getLeftSibling().id,
                        toRightId: node.getRightSibling().id
                    });
                }
            });
        });
    });
});