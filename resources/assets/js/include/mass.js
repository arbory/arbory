
const COOKIE_NAME_NODES = 'mass';

jQuery(document).ready(($) => {
    let massActions = $('a.mass-action');
    const url = massActions.attr('href');


    function performAction() {
        $(this).attr('href', getUrlwithIds)
    }

    function getUrlwithIds(){
        let ids = $( "input.mass-row:checked" ).serializeArray();
        return url + ( url.indexOf('?') >= 0 ? '&' : '?' ) + $.param(ids);
    }

    function allChecked() {
        $( "input.mass-row").prop("checked", this.checked);
    }

    massActions.on('click', performAction);

    massActions.ready(() => {
        $('input[name="mass-column"]').on('change', allChecked);
    });

    $('body').on('contentloaded', function(e, event_params) {$(e.target).trigger('massforminit', event_params);});

    $(document).bind('massforminit', function( e )
    {
        let target = $(e.target);
        target = target.find('.edit-resources');

        target.find('input.bulk-control').on('change', function(e){
            if($(this).prop('checked')){
                target.find('[name="resource['+$(this).attr('data-target')+']"]').prop("disabled", false);
            } else {
                target.find('[name="resource['+$(this).attr('data-target')+']"]').prop("disabled", true);

            }
        });
    });
});