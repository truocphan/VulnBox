<?php
class PM_DBhandler {

    public function insert_row( $identifier, $data, $format = null ) {
        global $wpdb;
        $pm_activator = new Profile_Magic_Activator();
        $table        = $pm_activator->get_db_table_name( $identifier );
        $result       = $wpdb->insert( $table, $data, $format );

        if ( $result !== false ) {
			return $wpdb->insert_id; } else {
			return false; }
    }

    public function update_row( $identifier, $unique_field, $unique_field_value, $data, $format = null, $where_format = null ) {
        global $wpdb;
        $pm_activator = new Profile_Magic_Activator();
        $table        = $pm_activator->get_db_table_name( $identifier );
        if ( $unique_field === false ) {
            $unique_field = $pm_activator->get_db_table_unique_field_name( $identifier );
        }

        if ( is_numeric( $unique_field_value ) ) {
            $unique_field_value = (int) $unique_field_value;
            $query              = $wpdb->prepare( "SELECT * from $table where $unique_field = %d", $unique_field_value );
        } else {
            $query = $wpdb->prepare( "SELECT * from $table where $unique_field = %s", $unique_field_value );
        }

        if ( $query != null ) {
            $result = $wpdb->get_row( $query );
        }

        if ( $result === null ) {
			return false; }

		$where = array( $unique_field => $unique_field_value );
        return $wpdb->update( $table, $data, $where, $format, $where_format );
    }

    public function remove_row( $identifier, $unique_field, $unique_field_value, $where_format = null ) {
        global $wpdb;
        $pm_activator = new Profile_Magic_Activator();
        $table        = $pm_activator->get_db_table_name( $identifier );
        if ( $unique_field === false ) {
			$unique_field = $pm_activator->get_db_table_unique_field_name( $identifier );
        }

        if ( is_numeric( $unique_field_value ) ) {
            $unique_field_value = (int) $unique_field_value;
            $query              = $wpdb->prepare( "SELECT * from $table WHERE $unique_field = %d", $unique_field_value );
        } else {
            $query = $wpdb->prepare( "SELECT * from $table WHERE $unique_field = %s", $unique_field_value );
        }

        if ( $query != null ) {
            $result = $wpdb->get_row( $query );
        }

        if ( $result === null ) {
			return false; }

		$where = array( $unique_field => $unique_field_value );
        return $wpdb->delete( $table, $where, $where_format );
    }

    public function get_row( $identifier, $unique_field_value, $unique_field = false, $output_type = 'OBJECT' ) {
        global $wpdb;
        $pm_activator = new Profile_Magic_Activator();
        $table        = $pm_activator->get_db_table_name( $identifier );
        $result       = null;
        if ( $unique_field === false ) {
			$unique_field = $pm_activator->get_db_table_unique_field_name( $identifier );
        }

        if ( is_numeric( $unique_field_value ) ) {
            $unique_field_value = (int) $unique_field_value;
            $query              = $wpdb->prepare( "SELECT * from $table where $unique_field = %d", $unique_field_value );
        } else {
            $query = $wpdb->prepare( "SELECT * from $table where $unique_field = %s", $unique_field_value );
        }

        if ( $query != null ) {
            $result = $wpdb->get_row( $query, $output_type );
        }

        if ( $result != null ) {
			return $result; }
    }

    public function get_value( $identifier, $field, $unique_field_value, $unique_field = false ) {
         global $wpdb;
        $pm_activator = new Profile_Magic_Activator();
        $table        = $pm_activator->get_db_table_name( $identifier );

        if ( $unique_field === false ) {
			$unique_field = $pm_activator->get_db_table_unique_field_name( $identifier );
        }

        if ( is_numeric( $unique_field_value ) ) {
            $unique_field_value = (int) $unique_field_value;
            $query              = $wpdb->prepare( "SELECT $field from $table where $unique_field = %d", $unique_field_value );
        } else {
            $query = $wpdb->prepare( "SELECT $field from $table where $unique_field = %s", $unique_field_value );
        }

        if ( $query != null ) {
            $result = $wpdb->get_var( $query );
        }

        if ( isset( $result ) && $result != null ) {
			return $result; }
    }

    public function get_value_with_multicondition( $identifier, $field, $where ) {
         global $wpdb;
        $pm_activator = new Profile_Magic_Activator();
        $table        = $pm_activator->get_db_table_name( $identifier );
        $qry          = "SELECT $field from $table where";
        $i            = 0;
        $args         = array();
        foreach ( $where as $column_name => $column_value ) {

			if ( $i !== 0 ) {
				$qry .= ' AND'; }

                $format = $pm_activator->get_db_table_field_type( $identifier, $column_name );
                $qry   .= " $column_name = $format";

			if ( is_numeric( $column_value ) ) {
				$args[] = (int) $column_value; } else {
				$args[] = $column_value; }

                $i++;
		}
             $results = $wpdb->get_var( $wpdb->prepare( $qry, $args ) );
             return $results;
    }

