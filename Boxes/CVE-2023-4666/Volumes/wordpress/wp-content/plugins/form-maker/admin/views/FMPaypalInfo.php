<?php

/**
 * Class FMViewPaypal_info
 */
class FMViewPaypal_info extends FMAdminView {
  /**
   * Display.
   *
   * @param array $params
   */
  public function display( $params = array() ) {
    $row = $params['row'];
    if ( !isset($row->ipn) ) {
      ?>
      <div style="width:100%; text-align: center; height: 70%; vertical-align: middle;">
        <h1 style="vertical-align: middle; margin: auto; color: #000"><p><?php _e('No information yet', WDFMInstance(self::PLUGIN)->prefix); ?></p></h1>
      </div>
      <?php
    }
    else {
      ?>
      <style>
        table.admintable td.key, table.admintable td.paramlist_key {
          background-color: #F6F6F6;
          border-bottom: 1px solid #E9E9E9;
          border-right: 1px solid #E9E9E9;
          color: #666666;
          font-weight: bold;
          margin-right: 10px;
          text-align: right;
          width: 140px;
        }
      </style>
      <h2><?php _e('Payment Info', WDFMInstance(self::PLUGIN)->prefix); ?></h2>
      <table class="admintable">
        <?php
        if ( !empty($row->transaction_id) ) {
          ?>
          <tr>
            <td class="key"><?php _e('Transaction ID', WDFMInstance(self::PLUGIN)->prefix); ?></td>
            <td><?php echo $row->transaction_id; ?></td>
          </tr>
          <?php
        }
        if ( $row->currency ) {
          ?>
          <tr>
            <td class="key">Currency</td>
            <td><?php echo $row->currency; ?></td>
          </tr>
          <?php
        }
        ?>
        <?php
        if ( $row->ord_last_modified ) {
          ?>
          <tr>
            <td class="key"><?php _e('Last modified', WDFMInstance(self::PLUGIN)->prefix); ?></td>
            <td><?php echo $row->ord_last_modified; ?></td>
          </tr>
          <?php
        }
        ?>
        <?php
        if ( $row->status ) {
          ?>
          <tr>
            <td class="key"><?php _e('Status', WDFMInstance(self::PLUGIN)->prefix); ?></td>
						<td>
							<?php
							if ( $row->status == "requires_capture" ){
			 					echo $row->status = "Requires capture";
							} elseif ( $row->status == "succeeded" ) {
			 					echo $row->status = "Succeeded";
							} else {
			 					echo $row->status = $row->status;
							}
							?>
						</td>
          </tr>
          <?php
        }
        ?>
        <?php
        if ( $row->full_name ) {
          ?>
          <tr>
            <td class="key"><?php _e('Full name', WDFMInstance(self::PLUGIN)->prefix); ?></td>
            <td><?php echo $row->full_name; ?></td>
          </tr>
          <?php
        }
        ?>
        <?php
        if ( $row->email ) {
          ?>
          <tr>
            <td class="key"><?php _e('Email', WDFMInstance(self::PLUGIN)->prefix); ?></td>
            <td><?php echo $row->email; ?></td>
          </tr>
          <?php
        }
        ?>
        <?php
        if ( $row->phone ) {
          ?>
          <tr>
            <td class="key"><?php _e('Phone', WDFMInstance(self::PLUGIN)->prefix); ?></td>
            <td><?php echo $row->phone; ?></td>
          </tr>
          <?php
        }
        ?>
        <?php
        if ( $row->mobile_phone ) {
          ?>
          <tr>
            <td class="key"><?php _e('Mobile phone', WDFMInstance(self::PLUGIN)->prefix); ?></td>
            <td><?php echo $row->mobile_phone; ?></td>
          </tr>
          <?php
        }
        ?>
        <?php
        if ( $row->fax ) {
          ?>
          <tr>
            <td class="key"><?php _e('Fax', WDFMInstance(self::PLUGIN)->prefix); ?></td>
            <td><?php echo $row->fax; ?></td>
          </tr>
          <?php
        }
        ?>
        <?php
        if ( $row->address ) {
          ?>
          <tr>
            <td class="key"><?php _e('Address', WDFMInstance(self::PLUGIN)->prefix); ?></td>
            <td><?php echo $row->address; ?></td>
          </tr>
          <?php
        }
        ?>
        <?php
        if ( $row->paypal_info ) {
          ?>
          <tr>
            <td class="key"><?php _e('Info', WDFMInstance(self::PLUGIN)->prefix); ?></td>
            <td><?php echo $row->paypal_info; ?></td>
          </tr>
          <?php
        }
        ?>
        <?php
        if ( $row->ipn ) {
          ?>
          <tr>
            <td class="key"><?php _e('IPN', WDFMInstance(self::PLUGIN)->prefix); ?></td>
            <td><?php echo $row->ipn; ?></td>
          </tr>
          <?php
        }
        ?>
        <?php
        if ( $row->tax ) {
          ?>
          <tr>
            <td class="key"><?php _e('Tax', WDFMInstance(self::PLUGIN)->prefix); ?></td>
            <td><?php echo $row->tax; ?></td>
          </tr>
          <?php
        }
        ?>
        <?php
        if ( $row->shipping ) {
          ?>
          <tr>
            <td class="key"><?php _e('Shipping', WDFMInstance(self::PLUGIN)->prefix); ?></td>
            <td><?php echo $row->shipping; ?></td>
          </tr>
          <?php
        }
        ?>
        <?php
        if ( $row->read ) {
          ?>
          <tr>
            <td class="key"><?php _e('Read', WDFMInstance(self::PLUGIN)->prefix); ?></td>
            <td><?php echo $row->read; ?></td>
          </tr>
          <?php
        }
        ?>
        <?php
        if ( $row->total ) {
          ?>
          <tr>
            <td class="key"><?php _e('Total', WDFMInstance(self::PLUGIN)->prefix); ?></td>
            <td><b><?php echo $row->total; ?></b></td>
          </tr>
          <?php
        }
        ?>
      </table>
      <?php
    }

    die();
  }
}
