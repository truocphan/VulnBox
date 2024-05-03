<?php
namespace Frontend_Admin;

if (! defined('ABSPATH') ) {
    exit; // Exit if accessed directly
}

if(! class_exists('Frontend_Admin_Gutenberg') ) :

    class Frontend_Admin_Gutenberg
    {

        public function register_blocks()
        {
            $blocks = [ 
                'admin-form' => 'form',
                'submissions' => 'submissions',
            ];

            foreach( $blocks as $block => $name ){
                register_block_type(
                    __DIR__ . "/build/blocks/$block", [
                    'render_callback' => [ $this, 'render_' . $name ],
                    ] 
                );
            }

            $field_types = fea_instance()->frontend->field_types;

            if( $field_types ){
                foreach( $field_types as $type ){
                    if ( $type instanceof Field_Types\Field_Base ) {
                        $name = str_replace( '_', '-', $type->name );
                        if( ! empty( $name ) && file_exists( FEA_DIR . "/main/gutenberg/build/$name/index.js" ) ){
                            register_block_type(
                                FEA_DIR . "/main/gutenberg/build/blocks/$name", [
                                'render_callback' => [ $this, 'render_field_block' ],
                                ] 
                            );        
                        }
                    }
                }
            }
        }

        public function render_field_block( $attr, $content, $block )
        {
            $render = '';        
            $field = acf_get_valid_field($attr);
            
            $field['type'] = str_replace(
                array( 'frontend-admin/', '-field', '-' ),
                array( '', '', '_' ),
                $block->name
            );

            ob_start();
            fea_instance()->form_display->render_field_wrap( $field );
            $render = ob_get_contents();
            ob_end_clean();    
            return $render;
        }
        
        public function render_text_field($attr, $content)
        {
            $render = '';        
            $field = acf_get_valid_field($attr);
            ob_start();
            fea_instance()->form_display->render_field_wrap( $field );
            $render = ob_get_contents();
            ob_end_clean();    
            return $render;
        }
        public function render_form($attr, $content)
        {
            $render = '';
            if ($attr['formID'] == 0 ) {
                   return $render;
            }
            if (get_post_type($attr['formID']) == 'admin_form' ) {
                ob_start();
                if(is_admin() ) {
                    $attr['editMode'] = true;
                }else{
                    $attr['editMode'] = false;
                }
                fea_instance()->form_display->render_form($attr['formID'], $attr['editMode']);
                $render = ob_get_contents();
                ob_end_clean();    
            }
            return $render;
        }
        public function render_submissions($attr, $content)
        {
            $render = '';
            if ($attr['formID'] == 0 ) {
                return $render;
            }
            if (get_post_type($attr['formID']) == 'admin_form' ) {
                ob_start();
                if(is_admin() ) { $attr['editMode'] = true;
                }
                fea_instance()->form_display->render_submissions($attr['formID'], $attr['editMode']);
                $render = ob_get_contents();
                ob_end_clean();    
            }
            if(! $render ) {
                return __('No Submissions Found', 'acf-frontend-form-element');
            }
            return $render;
        }

        function add_block_categories( $block_categories )
        {
            return array_merge(
                $block_categories,
                [
                [
                'slug'  => 'frontend-admin',
                'title' => 'Frontend Admin',
                'icon'  => 'feedback', 
                ],
                ]
            );
        }
        /**
         *  enqueue_block_editor_assets
         *
         *  Allows a safe way to customize Guten-only functionality.
         *
         * @date  14/11/22
         * @since 5.8.0
         *
         * @param  void
         * @return void
         */
        function enqueue_block_editor_assets()
        {
            // Load the compiled blocks into the editor.
            wp_enqueue_script(
                'fea-dynamic-values',
                FEA_URL . '/main/gutenberg/build/dynamic-values/index.js',
                ['wp-edit-post'],
                '1.0',
                true
            );
        }

        public function __construct()
        {
            add_filter('block_categories_all', array( $this, 'add_block_categories' ));
            add_action('init', array( $this, 'register_blocks' ));
            //add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
        }
    }

    fea_instance()->gutenberg = new Frontend_Admin_Gutenberg();

endif;    