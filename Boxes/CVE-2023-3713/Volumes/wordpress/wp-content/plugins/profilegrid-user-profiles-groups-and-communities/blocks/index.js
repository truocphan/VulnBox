//import apiFetch from '@wordpress/api-fetch';
const {registerBlockType} = wp.blocks; //Blocks API
const {createElement,useState} = wp.element; //React.createElement
const {__} = wp.i18n; //translation functions
const {InspectorControls} = wp.blockEditor; //Block inspector wrapper
const {TextControl,SelectControl,ServerSideRender,PanelBody,ToggleControl} = wp.components; //WordPress form inputs and server-side renderer
const el = wp.element.createElement;
const iconEl = el('svg', { width: 20, height: 20 },
  el('rect',{fill:"none",height:"24",width:"24"}),
  el('rect',{height:"4",width:"4",x:"10",y:"4"}),
  el('rect',{height:"4",width:"4",x:"4",y:"16"}),
  el('rect',{height:"4",width:"4",x:"4",y:"10"}),
  el('rect',{height:"4",width:"4",x:"4",y:"4"}),
  el('rect',{height:"4",width:"4",x:"16",y:"4"}),
  el('polygon', { points: "11,17.86 11,20 13.1,20 19.08,14.03 16.96,11.91" } ),
  el('polygon', { points: "14,12.03 14,10 10,10 10,14 12.03,14" } ),
 // el('polygon', { points: "11,17.86 11,20 13.1,20 19.08,14.03 16.96,11.91" } ),
  el('path', { d: "M20.85,11.56l-1.41-1.41c-0.2-0.2-0.51-0.2-0.71,0l-1.06,1.06l2.12,2.12l1.06-1.06C21.05,12.07,21.05,11.76,20.85,11.56z" } )
);
var Groups = '';
//console.log(pg_groups[0].id);
wp.apiFetch( { path: 'profilegrid/v1/groups' } ).then( ( groups ) => {
    Groups = groups;
} );

var searchRequest = null; 
function pm_advance_user_search(pagenum)
{


    var form = jQuery("#pm-advance-search-form");
    jQuery("#pm_result_pane").html('<div class="pm-loader"></div>');
    var pmDomColor = jQuery(".pmagic").find("a").css('color');
    jQuery(".pm-loader").css('border-top-color', pmDomColor);
  


       
       
    if(pagenum!== '')
    {
            if(pagenum=='Reset')
            {
                form.trigger('reset');
                jQuery('#advance_search_pane').hide(200);
                jQuery('#pagenum').attr("value",1);
                jQuery('input[type=checkbox]').attr("checked",false);
                pm_change_search_field('');
            }
            else
            {
                jQuery('#pagenum').attr("value",pagenum);
            }
        
    }
    else
    {
         jQuery('#pagenum').attr("value",1);
    }
    var form_values = form.serializeArray();

    var data = {'nonce': pm_ajax_object.nonce};

    //creating data in object format and array for multiple checkbox
    jQuery.each(form_values, function () {
        if (data[this.name] !== undefined) {
            if (!data[this.name].push) {
                data[this.name] = [data[this.name]];
            }
            data[this.name].push(this.value);
        } else {
            data[this.name] = this.value;
        }
    });
    //console.log(data);
   
    if(searchRequest != null)
        searchRequest.abort();
        //ajax call start
    searchRequest =    jQuery.post(pm_ajax_object.ajax_url, data, function (resp) 
        {
        
                if (resp)
                {   
                    jQuery("#pm_result_pane").html(resp);
                    
        var pmDomColor = jQuery(".pmagic").find("a").css('color');
        jQuery(".pm-color").css('color', pmDomColor);
        jQuery( ".page-numbers.current" ).css('background', pmDomColor); 
                } 
                else
                {
                    //console.log("err");
                }
            
         });
         //ajax call ends here
         
         


}

function groups_option()
{
    return group_options();
}

function group_layout()
{
    var gutenbProfileArea = jQuery('.pmagic').innerWidth();    //$('span#pm-cover-image-width').text(profileArea);
    //$('.pm-cover-image').children('img').css('width', profileArea);
    if (gutenbProfileArea < 550) {
        jQuery('.pm-user-card').addClass('pm100');
    } else if (gutenbProfileArea < 900) {
        jQuery('.pm-user-card').addClass('pm50');
    } else if (gutenbProfileArea >= 900) {
        jQuery('.pm-user-card').addClass('pm33');
    }
}

