Ext.define('Ext.calendar.data.EventModel', {
    extend: 'Ext.data.Model',

    fields: [
        {
            name: 'id',
            type: 'int'
        },
        {
            name: 'room_id',
            type: 'int'
        },
        {
            name: 'title',
            type: 'string'
        },
        {
            name: 'description',
            type: 'string'
        },
        {
            name: 'attendees',
            type: 'int'
        },
        {
            name: 'date_start',
            type: 'date',
            dateFormat: 'timestamp'
        },
        {
            name: 'date_end',
            type: 'date',
            dateFormat: 'timestamp'
        },
        {
            name: 'n',
            type: 'boolean'
        },
        {
            name: 'owner',
            type: 'string'
        },
        {
            name: 'repeatable',
            type: 'boolean'
        },
        {
            name: 'repeated_on'
        },
        {
            name: 'hidden',
            type: 'boolean'
        }
    ]
});