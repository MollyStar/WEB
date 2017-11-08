/**
 * jQuery Editable Select
 * Indri Muska <indrimuska@gmail.com>
 *
 * Source on GitHub @ https://github.com/indrimuska/jquery-editable-select
 */

+(function ($) {
    // jQuery Editable Select
    EditableSelect = function (select, options) {
        var that = this;

        this.options = options;
        this.$select = $(select);
        this.$input = $('<input type="text" autocomplete="off">');
        this.$list = $('<ul class="es-list">');
        this.$LAST_SEARCH = null;

        if (this.options.additional) {
            this.$additional = $('<input type="hidden">');
            this.$additional.attr('name', this.$select.attr('name'));
            this.$select.get(0).removeAttribute('name');
            this.$additional.appendTo(this.options.appendTo || this.$select.parent());
        }

        this.utility = new EditableSelectUtility(this);

        if (['default', 'fade', 'slide'].indexOf(this.options.effects) < 0) this.options.effects = 'default';
        if (isNaN(this.options.duration) || [
                'fast',
                'slow'
            ].indexOf(this.options.duration) < 0) this.options.duration = 'fast';

        // create text input
        this.$select.replaceWith(this.$input);
        this.$list.appendTo(this.options.appendTo || this.$input.parent());

        this.handler = {
            ajax: null,
            inputListener: null
        };

        this.options.initFromSource && this.loadFromSource();

        // initalization
        this.utility.initialize();
        this.utility.initializeList();
        this.utility.initializeInput();
        this.utility.trigger('create');
    };

    EditableSelect.DEFAULTS = {
        filter: true,
        effects: 'default',
        duration: 'fast',
        source: null,
        sourceFilter: '',
        initFromSource: false,
        sourceMethod: 'get'
    };

    EditableSelect.prototype.loadFromSource = function ($filter) {
        var that = this;
        if (this.options.source == null)
            return;

        var $data = {};
        if ($filter) {
            $data[this.options.sourceFilter] = $filter;
        }
        this.handler.ajax = $.ajax(this.options.source, {
            type: this.options.sourceMethod,
            cache: false,
            dataType: 'json',
            data: $data
        }).done(function (ret) {
            that.$list.empty();
            if (ret) {
                if (ret.code === 0) {
                    that.$list.empty();
                    if ($.isArray(ret.response) && ret.response.length) {
                        $.each(ret.response, function (_, item) {
                            that.add(item[1], NaN, [{name: 'value', value: item[0]}]);
                        });
                        that.filter();
                        that.utility.highlight(0);
                        return;
                    }
                }
            }
            that.hide();
        }).fail(function () {
            that.$list.empty();
            that.hide();
        });
    };

    EditableSelect.prototype.inputing = function () {
        var that = this;
        var value = that.$input.val().trim();
        if (that.$LAST_SEARCH != value && that.options.source) {
            that.$LAST_SEARCH = value;
            if (that.handler.ajax && that.handler.ajax.state() == 'pending') {
                that.handler.ajax.abort();
                that.handler.ajax = null;
            }
            that.handler.inputListener && clearTimeout(that.handler.inputListener);
            that.handler.inputListener = setTimeout(function () {
                that.loadFromSource(value);
                if (!value && that.options.additional) {
                    that.$additional.val('');
                }
            }, 500);
        } else {
            that.handler.inputListener && clearTimeout(that.handler.inputListener);
            that.handler.inputListener = setTimeout(function () {
                that.filter();
                that.utility.highlight(0);
                if (!value && that.options.additional) {
                    that.$additional.val('');
                }
            }, 500);
        }
    };
    EditableSelect.prototype.filter = function () {
        var hiddens = 0;
        var search = this.$input.val().toLowerCase().trim();

        this.$list.find('li').addClass('es-visible').show();
        if (this.options.filter) {
            hiddens = this.$list.find('li').filter(function (i, li) {
                return $(li).text().toLowerCase().indexOf(search) < 0;
            }).hide().removeClass('es-visible').length;
            if (this.$list.find('li').length == hiddens) this.hide();
        }
    };
    EditableSelect.prototype.show = function () {
        this.$list.css({
            top: this.$input.position().top + this.$input.outerHeight() - 1,
            left: this.$input.position().left,
            width: this.$input.outerWidth()
        });

        if (this.$list.is(':visible') || this.$list.find('li.es-visible').length == 0) return;

        this.$input.addClass('open');
        switch (this.options.effects) {
            case 'fade':
                this.$list.fadeIn(this.options.duration);
                break;
            case 'slide':
                this.$list.slideDown(this.options.duration);
                break;
            default:
                this.$list.show(this.options.duration);
                break;
        }
        this.utility.trigger('show');
    };
    EditableSelect.prototype.hide = function () {
        this.$input.removeClass('open');
        switch (this.options.effects) {
            case 'fade':
                this.$list.fadeOut(this.options.duration);
                break;
            case 'slide':
                this.$list.slideUp(this.options.duration);
                break;
            default:
                this.$list.hide(this.options.duration);
                break;
        }
        this.utility.trigger('hide');
    };
    EditableSelect.prototype.select = function ($li) {
        if (!this.$list.has($li) || !$li.is('li.es-visible')) return;
        this.selectChange = true;
        if ($li.text() !== this.$input.attr('placeholder')) {
            this.$input.val($li.text());
        } else {
            this.$input.val('');
        }
        if (this.options.additional) {
            this.$additional.val($li.attr('value'));
        }
        this.hide();
        this.utility.trigger('select', $li);
    };
    EditableSelect.prototype.add = function (text, index, attrs, data) {
        var $li = $('<li>').html(text);
        var last = this.$list.find('li').length;

        if (isNaN(index)) index = last;
        else index = Math.min(Math.max(0, index), last);
        if (index == 0) this.$list.prepend($li);
        else this.$list.find('li').eq(index - 1).after($li);
        this.utility.setAttributes($li, attrs, data);
    };

    // Utility
    EditableSelectUtility = function (es) {
        this.es = es;
    };
    EditableSelectUtility.prototype.initialize = function () {
        var that = this;
        that.setAttributes(that.es.$input, that.es.$select[0].attributes, that.es.$select.data());
        that.es.$input.addClass('es-input').data('editable-select', that.es);
        that.es.$select.find('option').each(function (i, option) {
            var $option = $(option);
            that.es.add($option.text(), i, option.attributes, $option.data());
            if ($option.attr('selected') && $option.text() !== that.es.$input.attr('placeholder')) {
                that.es.$input.val($option.text());
                if (that.es.options.additional) {
                    that.es.$additional.val($option.val());
                }
            }
        });
        that.es.filter();
    };
    EditableSelectUtility.prototype.initializeList = function () {
        var that = this;
        that.es.$list
            .on('mousemove', 'li', function () {
                that.es.$list.find('.selected').removeClass('selected');
                $(this).addClass('selected');
            })
            .on('mousedown', 'li', function () {
                that.es.select($(this));
            })
            .on('mouseenter', function () {
                that.es.$list.find('li.selected').removeClass('selected');
            });
    };
    EditableSelectUtility.prototype.initializeInput = function () {
        var that = this;
        that.es.$input
            .on('focus', $.proxy(that.es.show, that.es))
            .on('blur', $.proxy(that.es.hide, that.es))
            .on('input keydown', function (e) {
                switch (e.keyCode) {
                    case 38: // Up
                        var visibles = that.es.$list.find('li.es-visible');
                        var selected = visibles.index(visibles.filter('li.selected'));
                        that.highlight(selected - 1);
                        break;
                    case 40: // Down
                        if (that.es.$list.children().length === 0) {
                            that.es.inputing();
                        } else {
                            var visibles = that.es.$list.find('li.es-visible');
                            var selected = visibles.index(visibles.filter('li.selected'));
                            that.highlight(selected + 1);
                        }
                        break;
                    case 13: // Enter
                        if (that.es.$list.is(':visible')) {
                            that.es.select(that.es.$list.find('li.selected'));
                            e.preventDefault();
                        }
                    case 9:  // Tab
                    case 27: // Esc
                        that.es.hide();
                        break;
                    default:
                        that.es.inputing();
                        break;
                }
            })
            .on('change', function (e) {
                if (that.es.selectChange) {
                    that.es.selectChange = false;
                    return;
                }
                that.es.inputing();
            });
    };
    EditableSelectUtility.prototype.highlight = function (index) {
        var that = this;
        that.es.show();
        setTimeout(function () {
            var visibles = that.es.$list.find('li.es-visible');
            var oldSelected = that.es.$list.find('li.selected').removeClass('selected');
            var oldSelectedIndex = visibles.index(oldSelected);

            if (visibles.length > 0) {
                var selectedIndex = (visibles.length + index) % visibles.length;
                var selected = visibles.eq(selectedIndex);
                var top = selected.position().top;

                selected.addClass('selected');
                if (selectedIndex < oldSelectedIndex && top < 0)
                    that.es.$list.scrollTop(that.es.$list.scrollTop() + top);
                if (selectedIndex > oldSelectedIndex && top + selected.outerHeight() > that.es.$list.outerHeight())
                    that.es.$list.scrollTop(that.es.$list.scrollTop() + selected.outerHeight() + 2 * (top - that.es.$list.outerHeight()));
            }
        }, 100);
    };
    EditableSelectUtility.prototype.setAttributes = function ($element, attrs, data) {
        $.each(attrs || {}, function (i, attr) {
            $element.attr(attr.name, attr.value);
        });
        $element.data(data);
    };
    EditableSelectUtility.prototype.trigger = function (event) {
        var params = Array.prototype.slice.call(arguments, 1);
        var args = [event + '.editable-select'];
        args.push(params);
        this.es.$select.trigger.apply(this.es.$select, args);
        this.es.$input.trigger.apply(this.es.$input, args);
    };

    // Plugin
    Plugin = function (option) {
        var args = Array.prototype.slice.call(arguments, 1);
        return this.each(function () {
            var $this = $(this);
            var data = $this.data('editable-select');
            var options = $.extend({}, EditableSelect.DEFAULTS, $this.data(), typeof option == 'object' && option);

            if (!data) data = new EditableSelect(this, options);
            if (typeof option == 'string') data[option].apply(data, args);
        });
    };
    $.fn.editableSelect = Plugin;
    $.fn.editableSelect.Constructor = EditableSelect;
})(jQuery);