function type_options()
{
    var type = [];
    type[0] = {value: 'single', label: 'No'};
    type[1] = {value: 'paged', label: 'Yes'};
    return type;
}
registerBlockType( 'profilegrid-blocks/group-registration', {
	title: __( 'ProfileGrid Sign-Up Form' ), // Block title.
	category:  __( 'widgets' ), //category
        icon: iconEl,
        supports: {
		customClassName: false,
		className: false,
		html: false
	},
	attributes:  {
		gid : {
			default:pg_groups[0].id,
                        type: 'string',
		},
		type: {
			default: 'single',
                         type: 'string',
		}
	},
        //display the post title
	edit(props){
		const attributes =  props.attributes;
		const setAttributes =  props.setAttributes;

		//Function to update id attribute
		function changeGid(gid){
			setAttributes({gid});
		}

		//Function to update heading level
		function changeType(type){
			setAttributes({type});
		}
                
                

		//Display block preview and UI
		return createElement('div', {}, [
                    
					
			//Preview a block with a PHP render callback
			createElement( wp.serverSideRender, {
				block: 'profilegrid-blocks/group-registration',
				attributes: attributes
			} ),
			//Block inspector
			createElement( InspectorControls, {},
				[
                                    createElement( PanelBody, { title: 'Form Settings', initialOpen: true },
					//A simple text control for post id
                                        createElement(SelectControl, {
						value: attributes.gid,
						label: __( 'User Group' ),
                                                help:__('Choose the ProfileGrid user group for which you wish to display the sign-up form for.','profilegrid-user-profiles-groups-and-communities'),
						onChange: changeGid,
						options:Groups
					}),
					//Select heading level
					createElement(SelectControl, {
						value: attributes.type,
                                                help:__("Display forms with more than one section as multi-page form on frontend. Please note, multi-page forms will render as single page in block editor view for usability reasons.",'profilegrid-user-profiles-groups-and-communities'),
						label: __( 'Multi-Page' ),
						onChange: changeType,
						options: type_options()
					})
                                     )
				]
			)
		] )
	},
	save(){
		return null;//save has to exist. This all we need
	}
});


registerBlockType( 'profilegrid-blocks/login-form', {
	title: __( 'ProfileGrid Login Form' ), // Block title.
	category:  __( 'widgets' ), //category
        icon: iconEl,
        supports: {
		customClassName: false,
		className: false,
		html: false
	},
        //display the post title
	edit(props){
		
                
                

		//Display block preview and UI
		return createElement('div', {}, [
                    
					
			//Preview a block with a PHP render callback
			createElement( wp.serverSideRender, {
				block: 'profilegrid-blocks/login-form'
			} )
			
		] )
	},
	save(){
		return null;//save has to exist. This all we need
	}
});

registerBlockType( 'profilegrid-blocks/all-users', {
	title: __( 'ProfileGrid All Users' ), // Block title.
	category:  __( 'widgets' ), //category
        icon: iconEl,
        supports: {
		customClassName: false,
		className: false,
		html: false
	},
        //display the post title
	edit(props){
		
                
                

		//Display block preview and UI
		return createElement('div', {}, [
                    
					
			//Preview a block with a PHP render callback
			createElement( wp.serverSideRender, {
				block: 'profilegrid-blocks/all-users'
			} )
			
		] )
	},
	save(){
		return null;//save has to exist. This all we need
	}
});

registerBlockType( 'profilegrid-blocks/all-groups', {
	title: __( 'ProfileGrid All Groups' ), // Block title.
	category:  __( 'widgets' ), //category
        icon: iconEl,
        supports: {
		customClassName: false,
		className: false,
		html: false
	},
        //display the post title
        attributes:  {
		view: {
			default: 'grid',
                         type: 'string'
		},
		sortby: {
			default: 'newest',
                         type: 'string'
		},
		sorting_dropdown: {
			type: 'boolean',
			default: true
		},
		view_icon: {
			type: 'boolean',
			default: true
		},
		search_box: {
                        type: 'boolean',
			default: true
		}
	},
	edit(props){
		const attributes =  props.attributes;
		const setAttributes =  props.setAttributes;


		//Function to update heading level
		function changeView(view){
			setAttributes({view});
		}
                
                function changeSortby(sortby){
			setAttributes({sortby});
		}
                
                const toggleSortingdropdown = () => {
                    setAttributes( { sorting_dropdown: ! attributes.sorting_dropdown } );
		};
                
                const toggleViewicon = () => {
                    setAttributes( { view_icon: ! attributes.view_icon } );
		};
                
                const toggleSearchBox = () => {
                    setAttributes( { search_box: ! attributes.search_box } );
		};

		//Display block preview and UI
		return createElement('div', {}, [
                    
					
			//Preview a block with a PHP render callback
			createElement( wp.serverSideRender, {
				block: 'profilegrid-blocks/all-groups',
                                attributes: attributes
			} ),
                        //Block inspector
			createElement( InspectorControls, {},
				[
                                    createElement( PanelBody, { title: 'Settings', initialOpen: true },
				
                                        createElement(SelectControl, {
						value: attributes.sortby,
						label: __( 'Default Sorting' ),
                                                onChange: changeSortby,
						options:[{value:'newest',label:'Newest'},{value:'oldest',label:'Oldest'},{value:'name_asc',label:'Alphabetical (A-Z)'},{value:'name_desc',label:'Alphabetical (Z-A)'}]
					}),
                                        createElement(SelectControl, {
						value: attributes.view,
						label: __( 'Default View' ),
                                                onChange: changeView,
						options:[{value:'grid',label:'Grid'},{value:'list',label:'List'}]
					}),
                                        createElement(ToggleControl, {
						checked: attributes.sorting_dropdown,
						label: __( 'Show Sorting Dropdown' ),
                                                onChange: toggleSortingdropdown
			
					}),
                                        createElement(ToggleControl, {
						checked: attributes.view_icon,
						label: __( 'Show View Icons' ),
                                                onChange: toggleViewicon
						
					}),
                                        createElement(ToggleControl, {
                                                checked: attributes.search_box,
						label: __( 'Show Search Box' ),
                                                onChange: toggleSearchBox
						//options:[{value:'1',label:'Yes'},{value:'0',label:'No'}]
					})
                                     )
				]
			)
			
		] )
	},
	save(){
		return null;//save has to exist. This all we need
	}
});

