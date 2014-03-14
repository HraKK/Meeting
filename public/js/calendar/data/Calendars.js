Ext.define('Ext.calendar.data.Calendars', {
    statics: {
        getData: function() {
            return {
                "calendars": [
                    {
                        id: '1',
                        title: 'Yellow Room',
                        desc: 'Meeting room in the building 2c.'
                    },
                    {
                        id: '2',
                        title: 'Green Room',
                        desc: 'Main meeting room on the third floor.'
                    },
                    {
                        id: '3',
                        title: 'Lobby Small Room #1',
                        desc: 'Small meeting room in the lobby.'
                    },
                    {
                        id: '4',
                        title: 'Lobby Small Room #2',
                        desc: 'Small meeting room in the lobby.'
                    },
                    {
                        id: '5',
                        title: 'Lobby Big Room',
                        desc: 'Big meeting room in the lobby.'
                    }
                ]
            };
        }
    }
});