(function ($) {
    'use strict';

    var categorize = function ($tableNode, data) {
            var $table = $tableNode.DataTable(),
                $tableBody = $tableNode.find('tbody'),
                classes = $tableBody.attr('class'),
                columnCount = $table.columns().nodes().length;

            $tableNode.addClass('category-table');

            $.each(data, function (index, category) {
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

    $.each(window.TABLE_CATEGORIES, function (tableId, data) {
        var $table = $('#' + tableId);

        $table.on( 'draw.dt', function () {
            categorize($table, data);
        });
    });
})(jQuery);