    public function get_all_result( $identifier, $column = '*', $where = 1, $result_type = 'results', $offset = 0, $limit = false, $sort_by = null, $descending = false, $additional = '', $output = 'OBJECT', $distinct = false ) {
        global $wpdb;
        $pm_activator   = new Profile_Magic_Activator();
        $table          = $pm_activator->get_db_table_name( $identifier );
        $unique_id_name = $pm_activator->get_db_table_unique_field_name( $identifier );
        $args           = array();
        if ( !$sort_by ) {
            $sort_by = $unique_id_name;
        }
        if ( is_string( $column ) && strpos( $column, 'distinct' ) ) {
            $column   = str_replace( 'distinct ', '', $column );
            $distinct = true;
        } elseif ( is_string( $column ) && strpos( $column, 'DISTINCT' ) ) {
            $column   = str_replace( 'DISTINCT ', '', $column );
            $distinct = true;
        }

        if ( $column != '' && !is_array( $column ) && $distinct == false ) {
            $qry = "SELECT $column FROM $table WHERE";
        } elseif ( $column != '' && !is_array( $column ) && $distinct == true ) {
            $qry = "SELECT DISTINCT $column FROM $table WHERE";
        } elseif ( is_array( $column ) ) {
            $qry = 'SELECT ' . implode( ', ', $column ) . " FROM $table WHERE";
        }

        if ( is_array( $where ) ) {
            $i = 0;
            foreach ( $where as $column_name => $column_value ) {

                if ( $i !== 0 ) {
					$qry .= ' AND'; }

                $format = $pm_activator->get_db_table_field_type( $identifier, $column_name );
                $qry   .= " $column_name = $format";

                if ( is_numeric( $column_value ) ) {
					$args[] = (int) $column_value; } else {
					$args[] = $column_value; }

					$i++;
            }
			if ( $additional!='' ) {
                $qry .= ' ' . $additional;
			}
        } elseif ( $where == 1 ) {
            if ( $additional!='' ) {
                $qry .= ' ' . $additional;
            } else {
                $qry .= ' 1';
            }
        }

        if ( $descending === false ) {
            $qry .= " ORDER BY $sort_by";
        } else {
            $qry .= " ORDER BY $sort_by DESC";
        }

		if ( $limit===false ) {
            $qry .= '';
        } else {
            $qry .= " LIMIT $limit OFFSET $offset";
        }

        if ( $result_type === 'results' || $result_type === 'row' || $result_type === 'var' ) {
            $method_name = 'get_' . $result_type;
            if ( count( $args ) === 0 ) {
                if ( $result_type === 'results' ) :
                    $results = $wpdb->$method_name( $qry, $output );
                else :
                    $results = $wpdb->$method_name( $qry );
                endif;
            } else {
                if ( $result_type === 'results' ) :
                    $results = $wpdb->$method_name( $wpdb->prepare( $qry, $args ), $output );
                else :
                    $results = $wpdb->$method_name( $wpdb->prepare( $qry, $args ) );
                endif;
            }
        } else {
            return null;
        }

        if ( is_array( $results ) && count( $results )===0 ) {
            return null;
        }
        return $results;
    }

    public function pm_count( $identifier, $where = 1, $data_specifiers = '' ) {
        global $wpdb;
        $pm_activator = new Profile_Magic_Activator();
        $table_name   = $pm_activator->get_db_table_name( $identifier );
        if ( $data_specifiers=='' ) {
            $unique_id_name = $pm_activator->get_db_table_unique_field_name( $identifier );
            if ( $unique_id_name === false ) {
				return false; }
        } else {
			$unique_id_name = $data_specifiers; }

        $qry = "SELECT COUNT($unique_id_name) FROM $table_name WHERE ";

        if ( is_array( $where ) ) {
            $i =0;
            foreach ( $where as $column_name => $column_value ) {
                if ( $i!=0 ) {
					$qry .= 'AND '; }
                if ( is_numeric( $column_value ) ) {
                    $column_value = (int) $column_value;
                    $qry         .= $wpdb->prepare( "$column_name = %d ", $column_value );
                } else {
                    $qry .= $wpdb->prepare( "$column_name = %s ", $column_value );
                }
            }
        } elseif ( $where == 1 ) {
			$qry .= '1 '; }

        $count = $wpdb->get_var( $qry );

        if ( $count === null ) {
			return false; }

        return (int) $count;
    }

	public function pm_add_user( $user_name, $password, $user_email, $user_role = 'subscriber' ) {
		if ( is_multisite() ) {
			$blog_id = get_current_blog_id();
			if ( email_exists( $user_email ) ) {
				 $user_id = email_exists( $user_email );
				if ( !is_user_member_of_blog( $user_id, $blog_id ) ) {
					add_user_to_blog( $blog_id, $user_id, $user_role );
				}
			} else {
                                $user_id = wp_create_user( $user_name, $password, $user_email );
				if ( is_numeric( $user_id ) ) {
                    $user_id = wp_update_user(
                        array(
							'ID'   => $user_id,
							'role' => $user_role,
                        )
                    );
                    if ( !is_user_member_of_blog( $user_id, $blog_id ) ) {
                        add_user_to_blog( $blog_id, $user_id, $user_role );
                    }
                }
			}
		} else {

			$user_id = wp_create_user( $user_name, $password, $user_email );
			if ( is_numeric( $user_id ) ) {
                $user_id = wp_update_user(
                    array(
						'ID'   => $user_id,
						'role' => $user_role,
                    )
                );
            }
		}
		return $user_id;
	}

