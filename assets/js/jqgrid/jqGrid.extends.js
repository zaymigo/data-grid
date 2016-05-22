/**
 * Created by deller on 31.03.16.
 */
var NNX = NNX || {};
NNX.jqGrid = NNX.jqGrid || {};
NNX.jqGrid.oddRow = function(){
    $("tr.jqgrow:odd").addClass('odd-row');
};

NNX.jqGrid.expandAll = function (grid) {
    $("#" + grid.attr('id') + " .tree-plus").each(function () {
        $(this).trigger('click');
    });
};

NNX.jqGrid.collapseAll = function (grid) {
    $("#" + grid.attr('id') + " .tree-minus").each(function () {
        $(this).trigger('click');
    });
};

NNX.jqGrid.indent = function (grid) {
    var rootNodes = grid.getRootNodes();
    var fullTreeNode, k;
    for (var i = 0; i < rootNodes.length; i++) {
        fullTreeNode = grid.getFullTreeNode(rootNodes[i]);
        for (k = 0; k < fullTreeNode.length; k++) {
            $('#' + fullTreeNode[k]._id_).find('.cell-wrapper, .cell-wrapperleaf').css('margin-left', (parseInt(fullTreeNode[k].level) + 1) * 18);
        }
    }
};
NNX.jqGrid.saveColums = function (grid, perm) {
    grid.remapColumns(perm,true,false);
    var colums = grid.jqGrid('getGridParam','colModel');
    var gridName = $(grid).attr('id').substr(5);
    var settings = [];
    var h;
    for (var i = 0; i < colums.length; i++) {
        if (colums[i]['hidden']) {
            h = 1;
        } else {
            h = 0;
        }
        settings[i] = {'n':colums[i]['name'], 'h':h};
    }
    $.cookie('nnx[grid][' + gridName + ']', JSON.stringify(settings), {expires: 1, path: '/'});
};

NNX.jqGrid.getCollapsedRows = function(grid) {
    var collapsed = [];
    $(grid).find('tr[role=row] > td[role=gridcell] > div.tree-wrap > div.tree-plus.treeclick').each(function() {
        collapsed.push($(this).parents('tr[role=row]').attr('id'));
    });
    return collapsed;
};

NNX.jqGrid.reloadWithSaveCollapsedRows = function(grid) {
    $(grid).jqGrid('setGridParam', { postData: { collapsedRows: NNX.jqGrid.getCollapsedRows(grid)} });
    $(grid).trigger('reloadGrid');
    $(grid).jqGrid('setGridParam', { postData: {collapsedRows:null} });
};
