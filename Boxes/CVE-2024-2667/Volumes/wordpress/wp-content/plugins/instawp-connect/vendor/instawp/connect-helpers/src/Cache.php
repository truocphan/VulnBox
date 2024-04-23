<?php
namespace InstaWP\Connect\Helpers;

class Cache {

    public function clean() {
        $results = [];

        if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$results = [];

		// Elementor.
		if ( is_plugin_active( 'elementor/elementor.php' ) ) {
			$message = '';

			if ( class_exists( '\Elementor\Plugin' ) ) {
				\Elementor\Plugin::$instance->files_manager->clear_cache();
			} else {
				$message = 'Class or Method not exists.';
			}
			
			$results[] = [
				'slug'    => 'elementor',
				'name'    => 'Elementor',
				'message' => $message
			];
		}

		// WordPress Cache / Object Cache Plugins (e.g. Radis Cache, Docket Cache).
		if ( function_exists( 'wp_cache_flush' ) ) {
			wp_cache_flush();

			$results[] = [
				'slug'    => 'wordpress',
				'name'    => 'WordPress Cache',
				'message' => ''
			];
		}

		// WP Rocket.
		if ( is_plugin_active( 'wp-rocket/wp-rocket.php' ) ) {
			$message = '';
			
			if ( function_exists( 'rocket_clean_minify' ) && function_exists( 'rocket_clean_domain' ) ) {
				rocket_clean_minify();
				rocket_clean_domain();
			} else {
				$message = 'Function not exists.';
			}

			$results[] = [
				'slug'    => 'wp-rocket',
				'name'    => 'WP Rocket',
				'message' => $message
			];
		}

		// Autoptimize.
		if ( is_plugin_active( 'autoptimize/autoptimize.php' ) ) {
			$message = '';

			if ( class_exists( '\autoptimizeCache' ) && method_exists( '\autoptimizeCache', 'clearall' ) ) {
				\autoptimizeCache::clearall();
			} else {
				$message = 'Class or Method not exists.';
			}
			
			$results[] = [
				'slug'    => 'autoptimize',
				'name'    => 'Autoptimize',
				'message' => $message
			];
		}

		// W3 Total Cache.
		if ( is_plugin_active( 'w3-total-cache/w3-total-cache.php' ) ) {
			$message = '';

			if ( function_exists( 'w3tc_flush_all' ) ) {
				w3tc_flush_all();
			} else {
				$message = 'Function not exists.';
			}

			$results[] = [
				'slug'    => 'w3-total-cache',
				'name'    => 'W3 Total Cache',
				'message' => $message
			];
		}

		// LiteSpeed Cache.
		if ( is_plugin_active( 'litespeed-cache/litespeed-cache.php' ) ) {
			$message = '';

			if ( class_exists( '\LiteSpeed\Purge' ) && method_exists( '\LiteSpeed\Purge', 'purge_all' ) ) {
				\LiteSpeed\Purge::purge_all();
			} else {
				$message = 'Class or Method not exists.';
			}

			$results[] = [
				'slug'    => 'litespeed-cache',
				'name'    => 'LiteSpeed Cache',
				'message' => $message
			];
		}

		// WP Fastest Cache.
		if ( is_plugin_active( 'wp-fastest-cache/wpFastestCache.php' ) ) {
			$message = '';

			if ( function_exists( 'wpfc_clear_all_cache' ) ) {
				$cleared = wpfc_clear_all_cache( true );
				if ( is_array( $cleared ) && ! empty( $cleared['message'] ) ) {
					$message = $cleared['message'];
				}
			} else {
				$message = 'Function not exists.';
			}

			$results[] = [
				'slug'    => 'wp-fastest-cache',
				'name'    => 'WP Fastest Cache',
				'message' => $message
			];
		}

		// WP Super Cache.
		if ( is_plugin_active( 'wp-super-cache/wp-cache.php' ) ) {
			$message = '';

			if ( function_exists( 'wp_cache_clean_cache' ) ) {
				global $file_prefix;
				wp_cache_clean_cache( $file_prefix, true );
			} else {
				$message = 'Function not exists.';
			}

			$results[] = [
				'slug'    => 'wp-super-cache',
				'name'    => 'WP Super Cache',
				'message' => $message
			];
		}

		// Cache Enabler.
		if ( is_plugin_active( 'cache-enabler/cache-enabler.php' ) ) {
			$message = '';

			if ( has_action( 'cache_enabler_clear_complete_cache' ) ) { // Cache Enabler >= 1.6.0
				do_action( 'cache_enabler_clear_complete_cache' );
			} else if ( has_action( 'ce_clear_cache' ) ) { // Cache Enabler < 1.6.0
				do_action( 'ce_clear_cache' );
			} else {
				$message = 'Action not exists.';
			}

			$results[] = [
				'slug'    => 'cache-enabler',
				'name'    => 'Cache Enabler',
				'message' => $message
			];
		}

		// Hyper Cache.
		if ( is_plugin_active( 'hyper-cache/plugin.php' ) ) {
			$message = '';

			if ( class_exists( '\HyperCache' ) && method_exists( '\HyperCache', 'clean' ) ) {
				$hC = new \HyperCache();
				$hC->clean();
			} else {
				$message = 'Class or Method not exists.';
			}

			$results[] = [
				'slug'    => 'hyper-cache',
				'name'    => 'Hyper Cache',
				'message' => $message
			];
		}

		// Breeze.
		if ( is_plugin_active( 'breeze/breeze.php' ) ) {
			$message = '';

			if ( has_action( 'breeze_clear_all_cache' ) ) {
				do_action( 'breeze_clear_all_cache' );
			} else {
				$message = 'Action not exists.';
			}

			$results[] = [
				'slug'    => 'breeze',
				'name'    => 'Breeze',
				'message' => $message
			];
		}

		// Comet Cache.
		if ( is_plugin_active( 'comet-cache/comet-cache.php' ) ) {
			$message = '';

			if ( class_exists( '\comet_cache' ) && method_exists( '\comet_cache', 'clear' ) ) {
				\comet_cache::clear();
			} else {
				$message = 'Class or Method not exists.';
			}

			$results[] = [
				'slug'    => 'comet-cache',
				'name'    => 'Comet Cache',
				'message' => $message
			];
		}

		// WP OPcache.
		if ( is_plugin_active( 'flush-opcache/flush-opcache.php' ) ) {
			$message = '';

			if ( defined( 'FLUSH_OPCACHE_NAME' ) && defined( 'FLUSH_OPCACHE_VERSION' ) ) {
				if ( class_exists( '\Flush_Opcache_Admin' ) && method_exists( '\Flush_Opcache_Admin', 'flush_opcache_reset' ) ) {
					$oc = new \Flush_Opcache_Admin( FLUSH_OPCACHE_NAME, FLUSH_OPCACHE_VERSION );
					$oc->flush_opcache_reset();
				} else {
					$message = 'Class or Method not exists.';
				}
			} else {
				$message = 'Object Cache name or version is not defined!.';
			}

			$results[] = [
				'slug'    => 'flush-opcache',
				'name'    => 'WP OPcache',
				'message' => $message
			];
		}

		// FlyingPress.
		if ( is_plugin_active( 'flying-press/flying-press.php' ) ) {
			$message = '';

			if ( class_exists( '\FlyingPress\Purge' ) && method_exists( '\FlyingPress\Purge', 'purge_everything' ) ) {
				\FlyingPress\Purge::purge_everything();
			} else {
				$message = 'Class or Method not exists.';
			}

			$results[] = [
				'slug'    => 'flying-press',
				'name'    => 'FlyingPress',
				'message' => $message
			];
		}

		// Cachify.
		if ( is_plugin_active( 'cachify/cachify.php' ) ) {
			$message = '';

			if ( has_action( 'cachify_flush_cache' ) ) {
				do_action( 'cachify_flush_cache' );
			} else {
				$message = 'Action not exists.';
			}

			$results[] = [
				'slug'    => 'cachify',
				'name'    => 'Cachify',
				'message' => $message
			];
		}

		// Powered Cache.
		if ( is_plugin_active( 'powered-cache/powered-cache.php' ) ) {
			$message = '';

			if ( function_exists( '\PoweredCache\Utils\powered_cache_flush' ) ) {
				\PoweredCache\Utils\powered_cache_flush();
			} else {
				$message = 'Function not exists.';
			}

			$results[] = [
				'slug'    => 'powered-cache',
				'name'    => 'Powered Cache',
				'message' => $message
			];
		}

		// Swift Performance Lite.
		if ( is_plugin_active( 'swift-performance-lite/performance.php' ) ) {
			$message = '';

			if ( class_exists( '\Swift_Performance_Cache' ) && method_exists( '\Swift_Performance_Cache', 'clear_all_cache' ) ) {
				\Swift_Performance_Cache::clear_all_cache();
			} else {
				$message = 'Class or Method not exists.';
			}

			$results[] = [
				'slug'    => 'swift-performance-lite',
				'name'    => 'Swift Performance',
				'message' => $message
			];
		}

		// bunny.net.
		if ( is_plugin_active( 'bunnycdn/bunnycdn.php' ) ) {
			$message = '';

			if ( class_exists( '\BunnyCdn' ) && method_exists( '\BunnyCdn', 'getOptions' ) ) {
				$options = \BunnyCdn::getOptions();
				$domain  = 'instawpcom.b-cdn.net';

				if ( ! empty( $domain ) ) {
					$response = wp_remote_post( 'https://bunnycdn.com/api/pullzone/purgeCacheByHostname?hostname=' . $domain, [
						'headers' => [
							'AccessKey' => htmlspecialchars( $options['api_key'] ),
						],
					] );
					if ( is_wp_error( $response ) ) {
						$message = $response->get_error_message();
					}
				} else {
					$message = 'CDN Domain is empty.';
				}
			} else {
				$message = 'Class or Method not exists.';
			}

			$results[] = [
				'slug'    => 'bunnycdn',
				'name'    => 'bunny.net',
				'message' => $message
			];
		}

		// Nginx Helper.
		if ( is_plugin_active( 'nginx-helper/nginx-helper.php' ) ) {
			$message = '';

			if ( has_action( 'rt_nginx_helper_purge_all' ) ) {
				do_action( 'rt_nginx_helper_purge_all' );
			} else {
				$message = 'Action not exists.';
			}

			$results[] = [
				'slug'    => 'nginx-helper',
				'name'    => 'Nginx Helper',
				'message' => $message
			];
		}

		// Hummingbird.
		if ( is_plugin_active( 'hummingbird-performance/wp-hummingbird.php' ) ) {
			$message = '';

			if ( class_exists( '\Hummingbird\WP_Hummingbird' ) && method_exists( '\Hummingbird\WP_Hummingbird', 'flush_cache' ) ) {
				\Hummingbird\WP_Hummingbird::flush_cache();
			} else {
				$message = 'Class or Method not exists.';
			}

			$results[] = [
				'slug'    => 'hummingbird-performance',
				'name'    => 'Hummingbird',
				'message' => $message
			];
		}

		// SiteGround Optimizer.
		if ( is_plugin_active( 'sg-cachepress/sg-cachepress.php' ) ) {
			$message = '';

			if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
				sg_cachepress_purge_cache();
			} else {
				$message = 'Function not exists.';
			}

			$results[] = [
				'slug'    => 'sg-cachepress',
				'name'    => 'SiteGround Optimizer',
				'message' => $message
			];
		}