    public function get_global_option_value( $option, $default = '' ) {
            $value = get_option( $option, $default );
		if ( !isset( $value ) || $value=='' ) {
			$value = $default; }
            $value = maybe_unserialize( $value );
            return $value;
    }

    public function update_global_option_value( $option, $value ) {
            update_option( $option, $value );
    }

    public function pm_get_all_users_ajax( $search = '', $meta_query = array(), $role = '', $offset = '', $limit = '', $order = 'ASC', $orderby = 'ID', $exclude = array(), $datequery = array(), $include = array() ) {
         $args = array(
			 'order'       => $order,
			 'orderby'     => $orderby,
			 'count_total' => true,
		 );

		 if ( $orderby=='first_name' || $orderby=='last_name' ) {
			 $args['orderby']  = 'meta_value';
			 $args['meta_key'] = $orderby;
		 }
		 if ( $offset!='' ) {
			 $args['offset'] = $offset; }
		 if ( $limit!='' ) {
			 $args['number'] = $limit; }
		 if ( $role!='' ) {
			 $args['role'] = $role; }
		 if ( $search!='' ) {
			 $args['search'] = '*' . esc_attr( $search ) . '*'; }
		 if ( $role!='' ) {
			 $args['role'] = $role; }
		 if ( !empty( $meta_query ) ) {
			 if ( isset( $meta_query['search'] ) ) {
				 $args['search'] = '*' . esc_attr( $meta_query['search'] ) . '*';
				 unset( $meta_query['search'] );
			 }
			 if ( isset( $meta_query['search_columns'] ) ) {
				 $args['search_columns'] = array( $meta_query['search_columns'] );
				 unset( $meta_query['search_columns'] );
			 }
				$args['meta_query'] = $meta_query;

		 }
		 if ( !empty( $exclude ) ) {
			 $args['exclude'] = $exclude; }
		 if ( !empty( $include ) ) {
			 $args['include'] = $include; }
		 if ( !empty( $datequery ) ) {
			 $args['date_query'] = $datequery; }

		 $user_query = new WP_User_Query( $args );

		 return $user_query;
    }

	public function pm_get_all_users( $search = '', $meta_query = array(), $role = '', $offset = '', $limit = '', $order = 'ASC', $orderby = 'ID', $exclude = array(), $datequery = array(), $include = array() ) {
		$args = array(
			'order'       => $order,
			'orderby'     => $orderby,
			'count_total' => true,
		);

		if ( $orderby=='first_name' || $orderby=='last_name' ) {
			$args['orderby']  = 'meta_value';
			$args['meta_key'] = $orderby;
		}

		if ( $offset!='' ) {
			$args['offset'] = $offset; }
		if ( $limit!='' ) {
			$args['number'] = $limit; }
		if ( $role!='' ) {
			$args['role'] = $role; }
		if ( $search!='' ) {
			$args['search'] = '*' . esc_attr( $search ) . '*'; }
		if ( $role!='' ) {
			$args['role'] = $role; }
		if ( !empty( $meta_query ) ) {
			if ( isset( $meta_query['search'] ) ) {
				$args['search'] = '*' . esc_attr( $meta_query['search'] ) . '*';
				unset( $meta_query['search'] );
			}
			if ( isset( $meta_query['search_columns'] ) ) {
				$args['search_columns'] = array( $meta_query['search_columns'] );
				unset( $meta_query['search_columns'] );
			}
                    $args['meta_query'] = $meta_query;

		}

		if ( !empty( $exclude ) ) {
			$args['exclude'] = $exclude; }
		if ( !empty( $include ) ) {
			$args['include'] = $include; }
		if ( !empty( $datequery ) ) {
			$args['date_query'] = $datequery; }
		$users = get_users( $args );

		return $users;
	}

	public function pm_get_pagination( $num_of_pages, $pagenum, $base = '' ) {
		if ( $pagenum=='' ) {
			$pagenum =1; }
        if ( $base=='' ) {
			$base = esc_url_raw( add_query_arg( 'pagenum', '%#%' ) ); }
		$args = array(
			'base'               => $base,
			'format'             => '',
			'total'              => $num_of_pages,
			'current'            => $pagenum,
			'show_all'           => false,
			'end_size'           => 1,
			'mid_size'           => 2,
			'prev_next'          => true,
			'prev_text'          => __( '&laquo;', 'profilegrid-user-profiles-groups-and-communities' ),
			'next_text'          => __( '&raquo;', 'profilegrid-user-profiles-groups-and-communities' ),
			'type'               => 'list',
			'add_args'           => false,
			'add_fragment'       => '',
			'before_page_number' => '',
			'after_page_number'  => '',
		);

		$page_links = paginate_links( $args );
		return $page_links;
	}

    public function pm_get_all_groups_ajax( $search, $offset = 0, $limit = '10', $order = 'DESC', $sort_by = 'members' ) {
    }


}
