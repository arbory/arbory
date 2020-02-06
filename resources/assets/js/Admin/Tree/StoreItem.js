export default class NodeStoreItem {
    /**
     *
     * @param id
     * @param contents
     * @param store
     */
    constructor(id, contents = {}, store) {
        this.id = id;
        this.contents = contents;
        this.store = store;
    }

    /**
     * @returns {*}
     */
    getContents() {
        return this.contents;
    }

    /**
     * @returns {Boolean}
     */
    isCollapsed() {
        return this.contents;
    }

    setCollapsed(state) {
        this.contents = state;

        this.save();
    }

    save() {
        if(this.store) {
            this.store.saveItem( this );
        }
    }
}