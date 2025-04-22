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
        const storedData = Cookies.get(this.storeName);

        if (typeof storedData === 'undefined') {
            this.save({});

            return {};
        }

        return JSON.parse(storedData);
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
        Cookies.set(this.storeName, JSON.stringify(data));
    }

    /**
     * @param {String} storeName
     * @returns {Store}
     */
    static getInstance(storeName) {
        return stores[storeName];
    }
}