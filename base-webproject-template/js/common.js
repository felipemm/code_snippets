var defaultMarginTopLeft = 20; // to not overlay the logo
var defaultMarginTop     = 40;
var defaultMarginBottom  = 20;
var defaultMarginLeft    = 20;
var defaultMarginRight   = 20;

var STATUS_APPROVED = 1;

var countryStore;
var stateStore;
var cityStore;


function initialize(){

	//Set up database model for USER
	Ext.define('USER', {
		extend: 'Ext.data.Model',
		fields: [
			{name: 'usuario_id',       type: 'int'},
			{name: 'usuario_nome',     type: 'string'},
			{name: 'usuario_email',    type: 'string'},
			{name: 'usuario_senha',    type: 'string'},
			{name: 'usuario_telefone', type: 'string'},
			{name: 'cidade_id',        type: 'int'},
			{name: 'status_id',        type: 'int'},
			{name: 'usuario_admin',    type: 'int'}
		]
	});

	//Set up database model for STATUS
	Ext.define('STATUS', {
		extend: 'Ext.data.Model',
		fields: [
			{name: 'status_id',   type: 'int'},
			{name: 'status_nome', type: 'string'}
		]
	});

	//Set up database model for view CIDADE
	Ext.define('CIDADE', {
		extend: 'Ext.data.Model',
		fields: [
			{name: 'cidade_id',    type: 'int'},
			{name: 'cidade_nome',  type: 'string'},
			{name: 'estado_id',    type: 'int'}
		]
	});

	
	//Set up database model for view ESTADO
	Ext.define('ESTADO', {
		extend: 'Ext.data.Model',
		fields: [
			{name: 'estado_id',    type: 'int'},
			{name: 'estado_nome',  type: 'string'},
			{name: 'estado_sigla', type: 'string'},
			{name: 'pais_id',      type: 'int'}
		]
	});

	//Set up database model for view PAIS
	Ext.define('PAIS', {
		extend: 'Ext.data.Model',
		fields: [
			{name: 'pais_id',      type: 'int'},
			{name: 'pais_nome',    type: 'string'},
			{name: 'pais_sigla',   type: 'string'}
		]
	});
}


//==============================================================================
// FUNCTION: setBoxInitialPosition(id, location)
//
// DESCRIPTION: set the position of any panel tot the location specified.
//==============================================================================
function setBoxInitialPosition(id, location){
	var panel = Ext.getCmp(id);

	//validate id the DOM components were created
	if(panel != undefined && Ext.getCmp('MainViewPort') != undefined){
		//hide the panel if already shown
		//panel.hide();
		//check where to put the panel
		switch(location){
			case "bottom-right":
				var x = Ext.getCmp('MainViewPort').getWidth() - panel.getWidth() - defaultMarginRight;
				var y = Ext.getCmp('MainViewPort').getHeight() - panel.getHeight() - defaultMarginBottom;
				break;
			case "bottom-center":
				var x = Ext.getCmp('MainViewPort').getWidth()/2 - panel.getWidth()/2;
				var y = Ext.getCmp('MainViewPort').getHeight() - panel.getHeight() - defaultMarginBottom;
				break;
			case "bottom-left":
				var x = defaultMarginLeft;
				var y = Ext.getCmp('MainViewPort').getHeight() - panel.getHeight() - defaultMarginBottom;
				break;
			case "middle-right":
				var x = Ext.getCmp('MainViewPort').getWidth() - panel.getWidth() - defaultMarginRight;
				var y = Ext.getCmp('MainViewPort').getHeight()/2 - panel.getHeight()/2;
				break;
			case "middle-center":
				var x = Ext.getCmp('MainViewPort').getWidth()/2 - panel.getWidth()/2;
				var y = Ext.getCmp('MainViewPort').getHeight()/2 - panel.getHeight()/2;
				break;
			case "middle-left":
				var x = defaultMarginLeft;
				var y = Ext.getCmp('MainViewPort').getHeight()/2 - panel.getHeight()/2;
				break;
			case "top-right":
				var x = Ext.getCmp('MainViewPort').getWidth() - panel.getWidth() - defaultMarginRight;
				var y = defaultMarginTop;
				break;
			case "top-center":
				var x = Ext.getCmp('MainViewPort').getWidth()/2 - panel.getWidth()/2;
				var y = defaultMarginTop;
				break;
			case "top-left":
				var x = defaultMarginLeft;
				var y = defaultMarginTopLeft;
				break;
		}
		//show the panel again
		//panel.show();
		//set the position accordingly
		panel.setPosition(x,y);
	}
}



function getCity(stateID){
	return Ext.create('Ext.data.Store', {
		storeId:'store_cities',
		model: 'CIDADE',
		proxy: {
			type: 'ajax',
			url: 'ajax/maint_city.php?action=SEL' + (stateID != undefined ? '&estado_id='+stateID : ''),
			reader: {
				type: 'json',
				root: 'result.data'
			}
		},
		autoLoad: true
	});
}

function getState(countryID){
	var store = Ext.create('Ext.data.Store', {
		storeId:'store_states',
		model: 'ESTADO',
		proxy: {
			type: 'ajax',
			url: 'ajax/maint_state.php?action=SEL' + (countryID != undefined ? '&pais_id='+countryID : ''),
			reader: {
				type: 'json',
				root: 'result.data'
			}
		},
		autoLoad: true
	});
	return store;
}

function getCountry(){
	var store =  Ext.create('Ext.data.Store', {
		storeId:'store_countries',
		model: 'PAIS',
		proxy: {
			type: 'ajax',
			url: 'ajax/maint_country.php?action=SEL',
			reader: {
				type: 'json',
				root: 'result.data'
			}
		},
		autoLoad: true
	});
	return store;
}