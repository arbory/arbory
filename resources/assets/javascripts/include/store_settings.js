jQuery(function(){
    var body = jQuery('body');
    var settings_path = body.data('settings-path');

    body.on('settingssave', function( event, key_or_settings, value )
    {
        if (!settings_path)
        {
            return;
        }

        var settings = key_or_settings;
        if (typeof settings === "string")
        {
            settings = {};
            settings[key_or_settings] = value;
        }

        jQuery.ajax({
            url:  settings_path,
            data: { "settings": settings},
            type: 'POST',
            dataType: 'json'
        });
    });
});
