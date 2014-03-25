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
            name: 'mon',
            type: 'boolean'
        },
        {
            name: 'tue',
            type: 'boolean'
        },
        {
            name: 'wed',
            type: 'boolean'
        },
        {
            name: 'thu',
            type: 'boolean'
        },
        {
            name: 'fri',
            type: 'boolean'
        },
        {
            name: 'sat',
            type: 'boolean'
        },
        {
            name: 'sun',
            type: 'boolean'
        }
    ]
});