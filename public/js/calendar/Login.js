(function() {
    var loginHandler = function() {
        var form = this.up('form').getForm();

        if (form.isValid()) {
            form.submit({
                method: 'POST',
                url: '/user/loginAjax',
                success: function() {
                    window.location.href = '/';
                },
                failure: function(form, action) {
                    Ext.Msg.alert('Failure', action.result.msg);
                }
            });
        }
    };
    var onEnterKeyHandler = function(field, e) {
        if (e.getKey() == e.ENTER) {
            loginHandler.apply(this);
        }
    };

    Ext.create('Ext.form.Panel', {
        title: 'Login into Meeting Room 2.0',
        bodyPadding: 10,
        border: 0,
        frame: true,
        width: 350,
        style: {
            marginLeft: 'auto',
            marginRight: 'auto',
            marginTop: '200px'
        },
        layout: 'anchor',
        defaults: {
            anchor: '100%'
        },
        defaultType: 'textfield',
        items: [
            {
                fieldLabel: 'Login',
                name: 'username',
                allowBlank: false,
                listeners: {
                    specialkey: onEnterKeyHandler,
                    afterrender: function(field) {
                        field.focus();
                    }
                }
            },
            {
                inputType: 'password',
                fieldLabel: 'Password',
                name: 'password',
                allowBlank: false,
                listeners: {
                    specialkey: onEnterKeyHandler
                }
            }
        ],
        buttons: [
            {
                text: 'Reset',
                handler: function() {
                    this.up('form').getForm().reset();
                }
            },
            {
                text: 'Submit',
                formBind: true,
                disabled: true,
                handler: loginHandler
            }
        ],
        renderTo: Ext.getBody()
    });
})();