Ext.onReady(function(){


	//-----------------------------------------------------
	// INITIALIZE THE COMMON OBJECTS
	//-----------------------------------------------------
	initialize();
	countryStore = getCountry();
	stateStore = getState();
	cityStore = getCity();

	//-----------------------------------------------------
	// OVERLAY THE LOGIN BUTTONS BOX IN THE PAGE
	//-----------------------------------------------------
	createBoxButtons();

	//-----------------------------------------------------
	// CREATE THE MAIN MAP VIEW
	//-----------------------------------------------------
    createMainGui();

	//-----------------------------------------------------
	// SHOW THE BUTTONS BOX IN THE MAP
	//-----------------------------------------------------
	setBoxInitialPosition('BoxButtons','top-right');

	validateLogin();
});



function createMainGui(){

	Ext.create('Ext.container.Viewport', {
		//layout: 'fit',
		layout: 'border',
		id: 'MainViewPort',
		//renderTo:'main',
		items: [{
            xtype: 'box',
            id: 'header',
            region: 'north',
            html: '<h1 id="mapName">' + document.title + '</h1><div class="fb-like socialBtn" data-href="'+(window.location).toString().split("?")[0]+'" data-send="true" data-width="450" data-show-faces="false"></div><div class="socialBtn"><div id="gplus"></div></div><div id="twitterBtn" class="socialBtn" style="margin-top: 4px"><a href="https://twitter.com/share" class="twitter-share-button" data-via="HyruleLegends"></a></div>',
            height: 30,
            collapsible: false,
            floatable: false,
            split: false
        },{
			id:'main_canvas',
			header: false,
			region:'center',
			margins: 'auto',
			xtype: 'box',
			autoEl: {
				tag: 'div',
				id: 'main_canvas',
				html: 'Default carregado.'
			}
		}],
		listeners:{
			resize: function( cmp,  width,  height,  oldWidth,  oldHeight,  eOpts ){
				setBoxInitialPosition('BoxButtons','top-right');
			},
			'afterrender': function() {
				renderPlusone();
				
				var script = document.createElement('script');
				script.type = 'text/javascript';
				script.src = 'js/twitter.js';
				document.getElementsByTagName("head")[0].appendChild(script);
			}
		}
	});
}


//==============================================================================
// FUNCTION: createBoxButtons(config)
//
// DESCRIPTION: create an overlay panel with the action buttons in the main
//              screen, overlaying a position in the map.
//==============================================================================
function createBoxButtons(){
	//creates the toolbar
	var tb = Ext.create('Ext.toolbar.Toolbar', {
		id:'BoxButtonsToolbar',
		items: [{
			id:'btnLogin',
			//text: 'Login',
			iconAlign:'top',
			iconCls: 'icon-login',
			tooltip: 'Clique para fazer login no sistema',
			width:60,
			height:50,
			handler: function(){
				createLoginForm();
			}
		},
			'->'
		,{
			id:'btnCadastro',
			//text: 'Cadastro',
			iconAlign:'top',
			iconCls: 'icon-cadastrar',
			cls: 'makeVisible',
			tooltip: 'Cadastrar um novo usuário',
			width:60,
			height:50,
			handler: function(){
				createRegisterForm();
			}
		},{
			id:'btnLogout',
			//text: 'Logout',
			hidden:true,
			iconAlign:'top',
			iconCls: 'icon-logout',
			tooltip: 'Sair do sistema',
			cls: 'makeVisible',
			width:60,
			height:50,
			handler: function(){
				//createRegisterForm();
				location.href = 'logout.php';
			}
		}]
	});


	//creates the filter window overlaying the map
    var win = new Ext.Window({
		id:'BoxButtons',
		cls: 'makeInvisible',
		//layout:'fit',
		//width: 140,
		border:false,
		resizable:false,
		header:false,
        items: [tb]
    });
    win.show();

}