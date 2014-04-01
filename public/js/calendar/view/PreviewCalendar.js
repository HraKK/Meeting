Ext.define('Ext.calendar.view.PreviewCalendar', {
    extend: 'Ext.container.Container',
    alias: 'widget.previewcalendar',

    style: {
        background: '#fff'
    },
    border: false,
    bodyStyle: {
        border: false
    },

    constructor: function() {

        var me = this;

        me.callParent(arguments);

        /*

         Time/Room | Yellow | Red | Green

         09:00      ||||||||||||||

         10:00      |||||||        ||||||||

         11:00             ||||||||||||||||

         12:00      |||||||||||||||||||||||

         13:00      |||||||        ||||||||

          */

        function insertTable(dom) {
            me.update('<table class="meeting-preview">' + dom + '</table>');
        }

        function buildTable() {
            me.calendarStore.on('load', function() {
                insertTable(createHeader() + createBody());
            });
        }

        function createHeader() {
            var header = '';

            me.calendarStore.each(function(record) {
                header += '<th>' +
                    '<i class="room-tab-icon room-tab-icon-' + record.get('id') + '"></i>' + record.get('title') +
                    '</th>';
            });

            return '<thead><tr><th class="first">Time&nbsp;/&nbsp;Rooms</th>' + header + '</tr></thead>';
        }

        function createBody() {
            var rows = '',
                dt = Ext.Date.clearTime(new Date('5/26/1972 09:00')),
                today = new Date();

            dt = Ext.calendar.util.Date.add(dt, {hours: 9});

            for (var i = 9; i < 20; i++) {
                rows += '<tr>' +
                    '<td class="first">' + Ext.Date.format(dt, 'H:i') + '</td>' +
                    getCells(today, dt) +
                    '</tr>';
                dt = Ext.calendar.util.Date.add(dt, {hours: 1});
            }

            return '<tbody>' + rows + '</tbody>';
        }

        function getCells(today, dt) {
            var cells = '';

            me.calendarStore.each(function(record) {
                cells += '<td></td>';
            });

//            console.log(today);
//            console.log(dt);

            me.eventStore.each(function(record) {
//                console.log(record.get('date_start'));
//                console.log(record.get('date_end'));
//                console.log(record.get('repeatable'));
//                console.log(record.get('repeated_on'));
//                console.log(record.get('room_id'));
//                console.log(record.get('title'));
            });

            return cells;
        }

        me.eventStore.on('load', buildTable);

    }
});