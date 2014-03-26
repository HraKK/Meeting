/*
 * This is a simple in-memory store implementation that is ONLY intended for use with
 * calendar samples running locally in the browser with no external data source. Under
 * normal circumstances, stores that use a MemoryProxy are read-only and intended only
 * for displaying data read from memory. In the case of the calendar, it's still quite
 * useful to be able to deal with in-memory data for sample purposes (as many people 
 * may not have PHP set up to run locally), but by default, updates will not work since the
 * calendar fully expects all CRUD operations to be supported by the store (and in fact
 * will break, for example, if phantom records are not removed properly). This simple
 * class gives us a convenient way of loading and updating calendar event data in memory,
 * but should NOT be used outside of the local samples.
 */
Ext.define('Ext.calendar.data.MemoryEventStore', {
    extend: 'Ext.data.Store',
    model: 'Ext.calendar.data.EventModel',

    requires: [
        'Ext.data.proxy.Memory',
        'Ext.data.reader.Json',
        'Ext.data.writer.Json',
        'Ext.calendar.data.EventModel'
    ],

    proxy: {
        type: 'ajax',
        reader: {
            type: 'json',
            root: 'events'
        },
        writer: {
            type: 'eventwriter'
        },
        actionMethods: {
            create: 'POST',
            read: 'POST',
            update: 'POST',
            destroy: 'POST'
        },
        api: {
            read: '/event/index',
            create: '/event/create',
            update: '/event/update',
            destroy: '/event/delete'
        }
    },

    // private
    constructor: function (config) {
        this.callParent(arguments);

        this.sorters = this.sorters || [
            {
                property: 'date_start',
                direction: 'ASC'
            }
        ];

        this.idProperty = this.idProperty || 'id';
        this.fields = Ext.calendar.data.EventModel.prototype.fields.getRange();
        this.initRecs();
    },

    // If the store started with preloaded inline data, we have to make sure the records are set up
    // properly as valid "saved" records otherwise they may get "added" on initial edit.
    initRecs: function () {
        this.each(function (rec) {
            rec.store = this;
            rec.phantom = false;
        }, this);
    },

    // private - override the default logic for memory storage
    onProxyLoad: function (operation) {
        var me = this,
            records;

        if (me.data && me.data.length > 0) {
            // this store has already been initially loaded, so do not reload
            // and lose updates to the store, just use store's latest data
            me.totalCount = me.data.length;
            records = me.data.items;
        } else {
            // this is the initial load, so defer to the proxy's result
            var resultSet = operation.getResultSet(),
                successful = operation.wasSuccessful();

            records = operation.getRecords();

            if (resultSet) {
                me.totalCount = resultSet.total;
            }

            if (successful) {
                me.loadRecords(records, operation);
            }
        }

        me.loading = false;
        me.fireEvent('load', me, records, successful);
    }
});

Ext.define('Ext.calendar.writer.MemoryEventStore', {
    extend: 'Ext.data.writer.Json',
    alias: 'writer.eventwriter',
    getRecordData: function (model, operation) {

        var serialized = this.callParent([model, operation]);

        serialized.date_start = parseInt(serialized.date_start, 10);
        serialized.date_end = parseInt(serialized.date_end, 10);

        return  serialized;

    }
});