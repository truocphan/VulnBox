# New Customizer

## `new-customizer.js`

* The `customizer.js` file is still in use. We just defined a new section type in a new file. It should not be deleted. Old section types (popup, pane) could be deleted from that file.

`JupiterX.ContainerSection`
Defines a new section type called **`container`**.

In this section type, we have `boxes` and `tabs`.
Each section has its own control. **Boxes** are a dummy hierarchy between **Section** and **Controls**. That means, generally we use the same structure as WordPress is using, *Panels->Sections->Controls* but we are adding **Boxes** between **Sections** and **Controls**.

**Tabs** are normal buttons that are adding a CSS Class Name to boxes or removing that class to make specific boxes visible or hidden. This is happening using data attributes.


`api.bind 'ready'`

In this block of code, we are adding raw markups needed for searchbox, grouping panels and sections, and then we attach needed events for search box.

`api.bind 'pane-contents-reflowed'`

In this event, We are moving panels and sections to their own groups. This could not be done in `ready` event because some functionalities are running later and it could cause unwanted results in the view.


`api.reflowPaneContents`

This block of codes is coming from core of WordPress. In order to move controls to their boxes (Instead of having them in root of section and out of Boxes). Also, we are adding support for **Groups** for native WordPress sections and panels like `Widgets`.


* How to add tabs, boxes, and new sections?

```
JupiterX_Customizer::add_section(
	'jupiterx_header',
	array(
		'priority' => 50,
		'title'    => __( 'Header', 'jupiterx-core' ),
		'type'     => 'container',
		'tabs'     => array(
			'settings' => __( 'Settings', 'jupiterx-core' ),
			'styles'   => __( 'Styles', 'jupiterx-core' ),
		),
		'boxes' => array(
			'settings'             => array(
				'label' => __( 'Settings', 'jupiterx-core' ),
				'tab'   => 'settings',
			),
			'logo'             => array(
				'label' => __( 'Logo', 'jupiterx-core' ),
				'tab'   => 'styles',
			),
			'menu'             => array(
				'label' => __( 'Menu', 'jupiterx-core' ),
				'tab'   => 'styles',
			),
			'submenu'          => array(
				'label' => __( 'Submenu', 'jupiterx-core' ),
				'tab'   => 'styles',
			),
			'search'           => array(
				'label' => __( 'Search', 'jupiterx-core' ),
				'tab'   => 'styles',
			),
			'container'        => array(
				'label' => __( 'Container', 'jupiterx-core' ),
				'tab'   => 'styles',
			),
			'sticky_container' => array(
				'label' => __( 'Sticky Container', 'jupiterx-core' ),
				'tab'   => 'styles',
			),
			'sticky_logo'      => array(
				'label' => __( 'Sticky Logo', 'jupiterx-core' ),
				'tab'   => 'styles',
			),
		),
		'help'     => array(
			'url'   => 'https://themes.artbees.net/docs/assigning-the-header-globally',
			'title' => __( 'Assigning the Header Globally', 'jupiterx-core' ),
		),
		'group'    => 'template_parts',
	)
);

```

* In order to dd a control to a box, just set the **`box`** prop like below.

```
'box'         => 'logo',
```
