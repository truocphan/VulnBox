jQuery(document).ready(function($){

	//Global variables
	var _types 				= window.xoo_aff_field_types,
		_sections			= window.xoo_aff_field_sections,
		_fieldsLayout 		= window.xoo_aff_fields_layout;
	window._userFields 		= window.xoo_aff_db_fields || {};
	var required_settings 	= {}; 

	var $selectable 	= $( '.xoo-aff-select-fields-type' ),
		$fieldsDisplay 	= $( '.xoo-aff-main' ),
		$fieldSettings 	= $( '.xoo-aff-field-settings-container' ),
		$fieldSelector 	= $( '.xoo-aff-field-selector' ),
		$container 		= $( '.xoo-aff-settings-container' );


	//Select multiple list
	var MultipleList = function( $field ){
		var self 				= this;
		self.$field 			= $field;
		self.$form 				= $field.closest('.xoo-aff-field-settings');
		self.$cont 				= $field.closest('.xoo-aff-select-multiple-container');
		self.setting_id 		= (self.$cont.find('.xoo-aff-trigger-change').attr('name')).replace("xoo_aff_","");
		self.$itemList 			= self.$cont.find( 'ul.xoo-aff-multiple-list' );
		self.$select 			= self.$cont.find('.xoo-aff-select-multiple');
		self.$selectedListArea  = self.$cont.find('.xoo-aff-select-multiple-textarea');
		self.$selectedList 		= self.$selectedListArea.find('ul');

		//Methods
		self.getFieldValue 	= self.getFieldValue.bind(this); 

		//Events
		self.$itemList.on( 'click', 'li', { field: self }, self.onItemSelect );
		self.$selectedList.on( 'click', 'li', { field: self }, self.setDefaultItem );
		self.$selectedListArea.on( 'click', { field: self }, self.openList );
		self.$selectedList.on( 'click', '.xoo-aff-sel-remove', { field: self }, self.removeItem )
		$(document).on( 'click', { field: self }, self.hideList );
		self.$form.on( 'xoo_aff_settings_updated', { field: this }, this.updateValue );
		
	}

	/**
	 * Get field Value.
	 * @return array
	 */
	MultipleList.prototype.getFieldValue = function(){
		return Array.isArray( this.$select.val() ) ? this.$select.val() : []
	}

	MultipleList.prototype.onItemSelect = function(event){
		var field  		= event.data.field,
			select_val 	= field.getFieldValue(),
			item_val 	= $(this).data('value');

		//If item not already selected
		if( $.inArray(item_val, select_val) === -1 ){
			select_val.push( item_val );
			field.$select.val( select_val ).trigger('change');
			field.$selectedList.append('<li data-value="'+item_val+'"><span class="xoo-aff-sel-remove dashicons dashicons-no-alt"></span>'+$(this).text()+'</li>');
		}

		field.$itemList.hide();
	}


	MultipleList.prototype.setDefaultItem = function(event){
		var field  = event.data.field;
		field.$selectedList.find('li.aff-default').add( field.$select.find('option.aff-default') ).removeClass('aff-default');
		field.$select.find('option[value="'+$(this).data('value')+'"]').add($(this)).addClass('aff-default');
		field.$select.trigger('change');
	}


	MultipleList.prototype.hideList = function(event){
		var field  = event.data.field;
		$.each(event.target.classList,function(key,value){
			if(value !== "xoo-aff-multiple-list" && value !== 'xoo-aff-select-multiple-textarea'){
				field.$itemList.hide();
			}
		})
	}


	MultipleList.prototype.openList = function(event){
		event.data.field.$itemList.show();
	}


	MultipleList.prototype.removeItem = function(event){
		var field  	= event.data.field,
			$li 	= $(this).closest('li');
			selVal 	= field.getFieldValue();

		$li.remove();
		selVal.splice( $.inArray( $li.data( 'value') , selVal) , 1 );
		field.$select.val( selVal ).trigger('change');
	}


	MultipleList.prototype.updateValue = function( event, field_id){

		var field  = event.data.field,
			list 	= {};

		$.each( field.$field.val(), function( index, value ){
			var $option = field.$select.find('option[value="'+value+'"]');
			list[value] = {
				value: value,
				checked: $option.hasClass('aff-default') ? 'checked' : '',
				label: $option.text(),
			}
		} );

		_userFields[field_id]['settings'][field.setting_id] = list;

	}

	/* ---XXXXX--- */

	//Multiple option
	var MultipleOptions = function( $field ){
		var self 				= this;
		self.$field 			= $field;
		self.$form 				= self.$field.closest('form.xoo-aff-field-settings');
		self.setting_id 		= (self.$field.find('.xoo-aff-trigger-change').attr('name')).replace("xoo_aff_","");
		self.$addOption 		= self.$field.find('.xoo-add-option');
		self.$optionsList 		= self.$field.find('.xoo-aff-options-list');

		//Events
		self.$optionsList.on( 'click', '.mcb-del', { field: self }, self.deleteOption );
		self.$addOption.on( 'click', { field: self }, self.addOption );
		self.$optionsList.sortable();
		self.$form.on( 'xoo_aff_settings_updated', { field: this }, this.updateValue );
	}

	/**
	 * Get field Value.
	 * @param  $field 	Field Element
	 * @return array
	 */
	MultipleOptions.prototype.updateValue = function(event, field_id){

		var field = event.data.field,
			field_value = {},
			priority 	= 0;

		field.$field.find('li').each( function( index, li){
			var $li_el  	= $(li),
				li_checked  = $li_el.find('.option-check').is(":checked") ? 'checked' : false,
				li_label 	= $li_el.find('.mcb-label').val(),
				li_value 	= $li_el.find('.mcb-value').val();

			if( !li_label || !li_value  ) return true;

			var checkbox_data = {
				checked: li_checked,
				label:  li_label,
				value: li_value,
				priority: priority += 10, 
			};

			field_value[li_value] = checkbox_data;

		} );

		_userFields[field_id]['settings'][field.setting_id] = field_value;
		
	}

	MultipleOptions.prototype.addOption = function(event){
		event.preventDefault();
		event.data.field.$optionsList.find('li:last-of-type')
			.clone()
			.appendTo(event.data.field.$optionsList);
	}


	MultipleOptions.prototype.deleteOption = function(event){
		var $li = $(this).closest('li');
		if( $li.index() === 0 ) return; //cannot delete first one.
		$li.remove();
		//event.data.field.$changeTrigger.trigger('change');
	}

	/* ----- XXXX ---- */

	// Field
	var Field = function( id, type ){

		var self = this;

		if( !id && !type ){
			return;
		}

		self.newField 	= false;
		self.id 		= id;
		self.type 		= type;

		//Methods
		self.generateID 		= self.generateID.bind( self );
		self.createSettings 	= self.createSettings.bind( self );
		self.createSettingsHTML = self.createSettingsHTML.bind( self );
		self.openFieldView 		= self.openFieldView.bind( self );
		self.initializeSettings = self.initializeSettings.bind( self );
		self.updateSettings 	= self.updateSettings.bind( self );
		self.delete 			= self.delete.bind( self );
		self.addToDisplayList 	= self.addToDisplayList.bind( self );

		if( !self.id ){
			self.id = self.generateID( self.type );
			self.newField = true;
			//Placeholder for field settings
			window._userFields[ self.id ] = {
				field_type: self.type,
				input_type: _types[ self.type ]['type'],
				settings: {},
				priority: 0 //sort fields later
			}
			self.createSettings();
		}

		if( !self.type ){
			self.type = _userFields[ self.id ][ 'field_type' ];
		}

	}

	//Templates
	Field.prototype.section_template 			= wp.template('xoo-aff-field-section');
	Field.prototype.setting_template 			= wp.template('xoo-aff-field-settings');
	Field.prototype.settings_container_template = wp.template('xoo-aff-field-settings-container');
	Field.prototype.display_template 			= wp.template('xoo-aff-field-display');


	Field.prototype.createSettings = function(){
		var fieldObj = this;
		if( _fieldsLayout[ fieldObj.type ] === undefined ) return;
		var settings = JSON.parse( JSON.stringify ( _fieldsLayout[ fieldObj.type ] ) );

		$.each( settings, function( key, setting_data ){
			if( setting_data['type'] === 'section' ) return true;
			if( fieldObj.newField ){
				_userFields[ fieldObj.id ]['settings'][setting_data['id']] = setting_data['value'];
			}
		})
		//Setting unique id
		_userFields[ fieldObj.id ]['settings']['unique_id'] = fieldObj.id;
		this.addToDisplayList();
	}


	Field.prototype.openFieldView = function(){
		$(document).trigger( 'xoo_aff_before_opening_field', this.id );
		this.createSettingsHTML();
		this.initializeSettings();
	}


	Field.prototype.createSettingsHTML = function() {

		var fieldObj = this;
		if( _fieldsLayout[ fieldObj.type ] === undefined ) return;
		var settings = JSON.parse( JSON.stringify ( _fieldsLayout[ fieldObj.type ] ) );
		var fields_html = section_html = '';

		var user_settings = _userFields[ fieldObj.id ]['settings'];

		$.each( settings, function( index, setting ){
			//Creating settings HTML
			setting.value = user_settings[ setting['id'] ];
			fields_html += fieldObj.setting_template( setting );
		} )

		//Generate field settings Container & Push fields HTML to container
		var settings_container_data = {
			field_id: fieldObj.id,
			type_data: _types[ fieldObj.type ],
			fields_html: fields_html
		}
		$fieldSettings.html( this.settings_container_template( settings_container_data ) );

	};

	Field.prototype.initializeSettings = function() {

		var fieldObj = this,
			field_id = fieldObj.id;

		var settings = {
			$displayField: $('.xoo-aff-fs-display#'+field_id),
			$containerField: $( '.xoo-aff-field-settings#'+field_id ),

			//Initialize
			init: function(){
				settings.displayFocus();
				settings.initDatePicker();
				settings.initMisc();
			},

			//Focus on generated field
			displayFocus: function(){
				$('.xoo-aff-fs-display').removeClass('active');
				settings.$displayField.addClass('active');
			},

			//Init datepicker
			initDatePicker: function(){
				if( !settings.$containerField.find('.xoo-aff-datepicker').length ) return;
				$('.xoo-aff-datepicker').datepicker({
					altFormat: "yy-mm-dd",
					changeMonth: true,
					changeYear: true,
					yearRange: 'c-100:c+10',
				})
			},

			setLabel: function(){
				var	$label = $('#'+field_id + ' .xoo-aff-label span:last-of-type'),
					label = null;



				if( _userFields[field_id]['settings']['label_text']){

					label = _userFields[field_id]['settings']['label_text'];
				}
				else if( _userFields[field_id]['settings']['placeholder'] ){
					label = _userFields[field_id]['settings']['placeholder'];
				}
				
				$label.html( label === null ? '' :  '- '+ label );

			},

			initMisc: function(){
		
				$.each( $('select.xoo-aff-select-multiple'), function( index, el){
					new MultipleList( $(el) );
				} )

				$.each( $('.xoo-aff-multiple-options'), function( index, el){
					new MultipleOptions( $(el) );
				} )

				settings.setLabel();
				$fieldSettings.show();
				$fieldsDisplay.show();
				$fieldSelector.hide();
			}

		}

		settings.init();


	};

	Field.prototype.generateID = function() {
		var field_id = this.type + '_' + Math.random().toString(36).substr(2, 5);
		return field_id;
	};

	Field.prototype.addToDisplayList = function() {

		//If already displayed or Id not found
		if( !_userFields[ this.id ] ) return;

		var field_display_data = {
			field_id: this.id,
			type_data: _types[this.type]
		};

		var settings 	= _userFields[ this.id ]['settings'],
		title 			= settings.label ? settings.label : ( settings.placeholder ? settings.placeholder : this.id );

		field_display_data.type_data.title = title;

		var displayFieldHTML = this.display_template( field_display_data );

		if( $fieldsDisplay.find('#'+this.id).length ){
			console.log('trigger');
			$fieldsDisplay.find('#'+this.id).replaceWith(displayFieldHTML); 
		}
		else{
			$fieldsDisplay.append(displayFieldHTML);
		}

	};


	Field.prototype.delete = function(){

		delete _userFields[ this.id ];

		var displayField 	= $('body .xoo-aff-fs-display#'+this.id),
			switch_to_field = false;

		//Set focus on next element
		if( displayField.hasClass('active') &&  $fieldsDisplay.find('.xoo-aff-fs-display').length > 1 ){
			var switch_to_field = displayField.next().length ? displayField.next() : displayField.prev();
			
		}

		//Remove field
		$('body #'+ this.id).remove();

		if( switch_to_field ){
			switch_to_field.trigger('click');
		}

		//Check if there is any field
		if( $fieldsDisplay.find('.xoo-aff-fs-display').length === 0 ){
			$fieldsDisplay.hide();
			$fieldSelector.show();
		}
	}

	Field.prototype.updateSettings = function(){

		var $form 	= $('form#'+this.id),
			_t 		= this;

		if( !$form.length ) return;

		var valuesArray = $form.serializeArray(),
			settings 	= {};

		//Get unchecked checkboxes for keys as serialiezeArray doesn't fetch unchecked values
		$.each( $form.find('input:checkbox'), function( key, el ){
			if( $(el).attr('name') === undefined || $(el).is(':checked') || $(el).attr('name').trim().length < 1 ) return;
			valuesArray.push( {
				name: $(el).attr('name'),
				value: 'no' 
			} )
		} );

		//Adding form fields to settings object
		$.each( valuesArray, function( index, setting ){
			settings[ (setting.name).replace("xoo_aff_","") ] = setting.value;
		} );

		//If has field linking, set linking connections
		$.each( settings, function( key, value ){
			if( key !== 'linked_to' ) return;
			//Get the linked field value = fieldID here
			if( _userFields[ value ] !== undefined ){
				_userFields[ value ]['settings']['linked_by'] = _t.id;
			}
			return false;
		} );

		_userFields[ this.id ]['settings'] = settings;

		$form.trigger( 'xoo_aff_settings_updated', this.id );
		this.updateUniquedID();
		this.addToDisplayList();
	}


	Field.prototype.updateUniquedID = function(){

		var uniqueID = _userFields[ this.id ]['settings']['unique_id'],
			is_id_ok = true;

		if( uniqueID === undefined ){
			_userFields[ this.id ]['settings']['unique_id'] = this.id;
			return;
		}

		//check for length
		if( uniqueID.length <= 8 ){
			add_notice( 'Uniqued ID must be minimum 8 characters', 'error', 6000 );
			is_id_ok = false;
		}

		if( uniqueID !== this.id ){
			if( _userFields[ uniqueID ] !== undefined ){
				add_notice( 'Field with the same ID already exists. Please keep it unique', 'error', 6000 );
				is_id_ok = false;
			}
		}

		if( is_id_ok && this.id !== uniqueID ){
			//All good, ready to update ID
			_userFields[ uniqueID ] = _userFields[ this.id ];
			this.delete();
			var newField = Handler.getField( uniqueID );
			newField.addToDisplayList();
			newField.openFieldView();
		}
		else{
			_userFields[ this.id ]['settings']['unique_id'] = this.id
		}

	}

	var Handler = {

		init: function(){

			//Events
			$( '.xoo-aff-add-field' ).click(  this.openFieldsSelector );
			$selectable.on( 'selectableselected', this.addNewField);
			$( '.xoo-aff-reset-field' ).click( this.resetFields );
			$( 'body' ).on( 'click', '.xoo-aff-fsd-cta-del', this.deleteButtonClick );
			$( 'body' ).on( 'click', '.xoo-aff-fs-display', this.onDisplaySelect );
			$( document ).on( 'xoo_aff_before_opening_field', this.updateField );
			$( '#xoo-aff-save' ).on( 'click', this.saveFields);

			this.loadFields();
			$selectable.selectable();
		},

		openFieldsSelector: function(){
			$('.xoo-aff-fs-display').removeClass('active');
			$fieldSettings.hide();
			$fieldSelector.show();
		},

		getField: function( field_id ){
			return new Field( field_id );
		},

		addNewField: function( event, ui ){
			if( !xoo_aff_localize.addField ) return;
			var type 	= $(ui.selected).data('field');
			field 		= new Field( null, type );
			field.openFieldView();
		},

		onDisplaySelect: function(){
			var field_id = $(this).attr('id');
			Handler.getField( field_id ).openFieldView();
		},

		resetFields: function(e){
			e.preventDefault();
			if( !confirm("Are you sure.This will remove your custom fields & take you back to default fields settings?") ) return;
			add_notice( 'Resetting.. Please wait...','info' );

			//Ajax reset
			$.ajax({
				url: xoo_aff_localize.ajax_url,
				type: 'POST',
				data: {
					action: 'xoo_aff_reset_settings',
					plugin_info: window.xoo_aff_plugin_info,
					submit_nonce: xoo_aff_localize.submit_nonce,
					xoo_aff_plugin_action: window.xoo_aff_plugin_info.plugin_slug
				},
				success: function(response){
					console.log(response);
					if( response.success && response.success == 1){
						add_notice('Reset successfully. Refreshing page...','success');
						window.location.reload();
					}
					else{
						add_notice('Please contact support team','error');
					}
				}
			})
		},

		deleteButtonClick: function(e){
			e.stopPropagation();
			if( !confirm("Are you sure?") ) return;
			var field_id = $(this).closest('.xoo-aff-fs-display').attr('id');
			Handler.getField( field_id ).delete();
		},

		//Add priority to fields by order list
		addPriority: function(){
			var priority = 10;
			$fieldsDisplay.find('li').each(function( index, li ){
				var $li 	 = $( li ),
					field_id = $li.attr('id');
				if( !window._userFields[ field_id ] ) return true;

				_userFields[ field_id ]['priority'] = priority;

				priority = priority + 10;
				
			});

			console.log(_userFields);
		},

		//Load fields on page Load
		loadFields: function(){

			//Check if there are saved fields in database
			if( !_userFields || $.isEmptyObject( _userFields )) return;

			$fieldSettings.addClass('loading');
			add_notice('Loading fields, Please wait....','info',10000);

			//Converting into array type for sorting
			var _userFieldsArray = Object.entries(_userFields);

			_userFieldsArray.sort(function( a, b ){

				aPriority = parseInt(a[1]['priority']);
				bPriority = parseInt(b[1]['priority']);

				if( aPriority === bPriority ){
					return 0;
				}
	
				return aPriority > bPriority ? 1 : -1; 
			});


			console.log(_userFieldsArray);

			$.each( _userFieldsArray, function( index, field ){
				//field[0] = Field ID
				(new Field( field[0] )).createSettings();
			} )

			$(document).trigger( 'xoo_aff_all_settings_loaded' );

			$fieldSettings.removeClass('loading');
			clear_notice();
			$fieldsDisplay.find('.xoo-aff-fs-display:first-of-type').trigger('click');

		},

		getActiveField: function(){
			var $form = $('form.xoo-aff-field-settings');
			if( $form.length === 0 ) return;
			return Handler.getField(  $form.attr('id') );
		},

		//Update Field
		updateField: function(){
			var Field = Handler.getActiveField();
			if( !Field ) return;
			return Field.updateSettings();
		},

		//Save Fields in database
		saveFields: function(){

			//Save current opened field
			Handler.updateField();

			if( !Handler.validateFields() ) return;

			add_notice('Saving fields, Please wait....','info');

			//Sort data as per user display fields
			Handler.addPriority();
			var data_to_save = _userFields;

			var data = {
				action: 'xoo_aff_save_settings',
				is_ajax: 'yes',
				xoo_aff_plugin_action: window.xoo_aff_plugin_info.plugin_slug,
				xoo_aff_data: JSON.stringify(data_to_save),
				plugin_info: JSON.stringify(window.xoo_aff_plugin_info) ,
				submit_nonce: xoo_aff_localize.submit_nonce
			};

			var $hiddenForm = $('form.xoo-aff-form-save');


			//Ajax Save
			$.ajax({
				url: xoo_aff_localize.ajax_url,
				type: 'POST',
				data: data,
				success: function(response){
					console.log(response);
					if( response.success && response.success == 1){
						add_notice('Saved successfully.','success');
					}
					else{
						add_notice('Please contact support team','error');
					}
				},
				error: function( a,b,c ){

					data.is_ajax = 'no';

					$.each( data, function( key, value ){
						var $input = $('<input type="hidden" name="'+key+'">');
						$input.val( value );
						$hiddenForm.append( $input );
					} );

					$hiddenForm.trigger('submit');
				}
			})

		},

		//Fields validation
		validateFields: function(){
			var all_ok = true;
			//Required fields are filled
			$.each( _userFields, function( field_id, field_data) {
				var field_type = field_data.field_type;
				if( required_settings[ field_type ] === undefined ){
					field_required_settings = [];
					$.each( _fieldsLayout[field_type], function( index, setting ) {
						if( setting['required'] !== "yes" ) return true;
						field_required_settings.push( setting['id'] );
					} )
					if( field_required_settings.length > 0 ){
						required_settings[ field_type ] = field_required_settings;
					}
				}

				$.each( field_data.settings, function( field_setting_id, field_setting_value ){
					if( $.inArray( field_setting_id, required_settings[field_type] ) !== -1 && !field_setting_value.trim() ){
						add_notice( 'Please fill the all required (*) options of field ' + field_id + ' ( '+_types[field_type]['title']+' ) ', 'error' );
						all_ok = false;
						return false;
					}
				} )

				if( !all_ok ) return false;
				
			} )

			return all_ok;
		}

	}

	Handler.init();


	//Verify uniqueness
	function is_id_unique(input_id){

		//check for length
		if( input_id.length <= 8 ){
			add_notice( 'Uniqued ID must be minimum 8 characters', 'error', 6000 );
			return false;
		}

		//Check for uniqueness
		var unique_id = true;
		$.each( window._userFields, function( field_id, field_data ){
			if( field_id === input_id ){
				add_notice( 'Field with the same ID already exists. Please keep it unique', 'error', 6000 );
				unique_id = false;
				return false;
			}
		} )

		return unique_id; //Exit 
		
	}

	//Update unique id
	function update_uniqueid( old_value, new_value ){
		var updated = false,
			updated_userFields = {};
		$.each( _userFields, function( field_id, field_data ){
			if( field_id === old_value ){
				$('.xoo-aff-field-settings#'+field_id+',.xoo-aff-fs-display#'+field_id).attr( 'id', new_value );
				field_id = new_value;
				field_data['settings']['unique_id'] = new_value;
				updated = true;
			}
			updated_userFields[ field_id ] = field_data;
		} )

		window._userFields = JSON.parse( JSON.stringify ( updated_userFields ) );
		return updated;
	}


	//Notice
	function add_notice(notice,notice_type,duration=5000){
		clear_notice();
		var data = {
			text: notice,
			type: notice_type
		}
		var template = wp.template('xoo-aff-notice');
		$('.xoo-aff-notice-holder').html( template(data) );

		//Hide notice after 5 seconds
		setTimeout(function(){
			clear_notice();
		},duration);
	}

	function clear_notice(){
		$('.xoo-aff-notice-holder').html('');
	}

	//Font Awesome IconPicker
	$('body').on('focus', '.xoo-aff-iconpicker', function(){
		$(this).iconpicker({
			hideOnSelect: true,
		});
	} )

	$('body').on('iconpickerSelected','.xoo-aff-iconpicker',function(){
		$(this).trigger('change');
	})


	//Hide show Choose countries settings
	$container.on( 'change', '#xoo_aff_country_list', function(){
		var value = $(this).val();
		if( value === 'all' ){
			$('.xoo-aff-setting-country_choose').hide();
		}
		else{
			$('.xoo-aff-setting-country_choose').show();
		}	
	})

	//Field display sort
	$fieldsDisplay.sortable({
	    items : 'li:not(.xoo-aff-no-sort)'
	});

})




