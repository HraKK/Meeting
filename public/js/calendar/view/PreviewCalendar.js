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

        var me = this,
            dateFormat = 'd/m/y',
            today = new Date();

        me.callParent(arguments);

        if (!Ext.simpleInterface) {
            return;
        }

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

            me.calendarStore.each(function(room) {
                header += '<th>' +
                    '<i class="room-tab-icon room-tab-icon-' + room.get('id') + '"></i>' + room.get('title') +
                    '</th>';
            });

            return '<thead><tr><th class="first">Time&nbsp;/&nbsp;Rooms</th>' + header + '</tr></thead>';
        }

        function createBody() {
            var rows = '',
                dt = Ext.Date.clearTime(new Date('5/26/1972 09:00'));

            dt = Ext.calendar.util.Date.add(dt, {hours: 9});

            for (var i = 9; i < 20; i++) {
                rows += '<tr>' +
                    '<td class="first">' + Ext.Date.format(dt, 'H:i') + '</td>' +
                    getCells(dt) +
                    '</tr>';
                dt = Ext.calendar.util.Date.add(dt, {hours: 1});
            }

            return '<tbody>' + rows + '</tbody>';
        }

        function getCells(dt) {
            var cells = '',
                todayDate = Ext.Date.format(today, dateFormat),
                todayDay = today.getDay() - 1,
                cell;

            me.calendarStore.each(function(room) {
                cell = '<td><div class="meeting-preview-event-separator"></div>';
                me.eventStore.each(function(event) {
                    var startDate = event.get('date_start'),
                        endDate = event.get('date_end');

                    if (event.get('room_id') == room.get('id')) {
                        if (event.get('repeatable') && Ext.Array.contains(event.get('repeated_on'), todayDay) && dt.getHours() == startDate.getHours()) {
                            cell += getCell(startDate, endDate, event.get('title'));
                        } else if (Ext.Date.format(startDate, dateFormat) == todayDate && dt.getHours() == startDate.getHours()) {
                            cell += getCell(startDate, endDate, event.get('title'));
                        }
                    }
                });
                cell += '</td>';
                cells += cell;
            });

            return cells;
        }

        function getCell(startDate, endDate, title) {
            var top = startDate.getMinutes() == 30 ? 24 : 0,
                diff = endDate.getHours() + endDate.getMinutes() / 60 - startDate.getHours() - startDate.getMinutes() / 60,
                height = 48 * diff - 1;

            return '<div class="meeting-preview-event" style="height: ' + height + 'px;top: ' + top + 'px;">' +
                '<strong>' + Ext.Date.format(startDate, 'H:i') + '</strong> ' + title +
                '</div>';
        }

        me.eventStore.on('load', buildTable);

    }
});