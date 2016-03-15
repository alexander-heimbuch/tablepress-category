(function ($) {
    'use strict';

    var categorize = function (data) {
            var $table = $('#' + data.selector).DataTable(),
                $tableNode = $($table.table().node()),
                $tableBody = $tableNode.find('tbody'),
                classes = $tableBody.attr('class'),
                columnCount = $table.columns().nodes().length;

            $tableNode.addClass('category-table');

            $.each(data.categories, function (key, category) {
                var $tbody = $('<tbody />'),
                    $title = $('<tr class="category-title"><td colspan="' + columnCount + '"><a href="javascript:"><strong>' + category.name + '</strong></a></td></tr>');

                $tbody
                    .addClass(classes)
                    .addClass('category');

                if (category.opened === true) {
                    $tbody.addClass('opened');
                }

                $title.on('click', function () {
                    $tbody.toggleClass('opened');
                });

                $tbody.append($title);

                for (var index = category.row_start - 1; index < category.row_end; index += 1) {
                    var $row = $($table.row(index).node());
                    $row.appendTo($tbody);
                }

                $tableNode.append($tbody);
            });

            $tableBody.remove();
        };

    $.each(window.TABLE_CATEGORIES, function (key, data) {
        $('#' + data.selector).on( 'draw.dt', function () {
            categorize(data);
        });
    });
})(jQuery);
