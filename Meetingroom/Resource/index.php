<!doctype html>
<html>
<head>

    <meta charset="utf-8">
    <title>Meeting Room 2.0</title>

    <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico?version=1"/>
    <link rel="stylesheet" type="text/css" href="/js/extjs/resources/css/ext-all-neptune.css"/>
    <link rel="stylesheet" type="text/css" href="/css/calendar.css"/>
    <link rel="stylesheet" type="text/css" href="/css/common.css"/>

    <script type="text/javascript" src="/js/extjs/ext-all.js"></script>
    <script type="text/javascript" src="/js/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="/js/noty/jquery.noty.js"></script>
    <script type="text/javascript" src="/js/noty/themes/default.js"></script>
    <script type="text/javascript" src="/js/noty/layouts/top.js"></script>
    <script type="text/javascript" src="/js/noty/layouts/topCenter.js"></script>
    <script type="text/javascript" src="/js/noty/layouts/center.js"></script>

    <script type="text/javascript">
        Ext.Loader.setConfig({
            enabled: true,
            paths: {
                'Ext.calendar': '/js/calendar'
            }
        });
        Ext.require([
            'Ext.calendar.App'
        ]);
        Ext.onReady(function() {
            // launch the app
            Ext.currentUser = '<?=$currectUsername?>';
            Ext.create('Ext.calendar.App');
        });
    </script>
    
</head>
<body>
</body>
</html>