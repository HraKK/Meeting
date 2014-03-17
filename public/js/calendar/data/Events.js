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
                        "cid": 1,
                        "title": "Vacation",
                        "start": makeDate(0, 11, 30),
                        "end": makeDate(0, 13),
                        "notes": "Have fun"
                    },
                    {
                        "id": 1002,
                        "cid": 2,
                        "title": "Lunch with Matt",
                        "start": makeDate(0, 11, 30),
                        "end": makeDate(0, 13),
                        "loc": "Chuy's!",
                        "url": "http://chuys.com",
                        "notes": "Order the queso",
                        "rem": "15"
                    },
                    {
                        "id": 1003,
                        "cid": 3,
                        "title": "Project due",
                        "start": makeDate(0, 11, 30),
                        "end": makeDate(0, 13),
                    },
                    {
                        "id": 1004,
                        "cid": 4,
                        "title": "Sarah's birthday",
                        "start": makeDate(0, 11, 30),
                        "end": makeDate(0, 13),
                        "notes": "Need to get a gift"
                    },
                    {
                        "id": 1005,
                        "cid": 5,
                        "title": "A long one...",
                        "start": makeDate(0, 11, 30),
                        "end": makeDate(0, 13)
                    },
                    {
                        "id": 1006,
                        "cid": 4,
                        "title": "School holiday",
                        "start": makeDate(0, 9),
                        "end": makeDate(0, 9, 30),
                        "rem": "2880"
                    },
                    {
                        "id": 1007,
                        "cid": 3,
                        "title": "Haircut",
                        "start": makeDate(0, 9),
                        "end": makeDate(0, 9, 30),
                        "notes": "Get cash on the way"
                    },
                    {
                        "id": 1008,
                        "cid": 2,
                        "title": "An old event",
                        "start": makeDate(0, 9),
                        "end": makeDate(0, 9, 30),
                    },
                    {
                        "id": 1009,
                        "cid": 1,
                        "title": "Board meeting",
                        "start": makeDate(0, 9),
                        "end": makeDate(0, 9, 30),
                        "loc": "ABC Inc.",
                        "rem": "60"
                    },
                    {
                        "id": 1010,
                        "cid": 2,
                        "title": "Jenny's final exams",
                        "start": makeDate(0, 15),
                        "end": makeDate(0, 15, 30)
                    },
                    {
                        "id": 1011,
                        "cid": 3,
                        "title": "Movie night",
                        "start": makeDate(0, 15),
                        "end": makeDate(0, 15, 30),
                        "notes": "Don't forget the tickets!",
                        "rem": "60"
                    }
                ]
            }
        }
    }
});