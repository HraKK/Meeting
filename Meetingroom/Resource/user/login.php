<!doctype html>
<html>
<head>

    <meta charset="utf-8">
    <title>Login into Meeting Room 2.0</title>

    <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico?version=1"/>
    <link rel="stylesheet" type="text/css" href="/js/extjs/resources/css/ext-all-neptune.css"/>

    <script type="text/javascript" src="/js/extjs/ext-all.js"></script>
    <script type="text/javascript">
        Ext.Loader.setConfig({
            enabled: true,
            paths: {
                'Ext.calendar': '/js/calendar'
            }
        });
        Ext.require([
            'Ext.calendar.Login'
        ]);
        Ext.onReady(function() {
            // launch the app
            Ext.create('Ext.calendar.Login');
        });
    </script>

</head>
<body></body>
</html>