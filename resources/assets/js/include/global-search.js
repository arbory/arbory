jQuery(function () {
    'use strict';

    let form = jQuery('#global-search-form');
    let globalSearchInput = form.find('.global-search-input');
    let action = form.attr('action');
    let resultsList = form.find('.results-list');
    let records = resultsList.find('.records');
    let closeButton = resultsList.find('.close')

    globalSearchInput.on('input', debounce(function (event) {
        records.show();
        records.html('');

        if (!event.currentTarget.value) {
            return;
        }

        $.post(action, form.serializeArray(), function (data) {
            closeButton.show();

            if (data.no_results) {
                let listItem = $('<div>');

                $('<h4/>')
                    .text(data.no_results)
                    .appendTo(listItem);

                listItem.appendTo(records);

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

                    $("<i />", {class: "mt-icon", text: "arrow_right_alt"})
                        .appendTo(groupElement);

                    $("<a />", {href: row.url, text: row.title})
                        .appendTo(groupElement);
                });

                listItem.appendTo(records);
            })
        });
    }));

    closeButton.on('click', function () {
        clearSearch();
    });

    function clearSearch() {
        records.hide();
        records.html('');
        closeButton.hide();
        globalSearchInput.val('');
    }

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
            clearSearch();
        }
    });
});
