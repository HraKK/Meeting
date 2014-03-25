Ext.define('Ext.calendar.data.Days', {
    extend: 'Ext.data.Store',

    fields: [
        {name: 'name'},
        {name: 'value'},
        {name: 'id'}
    ],
    data: [
        {name: 'Monday', value: 'mon', id: 0},
        {name: 'Tuesday', value: 'tue', id: 1},
        {name: 'Wednesday', value: 'wed', id: 2},
        {name: 'Thursday', value: 'thu', id: 3},
        {name: 'Friday', value: 'fri', id: 4},
        {name: 'Saturday', value: 'sat', id: 5},
        {name: 'Sunday', value: 'sun', id: 6}
    ]
});