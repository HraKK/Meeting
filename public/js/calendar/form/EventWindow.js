/**
 * @class Ext.calendar.form.EventWindow
 * @extends Ext.Window
 * <p>A custom window containing a basic edit form used for quick editing of events.</p>
 * <p>This window also provides custom events specific to the calendar so that other calendar components can be easily
 * notified when an event has been edited via this component.</p>
 * @constructor
 * @param {Object} config The config object
 */
Ext.define('Ext.calendar.form.EventWindow', {
    extend: 'Ext.window.Window',
    alias: 'widget.eventeditwindow',

    requires: [
        'Ext.form.Panel',
        'Ext.calendar.util.Date',
        'Ext.calendar.data.EventModel'
    ],

    constructor: function (config) {
        var formPanelCfg = {
            xtype: 'form',
            fieldDefaults: {
                msgTarget: 'side',
                labelWidth: 65
            },
            frame: false,
            bodyStyle: 'background:transparent;padding:5px 10px 10px;',
            bodyBorder: false,
            border: false,
            items: [
                {
                    itemId: 'title',
                    name: 'title',
                    fieldLabel: 'Title',
                    labelWidth: 100,
                    xtype: 'textfield',
                    allowBlank: false,
                    emptyText: 'Event Title',
                    minLength: 3,
                    anchor: '100%'
                },
                {
                    xtype: 'textarea',
                    anchor: '100%',
                    fieldLabel: 'Description',
                    name: 'description',
                    emptyText: 'Event Description',
                    labelWidth: 100
                },
                {
                    xtype: 'daterangefield',
                    itemId: 'date-range',
                    name: 'dates',
                    anchor: '100%',
                    fieldLabel: 'When',
                    labelWidth: 100
                },
                {
                    xtype: 'numberfield',
                    fieldLabel: 'Attendees',
                    labelWidth: 100,
                    value: 3,
                    maxValue: 99,
                    minValue: 0,
                    name: 'attendees',
                    emptyText: 'Attendees Title',
                    anchor: '50%'
                },
                {
                    xtype: 'textfield',
                    fieldLabel: 'Owner',
                    name: 'owner',
                    itemId: 'owner',
                    labelWidth: 100,
                    readOnly: true,
                    anchor: '50%'
                },
                {
                    xtype: 'textfield',
                    fieldLabel: 'Room',
                    name: 'room_id',
                    hidden: true
                }
            ]
        };

        if (config.calendarStore) {
            this.calendarStore = config.calendarStore;
            delete config.calendarStore;

            formPanelCfg.items.push({
                xtype: 'calendarpicker',
                itemId: 'calendar',
                name: 'room_id',
                labelWidth: 100,
                anchor: '100%',
                store: this.calendarStore
            });
        }

        this.callParent([Ext.apply({
                titleTextAdd: 'Add Event',
                titleTextEdit: 'Edit Event',
                width: 620,
                autocreate: true,
                border: true,
                closeAction: 'hide',
                modal: false,
                resizable: false,
                buttonAlign: 'left',
                savingMessage: 'Saving changes...',
                deletingMessage: 'Deleting event...',
                layout: 'fit',

                defaultFocus: 'title',
                onEsc: function (key, event) {
                    event.target.blur(); // Remove the focus to avoid doing the validity checks when the window is shown again.
                    this.onCancel();
                },

                fbar: [
                    {
                        xtype: 'tbtext'
                    },
                    '->',
                    {
                        itemId: 'delete-btn',
                        text: 'Delete Event',
                        disabled: false,
                        handler: this.onDelete,
                        scope: this,
                        minWidth: 150,
                        hideMode: 'offsets'
                    },
                    {
                        text: 'Save',
                        disabled: false,
                        handler: this.onSave,
                        scope: this
                    },
                    {
                        text: 'Cancel',
                        disabled: false,
                        handler: this.onCancel,
                        scope: this
                    }
                ],
                items: formPanelCfg
            },
            config)]);
    },

    // private
    initComponent: function () {
        this.callParent();

        this.formPanel = this.items.items[0];

        this.addEvents({
            /**
             * @event eventadd
             * Fires after a new event is added
             * @param {Ext.calendar.form.EventWindow} this
             * @param {Ext.calendar.EventRecord} rec The new {@link Ext.calendar.EventRecord record} that was added
             */
            eventadd: true,
            /**
             * @event eventupdate
             * Fires after an existing event is updated
             * @param {Ext.calendar.form.EventWindow} this
             * @param {Ext.calendar.EventRecord} rec The new {@link Ext.calendar.EventRecord record} that was updated
             */
            eventupdate: true,
            /**
             * @event eventdelete
             * Fires after an event is deleted
             * @param {Ext.calendar.form.EventWindow} this
             * @param {Ext.calendar.EventRecord} rec The new {@link Ext.calendar.EventRecord record} that was deleted
             */
            eventdelete: true,
            /**
             * @event eventcancel
             * Fires after an event add/edit operation is canceled by the user and no store update took place
             * @param {Ext.calendar.form.EventWindow} this
             * @param {Ext.calendar.EventRecord} rec The new {@link Ext.calendar.EventRecord record} that was canceled
             */
            eventcancel: true,
            /**
             * @event editdetails
             * Fires when the user selects the option in this window to continue editing in the detailed edit form
             * (by default, an instance of {@link Ext.calendar.EventEditForm}. Handling code should hide this window
             * and transfer the current event record to the appropriate instance of the detailed form by showing it
             * and calling {@link Ext.calendar.EventEditForm#loadRecord loadRecord}.
             * @param {Ext.calendar.form.EventWindow} this
             * @param {Ext.calendar.EventRecord} rec The {@link Ext.calendar.EventRecord record} that is currently being edited
             */
            editdetails: true
        });
    },

    // private
    afterRender: function () {
        this.callParent();

        this.el.addCls('ext-cal-event-win');

        this.titleField = this.down('#title');
        this.ownerField = this.down('#owner');
        this.dateRangeField = this.down('#date-range');
        this.calendarField = this.down('#calendar');
        this.deleteButton = this.down('#delete-btn');
    },

    /**
     * Shows the window, rendering it first if necessary, or activates it and brings it to front if hidden.
     * @param {Ext.data.Record/Object} o Either a {@link Ext.data.Record} if showing the form
     * for an existing event in edit mode, or a plain object containing a StartDate property (and
     * optionally an EndDate property) for showing the form in add mode.
     * @param {String/Element} animateTarget (optional) The target element or id from which the window should
     * animate while opening (defaults to null with no animation)
     * @return {Ext.Window} this
     */
    show: function (o, animateTarget) {
        // Work around the CSS day cell height hack needed for initial render in IE8/strict:
        var me = this,
            anim = (Ext.isIE8 && Ext.isStrict) ? null : animateTarget;

        this.callParent([anim, function () {
            me.titleField.focus(true);
        }]);

        this.deleteButton[o.data && o.data.id ? 'show' : 'hide']();

        var rec,
            f = this.formPanel.form;

        f.reset();

        if (o.data) {

            rec = o;
            this.setTitle(rec.phantom ? this.titleTextAdd : this.titleTextEdit);
            this.ownerField.show();

            f.loadRecord(rec);
        } else {
            this.setTitle(this.titleTextAdd);
            this.ownerField.hide();

            var start = o['date_start'],
                end = o['date_end'] || Ext.calendar.util.Date.add(start, {hours: 0.5});

            rec = Ext.create('Ext.calendar.data.EventModel');
            rec.data['date_start'] = start;
            rec.data['date_end'] = end;
            rec.data['owner'] = Ext.currentUser;
            rec.data['attendees'] = 3;
            rec.data['room_id'] = Ext.currentCalendarId;

            f.loadRecord(rec);
        }

        if (this.calendarStore) {
            this.calendarField.setValue(rec.data['room_id']);
        }

        this.dateRangeField.setValue(rec.data);
        this.activeRecord = rec;

        return this;
    },

    // private
    onCancel: function () {
        this.cleanup(true);
        this.fireEvent('eventcancel', this);
    },

    // private
    cleanup: function (hide) {
        if (this.activeRecord && this.activeRecord.dirty) {
            this.activeRecord.reject();
        }
        delete this.activeRecord;

        if (hide === true) {
            // Work around the CSS day cell height hack needed for initial render in IE8/strict:
            //var anim = afterDelete || (Ext.isIE8 && Ext.isStrict) ? null : this.animateTarget;
            this.hide();
        }
    },

    // private
    updateRecord: function (record, keepEditing) {
        var fields = record.fields,
            values = this.formPanel.getForm().getValues(),
            name,
            obj = {};

        fields.each(function (f) {
            name = f.name;
            if (name in values) {
                obj[name] = values[name];
            }
        });

        var dates = this.dateRangeField.getValue();
        obj['date_start'] = dates[0];
        obj['date_end'] = dates[1];

        record.beginEdit();
        record.set(obj);

        if (!keepEditing) {
            record.endEdit();
        }

        return this;
    },

    // private
    onSave: function () {

        if (!this.formPanel.form.isValid()) {
            return;
        }

        if (!this.updateRecord(this.activeRecord)) {
            this.onCancel();
            return;
        }

        if (this.activeRecord.data.date_start.getTime() == this.activeRecord.data.date_end.getTime()) {
            Ext.noty('Wrong time selected', 'error', 1000);
            return;
        }

        this.fireEvent(this.activeRecord.phantom ? 'eventadd' : 'eventupdate', this, this.activeRecord, this.animateTarget);

    },

    // private
    onDelete: function () {
        this.fireEvent('eventdelete', this, this.activeRecord, this.animateTarget);
    }
});