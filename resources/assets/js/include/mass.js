
const COOKIE_NAME_NODES = 'bulk';

jQuery(document).ready(($) => {

    let bulkGrid = $('.bulk-edit-grid'),
        bulkActions,
        bulkEditRows,
        bulkEditHeader,
        bulkUrl;

    function modifyUrl() {
        bulkActions.attr('href', getUrlwithIds)
    }

    function getUrlwithIds(){
        let ids = bulkEditRows.filter(':checked').serializeArray();
        return bulkUrl + ( bulkUrl.indexOf('?') >= 0 ? '&' : '?' ) + $.param(ids);
    }

    function allChecked() {
        bulkEditRows.prop("checked", this.checked);
        modifyUrl();
    }

    function updateSelectors(){
        bulkActions = $('.js-bulk-edit-button', bulkGrid);
        bulkUrl = bulkActions.attr('href');
        bulkEditRows = $('.js-bulk-edit-row-checkbox', bulkGrid);
        bulkEditHeader = $('.js-bulk-edit-header-checkbox', bulkGrid);
    }

    function prepareFormEvents(target){
        target.find('input.bulk-control').on('change', function(e){
            target.find('[name="resource['+$(this).attr('data-target')+']"]').prop("disabled", !this.checked);
        });
    }

    function prepareGridEvents(){
        bulkEditRows.on('change', modifyUrl);
        bulkEditHeader.on('change', allChecked);
    }

    $('body').on('contentloaded', function(e, event_params) {

        if(bulkGrid.length){
            updateSelectors();
            prepareGridEvents();
        }
        $(e.target).trigger('bulkforminit', event_params);
    });

    $(document).bind('bulkforminit', function( e )
    {
        let target = $(e.target);
        target = target.find('.edit-resources');
        prepareFormEvents(target);
    });

});