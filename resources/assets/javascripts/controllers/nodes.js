
const COOKIE_NAME_NODES = 'nodes';

class Node {
    constructor(element) {
        this.element = element;

        this.id = element.data('id');
        this.level = element.data('level');
        this.store = NodeStore.get(this.id);
    }

    makeSortable(params) {
        return this.element.parent().sortable(params);
    }

    toggleChildVisibility() {
        this.store.setCollapsed(! this.store.isCollapsed());

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
            stored[id] = new NodeStoreItem({id: id});
            NodeStore.save(stored);
        }

        return new NodeStoreItem(stored[id]);
    }

    static saveItem(store) {
        let stored = this.getStored();

        stored[store.id] = store;

        NodeStore.save(stored);
    }

    static save(data) {
        jQuery.cookie(COOKIE_NAME_NODES, JSON.stringify(data));
    }
}

class NodeStoreItem {
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
        NodeStore.saveItem(this);
    }
}

class SlugApiHandler {
    constructor(apiUrl) {
        this.apiUrl = apiUrl;
    }

    create(string) {
        return jQuery.get( this.apiUrl, { name: string } );
    }
}

jQuery(document).ready(() => {
    let body = jQuery('body.controller-nodes');
    let collection = jQuery('.collection');
    let form = jQuery('#edit-resource');

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

    form.ready(() => {
        let slugField  = form.find('.field[data-name=slug]');

        let nameInput = jQuery('#resource_name');
        let slugInput = jQuery('#resource_slug');

        let generateButton = slugField.find('.button.generate');
        let slugLink = slugField.find('.link');

        let slugApi = new SlugApiHandler(slugInput.data('generatorUrl'));

        let updateSlugLink = () => {
            slugLink.find('span:last').text(encodeURIComponent(slugInput.val()));
        };

        let generateSlug = () => {
            slugApi.create(nameInput.val()).done((value) => {
                slugInput.val(value);
                updateSlugLink();
            });
        };

        nameInput.on('blur', () => {
            if (! slugInput.val().length) {
                generateSlug();
            }
        });

        generateButton.on('click', () => generateSlug());
        slugInput.on('keyup', () => updateSlugLink());
    });
});