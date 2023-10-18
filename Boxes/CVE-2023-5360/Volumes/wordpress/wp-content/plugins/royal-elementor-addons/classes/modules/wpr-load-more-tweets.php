<?php
namespace WprAddons\Classes\Modules;

use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Load_More_Tweets setup
 *
 * @since 3.4.6
 */

 class WPR_Load_More_Tweets {

    public function __construct() {
      add_action('wp_ajax_wpr_load_more_tweets', [$this, 'wpr_load_more_tweets_function']);
      add_action('wp_ajax_nopriv_wpr_load_more_tweets', [$this, 'wpr_load_more_tweets_function']);
    }	

    // Render User Name
    public function render_post_username( $settings, $class, $item ) {
      echo '<'. esc_attr($settings['element_username_tag']) .' class="'. esc_attr($class) .'">';
        echo '<div class="inner-block">';
                  echo '<a>'. $item['user']['name'] .'</a>';
        echo '</div>';
      echo '</'. esc_attr($settings['element_username_tag']) .'>';
    }
  
    // Render User Account
    public function render_post_user_profile_name( $settings, $class, $item ) {
      echo '<div class="'. esc_attr($class) .'">';
        echo '<div class="inner-block">';
              echo '<small>';
                  echo '<a href="https://twitter.com/' . $item['user']['screen_name'] .'">@'. $item['user']['screen_name'] .'</a>';
              echo '</small>';
        echo '</div>';
      echo '</div>';
    }
  
    // Render User Account
    public function render_post_user_profile_picture( $settings, $class, $item ) {
      echo '<div class="'. esc_attr($class) .'">';
        echo '<div class="inner-block">';
              ?>
              <figure>
                  <img src="<?php echo $item['user']['profile_image_url'] ?>" alt="Image">
              </figure>
              <?php
        echo '</div>';
      echo '</div>';
    }
  
    public function render_post_caption($settings, $class, $item) {
  
      if ( !isset($item['full_text']) || '' === $item['full_text'] ) {
        return;
      }
  
      echo '<div class="'. esc_attr($class) .'">';
        echo '<div class="inner-block">';
                  echo '<p>';
                      $string = preg_replace("~[[:alpha:]]+://[^<p>[:space:]]+[[:alnum:]/]~", "<a href=\"\\0\">\\0</a>", $item['full_text']);
                      $new_string = preg_replace('/\#([a-z0-9]+)/i', '<a href="https://twitter.com/hashtag/$1?src=hashtag_click">#$1</a>', $string);
                      echo preg_replace('/\@([a-z0-9]+)/i', '<a href="https://twitter.com/$1">@$1</a>' ,$new_string);
                  echo '</p>';
        echo '</div>';
      echo '</div>';
    }
  
    public function render_post_date($settings, $class, $item) {
      echo '<div class="'. esc_attr($class) .'">';
        echo '<div class="inner-block">';
          echo '<span>';
  
          // Text: Before
          // if ( 'before' === $settings['element_extra_text_pos'] ) {
          // 	echo '<span class="wpr-twitter-feed-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
          // }
          // Icon: Before
          // if ( 'before' === $settings['element_extra_icon_pos'] ) {
          // 	echo '<i class="wpr-twitter-feed-extra-icon-left '. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';
          // }
  
                  // echo wp_date(get_option( 'date_format' ), strtotime($item['created_at'])) 
                  echo human_time_diff(strtotime($item['created_at'])) .' '. esc_html__('ago', 'wpr-addons');
  
          // Icon: After
          // if ( 'after' === $settings['element_extra_icon_pos'] ) {
          // 	echo '<i class="wpr-twitter-feed-extra-icon-right '. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';
          // }
          // Text: After
          // if ( 'after' === $settings['element_extra_text_pos'] ) {
          // 	echo '<span class="wpr-twitter-feed-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
          // }
          
          echo '</span>';
        echo '</div>';
      echo '</div>';
    }
    
    public function render_post_lightbox( $settings, $class, $item ) { 
      echo '<div class="'. esc_attr($class) .'">';
        echo '<div class="inner-block">';
          $lightbox_source = $item[''];
  
          // Lightbox Button
          echo '<span data-src="'. esc_url( $lightbox_source ) .'">';
          
            // Text: Before
            if ( 'before' === $settings['element_extra_text_pos'] ) {
              echo '<span class="wpr-twitter-feed-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
            }
  
            // Lightbox Icon
            echo '<i class="'. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';
  
            // Text: After
            if ( 'after' === $settings['element_extra_text_pos'] ) {
              echo '<span class="wpr-twitter-feed-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
            }
  
          echo '</span>';
  
          // Media Overlay
          if ( 'yes' === $settings['element_lightbox_overlay'] ) {
            echo '<div class="wpr-twitter-feed-lightbox-overlay"></div>';
          }
        echo '</div>';
      echo '</div>';
    }
  
    public function render_post_likes($settings, $class, $item) {
  
      echo '<div class="'. esc_attr($class) .'">';
        echo '<div class="inner-block">'; 
  
              ?>
              <a href="https://twitter.com/intent/like?tweet_id=<?php echo $item['id'] ?>&related=<?php echo $item['user']['screen_name'] ?>" target="_blank" title="Likes">
                  <span class=""><i class="fas fa-heart"></i></span>
                  <span class="wpr-tweet-likes">
                      <?php echo $item['favorite_count'] ?>
                  </span>
              </a>
              <?php
  
        echo '</div>';
      echo '</div>';
    }
  
    public function render_post_comment($settings, $class, $item) {
  
      echo '<div class="'. esc_attr($class) .'">';
        echo '<div class="inner-block">'; 
  
              ?>
              <a href="https://twitter.com/intent/tweet?in_reply_to=<?php echo $item['id'] ?>&related=<?php echo $item['user']['screen_name'] ?>" target="_blank" title="Comments">
                  <span class=""><i class="fas fa-comment"></i></span>
              </a>
              <?php
  
        echo '</div>';
      echo '</div>';
    }
  
    public function render_post_retweets($settings, $class, $item) {
  
      echo '<div class="'. esc_attr($class) .'">';
        echo '<div class="inner-block">'; 
  
              ?>
              <a href="https://twitter.com/intent/retweet?tweet_id=<?php echo $item['id'] ?>&related=<?php echo $item['user']['screen_name'] ?>" target="_blank" title="Retweets">
                  <span class=""><i class="fa fa-retweet"></i></span>
                  <span class="wpr-retweets">
                      <?php echo $item['retweet_count'] ?>
                  </span>
              </a>
              <?php
  
        echo '</div>';
      echo '</div>';
    }
  
    // Render Post Element Separator
    public function render_post_element_separator( $settings, $class ) {
      echo '<div class="wpr-twitter-feed-sep-style-1 '. esc_attr($class) .'">';
        echo '<div class="inner-block"><span></span></div>';
      echo '</div>';
    }
  
    public function render_post_media($settings, $class, $item) {
      
      if ( isset($item['extended_entities']) && null !== $item['extended_entities'] ) {
        if ( $item['extended_entities']['media'] ) {
          $media = $item['extended_entities']['media'];
        } else if ( isset( $item['retweeted_status']['entities']['media'] ) ) {
          $media = $item['retweeted_status']['entities']['media'];
        } else if ( isset( $item['quoted_status']['entities']['media'] ) ) {
          $media = $item['quoted_status']['entities']['media'];
        } else {
          $media = [];
        }
      }
  
      if ( !empty($media) ) {
        echo '<div class="'. esc_attr($class) .'">';
          echo '<div class="inner-block">'; 
            // && $media[0]['type'] == 'photo'
            echo (isset( $media[0] )) ? '<img class="wpr-twit-image" src="' . $media[0]['media_url_https'] . '">' : '';
          echo '</div>';
        echo '</div>';
      }
    }

    // Get Elements
    public function get_elements( $type, $settings, $class, $item ) {
      if ( 'pro-lk' == $type || 'pro-shr' == $type || 'pro-cf' == $type ) {
        $type = 'title';
      }
  
      switch ( $type ) {
    
  
        case 'username':
          $this->render_post_username( $settings, $class, $item );
          break;
  
        case 'profile-name':
          $this->render_post_user_profile_name( $settings, $class, $item );
          break;
  
        case 'twit':
          $this->render_post_caption( $settings, $class, $item );
          break;
  
        case 'date':
          $this->render_post_date( $settings, $class, $item );
          break;
  
        case 'likes':
          $this->render_post_likes( $settings, $class, $item );
          break;
  
        case 'comment':
          $this->render_post_comment( $settings, $class, $item );
          break;
  
        case 'retweets':
          $this->render_post_retweets( $settings, $class, $item );
          break;

        case 'media':
          $this->render_post_media( $settings, $class, $item );
          break;
            
        case 'profile-picture':
          $this->render_post_user_profile_picture( $settings, $class, $item );
          break;
  
        // case 'read-more':
        // 	$this->render_post_account_name( $settings, $class );
        // 	break;
  
        // case 'lightbox':
        // 	$this->render_post_lightbox( $settings, $class, $item );
        // 	break;
  
        case 'separator':
          $this->render_post_element_separator( $settings, $class );
          break;
      }
  
    }
    
    // Get Elements by Location
    public function get_elements_by_location( $location, $settings, $item ) {
      $locations = [];
  
      foreach ( $settings['twitter_feed_elements'] as $data ) {
        $place = $data['element_location'];
        $align_vr = $data['element_align_vr'];
  
        if ( ! wpr_fs()->can_use_premium_code() ) {
          $align_vr = 'middle';
        }
  
        if ( ! isset($locations[$place]) ) {
          $locations[$place] = [];
        }
        
        if ( 'over' === $place ) {
          if ( ! isset($locations[$place][$align_vr]) ) {
            $locations[$place][$align_vr] = [];
          }
  
          array_push( $locations[$place][$align_vr], $data );
        } else {
          array_push( $locations[$place], $data );
        }
      }
  
      if ( ! empty( $locations[$location] ) ) {
  
        if ( 'over' === $location ) {
          foreach ( $locations[$location] as $align => $thiss ) {
  
            if ( 'middle' === $align ) {
              echo '<div class="wpr-cv-container"><div class="wpr-cv-outer"><div class="wpr-cv-inner">';
            }
  
            echo '<div class="wpr-twitter-feed-media-hover-'. esc_attr($align) .' elementor-clearfix">';
              foreach ( $thiss as $data ) {
                
                // Get Class
                $class  = 'wpr-twitter-feed-item-'. $data['element_select'];
                $class .= ' elementor-repeater-item-'. $data['_id'];
                $class .= ' wpr-twitter-feed-item-display-'. $data['element_display'];
                $class .= ' wpr-twitter-feed-item-align-'. $data['element_align_hr'];
                $class .= $this->get_animation_class( $data, 'element' );
  
                // Element
                $this->get_elements( $data['element_select'], $data, $class, $item );
              }
            echo '</div>';
  
            if ( 'middle' === $align ) {
              echo '</div></div></div>';
            }
          }
        } else {
          echo '<div class="wpr-twitter-feed-item-'. esc_attr($location) .'-content elementor-clearfix">';
            foreach ( $locations[$location] as $data ) {
  
              // Get Class
              $class  = 'wpr-twitter-feed-item-'. $data['element_select'];
              $class .= ' elementor-repeater-item-'. $data['_id'];
              $class .= ' wpr-twitter-feed-item-display-'. $data['element_display'];
              $class .= ' wpr-twitter-feed-item-align-'. $data['element_align_hr'];
  
              // Element
              $this->get_elements( $data['element_select'], $data, $class, $item );
            }
          echo '</div>';
        }
  
      }
    }

    public function wpr_load_more_tweets_function() {

        $settings = $_POST['wpr_load_more_settings'];

				$credentials = base64_encode($settings['twitter_feed_consumer_key'] . ':' . $settings['twitter_feed_consumer_secret']);

				add_filter('https_ssl_verify', '__return_false');

				$response = wp_remote_post('https://api.twitter.com/oauth2/token', [
					'method' => 'POST',
					'httpversion' => '1.1',
					'blocking' => true,
					'headers' => [
						'Authorization' => 'Basic ' . $credentials,
						'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
					],
					'body' => ['grant_type' => 'client_credentials'],
				]);

				$body = json_decode(wp_remote_retrieve_body($response));

				if ($body) {
					$token = $body->access_token;
				}

        add_filter('https_ssl_verify', '__return_false');
  
        $response = [];
        $items_array = [];

        foreach ($settings['twitter_accounts'] as $key=>$value) {
          
            $response[$key] = wp_remote_get('https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=' . $value['twitter_feed_account_name'] . '&count='. ($settings['number_of_posts'] + $_POST['next_post_index']) .'&tweet_mode=extended', [
              'httpversion' => '1.1',
              'blocking' => true,
              'headers' => [
                'Authorization' => "Bearer $token",
              ],
            ]);
    
            if ( is_wp_error( $response[$key] ) ) {
              return $response[$key];
            }
        
            if ( ! empty( $response[$key]['response'] ) && $response[$key]['response']['code'] == 200 ) {
              $items_array[] = json_decode( wp_remote_retrieve_body( $response[$key] ), true );
            }
          } 
            foreach ( $items_array as $key=>$items ) :
    
            if ($settings['twitter_feed_hashtag_name']) {
              $hashtag_names = explode(',', str_replace(' ', '', $settings['twitter_feed_hashtag_name']));
        
              foreach ($items as $key => $item) {
                $match = false;
        
                if ($item['entities']['hashtags']) {
                  foreach ($item['entities']['hashtags'] as $tag) {
                    if (in_array($tag['text'], $hashtag_names)) {
                      $match = true;
                    }
                  }
                }
        
                if ($match == false) {
                  unset($items[$key]);
                }
              }
            }
        
                  foreach ( $items as $key=>$item) :
                
                    if ( $key >= 6 && !wpr_fs()->can_use_premium_code() ) {
                      break;
                    }
      
                    if ($key < $_POST['next_post_index']) :
                      continue; 
                    endif;
                          
                  $banner_placeholder = WPR_ADDONS_ASSETS_URL . 'img/placeholder.png';
                  $banner = $item['user']['profile_banner_url'] ? $item['user']['profile_banner_url'] : $banner_placeholder;
                  ?>
                          <div class="wpr-tweet">
                                  <img src="<?php echo $banner ?>" alt="">
                                  <article class="media">
          
                                      <?php 
                                          // Content: Above Media
                                          echo $this->get_elements_by_location( 'above', $settings, $item );
                                      ?>
                                      
                                  </article>
                          </div>
                  <?php
          
                  endforeach;
              endforeach;

        die();
    }
}

new WPR_Load_More_Tweets();