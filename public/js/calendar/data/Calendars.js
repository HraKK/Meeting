Ext.define('Ext.calendar.data.Calendars', {
    statics: {
        getData: function() {
            return {
                "calendars": [
                    {
                        id: '1',
                        title: 'Yellow Room',
                        description: 'Meeting room in the building 2c.'
                    },
                    {
                        id: '2',
                        title: 'Green Room',
                        description: 'Main meeting room on the third floor.'
                    },
                    {
                        id: '3',
                        title: 'Small Hall Room #1',
                        description: 'Small meeting room in the hall.'
                    },
                    {
                        id: '4',
                        title: 'Small Hall Room #2',
                        description: 'Small meeting room in the hall.'
                    },
                    {
                        id: '5',
                        title: 'Big Hall Room',
                        description: 'Big meeting room in the hall.'
                    }
                ]
            };
        }
    }
});