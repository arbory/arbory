const COOKIE_NAME_NODES = 'bulk';

jQuery(document).ready(($) => {

    /**
     * @type {*|jQuery|HTMLElement}
     */
    let bulkGrid = $('.bulk-edit-grid');
    let bulkActions;
    let bulkEditRows;
    let bulkEditHeader;
    let bulkUrl;

    /**
     * Modify action url
     */
    function modifyUrl() {
        bulkActions.attr('href', getUrlWithIds)
    }

    /**
     * Serialize all checkboxes and concatenate with url
     * @returns {string}
     */
    function getUrlWithIds() {
        let ids = bulkEditRows.filter(':checked').serializeArray();
        return bulkUrl + (bulkUrl.indexOf('?') >= 0 ? '&' : '?') + $.param(ids);
    }

    /**
     * Check all rows if header checkbox is checked
     */
    function allChecked() {
        bulkEditRows.prop("checked", this.checked);
        modifyUrl();
    }

    /**
     * Useful when DOM changed
     */
    function updateSelectors() {
        bulkActions = $('.js-bulk-edit-button', bulkGrid);
        bulkUrl = bulkActions.attr('href');
        bulkEditRows = $('.js-bulk-edit-row-checkbox', bulkGrid);
        bulkEditHeader = $('.js-bulk-edit-header-checkbox', bulkGrid);
    }

    /**
     * Disable/enable form fields
     * @param target
     */
    function prepareFormEvents(target) {
        target.find('input.bulk-control').on('change', function (e) {
            target.find('[name="resource[' + $(this).attr('data-target') + ']"]').prop("disabled", !this.checked);
        });
    }

    /**
     * Events for grid checkboxes
     */
    function prepareGridEvents() {
        bulkEditRows.on('change', modifyUrl);
        bulkEditHeader.on('change', allChecked);
    }

    /**
     * Init grid events and try to init bulk form
     */
    $('body').on('contentloaded', function (e, event_params) {
        $(e.target).trigger('bulkforminit', event_params);
    });

    /**
     * Bulk edit form event
     */
    $(document).bind('bulkforminit', function (e) {
        let target = $(e.target);

        if (bulkGrid.length) {
            updateSelectors();
            prepareGridEvents();
        }
        target = target.find('.edit-resources');
        prepareFormEvents(target);
    });

});