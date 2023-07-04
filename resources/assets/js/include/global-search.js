jQuery(function () {
    'use strict';

    let form = jQuery('#global-search-form');
    let globalSearchInput = form.find('input');
    let action = form.attr('action');
    let results = form.find('.results-list');

    globalSearchInput.on('input', debounce(function (event) {
        results.html('');

        if (!event.currentTarget.value) {
            return;
        }

        $.post(action, form.serializeArray(), function (data) {
            if (data.no_results) {
                let listItem = $('<div>');

                $('<h4/>')
                    .text(data.no_results)
                    .appendTo(listItem);

                listItem.appendTo(results);

                return;
            }

            $.each(data, function (group, rows) {
                let listItem = $('<div>');

                $('<h4/>')
                    .text(group)
                    .appendTo(listItem);

                let groupElements = $('<ul/>')
                    .appendTo(listItem);

                $.each(rows, function (index, row) {
                    let groupElement = $('<li/>')
                        .appendTo(groupElements);

                    $("<a />", {href: row.url, text: row.title})
                        .appendTo(groupElement);
                });

                listItem.appendTo(results);
            })
        });
    }));

    function debounce(func, timeout = 500) {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => {
                func.apply(this, args);
            }, timeout);
        };
    }

    $(document).on('click', (event) => {
        let target = $(event.target);
        if (target.parents('form#global-search-form').length === 0) {
            results.html('');
        }
    });
});
