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

NNX.jqGrid.indent = function (grid) {
    var rootNodes = grid.getRootNodes();
    var fullTreeNode, k;
    for (var i = 0; i < rootNodes.length; i++) {
        fullTreeNode = grid.getFullTreeNode(rootNodes[i]);
        console.log(fullTreeNode);
        for (k = 0; k < fullTreeNode.length; k++) {
            $('#' + fullTreeNode[k]._id_).find('.cell-wrapper, .cell-wrapperleaf').css('margin-left', (parseInt(fullTreeNode[k].level) + 1) * 18);
        }
    }
};
