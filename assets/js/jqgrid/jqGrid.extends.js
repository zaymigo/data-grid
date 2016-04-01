/**
 * Created by deller on 31.03.16.
 */
var NNX = NNX || {};
NNX.jqGrid = NNX.jqGrid || {};

NNX.jqGrid.expandCollapseAll = function (grid) {
    $("#" + grid.attr('id') + " .treeclick").each(function () {
        $(this).trigger('click');
    });
};
