const SELECT_CLASS = 'permissions_select_all';
const UNSELECT_CLASS = 'permissions_select_none';
const CHECK_TRIGGERS = '#' + SELECT_CLASS + ', #' + UNSELECT_CLASS;

jQuery(document).ready(function () {
    jQuery('.type-empty-field').on('click', CHECK_TRIGGERS, function () {
        const checked = jQuery(this).attr('id') === SELECT_CLASS ? 'checked' : false;
        jQuery('input[type="checkbox"][name^="resource[permissions]"]').attr('checked', checked);
    });
})
