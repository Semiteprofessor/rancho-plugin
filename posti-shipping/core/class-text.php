<?php
namespace Woo_Pakettikauppa_Core;

// Prevent direct access to this script
if ( ! defined('ABSPATH') ) {
  exit();
}

if ( ! class_exists(__NAMESPACE__ . '\Text') ) {
  /**
   * Class used to hold all translatable strings of the plugin.
   * Allows whitelabel plugins to redefine some methods to change text displayed to the user.
   * @todo Add rest of the strings and remove default arguments
   */
  class Text {
    /**
     * @var Core
     */
    private $core = null;

    public function __construct( Core $plugin ) {
      $this->core = $plugin;
    }

    public function setup_title() {
      /* translators: %s: Vendor full name */
      return sprintf(esc_html__('%s Setup Wizard', 'woo-topship-africa'), $this->core->vendor_fullname);
    }

    public function setup_button_text() {
      return __('Start the setup wizard', 'woo-topship-africa');
    }

    public function shipping_method_name() {
      return $this->core->vendor_name;
    }

    public function shipping_method_desc() {
      return __('Edit to select shipping company and shipping prices.', 'woo-topship-africa');
    }

    public function selected_shipping_method( $method ) {
      return sprintf(
        /* translators: %s: shipping method */
        __('Selected shipping method: %s', 'woo-topship-africa'),
        $method
      );
    }

    public function no_shipping() {
      return esc_html__('No shipping', 'woo-topship-africa');
    }

    public function includes_pickup_points() {
      return esc_html__('includes pickup points', 'woo-topship-africa');
    }

    public function select_one_shipping_method() {
      return __('Select one shipping method', 'woo-topship-africa');
    }

    public function unable_connect_to_vendor_server() {
      return __('Can not connect to server - please check API credentials, servers error log and firewall settings.', 'woo-topship-africa');
    }

    public function legacy_shipping_method_desc( $vendor_name = null ) {
      if ( ! $vendor_name ) {
        $vendor_name = \esc_html($this->core->vendor_name);
      }

      return sprintf(
        /* translators: Vendor name, not translatable */
        __(
          'Only use this shipping method if no other shipping methods are available and suitable. Using this shipping method is not required to be able to use %s plugin.',
          'woo-topship-africa'
        ),
        $vendor_name
      );
    }

    public function shipping_methods() {
      return __('Shipping methods', 'woo-topship-africa');
    }

    public function shipping_method() {
      return __('Shipping method', 'woo-topship-africa');
    }

    public function price_vat_included() {
      return __('Price (vat included)', 'woo-topship-africa');
    }

    public function shipping_cost() {
      return __('Shipping cost', 'woo-topship-africa');
    }

    public function shipping_cost_vat_included() {
      return __('Shipping cost  (vat included)', 'woo-topship-africa');
    }

    public function free_shipping_tier() {
      return __('Free shipping tier', 'woo-topship-africa');
    }

    public function free_shipping_tier_desc() {
      return __('After which amount shipping is free.', 'woo-topship-africa');
    }

    public function default_shipping_class_cost() {
      return __('Default shipping class cost', 'woo-topship-africa');
    }

    public function no_shipping_class_cost() {
      return __('No shipping class cost (vat included)', 'woo-topship-africa');
    }


    public function vendor_website_link_label( $vendor_name = null ) {
      if ( ! $vendor_name ) {
        $vendor_name = $this->core->vendor_name;
      }

      /* translators: Vendor name, not translatable */
      return sprintf(esc_html__('Link to %s website', 'woo-topship-africa'), $vendor_name);
    }

    public function not_now() {
      return esc_html__('Not now', 'woo-topship-africa');
    }

    public function skip_this_step() {
      return esc_html__('Skip this step', 'woo-topship-africa');
    }

    public function lets_start() {
      return esc_html__('Let\'s start!', 'woo-topship-africa');
    }

    public function btn_continue() {
      return esc_html__('Continue', 'woo-topship-africa');
    }

    public function btn_exit() {
      return esc_html__('Exit', 'woo-topship-africa');
    }

    public function setup_intro() {
      /* translators: %s: Vendor full name */
      return sprintf(esc_html__('Thank you for installing %s! This wizard will guide you through the setup process to get you started.', 'woo-topship-africa'), $this->core->vendor_fullname);
    }

    public function setup_credential_info( $vendor_name = null, $vendor_url = null ) {
      if ( ! $vendor_name ) {
        $vendor_name = \esc_html($this->core->vendor_name);
      }

      if ( ! $vendor_url ) {
        $vendor_url = \esc_attr($this->core->vendor_url);
      }

      return sprintf(
        /*
         * translators:
         * %1$s: Vendor name, not translateable
         * %2$s: Vendor url, not translateable
         */
        __(
          'If you have already registered with %1$s, please choose "Production mode" and enter the credentials you received from %1$s. If you have not yet registered, please register at %2$s. If you wish to test the plugin before making a contract with %1$s, please choose "Test mode" and leave the API secret/key fields empty.',
          'woo-topship-africa'
        ),
        $vendor_name,
        '<a target="_blank" rel="noopener noreferrer" href="' . $vendor_url . '">' . $vendor_url . '</a>'
      );
    }

    public function setup_merchant_info() {
      return esc_html__(
        'Please fill the details of the customer below. The information provided here will be used as the sender in shipping labels.',
        'woo-topship-africa'
      );
    }

    public function setup_shipping_info() {
      $docs_url = esc_attr('https://docs.woocommerce.com/document/setting-up-shipping-zones/');
      return sprintf(
        /*
         * translators:
         * %1$s: vendor name
         * %2$s: link to WooCommerce shipping zone setting page
         * %3$s: link to external WooCommerce documentation
         */
        __('Please configure the shipping methods of the currently active shipping zones to use %1$s shipping. Note that this plugin requires WooCommerce shipping zones and methods to be preconfigured in %2$s. For more information, visit %3$s.', 'woo-topship-africa'),
        $this->core->vendor_name,
        '<a href="' . esc_url(admin_url('admin.php?page=wc-settings&tab=shipping')) . '">' . __('WooCommerce > Settings > Shipping > Shipping zones', 'woo-topship-africa') . '</a>',
        '<a target="_blank" href="' . $docs_url . '">' . $docs_url . '</a>'
      );
    }

    public function setup_processing_info() {
      return esc_html__('Customize the order processing phase.', 'woo-topship-africa');
    }

    public function setup_ready_info() {
      return esc_html__('Congratulations, everything is now set up and you are now ready to start using the plugin!', 'woo-topship-africa');
    }

    public function setup_credentials() {
      return __('Credentials', 'woo-topship-africa');
    }

    public function setup_merchant() {
      return __('Customer', 'woo-topship-africa');
    }

    public function setup_shipping() {
      return __('Shipping', 'woo-topship-africa');
    }

    public function setup_order_processing() {
      return __('Order Processing', 'woo-topship-africa');
    }

    public function setup_ready() {
      return __('Ready!', 'woo-topship-africa');
    }

    public function note() {
      return __('Note', 'woo-topship-africa');
    }

    public function mode() {
      return __('Mode', 'woo-topship-africa');
    }

    public function testing_environment() {
      return __('Testing environment', 'woo-topship-africa');
    }

    public function production_environment() {
      return __('Production environment', 'woo-topship-africa');
    }

    public function api_key_title() {
      return __('API key', 'woo-topship-africa');
    }

    public function api_key_desc( $vendor_name ) {
      return sprintf(
        /*
         * translators:
         * %1$s: Vendor name, not translatable
         */
        __('API key provided by %1$s', 'woo-topship-africa'),
        \esc_html($vendor_name)
      );
    }

    public function api_secret_title() {
      return __('API secret', 'woo-topship-africa');
    }

    public function api_secret_desc( $vendor_name ) {
      return sprintf(
        /*
         * translators:
         * %1$s: Vendor name, not translatable
         */
        __('API secret provided by %1$s', 'woo-topship-africa'),
        \esc_html($vendor_name)
      );
    }

    public function pickup_points_title() {
      return __('Shipping methods mapping', 'woo-topship-africa');
    }

    public function order_pickup_title() {
      return __('Order pickup', 'woo-topship-africa');
    }

    public function customer_id_title() {
      return __('Customer ID', 'woo-topship-africa');
    }

    public function invoice_id_title() {
      return __('Invoice ID', 'woo-topship-africa');
    }

    public function sender_id_title() {
      return __('Sender ID', 'woo-topship-africa');
    }

    public function shipping_settings_title() {
      return __('Shipping settings', 'woo-topship-africa');
    }

    public function shipping_settings_desc() {
      $url = 'https://docs.woocommerce.com/document/setting-up-shipping-zones/';
      return sprintf(
        /*
         * translators:
         * %1$s: Steps to Shipping zones page
         * %2$s: WooCommerce URL, not translatable
         */
        __('You can activate new shipping method to checkout in %1$s. For more information, see %2$s', 'woo-topship-africa'),
        '<b>' . __('WooCommerce > Settings > Shipping > Shipping zones', 'woo-topship-africa') . '</b>',
        '<a target="_blank" href="' . $url . '">' . $url . '</a>'
      );
    }

    public function add_tracking_link_to_email() {
      return __('Add tracking link to the order completed email', 'woo-topship-africa');
    }

    public function add_pickup_point_to_email() {
      return __('Add selected pickup point information to the order completed email', 'woo-topship-africa');
    }

    public function change_order_status_to() {
      return __('When creating shipping label change order status to', 'woo-topship-africa');
    }

    public function no_order_status_change() {
      return __('No order status change', 'woo-topship-africa');
    }

    public function create_shipments_automatically() {
      return __('Create shipping labels automatically', 'woo-topship-africa');
    }

    public function no_automatic_creation_of_labels() {
      return __('No automatic creation of shipping labels', 'woo-topship-africa');
    }

    public function when_order_status_is( $status ) {
      /* translators: order status, pretranslated */
      return sprintf(__('When order status is "%s"', 'woo-topship-africa'), $status);
    }

    public function labels_size_title() {
      return __('Shipping label size', 'woo-topship-africa');
    }

    public function download_type_of_labels_title() {
      return __('Print labels', 'woo-topship-africa');
    }

    public function download_type_of_labels_option_browser() {
      return __('Browser', 'woo-topship-africa');
    }

    public function download_type_of_labels_option_download() {
      return __('Download', 'woo-topship-africa');
    }

    public function post_shipping_label_to_url_title() {
      return __('Post shipping label to URL', 'woo-topship-africa');
    }

    public function post_shipping_label_to_url_desc() {
      return __('Plugin can upload shipping label to an URL when creating shipping label. Define URL if you want to upload PDF.', 'woo-topship-africa');
    }

    public function checkout_settings() {
      return __('Checkout options', 'woo-topship-africa');
    }

    public function field_phone_required() {
      return __('Make shipping phone number mandatory', 'woo-topship-africa');
    }

    public function pickup_points_type_title() {
      return __('Pickup points type', 'woo-topship-africa');
    }

    public function pickup_points_type_all() {
      return __('All', 'woo-topship-africa');
    }

    public function pickup_points_type_private_locker() {
      return __('Private lockers', 'woo-topship-africa');
    }

    public function pickup_points_type_outdoor_locker() {
      return __('Outdoor lockers', 'woo-topship-africa');
    }

    public function pickup_points_type_parcel_locker() {
      return __('Parcel lockers', 'woo-topship-africa');
    }

    public function pickup_points_type_pickup_point() {
      return __('Pickup points', 'woo-topship-africa');
    }

    public function pickup_points_type_agency() {
      return __('Agencies', 'woo-topship-africa');
    }

    public function pickup_points_type_desc() {
      return __('Choose which type of pickup points will be displayed in the list of pickup points', 'woo-topship-africa');
    }

    public function pickup_points_search_limit_title() {
      return __('Pickup point search limit', 'woo-topship-africa');
    }

    public function pickup_points_search_limit_desc() {
      return __('Limit the amount of nearest pickup points shown.', 'woo-topship-africa');
    }

    public function pickup_point_list_type_title() {
      return __('Show pickup points as', 'woo-topship-africa');
    }

    public function pickup_point_list_type_option_menu() {
      return __('Menu', 'woo-topship-africa');
    }

    public function pickup_point_list_type_option_list() {
      return __('List', 'woo-topship-africa');
    }

    public function pickup_points_override_query_desc() {
      return __('Allow user to use custom address for pickup point search.', 'woo-topship-africa');
    }


    public function store_owner_information() {
      return __('Store owner information', 'woo-topship-africa');
    }

    public function sender_name() {
      return __('Sender name', 'woo-topship-africa');
    }

    public function sender_address() {
      return __('Sender address', 'woo-topship-africa');
    }

    public function sender_postal_code() {
      return __('Sender postal code', 'woo-topship-africa');
    }

    public function sender_city() {
      return __('Sender city', 'woo-topship-africa');
    }

    public function sender_country() {
      return __('Sender country', 'woo-topship-africa');
    }

    public function sender_phone() {
      return __('Sender phone', 'woo-topship-africa');
    }

    public function sender_email() {
      return __('Sender email', 'woo-topship-africa');
    }

    public function info_code() {
      return __('Info-code for shipments', 'woo-topship-africa');
    }

    public function cod_settings() {
      return __('Cash on Delivery (COD) Settings', 'woo-topship-africa');
    }

    public function cod_iban() {
      return __('Bank account number for Cash on Delivery (IBAN)', 'woo-topship-africa');
    }

    public function cod_bic() {
      return __('BIC code for Cash on Delivery', 'woo-topship-africa');
    }

    public function advanced_settings() {
      return __('Advanced settings', 'woo-topship-africa');
    }

    public function setup_wizard() {
      return __('Setup wizard', 'woo-topship-africa');
    }

    public function show_shipping_method() {
      return __('Show shipping method', 'woo-topship-africa');
    }

    public function no_woo_error() {
      /* translators: %s: Vendor full name */
      return sprintf(__('%s requires WooCommerce to be installed and activated!', 'woo-topship-africa'), $this->core->vendor_fullname);
    }

    public function no_pickup_points_error() {
      return __('No pickup points were found. Check the address.', 'woo-topship-africa');
    }

    public function something_went_wrong_while_searching_pickup_points_error() {
      return __('An error occurred while searching for pickup points.', 'woo-topship-africa');
    }

    public function custom_pickup_point_desc() {
      return __('If none of your preferred pickup points are listed, fill in a custom address above and select another pickup point.', 'woo-topship-africa');
    }

    public function custom_pickup_point_title() {
      return __('Custom pickup address', 'woo-topship-africa');
    }

    public function pickup_point_title() {
      return __('Pickup address', 'woo-topship-africa');
    }

    public function fill_pickup_address_above() {
      return __('Search pickup points near you by typing your address above.', 'woo-topship-africa');
    }

    public function show_pickup_point_override_query() {
      return __('Show pickup point override in checkout', 'woo-topship-africa');
    }

    public function restart_setup_wizard() {
      return __('Restart setup wzard', 'woo-topship-africa');
    }

    public function confirm_private_pickup_selection() {
      return __('The pickup point you\'ve chosen is not available for public access. Are you sure that you can retrieve the package?', 'woo-topship-africa');
    }

    public function additional_info_param_title() {
      return __('Add additional text on labels', 'woo-topship-africa');
    }

    public function additional_info_param_order_number() {
      return __('Order number', 'woo-topship-africa');
    }

    public function additional_info_param_products_names() {
      return __('Names of the goods in the shipment', 'woo-topship-africa');
    }

    public function additional_info_param_products_sku() {
      return __('SKU codes of the goods in the shipment', 'woo-topship-africa');
    }
  }
}