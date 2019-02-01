import RichText, {CONFIG_EDITOR} from "./Fields/RichText";
import RichTextCompact, {CONFIG_EDITOR_COMPACT} from "./Fields/RichTextCompact";
import IconPicker from "./Fields/IconPicker";
import Sortable, {CONFIG_JQUERY_SORTABLE} from "./Fields/Sortable";
import Slug from "./Fields/Slug";
import ObjectRelation from "./Fields/ObjectRelation";
import MapCoordinates from "./Fields/MapCoordinates";
import File from "./Fields/File";

export let FIELD_TYPE_DEFINITIONS = [
    {
        handler: RichText,
        config: CONFIG_EDITOR,
        selector: '.type-richText.full'
    },
    {
        handler: RichTextCompact,
        config: CONFIG_EDITOR_COMPACT,
        selector: '.type-richText.compact'
    },
    {
        handler: IconPicker,
        selector: '.type-icon-picker'
    },
    {
        handler: Sortable,
        config: {
            vendor: CONFIG_JQUERY_SORTABLE
        },
        selector: '.type-sortable'
    },
    {
        handler: Slug,
        selector: '.type-slug > .localization'
    },
    {
        handler: Slug,
        selector: '.type-slug:not(.i18n)'
    },
    {
        handler: ObjectRelation,
        selector: ObjectRelation.getSelector()
    },
    {
        handler: MapCoordinates,
        selector: '.type-map-coordinates'
    },
    {
        handler: File,
        selector: '.type-file'
    }
];

class Definition {
    /**
     * @param {Object} data
     */
    constructor(data) {
        this._handler = data.handler;
        this._config = data.config;
        this._selector = data.selector;
    }

    /**
     * @return {string}
     */
    get selector() {
        return this._selector;
    }

    /**
     * @param {string} value
     */
    set selector(value) {
        this._selector = value;
    }

    /**
     * @return {Object}
     */
    get config() {
        return this._config;
    }

    /**
     * @param {Object} value
     */
    set config(value) {
        this._config = value;
    }

    /**
     * @return {Object}
     */
    get handler() {
        return this._handler;
    }

    /**
     * @param {Object} value
     */
    set handler(value) {
        this._handler = value;
    }
}

class FieldRegistry {
    /**
     * @param {Array} data
     */
    constructor(data) {
        let definitions = [];

        for (let definition of data) {
            definitions.push(new Definition(definition));
        }

        this._definitions = definitions;
    }

    /**
     * @return {Array<Definition>}
     */
    get definitions() {
        return this._definitions;
    }

    /**
     * @param field
     * @return {?Definition}
     */
    getDefinition(field) {
        for (let definition of this.definitions) {
            if (definition.handler === field) {
                return definition;
            }
        }

        return null;
    }
}

export default new FieldRegistry(FIELD_TYPE_DEFINITIONS);
