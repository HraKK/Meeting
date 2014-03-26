Ext.define('Ext.calendar.data.Days', {
    extend: 'Ext.data.Store',

    fields: [
        {name: 'name'},
        {name: 'value'}
    ],
    data: [
        {name: 'Monday', value: 0},
        {name: 'Tuesday', value: 1},
        {name: 'Wednesday', value: 2},
        {name: 'Thursday', value: 3},
        {name: 'Friday', value: 4},
        {name: 'Saturday', value: 5},
        {name: 'Sunday', value: 6}
    ]
});