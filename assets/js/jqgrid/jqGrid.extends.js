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
    if( NNX.jqGrid.stretchable.created[$(grid).attr('id')] ) {
        NNX.jqGrid.stretchable.created[$(grid).attr('id')].resize();
    }
};

NNX.jqGrid.stretchable = {};
NNX.jqGrid.stretchable.created = {};
NNX.jqGrid.stretchable.init = function (grid, params, minGridWidth, minColumWidth) {
    var stretch = {
        grid:grid,
        params:params,
        minGridWidth:minGridWidth,
        minColumWidth:minColumWidth,
        resize: function() {
            var avalibleWhidth = $(this.grid).parents('.ui-jqgrid').parent().innerWidth();
            var windowSize = null;
            if (this.minGridWidth && avalibleWhidth < this.minGridWidth) {
                windowSize = avalibleWhidth;
                avalibleWhidth = this.minGridWidth
            }

            var colums = $(this.grid).jqGrid('getGridParam','colModel');
            var unresizeColumsWidth = 0;
            var resizeColumsWidth = 0;
            var resizeColums = {};
            for (var i = 0; i < colums.length;i++) {
                var col = colums[i];
                if (col.hidden) {
                    continue;
                }
                if ($.inArray(col.index,this.params) == -1) {
                    unresizeColumsWidth += col.width;
                } else {
                    resizeColumsWidth += col.width;
                    resizeColums[col.index] = col.width;
                }
            }
            if (avalibleWhidth < unresizeColumsWidth) {
                avalibleWhidth = unresizeColumsWidth + resizeColumsWidth;
            }
            var resizeK = (avalibleWhidth - unresizeColumsWidth)/resizeColumsWidth;

            var newWidth = unresizeColumsWidth;
            for (var i in resizeColums) {
                resizeColums[i] = Math.round(resizeColums[i] * resizeK);
                if (this.minColumWidth && resizeColums[i] < this.minColumWidth) {
                    resizeColums[i] = this.minColumWidth
                }
                newWidth += resizeColums[i];
            }
            for (var i in resizeColums) {
                if (newWidth != avalibleWhidth) {
                    resizeColums[i] += avalibleWhidth - newWidth;
                    if (this.minColumWidth && resizeColums[i] < this.minColumWidth) {
                        resizeColums[i] = this.minColumWidth
                    }
                    avalibleWhidth = newWidth;
                }
                $(this.grid).jqGrid('setColProp',i,{widthOrg:resizeColums[i]});
            }
            $(this.grid).jqGrid('setGridWidth', avalibleWhidth, true);
            if (windowSize) {
                $(this.grid).jqGrid('setGridWidth', windowSize, false);
            }

        }
    };
    $(window).resize(function() {
        stretch.resize();
    });
    stretch.resize();
    NNX.jqGrid.stretchable.created[$(grid).attr('id')] = stretch;
    return stretch;

};
