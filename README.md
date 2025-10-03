# WooCommerce AXS Checkout Payment Gateway

A WooCommerce payment gateway integration for AXS Checkout.

## Requirements

- PHP 8.2 or higher
- WordPress 5.0 or higher
- WooCommerce 5.0 or higher

## Installation

1. Activate the plugin through the WordPress admin panel.
2. Create a page and set it as 'Thank you' title and set the slug of the page as 'thank-you' in Wordpress admin panel to redirect the page from AXS Checkout to order payment summary page.

## Configuration

1. Go to WooCommerce > Settings > Payments
2. Click on "AXS Checkout" to configure the payment gateway
3. Enter your AXS Checkout credentials:
   - Test/Live Payment Link
   - Test/Live Client ID
   - Test/Live Secret
4. Enable/disable test mode as needed
5. Save changes

## Usage

Once configured, customers will be able to pay using AXS Checkout during the checkout process that supports various payment methods.

## Security

- All sensitive data is encrypted using JWE (JSON Web Encryption)
- Test mode available for development and testing
- Secure key storage in WordPress options

## Support

For support, please contact support@axs.com.sg