		// RunCloud Hub.
		if ( is_plugin_active( 'runcloud-hub/runcloud-hub.php' ) ) {
			$message = '';

			if ( class_exists( '\RunCloud_Hub' ) && method_exists( '\RunCloud_Hub', 'purge_cache_all' ) ) {
				\RunCloud_Hub::purge_cache_all();
			} else {
				$message = 'Class or Method not exists.';
			}

			$results[] = [
				'slug'    => 'runcloud-hub',
				'name'    => 'RunCloud Hub',
				'message' => $message
			];
		}

		// Super Page Cache for Cloudflare.
		if ( is_plugin_active( 'wp-cloudflare-page-cache/wp-cloudflare-super-page-cache.php' ) ) {
			$message = '';

			if ( defined( 'SWCFPC_CACHE_BUSTER' ) && class_exists( '\SW_CLOUDFLARE_PAGECACHE' ) && class_exists( '\SWCFPC_Cache_Controller' ) && method_exists( '\SWCFPC_Cache_Controller', 'purge_all' ) ) {
				$cf = new \SWCFPC_Cache_Controller( SWCFPC_CACHE_BUSTER, new \SW_CLOUDFLARE_PAGECACHE() );
				$cf->purge_all();
			} else {
				$message = 'Class or Method or Constant not exists.';
			}

			$results[] = [
				'slug'    => 'wp-cloudflare-page-cache',
				'name'    => 'Super Page Cache for Cloudflare',
				'message' => $message
			];
		}

