/**
 * Created by deller on 31.03.16.
 */
var NNX = NNX || {};
NNX.jqGrid = NNX.jqGrid || {};
NNX.jqGrid.oddRow = function(){
    $("tr.jqgrow:odd").addClass('odd-row');
};

jQuery.extend($.fn.fmatter, {
    radio: function (cellvalue, options, rowdata) {
        var result = '<input type="radio" name="radio-' + options.colModel.name + '"';
        if (cellvalue) {
            result += 'value=' + cellvalue;
        }
        result += ' />';
        return result;
    }
});
jQuery.extend($.fn.fmatter.radio, {
    unformat: function (cellvalue, options) {
        return $(cellvalue).val();
    }
});

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

NNX.jqGrid.getCollapsedRows = function(grid) {
    var collapsed = [];
    $(grid).find('tr[role=row] > td[role=gridcell] > div.tree-wrap > div.tree-plus.treeclick').each(function() {
        collapsed.push($(this).parents('tr[role=row]').attr('id'));
    });
    if (collapsed.length == 0) {
        collapsed = ['_none'];
    }
    return collapsed;
};

NNX.jqGrid.reloadWithSaveCollapsedRows = function(grid) {
    $(grid).jqGrid('setGridParam', { postData: { collapsedRows: NNX.jqGrid.getCollapsedRows(grid)} });
    $(grid).trigger('reloadGrid');
    $(grid).jqGrid('setGridParam', { postData: {collapsedRows:null} });
};

/**
 * result = {
 *      elementName1: [value],
 *      elementName2: [value1, value2]      // checkbox or multiple-select
 *      elementName3: [],
 * }
 */
NNX.jqGrid.getFormValues = function (form, params)
{
    var result = {};
    params = params || {};

    var excludeElements = params.exludeElements || [];

    $(form).find('input[type="text"], input:checked[type="radio"], input:checked[type="checkbox"]').each(function (i, elem) {
        var elementName = $(elem).prop('name');
        if (!elementName || $.inArray(elementName, excludeElements) !== -1) {
            return;
        }

        var values = result[elementName] || [];
        values.push($(elem).val());
        result[elementName] = values;
    });

    $(form).find('select').each(function (i, elem) {
        var elementName = $(elem).prop('name');
        if (!elementName || $.inArray(elementName, excludeElements) !== -1) {
            return;
        }
        var values = [];
        $(elem).find('option:selected').each(function (i, option) {
            values.push( $(option).val())
        });
        result[elementName] = values;
    });

    return result;
};

/**
 * Привязываем фильтрацию к форме поиска.
 * @param grid
 * @param params
 */
NNX.jqGrid.initFilterForm = function (grid, params)
{
    params = params || {};

    var searchForm = params.searchForm ? $(params.searchForm) : $('form[id^="search"]');
    var searchButton = params.searchButton ? $(params.searchButton) : $('button[id$="-submit"]');
    var resetButton = params.searchResetButton ? $(params.searchResetButton) : $('button[id$="-reset"]');

    if (searchButton) {
        searchButton.unbind('click').click(function () {
            var values = NNX.jqGrid.getFormValues(searchForm);
            NNX.jqGrid.filterInputEx(grid, values);
            NNX.jqGrid.sortTree(grid);
        });
    }
    if (resetButton) {
        resetButton.click(function () {
            NNX.jqGrid.filterInputEx(grid, {});
            NNX.jqGrid.sortTree(grid);
        });
    }
};

/**
 * Создать правило для фильтра (не принимает пустые значения)
 * @param name
 * @param op
 * @param data (если массив, то будет группа правил объедененных ИЛИ)
 * @returns {string}
 */
NNX.jqGrid.createFilterRule = function (name, op, data)
{
    var result = '';
    var dataType = $.type(data);
    if ($.isArray(data) && data.length == 1 || $.inArray(dataType, ['string', 'number'])) {
        if ($.isArray(data)) {
            data = data.shift();
        }
        if (!data) {
            return;
        }
        result = '{\"field\":\"' + name + '\",\"op\":\"' + op + '\",\"data\":\"' + data + '\"}';
    } else if ($.isArray(data)) {
        data.each(function(val) {
            if (!val) {
                return;
            }
            var groupRules = NNX.jqGrid.createFilterRule(name, op, val);
            if (groupRules) {
                result += (result.length == 0 ? "" : ",") + groupRules;
            }
        });
        result = '{\"groupOp\":\"OR\",\"rules\":[' + result + ']}';
    }
    return result;
};

/**
 * @param grid
 * @param values (ex: {element1: value, element2: [value2, value3]})
 */
NNX.jqGrid.filterInputEx = function (grid, values)
{
    var field, fields = '', val;
    var colums = $(grid).jqGrid('getGridParam','colModel');
    colums.each(function(column) {
        val = values[column.name] || values[column.name + '[]'];
        if (!val) {
            return;
        }
        field = NNX.jqGrid.createFilterRule(column.name, 'cn', val);
        if (field) {
            fields += (fields.length == 0 ? "" : ",") + field;
        }

    });
    var filters = '{\"groupOp\":\"AND\",\"rules\":[' + fields + ']}';
    if (fields.length == 0) {
        $(grid).jqGrid('setGridParam', { search: false, postData: { "filters": ""} }).trigger("reloadGrid");
    } else {
        $(grid).jqGrid('setGridParam', { search: true, postData: { "filters": filters} }).trigger("reloadGrid");
    }
};

// Функция сортировки реестра может некоректно отрабатывать при multisort=false
NNX.jqGrid.sortTree = function (grid, params)
{
    params = params || {};
    var sortField = params.sortField  || $(grid).jqGrid('getGridParam', 'sortname');
    var sortOrder =  params.sortOrder || $(grid).jqGrid('getGridParam', 'sortorder') || 'asc';
    var sortType  =  params.sortType  || 'text';
    if (! params.sortType ) {
        var colums = $(grid).jqGrid('getGridParam','colModel');
        colums.each(function (column) {
            if (column.name == sortField && column.sorttype) {
                sortType = column.sorttype;
            }
        });
    }
    $(grid).jqGrid("SortTree", sortField, sortOrder, sortType);
};

// Сообщение пустого реестра
NNX.jqGrid.initEmptyMessage = function (grid, params)
{
    params = params || {};
    params.message = params.message || $(grid).getGridParam('emptyrecords') || 'Данных не найдено...';
    params.template = params.template || '<div id="noResultsDiv" style="display: none; padding: 10px; text-align: center;" class=""><span class="notice"><label>' + params.message + '</label></span></div>';

    var div = $(params.template);
    div.insertAfter($(grid).parent());

    $(grid).bind('jqGridLoadComplete', function (event, data) {
        if ($(grid).getGridParam('reccount') === 0) {
            $(div).show();
        } else {
            $(div).hide();
        }
    });
};

// Инициализирует мутатор AddClassToCell для грида
// todo: inject to jqGrid
NNX.jqGrid.initAddClassMutator = function (grid, data)
{
    var rows = data.rows || {};
    var gridName = $(grid).attr('id');
    $.each(rows, function(i,row) {
        var rowId = row.id;
        if (!rowId) {
            return null;
        }
        var additionalClass = row.additionalClass || {};
        $.each(additionalClass, function (columnName, classes) {
            var cell = $('#' + rowId).find('td[aria-describedby="' + gridName+'_'+columnName +'"]');
            $(classes).each(function (i, addClass) {
                $(cell).addClass(addClass);
            });
        });
    });
};