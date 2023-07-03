<?php
function awpa_get_allowed_roles( $post_id = null ) {
	return apply_filters( 'wpmat_get_allowed_roles', array( 'administrator', 'editor', 'author' ), $post_id );
}

function awpa_get_contributors_role_in( $post_id = null ) {
	return apply_filters( 'wpmat_get_contributors_role_in', array( 'administrator', 'editor', 'author', 'contributor' ), $post_id );
}