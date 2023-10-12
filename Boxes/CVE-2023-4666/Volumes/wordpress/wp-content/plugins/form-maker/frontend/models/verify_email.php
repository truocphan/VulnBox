<?php

/**
 * Class FMModelVerify_email
 */
class FMModelVerify_email {
  /**
   * PLUGIN = 2 points to Contact Form Maker
   */
  const PLUGIN = 1;

  /**
   * Set given email as validated and return message.
   *
   * @param int $gid
   * @param string $md5
   * @param string $email
   *
   * @return bool|mixed|string|void
   */
  function set_validation( $gid = 0, $md5 = '', $email = '' ) {
    global $wpdb;
    $query = $wpdb->prepare("SELECT * FROM `" . $wpdb->prefix . "formmaker_submits` WHERE group_id='%d' AND element_label REGEXP 'verifyInfo'", $gid);
    $submissions = $wpdb->get_results($query);
    if ( !$submissions ) {
      return FALSE;
    }

    $message = '';
    foreach ( $submissions as $submission ) {
      if ( $submission->element_label == 'verifyInfo' ) {
        $message = __('Your email address is already verified.', WDFMInstance(self::PLUGIN)->prefix);
        continue;
      }
      elseif ( $submission->element_label == 'verifyInfo@' . $email ) {
        $verifyInfo = explode('**', $submission->element_value);
        $key = $verifyInfo[0];
        $expHour = $verifyInfo[1];
        $recipient = $verifyInfo[2];
        if ( $recipient == $email ) {
          $date = strtotime($submission->date);
          if ( $key === $md5 ) {
            $now = time();
            $hourInterval = ($now - $date) / 3600;
            if ( $expHour > 0 && $hourInterval > $expHour ) {
              $message = __('Your email verification has timed out.', WDFMInstance(self::PLUGIN)->prefix);
            }
            else {
              $data = array(
                'element_value' => 'verified**' . $recipient,
                'element_label' => 'verifyInfo',
              );
              $where = array(
                'group_id' => $gid,
                'element_label' => 'verifyInfo@' . $recipient,
              );

              $updated = $wpdb->update( $wpdb->prefix . 'formmaker_submits', $data, $where, array('%s','%s'), array('%d','%s') );

              if ( $updated !== FALSE ) {
                $message = __('Your email has been successfully verified.', WDFMInstance(self::PLUGIN)->prefix);
              }
            }
          }
          else {
            $message = __('Verification link is invalid.', WDFMInstance(self::PLUGIN)->prefix);
          }
          break;
        }
        else {
          continue;
        }
      }
    }

    return $message;
  }
}
