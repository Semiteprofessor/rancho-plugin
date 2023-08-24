<?php
/****************************************************************
 * WC Pakettikauppa template: Attach tracking to email (TXT)
 *
 * Variables:
 *   (array) $tracking_codes - Tracking code
 *   (bool) $add_pickup_point_to_email - Checks if pickup point info should be added in the email
 ***************************************************************/
?>

<?php

  echo sprintf("%s:\n\n", esc_attr__('Tracking', 'woo-topship-africa'));

  foreach ( $tracking_codes as $code ) {
      if ( $add_pickup_point_to_email === 'yes' ) {
        /* translators: 1: Name 2: Shipment tracking code */
        if ( ! empty($code['point']) ) {
          echo sprintf("%1\$s: %2\$s.\n", __('Pickup point', 'woo-topship-africa'), $code['point']);
        } else {
          echo sprintf("%1\$s: %2\$s.\n", __('Pickup point', 'woo-topship-africa'), '—');
        }
      }
      /* translators: Shipment tracking URL */
      echo sprintf(__('You can track your order at %1$s.', 'woo-topship-africa'), esc_url($code['url'])) . "\n\n";
  }
