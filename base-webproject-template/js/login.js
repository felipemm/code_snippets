//==============================================================================
// FUNCTION: createLoginForm()
//
// DESCRIPTION: creates a login window to authenticate users. A window will
//              popup in the screen to put username and password.
//==============================================================================
function createLoginForm(){
    var login = Ext.create('Ext.form.Panel', {
        title: 'Login',
        bodyPadding: 5,
        frame:true,
        width: '100%',
        fieldDefaults: {
            defaultType:'textfield',
            labelAlign: 'left',
            labelWidth: 50,
            msgTarget: 'side'
        },

        // The form will submit an AJAX request to this URL when submitted
        url: 'ajax/check_login.php',

        // Fields will be arranged vertically, stretched to full width
        layout: 'anchor',
        defaults: {
            anchor: '100%'
        },

        // The fields
        defaultType: 'textfield',
        items: [{
            fieldLabel: 'Usuário',
            name: 'username',
            allowBlank: false
        },{
            fieldLabel: 'Senha',
            name: 'password',
            inputType:'password',
            allowBlank: false
        }],

        // Reset and Submit buttons
        buttons: [{
            text: 'Entrar',
            formBind: true, //only enabled once the form is valid
            disabled: true,
            handler: function() {
                var form = this.up('form').getForm();
                if (form.isValid()) {
                    form.submit({
                        success: function(form, action) {
                            Ext.Msg.alert('Success', action.result.msg, function(){
								validateLogin();
								Ext.getCmp('BoxButtons').doLayout();
								win.close();
							});
                        },
                        failure: function(form, action) {
                            Ext.Msg.alert('Failed', action.result.msg);
                            form.reset();
                        }
                    });
                }
            }
        },{
            text: 'Cancelar',
            handler: function(){
				win.close();
			}
        }]
    });

    // This just creates a window to wrap the login form.
    // The login object is passed to the items collection.
    var win = new Ext.Window({
        layout:'fit',
		id:'LoginForm',
		header:false,
        preventHeader:true,
        width:300,
        height:140,
        closable: false,
        resizable: false,
        plain: true,
        border: false,
        modal:true,
        items: [login]
    });
    win.show();
}



