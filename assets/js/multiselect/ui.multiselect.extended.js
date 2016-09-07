$(function ($) {
    $.widget('ui.multiselect', $.ui.multiselect, {
        _create: function () {
            this.locale = 'ru';
            this.element.hide();
            this.id = this.element.attr("id");
            this.container = $('<div class="ui-multiselect ui-helper-clearfix ui-widget"></div>').insertAfter(this.element);
            this.count = 0; // number of currently selected options
            this.selectedContainer = $('<div class="selected"></div>').appendTo(this.container);
            this.availableContainer = $('<div class="available"></div>')[this.options.availableFirst ? 'prependTo' : 'appendTo'](this.container);
            this.selectedActions = $('<div class="actions ui-widget-header ui-helper-clearfix"><span class="count">0 ' + $.ui.multiselect.locale.itemsCount + '</span><a href="#" class="remove-all">' + $.ui.multiselect.locale.removeAll + '</a></div>').appendTo(this.selectedContainer);
            this.availableActions = $('<div class="actions ui-widget-header ui-helper-clearfix"><input type="text" class="search empty ui-widget-content ui-corner-all"/><a href="#" class="add-all">' + $.ui.multiselect.locale.addAll + '</a></div>').appendTo(this.availableContainer);
            this.selectedList = $('<ul class="selected connected-list"><li class="ui-helper-hidden-accessible"></li></ul>').bind('selectstart', function () {
                return false;
            }).appendTo(this.selectedContainer);
            this.availableList = $('<ul class="available connected-list"><li class="ui-helper-hidden-accessible"></li></ul>').bind('selectstart', function () {
                return false;
            }).appendTo(this.availableContainer);

            var that = this;

            // set dimensions
            this.container.width(this.element.width() + 1);
            this.selectedContainer.width(Math.floor(this.element.width() * this.options.dividerLocation));
            this.availableContainer.width(Math.floor(this.element.width() * (1 - this.options.dividerLocation)));

            // fix list height to match <option> depending on their individual header's heights
            this.selectedList.height(Math.max(this.element.height() - this.selectedActions.height(), 1));
            this.availableList.height(Math.max(this.element.height() - this.availableActions.height(), 1));

            if (!this.options.animated) {
                this.options.show = 'show';
                this.options.hide = 'hide';
            }

            // init lists
            this._populateLists(this.element.find('option'));

            // make selection sortable
            if (this.options.sortable) {
                this.selectedList.sortable({
                    placeholder: 'ui-state-highlight',
                    axis: 'y',
                    update: function (event, ui) {
                        // apply the new sort order to the original selectbox
                        that.selectedList.find('li').each(function () {
                            if ($(this).data('optionLink'))
                                $(this).data('optionLink').remove().appendTo(that.element);
                        });
                    },
                    receive: function (event, ui) {
                        ui.item.data('optionLink').prop('selected', true);
                        // increment count
                        that.count += 1;
                        that._updateCount();
                        // workaround, because there's no way to reference
                        // the new element, see http://dev.jqueryui.com/ticket/4303
                        that.selectedList.children('.ui-draggable').each(function () {
                            $(this).removeClass('ui-draggable');
                            $(this).data('optionLink', ui.item.data('optionLink'));
                            $(this).data('idx', ui.item.data('idx'));
                            that._applyItemState($(this), true);
                        });

                        // workaround according to http://dev.jqueryui.com/ticket/4088
                        setTimeout(function () {
                            ui.item.remove();
                        }, 1);
                    }
                });
            }

            // set up livesearch
            if (this.options.searchable) {
                this._registerSearchEvents(this.availableContainer.find('input.search'));
            } else {
                $('.search').hide();
            }

            // batch actions
            this.container.find(".remove-all").click(function () {
                that._populateLists(that.element.find('option').prop('selected',false));
                return false;
            });

            this.container.find(".add-all").click(function () {
                var options = that.element.find('option').not(":selected");
                if (that.availableList.children('li').length > 1) {
                    that.availableList.children('li').each(function (i) {
                        if ($(this).is(":visible")) $(options[i - 1]).prop('selected',true);
                    });
                } else {
                    options.attr('selected', 'selected');
                }
                that._populateLists(that.element.find('option'));
                return false;
            });
        },
        _setSelected: function (item, selected) {
            item.data('optionLink').prop('selected', selected);

            if (selected) {
                var selectedItem = this._cloneWithData(item);
                item[this.options.hide](this.options.animated, function () {
                    $(this).remove();
                });
                selectedItem.appendTo(this.selectedList).hide()[this.options.show](this.options.animated);

                this._applyItemState(selectedItem, true);
                return selectedItem;
            } else {

                // look for successor based on initial option index
                var items = this.availableList.find('li'), comparator = this.options.nodeComparator;
                var succ = null, i = item.data('idx'), direction = comparator(item, $(items[i]));

                // TODO: test needed for dynamic list populating
                if (direction) {
                    while (i >= 0 && i < items.length) {
                        direction > 0 ? i++ : i--;
                        if (direction != comparator(item, $(items[i]))) {
                            // going up, go back one item down, otherwise leave as is
                            succ = items[direction > 0 ? i : i + 1];
                            break;
                        }
                    }
                } else {
                    succ = items[i];
                }

                var availableItem = this._cloneWithData(item);
                succ ? availableItem.insertBefore($(succ)) : availableItem.appendTo(this.availableList);
                item[this.options.hide](this.options.animated, function () {
                    $(this).remove();
                });
                availableItem.hide()[this.options.show](this.options.animated);

                this._applyItemState(availableItem, false);
                return availableItem;
            }
        },
        _applyItemState: function (item, selected) {
            if (selected) {
                item.removeClass('ui-draggable');
                if (this.options.sortable)
                    item.children('span').addClass('ui-icon-arrowthick-2-n-s').removeClass('ui-helper-hidden').addClass('ui-icon');
                else
                    item.children('span').removeClass('ui-icon-arrowthick-2-n-s').addClass('ui-helper-hidden').removeClass('ui-icon');
                item.find('a.action span').addClass('ui-icon-minus').removeClass('ui-icon-plus');
                this._registerRemoveEvents(item.find('a.action'));

            } else {
                item.children('span').removeClass('ui-icon-arrowthick-2-n-s').addClass('ui-helper-hidden').removeClass('ui-icon');
                item.find('a.action span').addClass('ui-icon-plus').removeClass('ui-icon-minus');
                this._registerAddEvents(item.find('a.action'));
            }

            this._registerDoubleClickEvents(item);
            this._registerHoverEvents(item);
        }
    });

    $.extend($.ui.multiselect.locale, {
        addAll: 'Добавить все',
        removeAll: 'Удалить все',
        itemsCount: 'элементов выбрано'
    });
});