		// WP Engine
		if ( class_exists( '\WpeCommon' ) ) {
			$message = [];

			if ( method_exists( '\WpeCommon', 'purge_varnish_cache' ) ) {
				\WpeCommon::purge_varnish_cache();
			} else {
				$message[] = 'WP Engine Varnish Cache Class or Method not exists.';
			}

			if ( method_exists( '\WpeCommon', 'purge_memcached' ) ) {
				\WpeCommon::purge_memcached();
			} else {
				$message[] = 'WP Engine Memcache Class or Method not exists.';
			}
	
			if ( method_exists( '\WpeCommon', 'clear_maxcdn_cache' ) ) {
				\WpeCommon::clear_maxcdn_cache();
			} else {
				$message[] = 'WP Engine MaxCDN Cache Class or Method not exists.';
			}

			$results[] = [
				'slug'    => 'wp-engine',
				'name'    => 'WP Engine',
				'message' => join( ' ', $message ),
			];
		}

		// Kinsta
		if ( array_key_exists( 'KINSTA_CACHE_ZONE', $_SERVER ) ) {
			$clear_cache_url = 'https://localhost/kinsta-clear-cache-all';
			$response        = wp_remote_get( $clear_cache_url, [
				'sslverify' => false,
				'timeout'   => 30,
			] );

			$results[] = [
				'slug'    => 'kinsta',
				'name'    => 'Kinsta',
				'message' => ''
			];
		}

		// Cloudflare.
		if ( is_plugin_active( 'cloudflare/cloudflare.php' ) ) {
			$message = '';

			if ( class_exists( '\CF\WordPress\Hooks' ) && method_exists( '\CF\WordPress\Hooks', 'purgeCacheEverything' ) ) {
				$cf = new \CF\WordPress\Hooks();
				$cf->purgeCacheEverything();
			} else {
				$message = 'Class or Method not exists.';
			}

			$results[] = [
				'slug'    => 'cloudflare',
				'name'    => 'Cloudflare',
				'message' => $message
			];
		}

		return array_map( function( $result ) {
			$message = trim( $result['message'] );
			unset( $result['message'] );

			$result['success'] = empty( $message );
			$result['message'] = $message;
			
			return $result;
		}, $results );
    }
}