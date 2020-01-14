import Cookies from "js-cookie";
import StoreItem from "./StoreItem";

export const stores = {};

export default class Store {
    /**
     * @param {string} storeName
     */
    constructor(storeName) {
        this.storeName = storeName;

        stores[storeName] = this;
    }

    /**
     * @returns {Object}
     */
    getStored() {
        const storedData = Cookies.getJSON(this.storeName);

        if (typeof storedData !== 'object') {
            this.save({});

            return {};
        }

        return storedData;
    }

    /**
     * @param {*} id
     * @returns {NodeStoreItem}
     */
    get(id) {
        let stored = this.getStored();

        if (typeof stored[id] === 'undefined') {
            stored[id] = true;

            this.save(stored);
        }

        return new StoreItem(id, stored[id], this);
    }

    /**
     * @param {StoreItem} store
     */
    saveItem(store) {
        let stored = this.getStored();

        stored[store.id] = store.getContents();

        this.save(stored);
    }

    /**
     * @param {*} data
     */
    save(data) {
        Cookies.set(this.storeName, data);
    }

    /**
     * @param {String} storeName
     * @returns {Store}
     */
    static getInstance(storeName) {
        return stores[storeName];
    }
}