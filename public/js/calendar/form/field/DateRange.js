/**
 * @class Ext.form.field.DateRange
 * @extends Ext.form.Field
 * <p>A combination field that includes start and end dates and times, as well as an optional all-day checkbox.</p>
 * @constructor
 * @param {Object} config The config object
 */
Ext.define('Ext.calendar.form.field.DateRange', {
    extend: 'Ext.form.FieldContainer',
    alias: 'widget.daterangefield',

    requires: [
        'Ext.form.field.Date',
        'Ext.form.field.Time',
        'Ext.form.Label',
        'Ext.form.field.Checkbox',
        'Ext.layout.container.Column'
    ],

    /**
     * @cfg {String} toText
     * The text to display in between the date/time fields (defaults to 'to')
     */
    toText: 'to',

    isRepeatableText: 'Is Repeatable',

    /**
     * @cfg {String} dateFormat
     * The date display format used by the date fields (defaults to 'n/j/Y')
     */
    dateFormat: 'n/j/Y',
    /**
     * @cfg {String} timeFormat
     * The time display format used by the time fields. By default the DateRange uses the
     * {@link Ext.Date.use24HourTime} setting and sets the format to 'g:i A' for 12-hour time (e.g., 1:30 PM)
     * or 'G:i' for 24-hour time (e.g., 13:30). This can also be overridden by a static format string if desired.
     */
    timeFormat: 'H:i',

    // private
    fieldLayout: {
        type: 'hbox',
        defaultMargins: {
            top: 0,
            right: 5,
            bottom: 0,
            left: 0
        }
    },

    // private
    initComponent: function() {
        var me = this;

        me.addCls('ext-dt-range');

        me.items = [
            {
                xtype: 'container',
                layout: me.fieldLayout,
                items: [
                    me.getStartDateConfig(),
                    me.getStartTimeConfig(),
                    me.getDateSeparatorConfig(),
                    me.getEndTimeConfig(),
                    me.getEndDateConfig()
                ]
            },
            {
                xtype: 'container',
                padding: '5 0 0 0',
                layout: me.fieldLayout,
                items: me.getIsRepeatableConfig()
            }
        ];

        me.callParent(arguments);
        me.initRefs();
    },

    initRefs: function() {
        var me = this;
        me.startDate = me.down('#' + me.id + '-start-date');
        me.startTime = me.down('#' + me.id + '-start-time');
        me.endTime = me.down('#' + me.id + '-end-time');
        me.endDate = me.down('#' + me.id + '-end-date');
        me.toLabel = me.down('#' + me.id + '-to-label');

        me.startDate.validateOnChange = me.endDate.validateOnChange = false;

        me.startDate.isValid = me.endDate.isValid = function() {
            var me = this,
                valid = Ext.isDate(me.getValue());
            if (!valid) {
                me.focus();
            }
            return valid;
        };
    },

    getStartDateConfig: function() {
        return {
            xtype: 'datefield',
            itemId: this.id + '-start-date',
            format: this.dateFormat,
            width: 100,
            listeners: {
                'blur': {
                    fn: function() {
                        this.onFieldChange('date', 'start');
                    },
                    scope: this
                }
            }
        };
    },

    getStartTimeConfig: function() {
        return {
            xtype: 'timefield',
            itemId: this.id + '-start-time',
            hidden: this.showTimes === false,
            labelWidth: 0,
            hideLabel: true,
            width: 70,
            format: this.timeFormat,
            minValue: '09:00',
            maxValue: '19:30',
            increment: 30,
            listeners: {
                'select': {
                    fn: function() {
                        this.onFieldChange('time', 'start');
                    },
                    scope: this
                }
            }
        };
    },

    getEndDateConfig: function() {
        return {
            xtype: 'datefield',
            itemId: this.id + '-end-date',
            format: this.dateFormat,
            hideLabel: true,
            width: 100,
            listeners: {
                'blur': {
                    fn: function() {
                        this.onFieldChange('date', 'end');
                    },
                    scope: this
                }
            }
        };
    },

    getEndTimeConfig: function() {
        return {
            xtype: 'timefield',
            itemId: this.id + '-end-time',
            hidden: this.showTimes === false,
            labelWidth: 0,
            hideLabel: true,
            width: 70,
            format: this.timeFormat,
            minValue: '09:30',
            maxValue: '20:00',
            increment: 30,
            listeners: {
                'select': {
                    fn: function() {
                        this.onFieldChange('time', 'end');
                    },
                    scope: this
                }
            }
        };
    },

    getDuration: function() {
        var me = this,
            start = me.getDT('start'),
            end = me.getDT('end');

        return end.getTime() - start.getTime();
    },

    getIsRepeatableConfig: function() {
        return [
            {
                xtype: 'checkbox',
                name: 'IsRepeatable',
                boxLabel: this.isRepeatableText,
                margins: {
                    top: 2,
                    right: 5,
                    bottom: 0,
                    left: 0
                },
                inputValue: true,
                uncheckedValue: false,
                handler: this.onIsRepeatableChange,
                scope: this
            },
            {
                xtype: 'combo',
                name: 'RepeatedOn',
                flex: 1,
                disabled: true,
                editable: false,
                hidden: true,
                itemId: this.id + '-repeat-on',
                store: Ext.create('Ext.calendar.data.Days'),
                emptyText: 'Please select days to repeat on',
                displayField: 'name',
                valueField: 'value',
                multiSelect: true,
                allowBlank: false
            }
        ];
    },

    onIsRepeatableChange: function(chk, checked) {

        var me = this,
            repeatOnCombo = me.down('#' + me.id + '-repeat-on'),
            startDate = me.startDate,
            startDateValue = startDate.getValue(),
            endDate = me.endDate;

        Ext.suspendLayouts();
        repeatOnCombo.setDisabled(!checked).setVisible(checked);
        if (startDateValue != null) {
            repeatOnCombo.select(startDateValue.getDay() - 1);
        }
        startDate.setDisabled(checked).setVisible(!checked);
        endDate.setDisabled(checked).setVisible(!checked);
        Ext.resumeLayouts(true);

    },

    getDateSeparatorConfig: function() {
        return {
            xtype: 'label',
            itemId: this.id + '-to-label',
            text: this.toText,
            margins: { top: 4, right: 5, bottom: 0, left: 0 }
        };
    },

    // private
    onFieldChange: function(type, startend) {
        this.checkDates(type, startend);
        this.fireEvent('change', this, this.getValue());
    },

    // private
    checkDates: function(type, startend) {
        var me = this,
            startField = me.down('#' + me.id + '-start-' + type),
            endField = me.down('#' + me.id + '-end-' + type),
            startValue = me.getDT('start'),
            endValue = me.getDT('end');

        if (!startValue || !endValue) {
            return;
        }

        if (startValue > endValue) {
            if (startend == 'start') {
                endField.setValue(startValue);
            } else {
                startField.setValue(endValue);
                me.checkDates(type, 'start');
            }
        }
        if (type == 'date') {
            me.checkDates('time', startend);
        }
    },

    /**
     * Returns an array containing the following values in order:<div class="mdetail-params"><ul>
     * <li><b><code>DateTime</code></b> : <div class="sub-desc">The start date/time</div></li>
     * <li><b><code>DateTime</code></b> : <div class="sub-desc">The end date/time</div></li>
     * <li><b><code>Boolean</code></b> : <div class="sub-desc">True if the dates are all-day, false
     * if the time values should be used</div></li><ul></div>
     * @return {Array} The array of return values
     */
    getValue: function() {
        var eDate = Ext.calendar.util.Date,
            start = this.getDT('start'),
            end = this.getDT('end');

        return [
            start,
            end
        ];
    },

    // private getValue helper
    getDT: function(startend) {
        var time = this[startend + 'Time'].getValue(),
            dt = this[startend + 'Date'].getValue();

        if (Ext.isDate(dt)) {
            dt = Ext.Date.format(dt, this[startend + 'Date'].format);
        }
        else {
            return null;
        }
        if (time && time !== '') {
            time = Ext.Date.format(time, this[startend + 'Time'].format);
            var val = Ext.Date.parseDate(dt + ' ' + time, this[startend + 'Date'].format + ' ' + this[startend + 'Time'].format);
            return val;
            //return Ext.Date.parseDate(dt+' '+time, this[startend+'Date'].format+' '+this[startend+'Time'].format);
        }
        return Ext.Date.parseDate(dt, this[startend + 'Date'].format);

    },

    /**
     * Sets the values to use in the date range.
     * @param {Array/Date/Object} v The value(s) to set into the field. Valid types are as follows:<div class="mdetail-params"><ul>
     * <li><b><code>Array</code></b> : <div class="sub-desc">An array containing, in order, a start date, end date and all-day flag.
     * This array should exactly match the return type as specified by {@link #getValue}.</div></li>
     * <li><b><code>DateTime</code></b> : <div class="sub-desc">A single Date object, which will be used for both the start and
     * end dates in the range.  The all-day flag will be defaulted to false.</div></li>
     * <li><b><code>Object</code></b> : <div class="sub-desc">An object containing properties for StartDate, EndDate and IsAllDay
     * as defined in {@link Ext.calendar.data.EventMappings}.</div></li><ul></div>
     */
    setValue: function(v) {
        if (!v) {
            return;
        }
        if (Ext.isArray(v)) {
            this.setDT(v[0], 'start');
            this.setDT(v[1], 'end');
        } else if (Ext.isDate(v)) {
            this.setDT(v, 'start');
            this.setDT(v, 'end');
        } else if (v[Ext.calendar.data.EventMappings.StartDate.name]) { //object
            this.setDT(v[Ext.calendar.data.EventMappings.StartDate.name], 'start');
            if (!this.setDT(v[Ext.calendar.data.EventMappings.EndDate.name], 'end')) {
                this.setDT(v[Ext.calendar.data.EventMappings.StartDate.name], 'end');
            }
        }
    },

    // private setValue helper
    setDT: function(dt, startend) {
        if (dt && Ext.isDate(dt)) {
            this[startend + 'Date'].setValue(dt);
            this[startend + 'Time'].setValue(Ext.Date.format(dt, this[startend + 'Time'].format));
            return true;
        }
    },

    // inherited docs
    isDirty: function() {
        var dirty = false;
        if (this.rendered && !this.disabled) {
            this.items.each(function(item) {
                if (item.isDirty()) {
                    dirty = true;
                    return false;
                }
            });
        }
        return dirty;
    },

    // private
    onDisable: function() {
        this.delegateFn('disable');
    },

    // private
    onEnable: function() {
        this.delegateFn('enable');
    },

    // inherited docs
    reset: function() {
        this.delegateFn('reset');
    },

    // private
    delegateFn: function(fn) {
        this.items.each(function(item) {
            if (item[fn]) {
                item[fn]();
            }
        });
    },

    // private
    beforeDestroy: function() {
        Ext.destroy(this.fieldCt);
        this.callParent(arguments);
    },

    /**
     * @method getRawValue
     * @hide
     */
    getRawValue: Ext.emptyFn,
    /**
     * @method setRawValue
     * @hide
     */
    setRawValue: Ext.emptyFn
});