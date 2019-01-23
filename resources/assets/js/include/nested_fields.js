jQuery( document ).ready(function()
{
    var body = jQuery('body');

    jQuery(document).bind('nestedfieldsinit', function( e )
    {
        var target = jQuery(e.target);
        if (!target.is('section.nested'))
        {
            target = target.find('section.nested');
        }

        target.each(function()
        {
            var block = jQuery(this);
            var list   = block.find('.list').first();

            var block_name               = block.attr('data-name');
            var item_selector            = '.item[data-name="' + block_name + '"]';

            var new_item_selector        = '.item[data-name="' + block_name + '"].new';
            var existing_item_selector   = '.item[data-name="' + block_name + '"]:not(.new)';


            block.click( function( event, event_params )
            {
                var trigger = jQuery( event.target );

                // webkit browsers go beyond button node when setting click target
                if (!trigger.is('button'))
                {
                    trigger = trigger.parents( 'button' ).first();
                }

                if (!trigger.is('button.add-nested-item') && !trigger.is('button.remove-nested-item'))
                {
                    // irrelevant click
                    return;
                }

                // skip click on disabled buttons
                if(trigger.prop("disabled"))
                {
                    return;
                }

                var target_block = trigger.parents('section.nested').first();

                if (target_block.attr('data-name') !== block_name)
                {
                    return;   // only react to own clicks
                }

                if (trigger.is('.add-nested-item'))
                {
                    var template = null;

                    if (target_block.is('.polymorphic')) {
                        var type_select = target_block.find('footer select.template-types');
                        template = jQuery(type_select.find('option:selected').data('template'));
                    }
                    else {
                        template = jQuery(target_block.data('arbory-template'));
                    }

                    if (template.length !== 1)
                    {
                        return null;
                    }

                    var new_item = template;

                    new_item.addClass('new');

                    new_item.appendTo( list );

                    new_item.trigger( 'nestedfieldsreindex', event_params );

                    if (event_params && event_params.no_animation)
                    {
                        new_item.trigger( 'nestedfieldsitemadd', event_params);
                        new_item.trigger( 'contentloaded', event_params );
                    }
                    else
                    {
                        if (new_item.is('tr, td') )
                        {
                            new_item.css({ opacity: 1 }).hide();
                            new_item.fadeIn( 'normal', function()
                            {
                                new_item.trigger( 'nestedfieldsitemadd', event_params);
                                new_item.trigger( 'contentloaded', event_params );
                            });
                        }
                        else
                        {
                            new_item.css({ opacity: 0 });
                            new_item.slideDown( 'fast', function()
                            {
                                new_item.css({ opacity: 1 }).hide();
                                new_item.fadeIn( 'fast', function()
                                {
                                    new_item.trigger( 'nestedfieldsitemadd', event_params );
                                    new_item.trigger( 'contentloaded', event_params );
                                });
                            });
                        }
                    }

                }
                else if (trigger.is('.remove-nested-item'))
                {
                    var item = trigger.parents(item_selector).first();

                    var removeItem = function( item )
                    {
                        item.trigger( 'contentbeforeremove', event_params );

                        var parent = item.parent();

                        var destroy_inputs = item.find('input.destroy');

                        if (destroy_inputs.length > 0)
                        {
                            // mark as destroyable and hide
                            destroy_inputs.val( true );

                            item.hide();
                        }
                        else
                        {
                            item.remove();
                        }

                        target_block.trigger( 'nestedfieldsreindex', event_params );
                        parent.trigger( 'contentremoved', event_params );
                    };

                    item.addClass( 'removed' );

                    item.trigger( 'nestedfieldsitemremove', event_params );

                    if (event_params && event_params.no_animation)
                    {
                        removeItem( item );
                    }
                    else
                    {
                        item.fadeOut( 'fast', function()
                        {
                            if (item.is('tr,td'))
                            {
                                removeItem( item );
                            }
                            else
                            {
                                item.css({ opacity: 0 }).show().slideUp( 'fast', function()
                                {
                                    removeItem( item );
                                });
                            }
                        });
                    }
                }

                return;
            });


            block.on('nestedfieldsreindex', function()
            {
                // update data-index attributes and names/ids for all fields inside a block

                // in case of nested blocks, this bubbles up and gets called for each parent block also
                // so that each block can update it's own index in the names

                // only new items are changed.
                // existing items always preserve their original indexes
                // new item indexes start from largest of existing item indexes + 1

                var first_available_new_index = 0;

                var existing_items = block.find( existing_item_selector );
                existing_items.each(function()
                {
                    var index = jQuery(this).attr('data-index');
                    if (typeof index === 'undefined')
                    {
                        return;
                    }
                    index = index * 1;

                    if (index >= first_available_new_index)
                    {
                        first_available_new_index = index + 1;
                    }
                });

                var new_items = block.find(new_item_selector);

                var index = first_available_new_index;

                var changeable_attributes = [];
                new_items.each(function()
                {
                    var item = jQuery(this);
                    item.attr('data-index', index);

                    // this matches both of these syntaxes in attribute values:
                    //
                    //  resource[foo_attributes][0][bar]  /  resource[foo][_template_][bar]
                    //  resource_foo_attributes_0_bar     /  resource_foo__template__bar
                    //

                    var matchPattern   = new RegExp('(\\[|_)' + block_name + '(\\]\\[|_)(\\d*|_template_)?(\\]|_)');
                    var searchPattern  = new RegExp('((\\[|_)' + block_name + '(\\]\\[|_))(\\d*|_template_)?(\\]|_)', 'g');
                    var replacePattern = '$1' + index + '$5';
                    var attrs = ['name', 'id', 'for'];

                    // collect changeable attributes
                    item.find('input,select,textarea,button,label').each(function()
                    {
                        for (var i=0; i<attrs.length; i++)
                        {
                            var attr = jQuery(this).attr(attrs[i]);
                            if (attr && attr.match(matchPattern))
                            {
                                var params = {
                                    element:   this,
                                    attribute: attrs[i],
                                    old_value: attr,
                                    new_value: attr.replace(searchPattern, replacePattern)
                                };
                                if (params.old_value === params.new_value)
                                {
                                    continue;
                                }
                                changeable_attributes.push( params );
                            }
                        }
                    });

                    index++;
                });

                // perform change in two parts:
                // at first change all changeable attributes to unique temporary strings for ALL affected items
                // and then change the attributes to actual values

                // this is needed so that any code in external beforeattributechange / attributechange handlers
                // does not encounter ID collisions during the process (multiple elements temporarily sharing the same ID)

                // change to temporary values
                var temp_value_prefix = 'nestedfieldsreindex_temporary_value_';
                jQuery.each(changeable_attributes, function(attribute_index, params)
                {
                    var element = jQuery(params.element);
                    element.trigger('beforeattributechange', params );
                    element.attr(params.attribute, temp_value_prefix + attribute_index);
                });

                // change to actual new values
                jQuery.each(changeable_attributes, function(attribute_index, params)
                {
                    var element = jQuery(params.element);
                    element.attr(params.attribute, params.new_value);
                    element.trigger('attributechanged', params );
                });


            });

            block.on('sortableupdate', function()
            {
                block.trigger('nestedfieldsreindex');
            });

            block.on('nestedfieldsitemadd', function( e )
            {
                var item = jQuery( e.target );

                if (item.attr('data-name') !== block_name)
                {
                    return; // the added item does not belong to this block
                }

                // focus first visibile field in item
                item.find( 'input, select, textarea' ).filter(':visible').first().focus();

            });


        });

	});

    body.on('contentloaded', function(e, event_params)
    {
        jQuery(e.target).trigger('nestedfieldsinit', event_params);
    });


});
