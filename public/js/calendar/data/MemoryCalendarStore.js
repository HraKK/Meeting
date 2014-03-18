/*
 * A simple reusable store that loads static calendar field definitions into memory
 * and can be bound to the CalendarCombo widget and used for calendar color selection.
 */
Ext.define('Ext.calendar.data.MemoryCalendarStore', {
    extend: 'Ext.data.Store',
    model: 'Ext.calendar.data.CalendarModel',

    requires: [
        'Ext.data.proxy.Memory',
        'Ext.data.reader.Json',
        'Ext.data.writer.Json',
        'Ext.calendar.data.CalendarModel',
        'Ext.calendar.data.CalendarMappings'
    ],

    proxy: {
        type: 'ajax',
        reader: {
            type: 'json',
            root: 'rooms'
        },
        writer: {
            type: 'json'
        },
        api: {
            read: '/js/calendar/json/rooms.json',
            create: '',
            update: '',
            destroy: ''
        }
    },

    autoLoad: true,

    initComponent: function() {
        var me = this,
            calendarData = Ext.calendar.data;

        me.sorters = me.sorters || [{
            property: calendarData.CalendarMappings.Title.name,
            direction: 'ASC'
        }];

        me.idProperty = me.idProperty || calendarData.CalendarMappings.CalendarId.name || 'id';

        me.fields = calendarData.CalendarModel.prototype.fields.getRange();

        me.callParent(arguments);
    }
});