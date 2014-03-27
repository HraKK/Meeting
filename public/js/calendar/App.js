/*
 * This calendar application was forked from Ext Calendar Pro
 * and contributed to Ext JS as an advanced example of what can 
 * be built using and customizing Ext components and templates.
 * 
 * If you find this example to be useful you should take a look at
 * the original project, which has more features, more examples and
 * is maintained on a regular basis:
 * 
 *  http://ext.ensible.com/products/calendar
 */
Ext.define('Ext.calendar.App', {

        requires: [
            'Ext.Viewport',
            'Ext.layout.container.Border',
            'Ext.picker.Date',
            'Ext.calendar.util.Date',
            'Ext.calendar.CalendarPanel',
            'Ext.calendar.data.MemoryCalendarStore',
            'Ext.calendar.data.MemoryEventStore',
            'Ext.calendar.data.Days',
            'Ext.calendar.form.EventWindow'
        ],

        constructor: function() {

            var scope = this;

            // Fix ExtJS 4.2 tooltip issue
            if (Ext.isIE10) {
                Ext.supports.Direct2DBug = true;
            } else if (Ext.isChrome) {
                Ext.define('Ext.layout.container.AutoTip', {
                    alias: ['layout.autotip'],
                    extend: 'Ext.layout.container.Container',

                    childEls: [
                        'clearEl'
                    ],

                    renderTpl: [
                        '{%this.renderBody(out,values)%}',

                        '<div id="{ownerId}-clearEl" class="', Ext.baseCSSPrefix, 'clear" role="presentation"></div>'
                    ],

                    calculate: function(ownerContext) {
                        var me = this,
                            containerSize;

                        if (!ownerContext.hasDomProp('containerChildrenDone')) {
                            me.done = false;
                        } else {

                            containerSize = me.getContainerSize(ownerContext);
                            if (!containerSize.gotAll) {
                                me.done = false;
                            }

                            me.calculateContentSize(ownerContext);
                        }
                    }
                });

                Ext.override(Ext.tip.Tip, {
                    layout: {
                        type: 'autotip'
                    }
                });
            }

            Ext.QuickTips.init();

            // Minor workaround for OSX Lion scrollbars
            this.checkScrollOffset();

            // This is an example calendar store that enables event color-coding
            this.calendarStore = Ext.create('Ext.calendar.data.MemoryCalendarStore');

            // A sample event store that loads static JSON from a local file. Obviously a real
            // implementation would likely be loading remote data via an HttpProxy, but the
            // underlying store functionality is the same.
            this.eventStore = Ext.create('Ext.calendar.data.MemoryEventStore');

            // This is the app UI layout code.  All of the calendar views are subcomponents of
            // CalendarPanel, but the app title bar and sidebar/navigation calendar are separate
            // pieces that are composed in app-specific layout code since they could be omitted
            // or placed elsewhere within the application.
            Ext.create('Ext.Viewport', {
                layout: 'border',
                renderTo: 'calendar-ct',
                items: [
                    {
                        xtype: 'toolbar',
                        id: 'app-header',
                        region: 'north',
                        height: 40,
                        padding: 0,
                        border: false,
                        items: [
                            {
                                xtype: 'container',
                                html: '<div id="app-logo">' +
                                        '<div class="logo-top">&nbsp;</div>' +
                                        '<div id="logo-body">' + new Date().getDate() + '</div>' +
                                        '<div class="logo-bottom">&nbsp;</div>' +
                                    '</div>' +
                                    '<h1>Meeting Room 2.0</h1>'
                            },
                            {
                                xtype: 'tbfill'
                            },
                            {
                                xtype: 'tbtext',
                                width: 172,
                                cls: 'user-name',
                                text: Ext.getUser()
                            },
                            {
                                xtype: 'tbspacer'
                            },
                            {
                                xtype: 'button',
                                text: 'Logout',
                                handler: function() {
                                    window.location.href = 'user/logout';
                                }
                            }
                        ]
                    },
                    {
                        xtype: 'tabpanel',
                        region: 'north',
                        cls: 'room-tabs',
                        height: 31,
                        margin: '-31 192 0 214',
                        border: false,
                        bodyStyle: {
                            border: false
                        },
                        listeners: {
                            afterrender: function(tabPanel) {

                                // fill tab
                                scope.calendarStore.on('load', function() {

                                    this.each(function(record, index) {
                                        tabPanel.add({
                                            title: record.get('title'),
                                            tooltip: record.get('description'),
                                            iconCls: 'room-tab-icon room-tab-icon-' + record.get('id'),
                                            room_id: record.get('id')
                                        });
                                    });

                                    // set main room active
                                    tabPanel.setActiveTab(1);

                                });

                            },
                            tabchange: function(tabPanel, newCard, oldCard) {

                                var calendar = Ext.getCmp('app-calendar');
                                var activeItemId = Ext.getCmp('app-calendar').getActiveView().id;

                                scope.eventStore.each(function(record) {
                                    record.set('hidden', (record.get('room_id') != newCard.room_id));
                                });

                                Ext.defer(function() {
                                    Ext.getCmp('app-calendar').setActiveView(activeItemId);
                                }, 10);

                                Ext.currentCalendarId = newCard.room_id;

                            }
                        }
                    },
                    {
                        id: 'app-center',
                        title: '...', // will be updated to the current view's date range
                        region: 'center',
                        layout: 'border',
                        border: false,
                        items: [
                            {
                                xtype: 'container',
                                region: 'west',
                                width: 212,
                                border: false,
                                margin: '0 1 0 1',
                                items: [
                                    {
                                        xtype: 'datepicker',
                                        startDay: 1,
                                        id: 'app-nav-picker',
                                        cls: 'ext-cal-nav-picker',
                                        listeners: {
                                            'select': {
                                                fn: function(dp, dt) {
                                                    Ext.getCmp('app-calendar').setStartDate(dt);
                                                },
                                                scope: this
                                            }
                                        }
                                    }
                                ]
                            },
                            {
                                xtype: 'container',
                                region: 'center',
                                border: false,
                                style: {
                                    background: '#add2ed'
                                },
                                layout: {
                                    type: 'hbox',
                                    align: 'stretch'
                                },
                                items: [
                                    {
                                        xtype: 'calendarpanel',
                                        id: 'app-calendar',
                                        maxHeight: 534,
                                        flex: 1,
                                        eventStore: this.eventStore,
                                        calendarStore: this.calendarStore,
                                        border: false,
                                        bodyStyle: {
                                            border: false
                                        },
                                        monthViewCfg: {
                                            showHeader: true,
                                            showWeekLinks: true,
                                            showWeekNumbers: true
                                        },

                                        listeners: {
                                            'eventclick': {
                                                fn: function(vw, rec, el) {
                                                    this.showEditWindow(rec, el);
                                                },
                                                scope: this
                                            },
                                            'eventover': function(vw, rec, el) {
                                                //console.log('Entered evt rec='+rec.data.Title+', view='+ vw.id +', el='+el.id);
                                            },
                                            'eventout': function(vw, rec, el) {
                                                //console.log('Leaving evt rec='+rec.data.Title+', view='+ vw.id +', el='+el.id);
                                            },
                                            'eventadd': {
                                                fn: function(cp, rec) {
                                                    this.showMsg('Event ' + rec.data.title + ' was added');
                                                },
                                                scope: this
                                            },
                                            'eventupdate': {
                                                fn: function(cp, rec) {
                                                    this.showMsg('Event ' + rec.data.title + ' was updated');
                                                },
                                                scope: this
                                            },
                                            'eventcancel': {
                                                fn: function(cp, rec) {
                                                    // edit canceled
                                                },
                                                scope: this
                                            },
                                            'viewchange': {
                                                fn: function(p, vw, dateInfo) {
                                                    if (this.editWin) {
                                                        this.editWin.hide();
                                                    }
                                                    if (dateInfo) {
                                                        // will be null when switching to the event edit form so ignore
                                                        Ext.getCmp('app-nav-picker').setValue(dateInfo.activeDate);
                                                        this.updateTitle(dateInfo.viewStart, dateInfo.viewEnd);
                                                    }
                                                },
                                                scope: this
                                            },
                                            'dayclick': {
                                                fn: function(vw, dt, ad, el) {
                                                    var StartDate = dt,
                                                        EndDate;

                                                    if (dt.getHours() < 9) {
                                                        StartDate = Ext.calendar.util.Date.add(StartDate, {hours: 9});
                                                        EndDate = Ext.calendar.util.Date.add(StartDate, {hours: 0.5});
                                                    } else {
                                                        EndDate = Ext.calendar.util.Date.add(StartDate, {hours: 0.5});
                                                    }

                                                    this.showEditWindow({
                                                        date_start: StartDate,
                                                        date_end: EndDate
                                                    }, el);
                                                },
                                                scope: this
                                            },
                                            'rangeselect': {
                                                fn: function(win, dates, onComplete) {
                                                    this.showEditWindow(dates);
                                                    this.editWin.on('hide', onComplete, this, {single: true});
                                                },
                                                scope: this
                                            },
                                            'eventmove': {
                                                fn: function(vw, rec, success) {

                                                    var time = ' \\a\\t H:i';

                                                    if (success) {
                                                        rec.commit();
                                                        this.showMsg('Event <strong>' + rec.data['title'] + '</strong> was moved to ' + Ext.Date.format(rec.data['date_start'], ('F jS' + time)));
                                                    } else {
                                                        rec.reject();
                                                        this.showMsg('Can\' move <strong>' + rec.data['title'] + '</strong> event because it is repeatable', 'error');
                                                    }

                                                },
                                                scope: this
                                            },
                                            'eventresize': {
                                                fn: function(vw, rec) {
                                                    rec.commit();
                                                    this.showMsg('Event <strong>' + rec.data.title + '</strong> was updated');
                                                },
                                                scope: this
                                            },
                                            'eventdelete': {
                                                fn: function(win, rec) {
                                                    this.eventStore.remove(rec);
                                                    this.showMsg('Event <strong>' + rec.data.title + '</strong> was deleted');
                                                },
                                                scope: this
                                            },
                                            'initdrag': {
                                                fn: function(vw) {
                                                    if (this.editWin && this.editWin.isVisible()) {
                                                        this.editWin.hide();
                                                    }
                                                },
                                                scope: this
                                            }
                                        }
                                    }
                                ]
                            }
                        ]
                    }
                ]
            });
        },

        // The edit popup window is not part of the CalendarPanel itself -- it is a separate component.
        // This makes it very easy to swap it out with a different type of window or custom view, or omit
        // it altogether. Because of this, it's up to the application code to tie the pieces together.
        // Note that this function is called from various event handlers in the CalendarPanel above.
        showEditWindow: function(rec, animateTarget) {

            if (!this.editWin) {
                this.editWin = Ext.create('Ext.calendar.form.EventWindow', {
                    calendarStore: this.calendarStore,
                    listeners: {
                        'eventadd': {
                            fn: function(win, rec) {
                                var me = this;
                                rec.data.n = false;
                                rec.data.owner = Ext.getUser();
                                me.eventStore.add(rec);
                                me.eventStore.sync({
                                    success: function() {
                                        win.hide();
                                        rec.commit();
                                        me.showMsg('Event <strong>' + rec.data.title + '</strong> was added');
                                    },
                                    failure: function(batch) {
                                        me.eventStore.remove(rec);
                                        if (batch.proxy.reader.jsonData.success == false) {
                                            var error;
                                            if (batch.proxy.reader.jsonData.auth === false) {
                                                Ext.sessionExpired()
                                            }
                                            for (var i in batch.proxy.reader.jsonData.errors) {
                                                error = batch.proxy.reader.jsonData.errors[i];
                                                me.showMsg('Field <strong>' + error.field + '</strong> is not valid.<br>' + error.message, 'error', 2000);
                                            }
                                        } else {
                                            me.showMsg('Uncaught server error. Can\'t create <strong>' + rec.data.title + '</strong> event', 'error', 2000);
                                        }
                                    }
                                });
                            },
                            scope: this
                        },
                        'eventupdate': {
                            fn: function(win, rec) {
                                var me = this;
                                me.eventStore.sync({
                                    success: function() {
                                        win.hide();
                                        rec.commit();
                                        me.showMsg('Event <strong>' + rec.data.title + '</strong> was updated');
                                    },
                                    failure: function(batch) {
                                        if (batch.proxy.reader.jsonData.success == false) {
                                            var error;
                                            if (batch.proxy.reader.jsonData.auth === false) {
                                                Ext.sessionExpired()
                                            }
                                            for (var i in batch.proxy.reader.jsonData.errors) {
                                                error = batch.proxy.reader.jsonData.errors[i];
                                                me.showMsg('Field <strong>' + error.field + '</strong> is not valid.<br>' + error.message, 'error', 2000);
                                            }
                                        } else {
                                            me.showMsg('Uncaught server error. Can\'t update <strong>' + rec.data.title + '</strong> event', 'error', 2000);
                                        }
                                    }
                                });
                            },
                            scope: this
                        },
                        'eventdelete': {
                            fn: function(win, rec) {
                                var me = this;
                                me.eventStore.remove(rec);
                                me.eventStore.sync({
                                    success: function() {
                                        win.hide();
                                        me.showMsg('Event <strong>' + rec.data.title + '</strong> was deleted');
                                    },
                                    failure: function(batch) {
                                        if (batch.proxy.reader.jsonData.success == false && batch.proxy.reader.jsonData.auth === false) {
                                            Ext.sessionExpired()
                                        }
                                        me.showMsg('Server error. Can\'t delete <strong>' + rec.data.title + '</strong> event', 'error', 2000);
                                    }
                                });
                            },
                            scope: this
                        }
                    }
                });
            }

            this.editWin.show(rec, animateTarget);

        },

        // The CalendarPanel itself supports the standard Panel title config, but that title
        // only spans the calendar views.  For a title that spans the entire width of the app
        // we added a title to the layout's outer center region that is app-specific. This code
        // updates that outer title based on the currently-selected view range anytime the view changes.
        updateTitle: function(startDt, endDt) {

            var p = Ext.getCmp('app-center'),
                fmt = Ext.Date.format;

            if (Ext.Date.clearTime(startDt).getTime() == Ext.Date.clearTime(endDt).getTime()) {

                p.setTitle(fmt(startDt, 'F j, Y'));

            } else if (startDt.getFullYear() == endDt.getFullYear()) {

                if (startDt.getMonth() == endDt.getMonth()) {
                    p.setTitle(fmt(startDt, 'F j') + ' - ' + fmt(endDt, 'j, Y'));
                } else {
                    p.setTitle(fmt(startDt, 'F j') + ' - ' + fmt(endDt, 'F j, Y'));
                }

            } else {

                p.setTitle(fmt(startDt, 'F j, Y') + ' - ' + fmt(endDt, 'F j, Y'));

            }

        },

        // This is an application-specific way to communicate CalendarPanel event messages back to the user.
        // This could be replaced with a function to do "toast" style messages, growl messages, etc. This will
        // vary based on application requirements, which is why it's not baked into the CalendarPanel.
        showMsg: function(msg, type, duration) {
            var msgType = (typeof type == 'undefined') ? 'success' : type;
            var msgDuration = (typeof duration == 'undefined') ? 1000 : duration;
            Ext.noty(msg, msgType, msgDuration)
        },

        // OSX Lion introduced dynamic scrollbars that do not take up space in the
        // body. Since certain aspects of the layout are calculated and rely on
        // scrollbar width, we add a special class if needed so that we can apply
        // static style rules rather than recalculate sizes on each resize.
        checkScrollOffset: function() {
            var scrollbarWidth = Ext.getScrollbarSize ? Ext.getScrollbarSize().width : Ext.getScrollBarWidth();

            // We check for less than 3 because the Ext scrollbar measurement gets
            // slightly padded (not sure the reason), so it's never returned as 0.
            if (scrollbarWidth < 3) {
                Ext.getBody().addCls('x-no-scrollbar');
            }

            if (Ext.isWindows) {
                Ext.getBody().addCls('x-win');
            }
        }
    },
    function() {

        // A few Ext overrides needed to work around issues in the calendar
        Ext.form.Basic.override({
            reset: function() {
                var me = this;
                // This causes field events to be ignored. This is a problem for the
                // DateTimeField since it relies on handling the all-day checkbox state
                // changes to refresh its layout. In general, this batching is really not
                // needed -- it was an artifact of pre-4.0 performance issues and can be removed.
                //me.batchLayouts(function() {
                me.getFields().each(function(f) {
                    f.reset();
                });
                //});
                return me;
            }
        });

        // Currently MemoryProxy really only functions for read-only data. Since we want
        // to simulate CRUD transactions we have to at the very least allow them to be
        // marked as completed and successful, otherwise they will never filter back to the
        // UI components correctly.
        Ext.data.MemoryProxy.override({
            updateOperation: function(operation, callback, scope) {
                operation.setCompleted();
                operation.setSuccessful();
                Ext.callback(callback, scope || this, [operation]);
            },
            create: function() {
                this.updateOperation.apply(this, arguments);
            },
            update: function() {
                this.updateOperation.apply(this, arguments);
            },
            destroy: function() {
                this.updateOperation.apply(this, arguments);
            }
        });

        // Included jQuery notification plugin
        Ext.noty = function(text, type, duration) {
            noty({
                text: text,
                type: type,
                dismissQueue: true,
                modal: false,
                layout: 'topCenter',
                theme: 'defaultTheme',
                animation: {
                    open: {
                        height: 'toggle'
                    },
                    close: {
                        height: 'toggle'
                    },
                    easing: 'swing',
                    speed: 500
                },
                timeout: duration || 3000
            });
        };

        Ext.getUser = function() {
            return 'username';
        };

        Ext.sessionExpired = function() {
            window.location.href = '/user/login';
        };

    });