registerBlockType( 'profilegrid-blocks/user-blogs', {
	title: __( 'ProfileGrid User Blogs' ), // Block title.
	category:  __( 'widgets' ), //category
        icon: iconEl,
        supports: {
		customClassName: false,
		className: false,
		html: false
	},
        //display the post title
        attributes:  {
		wpblog: {
                        type: 'boolean',
			default: true
		}
	},
	edit(props){
		const attributes =  props.attributes;
		const setAttributes =  props.setAttributes;


		
               
                
                const toggleWPBlog = () => {
                    setAttributes( { wpblog: ! attributes.wpblog } );
		};

		//Display block preview and UI
		return createElement('div', {}, [
                    
					
			//Preview a block with a PHP render callback
			createElement( wp.serverSideRender, {
				block: 'profilegrid-blocks/user-blogs',
                                attributes: attributes
			} ),
                        //Block inspector
			createElement( InspectorControls, {},
				[
                                    createElement( PanelBody, { title: 'Settings', initialOpen: true },
				
                                        
                                        createElement(ToggleControl, {
                                                checked: attributes.wpblog,
						label: __( 'Show WP Blogs' ),
                                                onChange: toggleWPBlog
						//options:[{value:'1',label:'Yes'},{value:'0',label:'No'}]
					})
                                     )
				]
			)
			
		] )
	},
	save(){
		return null;//save has to exist. This all we need
	}
});

registerBlockType( 'profilegrid-blocks/group-page', {
	title: __( 'ProfileGrid Group Page' ), // Block title.
	category:  __( 'widgets' ), //category
        icon: iconEl,
        supports: {
		customClassName: false,
		className: false,
		html: false
	},
	attributes:  {
		gid : {
			default:pg_groups[0].id,
                        type: 'string',
		}
	},
        //display the post title
	edit(props){
		const attributes =  props.attributes;
		const setAttributes =  props.setAttributes;

		//Function to update id attribute
		function changeGid(gid){
			setAttributes({gid});
		}

		
                

		//Display block preview and UI
		return createElement('div', {}, [
                    
					
			//Preview a block with a PHP render callback
			createElement( wp.serverSideRender, {
				block: 'profilegrid-blocks/group-page',
				attributes: attributes
			} ),
			//Block inspector
			createElement( InspectorControls, {},
				[
                                    createElement( PanelBody, { title: 'Group Settings', initialOpen: true },
					//A simple text control for post id
                                        createElement(SelectControl, {
						value: attributes.gid,
						label: __( 'User Group' ),
                                                help:__('Choose the ProfileGrid User Group whose information you wish to display here.','profilegrid-user-profiles-groups-and-communities'),
						onChange: changeGid,
						options:Groups
					})
                                     )
				]
			),
                        group_layout()
		] )
	},
	save(){
		return null;//save has to exist. This all we need
	}
});

registerBlockType( 'profilegrid-blocks/blog-submission', {
	title: __( 'ProfileGrid Blog Submission' ), // Block title.
	category:  __( 'widgets' ), //category
        icon: iconEl,
        supports: {
		customClassName: false,
		className: false,
		html: false
	},
	edit(props){
		//Display block preview and UI
		return createElement('div', {}, [
                    
					
			//Preview a block with a PHP render callback
			createElement( wp.serverSideRender, {
				block: 'profilegrid-blocks/blog-submission'
			} )
		] )
	},
	save(){
		return null;//save has to exist. This all we need
	}
});
