
class Node {
    constructor(element) {
        this.element = element;

        this.id = element.data('id');
        this.level = element.data('level');
    }

    makeSortable(params) {
        return this.element.sortable(params);
    }

    getParent() {
        let parent = 'li[data-level=' + --this.level + ']';
        return new Node(this.element.closest(parent));
    }

    getLeftSibling() {
        return new Node(this.element.prev('li[data-id]'));
    }

    getRightSibling() {
        return new Node(this.element.next('li[data-id]'));
    }
}

jQuery(document).ready($ => {
    const token = $('[name=_token]:first').val();
    let container = $('.collection');
    let nodes = container.find('ul[data-level]');

    nodes.each(function () {
        let node = new Node($(this));

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