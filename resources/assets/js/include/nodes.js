// import 'jquery-ui/ui/widgets/sortable';
import Tree from '../Admin/Tree/Tree';

const COOKIE_NAME_NODES = 'nodes';

jQuery(document).ready(() => {
    const collection = jQuery('body.controller-nodes .collection');
    const tree = new Tree(
        collection,
        collection.data('reposition-url') || window.NODE_REPOSITION_BASEPATH,
        collection.data('store-name') || COOKIE_NAME_NODES
    );
});
