/**S
 * @class Ext.calendar.view.DayBody
 * @extends Ext.calendar.view.AbstractCalendar
 * <p>This is the scrolling container within the day and week views where non-all-day events are displayed.
 * Normally you should not need to use this class directly -- instead you should use {@link Ext.calendar.DayView DayView}
 * which aggregates this class and the {@link Ext.calendar.DayHeaderView DayHeaderView} into the single unified view
 * presented by {@link Ext.calendar.CalendarPanel CalendarPanel}.</p>
 * @constructor
 * @param {Object} config The config object
 */
Ext.define('Ext.calendar.view.DayBody', {
    extend: 'Ext.calendar.view.AbstractCalendar',
    alias: 'widget.daybodyview',

    requires: [
        'Ext.XTemplate',
        'Ext.calendar.template.DayBody',
        'Ext.calendar.dd.DayDragZone',
        'Ext.calendar.dd.DayDropZone'
    ],

    //private
    dayColumnElIdDelimiter: '-day-col-',

    //private
    initComponent: function() {
        this.callParent(arguments);

        this.addEvents({
            /**
             * @event eventresize
             * Fires after the user drags the resize handle of an event to resize it
             * @param {Ext.calendar.view.DayBody} this
             * @param {Ext.calendar.EventRecord} rec The {@link Ext.calendar.EventRecord record} for the event that was resized
             * containing the updated start and end dates
             */
            eventresize: true,
            /**
             * @event dayclick
             * Fires after the user clicks within the day view container and not on an event element
             * @param {Ext.calendar.view.DayBody} this
             * @param {Date} dt The date/time that was clicked on
             * @param {Boolean} allday True if the day clicked on represents an all-day box, else false. Clicks within the
             * DayBodyView always return false for this param.
             * @param {Ext.core.Element} el The Element that was clicked on
             */
            dayclick: true
        });
    },

    //private
    initDD: function() {
        var cfg = {
            createText: this.ddCreateEventText,
            moveText: this.ddMoveEventText,
            resizeText: this.ddResizeEventText
        };

        this.el.ddScrollConfig = {
            // scrolling is buggy in IE/Opera for some reason.  A larger vthresh
            // makes it at least functional if not perfect
            vthresh: Ext.isIE || Ext.isOpera ? 100 : 40,
            hthresh: -1,
            frequency: 50,
            increment: 100,
            ddGroup: 'DayViewDD'
        };
        this.dragZone = new Ext.calendar.dd.DayDragZone(this.el, Ext.apply({
                view: this,
                containerScroll: true
            },
            cfg)
        );

        this.dropZone = new Ext.calendar.dd.DayDropZone(this.el, Ext.apply({
                view: this
            },
            cfg)
        );
    },

    //private
    refresh: function() {
        var top = this.el.getScroll().top;
        this.prepareData();
        this.renderTemplate();
        this.renderItems();

        // skip this if the initial render scroll position has not yet been set.
        // necessary since IE/Opera must be deferred, so the first refresh will
        // override the initial position by default and always set it to 0.
        if (this.scrollReady) {
            this.scrollTo(top);
        }
    },

    /**
     * Scrolls the container to the specified vertical position. If the view is large enough that
     * there is no scroll overflow then this method will have no effect.
     * @param {Number} y The new vertical scroll position in pixels
     * @param {Boolean} defer (optional) <p>True to slightly defer the call, false to execute immediately.</p>
     * <p>This method will automatically defer itself for IE and Opera (even if you pass false) otherwise
     * the scroll position will not update in those browsers. You can optionally pass true, however, to
     * force the defer in all browsers, or use your own custom conditions to determine whether this is needed.</p>
     * <p>Note that this method should not generally need to be called directly as scroll position is managed internally.</p>
     */
    scrollTo: function(y, defer) {
        defer = defer || (Ext.isIE || Ext.isOpera);
        if (defer) {
            Ext.defer(function() {
                this.el.scrollTo('top', y, true);
                this.scrollReady = true;
            }, 10, this);
        } else {
            this.el.scrollTo('top', y, true);
            this.scrollReady = true;
        }
    },

    // private
    afterRender: function() {
        if (!this.tpl) {
            this.tpl = new Ext.calendar.template.DayBody({
                id: this.id,
                dayCount: this.dayCount,
                showTodayText: this.showTodayText,
                todayText: this.todayText,
                showTime: this.showTime
            });
        }
        this.tpl.compile();

        this.addCls('ext-cal-body-ct');

        this.callParent(arguments);

        // default scroll position to 7am: 7 * 48
        this.scrollTo(0);
    },

    // private
    forceSize: Ext.emptyFn,

    // private
    onEventResize: function(rec, data) {
        var D = Ext.calendar.util.Date,
            start = 'date_start',
            end = 'date_end';

        if (D.compare(rec.data[start], data.date_start) === 0 &&
            D.compare(rec.data[end], data.date_end) === 0) {
            // no changes
            return;
        }
        rec.set(start, data.date_start);
        rec.set(end, data.date_end);

        this.fireEvent('eventresize', this, rec);
    },

    // inherited docs
    getEventBodyMarkup: function() {
        if (!this.eventBodyMarkup) {
            this.eventBodyMarkup = [
                '{title}',
                '<tpl if="owner == Ext.currentUser">',
                    '<i class="ext-owner-icon"></i>',
                '</tpl>'
            ].join('');
        }
        return this.eventBodyMarkup;
    },

    getResizeEl: function() {
        if (!this.eventResizeMarkup) {
            this.eventResizeMarkup = [
                '<tpl if="owner == Ext.currentUser && !Ext.simpleInterface">',
                    '<div class="ext-evt-rsz">',
                        '<div class="ext-evt-rsz-h">&#160;</div>',
                    '</div>',
                '<tpl else></tpl>'
            ].join('');
        }
        return this.eventResizeMarkup;
    },

    getDragSelector: function() {
        if (!this.eventDragSelector) {
            this.eventDragSelector = [
                '<tpl if="owner == Ext.currentUser && !Ext.simpleInterface">',
                    'ext-cal-evt ext-cal-evt-draggable',
                '<tpl else>',
                    'ext-cal-evt',
                '</tpl>'
            ].join('');
        }
        return this.eventDragSelector;
    },

    // inherited docs
    getEventTemplate: function() {
        if (!this.eventTpl) {
            this.eventTpl = !(Ext.isIE || Ext.isOpera) ?
                new Ext.XTemplate(
                    '<div id="{_elId}" class="{_selectorCls} {_colorCls} ' + this.getDragSelector() + ' ext-cal-evr is-hidden-{hidden}" style="left: 0; width: 100%; top: {_top}px; height: {_height}px;" data-qtip="Booked by <strong>{owner}</strong>">',
                        '<div class="ext-evt-bd">', this.getEventBodyMarkup(), '</div>',
                        this.getResizeEl(),
                    '</div>'
                )
                : new Ext.XTemplate(
                '<div id="{_elId}" class="' + this.getDragSelector() + ' {_selectorCls} {_colorCls}-x is-hidden-{hidden}" style="left: 0; width: 100%; top: {_top}px;">',
                    '<div class="ext-cal-evb">&#160;</div>',
                    '<dl style="height: {_height}px;" class="ext-cal-evdm">',
                        '<dd class="ext-evt-bd">',
                            this.getEventBodyMarkup(),
                        '</dd>',
                        this.getResizeEl(),
                    '</dl>',
                    '<div class="ext-cal-evb">&#160;</div>',
                '</div>'
            );
            this.eventTpl.compile();
        }
        return this.eventTpl;
    },

    // private
    getTemplateEventData: function(evt) {
        var selector = this.getEventSelectorCls(evt['id']),
            data = {};

        this.getTemplateEventBox(evt);

        data._selectorCls = selector;
        data._colorCls = 'ext-color-' + (evt['id'] || '0');
        data._elId = selector + (evt._weekIndex ? '-' + evt._weekIndex : '');
        data._isRecurring = evt.Recurrence && evt.Recurrence != '';
        var title = evt['title'];
        data.title = Ext.Date.format(evt['date_start'], 'H:i ') + (!title || title.length == 0 ? '(No title)' : title);

        return Ext.applyIf(data, evt);
    },

    // private
    getTemplateEventBox: function(evt) {
        var timeCoef = 48 / 60,
            start = evt['date_start'],
            end = evt['date_end'],
            startMins = start.getHours() * 60 + start.getMinutes(),
            endMins = end.getHours() * 60 + end.getMinutes(),
            diffMins = endMins - startMins;
        evt._left = 0;
        evt._width = 100;
        evt._top = Math.round(startMins * timeCoef);
        evt._height = Math.round(diffMins * timeCoef);
    },

    // private
    renderItems: function() {
        var me = this,
            day = 0,
            evts = [],
            ev,
            d,
            ct,
            item,
            i,
            j,
            l,
            emptyCells, skipped,
            evt,
            evt2,
            overlapCols,
            prevCol,
            colWidth,
            evtWidth,
            markup,
            target;

        for (; day < this.dayCount; day++) {
            ev = emptyCells = skipped = 0;
            d = this.eventGrid[0][day];
            ct = d ? d.length : 0;

            for (; ev < ct; ev++) {
                evt = d[ev];
                if (!evt) {
                    continue;
                }
                item = evt.data || evt.event.data;
                Ext.apply(item, {
                    cls: 'ext-cal-ev',
                    _positioned: true
                });
                evts.push({
                    data: this.getTemplateEventData(item),
                    date: Ext.calendar.util.Date.add(this.viewStart, {days: day})
                });
            }
        }

        // overlapping event pre-processing loop
        i = j = overlapCols = prevCol = 0;
        l = evts.length;

        for (; i < l; i++) {
            evt = evts[i].data;
            evt2 = null;
            prevCol = overlapCols;
            for (j = 0; j < l; j++) {
                if (i == j) {
                    continue;
                }
                evt2 = evts[j].data;
                if (this.isOverlapping(evt, evt2)) {
                    evt._overlap = evt._overlap == undefined ? 1 : evt._overlap + 1;
                    if (i < j) {
                        if (evt._overcol === undefined) {
                            evt._overcol = 0;
                        }
                        evt2._overcol = evt._overcol + 1;
                        overlapCols = Math.max(overlapCols, evt2._overcol);
                    }
                }
            }
        }

        var isWeekView = this.eventGrid[0].length > 1;

        // rendering loop
        for (i = 0; i < l; i++) {

            evt = evts[i].data;

            if (evt._overlap !== undefined) {
                colWidth = 100 / (overlapCols + 1);
                evtWidth = 100 - (colWidth * evt._overlap);
                evt._width = colWidth;
                evt._left = colWidth * evt._overcol;
            }

            if (evt.repeatable == true) {

                var j,
                    evtClone,
                    todayDay = (new Date()).getDay() - 1,
                    evtCloneDay,
                    markupClone,
                    timeClone,
                    targetClone;

                if (isWeekView) {

                    if (evts[i].date.getDay() != 1) {
                        continue;
                    }

                    for (j = 0; j < evt.repeated_on.length; j++) {

                        evtCloneDay = evt.repeated_on[j] - todayDay;
                        evtClone = Ext.clone(evt);
                        evtClone.date_start = Ext.calendar.util.Date.add(evtClone.date_start, {days: evtCloneDay});
                        evtClone.date_end = Ext.calendar.util.Date.add(evtClone.date_end, {days: evtCloneDay});

                        timeClone = Ext.calendar.util.Date.add(evts[i].date, {days: evt.repeated_on[j]});
                        targetClone = me.id + '-day-col-' + Ext.Date.format(timeClone, 'Ymd');

                        if (Ext.get(targetClone) != null) {
                            markupClone = me.getEventTemplate().apply(evtClone);
                            Ext.core.DomHelper.append(targetClone, markupClone);
                        }

                    }

                } else {

                    for (j = 0; j < evt.repeated_on.length; j++) {

                        todayDay = evts[i].date.getDay();
                        evtCloneDay = (todayDay == 0) ? 6 : (todayDay - 1);

                        if (evt.repeated_on[j] != evtCloneDay) {
                            continue;
                        }

                        evtClone = Ext.clone(evt);
                        markupClone = me.getEventTemplate().apply(evtClone);
                        targetClone = me.id + '-day-col-' + Ext.Date.format(evts[i].date, 'Ymd');

                        if (Ext.get(targetClone) != null) {
                            Ext.core.DomHelper.append(targetClone, markupClone);
                        }

                    }
                }

            } else {

                markup = this.getEventTemplate().apply(evt);
                target = this.id + '-day-col-' + Ext.Date.format(evts[i].date, 'Ymd');

                Ext.core.DomHelper.append(target, markup);

            }
        }

        this.fireEvent('eventsrendered', this);
    },

    // private
    getDayEl: function(dt) {
        return Ext.get(this.getDayId(dt));
    },

    // private
    getDayId: function(dt) {
        if (Ext.isDate(dt)) {
            dt = Ext.Date.format(dt, 'Ymd');
        }
        return this.id + this.dayColumnElIdDelimiter + dt;
    },

    // private
    getDaySize: function() {
        var box = this.el.down('.ext-cal-day-col-inner').getBox();
        return {
            height: box.height,
            width: box.width
        };
    },

    // private
    getDayAt: function(x, y) {

        var xoffset = this.el.down('.ext-cal-day-times').getWidth(),
            viewBox = this.el.getBox(),
            daySize = this.getDaySize(false),
            relX = x - viewBox.x - xoffset,
            dayIndex = Math.floor(relX / daySize.width),
        // clicked col index
            scroll = this.el.getScroll(),
            row = this.el.down('.ext-cal-bg-row'),
        // first avail row, just to calc size
            rowH = row.getHeight() / 2,
        // 30 minute increment since a row is 60 minutes
            relY = y - viewBox.y - rowH + scroll.top + 432,// 432=48*9
            rowIndex = Math.max(0, Math.ceil(relY / rowH)),
            mins = rowIndex * 30,
            dt = Ext.calendar.util.Date.add(this.viewStart, {days: dayIndex, minutes: mins}),
            el = this.getDayEl(dt),
            timeX = x;

        if (el) {
            timeX = el.getX();
        }

        return {
            date: dt,
            el: el,
            // this is the box for the specific time block in the day that was clicked on:
            timeBox: {
                x: timeX,
                y: (rowIndex * 24) + viewBox.y - scroll.top - 432, // 432=48*9
                width: daySize.width,
                height: rowH
            }
        };
    },

    // private
    onClick: function(e, t) {
        if (this.dragPending || Ext.calendar.view.DayBody.superclass.onClick.apply(this, arguments)) {
            // The superclass handled the click already so exit
            return;
        }

        if (e.getTarget('.ext-cal-day-times', 3) !== null) {
            // ignore clicks on the times-of-day gutter
            return;
        }

        var el = e.getTarget('td', 3);

        if (el) {
            if (el.id && el.id.indexOf(this.dayElIdDelimiter) > -1 && Ext.simpleInterface) {
                var dt = this.getDateFromId(el.id, this.dayElIdDelimiter);
                this.fireEvent('dayclick', this, Ext.Date.parseDate(dt, 'Ymd'), false, Ext.get(this.getDayId(dt, true)));
                return;
            }
        }

        var day = this.getDayAt(e.getX(), e.getY());

        if (day && day.date && Ext.simpleInterface) {
            this.fireEvent('dayclick', this, day.date, false, null);
        }
    }
});