//==============================================================================
// FUNCTION: createRegisterForm()
//
// DESCRIPTION: creates a registration form so the user can login in the app.
//==============================================================================
function createRegisterForm(){

	//create the registration form
    var regForm = Ext.create('Ext.form.Panel', {
        title: 'Formulário de Registro',
        bodyPadding: 5,
        frame:true,
        width: '100%',
        fieldDefaults: {
            labelAlign: 'left',
            labelWidth: 100,
            msgTarget: 'side'
        },

        // The form will submit an AJAX request to this URL when submitted
        url: 'ajax/maint_user.php?action=ADD',

        // Fields will be arranged vertically, stretched to full width
        layout: 'anchor',
        defaults: {
            anchor: '100%'
        },

        // The fields
        defaultType: 'textfield',
        items: [{
            fieldLabel: 'Usuário',
			id:'usuario_nome',
            name: 'usuario_nome',
            allowBlank: false
        },{
            fieldLabel: 'E-Mail',
            name: 'usuario_email',
            allowBlank: false
        },{
            fieldLabel: 'Telefone',
            name: 'usuario_telefone',
            allowBlank: false
        },{
            fieldLabel: 'Senha',
            name: 'usuario_senha',
			id: 'usuario_senha',
            inputType:'password',
            allowBlank: false
        },{
            fieldLabel: 'Confirma Senha',
            name: 'usuario_senha2',
            inputType:'password',
            allowBlank: false
        },{
			//country combobox, after the selection of the country, we should enable the state field and filter the data
			xtype:'combo',
            fieldLabel: 'País',
			id:'pais',
            name: 'pais_id',
			valueField: 'pais_id',
			displayField: 'pais_nome',
			queryMode: 'local',
			emptyText:'Selecione o País',
			store: getCountry(),
            allowBlank: false,
			forceSelection:true,
			listeners:{
				select: function(combo,record,index){
					//disable city combobox
					cidade = Ext.getCmp('cidade');
					cidade.disable();
					cidade.setValue('');
					//reload state combo
					estado = Ext.getCmp('estado');
					estado.disable();
					estado.setValue('');
					estado.store.removeAll();
					estado.store.load({
						params: {
							pais_id: Ext.getCmp('pais').getValue()
						},
					});
					estado.enable();
					regForm.doLayout();
				}
			}
        },{
			//this is the state combobox which will be only enable for editing when the country is selected, and will show only states
			//for the selected country
			xtype:'combo',
            fieldLabel: 'Estado',
			id:'estado',
            name: 'estado_id',
			hiddenName:'estado_id',
            hiddenValue:0,
			queryMode: 'local',
			valueField: 'estado_id',
			displayField: 'estado_nome',
			emptyText:'Selecione o Estado',
			disabled: true,
			allowBlank: false,
			forceSelection:true,
			store: getState(),
			listeners:{
				select: function(combo,record,index){
					cidade = Ext.getCmp('cidade');
					cidade.disable();
					cidade.setValue('');
					cidade.store.removeAll();
					cidade.store.load({
						params: {
							estado_id: Ext.getCmp('estado').getValue()
						},
					});

					cidade.enable();
					regForm.doLayout();
				}
			}
        },{
			//city combobox which will be enabled after the state is selected.
			xtype:'combo',
            fieldLabel: 'Cidade',
			id:'cidade',
            name: 'cidade_id',
			hiddenName:'cidade_id',
            hiddenValue:0,
			queryMode: 'local',
			emptyText:'Selecione a cidade',
			valueField: 'cidade_id',
			displayField: 'cidade_nome',
			disabled: true,
			store: getCity(),
			allowBlank: false,
			forceSelection:true,
        }],

        // Reset and Submit buttons
        buttons: [{
            text: 'Cadastrar',
            formBind: true, //only enabled once the form is valid
            disabled: true,
            handler: function() {
                var form = this.up('form').getForm();
				var cmbCidade = Ext.getCmp('cidade');
				if(cmbCidade.getValue != ''){
					if (form.isValid()) {
						form.submit({
							success: function(form, action) {
								//if the registration form was submitted sucessfully, try to log the user to the application
								//Ext.Msg.alert('Sucesso', action.result.msg, function(){
								//alert(action.response);
								if(action.result.success){
									Ext.Ajax.request({
										url: 'ajax/check_login.php',
										params: {
											username:Ext.getCmp('usuario_nome').getValue(),
											password:Ext.getCmp('usuario_senha').getValue()
										},
										method: 'POST',
										success: function(response){
											var json = Ext.JSON.decode(response.responseText);
											if(json.success){
												//confirm session in the database and refresh the login buttons
												validateLogin();
												//close the form
												win.close();
											} else {
												Ext.Msg.alert('Erro', json.msg);
											}
										}
									});
								} else {
									Ext.Msg.alert('Erro', action.result.msg);
								}
								//});
							},
							failure: function(form, action) {
								Ext.Msg.alert('Erro', action.result.msg);
								//form.reset();
							}
						});
					}
				} else {
					Ext.Msg.alert('Erro', 'Digite as informações de cidade/estado/país');
				}
            }
        },{
            text: 'Limpar',
            handler: function() {
                this.up('form').getForm().reset();
            }
        },{
            text: 'Fechar',
            handler: function() {
                this.up('window').close();
            }
        }]
    });

    var win = new Ext.Window({
        layout:'fit',
        header:false,
        closable: false,
        resizable: false,
		width: 400,
        plain: true,
        border: false,
        modal:true,
        items: [regForm]
    });
    win.show();
}


//==============================================================================
// FUNCTION: validateLogin()
//
// DESCRIPTION: re-validate the login of the logged user and confront with the 
//              session id registered to the user, if the session doesn't 
//              correspond to the session, force a logout.
//==============================================================================
function validateLogin(fnSuccess, fnError){
	//TODO: check in the database if the user is logged, if yes then refresh the login buttons, otherwise make a logout
	Ext.Ajax.request({
		url: 'ajax/check_session.php',
		method: 'POST',
		success: function(response){
			var json = Ext.JSON.decode(response.responseText);
			if(json.success){
				Ext.getCmp('btnLogin').hide();
				Ext.getCmp('btnCadastro').hide();
				Ext.getCmp('btnLogout').show();
				if(fnSuccess != undefined) fnSuccess();
			} else {
				//Ext.Msg.alert('Erro', json.msg);
				Ext.getCmp('btnLogin').show();
				Ext.getCmp('btnCadastro').show();
				Ext.getCmp('btnLogout').hide();
				if(fnError != undefined) fnError();
			}
		}
	});	
}