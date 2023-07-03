<?php

function awpa_ma_multi_authors_list_post(){
    $wpaMultiAuthors = new WPAMultiAuthors();
    $post_id = get_the_ID();
    $wpaMultiAuthors->awpa_get_authors_frontend($post_id);
}

add_action('theme_setup', 'awpa_ma_multi_authors_list_post');