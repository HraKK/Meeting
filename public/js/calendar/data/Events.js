Ext.define('Ext.calendar.data.Events', {

    statics: {
        getData: function() {
            var today = Ext.Date.clearTime(new Date()),
                makeDate = function(d, h, m, s) {
                    d = d * 86400;
                    h = (h || 0) * 3600;
                    m = (m || 0) * 60;
                    s = (s || 0);
                    return Ext.Date.add(today, Ext.Date.SECOND, d + h + m + s);
                };

            return {
                "evts": [
                    {
                        "id": 1001,
                        "room": 1,
                        "title": "Vacation",
                        "start": makeDate(0, 11, 30),
                        "end": makeDate(0, 13),
                        "notes": "Have fun",
                        "owner": "stevejobs"
                    },
                    {
                        "id": 1002,
                        "room": 2,
                        "title": "Lunch with Matt",
                        "start": makeDate(0, 11, 30),
                        "end": makeDate(0, 13),
                        "loc": "Chuy's!",
                        "url": "http://chuys.com",
                        "notes": "Order the queso",
                        "rem": "15",
                        "owner": "alancarr"
                    },
                    {
                        "id": 1003,
                        "room": 3,
                        "title": "Project due",
                        "start": makeDate(0, 11, 30),
                        "end": makeDate(0, 13),
                        "owner": "zelazny"
                    },
                    {
                        "id": 1004,
                        "room": 4,
                        "title": "Sarah's birthday",
                        "start": makeDate(0, 11, 30),
                        "end": makeDate(0, 13),
                        "notes": "Need to get a gift",
                        "owner": "tommy"
                    },
                    {
                        "id": 1005,
                        "room": 5,
                        "title": "A long one...",
                        "start": makeDate(0, 11, 30),
                        "end": makeDate(0, 13),
                        "owner": "bogi"
                    },
                    {
                        "id": 1006,
                        "room": 4,
                        "title": "School holiday",
                        "start": makeDate(0, 9),
                        "end": makeDate(0, 9, 30),
                        "rem": "2880",
                        "owner": "moo"
                    },
                    {
                        "id": 1007,
                        "room": 3,
                        "title": "Haircut",
                        "start": makeDate(0, 9),
                        "end": makeDate(0, 9, 30),
                        "notes": "Get cash on the way",
                        "owner": "tyler"
                    },
                    {
                        "id": 1008,
                        "room": 2,
                        "title": "An old event",
                        "start": makeDate(0, 9),
                        "end": makeDate(0, 9, 30),
                        "owner": "maxanter"
                    },
                    {
                        "id": 1009,
                        "room": 1,
                        "title": "Board meeting",
                        "start": makeDate(0, 9),
                        "end": makeDate(0, 9, 30),
                        "loc": "ABC Inc.",
                        "rem": "60",
                        "owner": "cooper"
                    },
                    {
                        "id": 1010,
                        "room": 2,
                        "title": "Jenny's final exams",
                        "start": makeDate(0, 15),
                        "end": makeDate(0, 15, 30),
                        "owner": "stevejobs"
                    },
                    {
                        "id": 1011,
                        "room": 3,
                        "title": "Movie night",
                        "start": makeDate(0, 15),
                        "end": makeDate(0, 15, 30),
                        "notes": "Don't forget the tickets!",
                        "rem": "60",
                        "owner": "stevejobs"
                    }
                ]
            }
        }
    }
});