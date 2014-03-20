//@define Ext.calendar.data.EventMappings
/**
 * @class Ext.calendar.data.EventMappings
 * @extends Object
 * A simple object that provides the field definitions for Event records so that they can be easily overridden.
 */
Ext.ns('Ext.calendar.data');

Ext.calendar.data.EventMappings = {
    EventId: {
        name: 'EventId',
        mapping: 'id',
        type: 'int'
    },
    CalendarId: {
        name: 'CalendarId',
        mapping: 'room',
        type: 'int'
    },
    Title: {
        name: 'Title',
        mapping: 'title',
        type: 'string'
    },
    StartDate: {
        name: 'StartDate',
        mapping: 'start',
        type: 'date',
        dateFormat: 'timestamp'
    },
    EndDate: {
        name: 'EndDate',
        mapping: 'end',
        type: 'date',
        dateFormat: 'timestamp'
    },
    IsNew: {
        name: 'IsNew',
        mapping: 'n',
        type: 'boolean'
    },
    Owner: {
        name: 'Owner',
        mapping: 'owner',
        type: 'string'
    },
    IsRepeatable: {
        name: 'IsRepeatable',
        mapping: 'repeatable',
        type: 'boolean'
    }
};