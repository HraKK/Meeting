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
            today = Ext.Date.format(new Date(), dateFormat),
            hoursCoeficient = 1000 * 60 * 60;

        me.callParent(arguments);

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
                    getCells(today, dt) +
                    '</tr>';
                dt = Ext.calendar.util.Date.add(dt, {hours: 1});
            }

            return '<tbody>' + rows + '</tbody>';
        }

        function getCells(today, dt) {
            var cells = '',
                cell;

            me.calendarStore.each(function(room) {
                cell = '<td><div class="meeting-preview-event-separator"></div>';
                me.eventStore.each(function(event) {
                    var startDate = event.get('date_start'),
                        endDate = event.get('date_end'),
                        diff = (endDate.getTime() - startDate.getTime()) / hoursCoeficient,
                        top = 0,
                        height = 48;

                    if (event.get('room_id') == room.get('id')) {
                        if (event.get('repeatable')) {
                            // TODO check if it is repeatable today using event.get('repeated_on')
                        } else if (Ext.Date.format(startDate, dateFormat) == today) {
                            if (dt.getHours() == startDate.getHours()) {
                                height *= diff;
                                if (startDate.getMinutes() == 30) {
                                    top = 24;
                                }
                                cell += '<div class="meeting-preview-event" style="height: ' + height + 'px;top: ' + top + 'px;">' +
                                    '<strong>' + Ext.Date.format(startDate, 'H:i') + '</strong> ' +
                                    event.get('title') +
                                    '</div>';
                            }
                        }
                    }
                });
                cell += '</td>';
                cells += cell;
            });

            return cells;
        }

        me.eventStore.on('load', buildTable);

    }
});