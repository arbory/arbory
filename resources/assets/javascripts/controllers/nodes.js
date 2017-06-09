
const COOKIE_NAME_NODES = 'nodes';
let body = jQuery('body.controller-nodes');

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

body.ready($ => {
    const token = $('[name=_token]:first').val();
    let container = $('.collection');
    let nodes = container.find('ul[data-level] > li');

    nodes.each(function () {
        let node = new Node($(this));

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

jQuery(function()
{
    var body = jQuery('body.controller-nodes');

    body.on('contentloaded', function(e)
    {
        var block = jQuery(e.target);

        // slug generation
        var name_input  = block.find('.field[data-name="name"] input');
        var slug_field  = block.find('.field[data-name="slug"]');

        if (name_input.length && slug_field.length)
        {
            var slug_input  = slug_field.find('input');
            var slug_button = slug_field.find('.generate');
            var slug_link   = slug_field.find('a');

            slug_input.on('sluggenerate', function()
            {
                var url = slug_input.attr('data-generator-url');

                slug_button.trigger('loadingstart');
                jQuery.get( url, { name: name_input.val() }, function( slug )
                {
                    slug_input.val( slug );
                    slug_link.find('span').text( encodeURIComponent( slug ) );
                    slug_button.trigger('loadingend');
                }, 'text');
            });

            slug_button.click(function()
            {
                slug_input.trigger('sluggenerate');
            });

            if (name_input.val() === '')
            {
                // bind onchange slug generation only if starting out with an empty name
                name_input.change(function()
                {
                    slug_input.trigger('sluggenerate');
                });
            }
        }

    });

    body.on('click', '.dialog .node-cell label', function() {
        jQuery('.dialog .node-cell label').removeClass('selected');
        jQuery(this).addClass('selected');
    });
});
