# AXS Checkout for WooCommerce

#Simplified Online Payments for Merchants

Easily accept secure online payments with the AXS Checkout plugin for WooCommerce. Whether you're selling products or services, AXS Checkout lets you get paid via payment links or a customizable checkout page.

## Key Features

- All sensitive data is encrypted using JWE (JSON Web Encryption)
- Test mode available for development and testing
- Seamless integration with no coding required

## Requirements

- PHP 8.2 or higher
- WordPress 5.0 or higher
- WooCommerce 5.0 or higher

## Notes

AXS is not responsible for any problems that might arise from the use of this module. Use at your own risk. Please backup any critical data before proceeding. For any query or assistance, please email support@axscheckout.com.

## Prerequisites

Before installing the plugin, you’ll need your AXS Checkout credentials.

1. **Log in** to the [AXS Merchant Portal](https://merchant.axs.com.sg).

![pre-img](https://axs.com.sg/wp-content/uploads/2025/10/woo_plugin_1.webp "pre-1")

2. Go to the **Developer** section.

3. If you haven't created credentials yet, click **Create Application Credential** and enter your Application Name for your reference.

![pre-img](https://axs.com.sg/wp-content/uploads/2025/10/woo_plugin_2.webp "pre-2")

4. Save your:
    - **Client ID**
    - **Secret Key**

![pre-img](https://axs.com.sg/wp-content/uploads/2025/10/woo_plugin_3.webp "pre-3")

5. Navigate to **AXS Checkout → Manage Payment Link** and copy the **Payment Link**.

![pre-img](https://axs.com.sg/wp-content/uploads/2025/10/woo_plugin_4.jpg "pre-4")

You will need these details when configuring the plugin.

## Installation

1. **Download** the latest plugin version from [GitHub Releases](https://github.com/axscheckout/woocommerce-plugin/releases).

2. Log in to your **WordPress admin dashboard**.

![pre-img](https://axs.com.sg/wp-content/uploads/2025/10/woo_plugin_9.webp "pre-9")

3. Go to **Plugins > Add New**.

![pre-img](https://axs.com.sg/wp-content/uploads/2025/10/woo_plugin_10.webp "pre-10")

4. Click **Upload Plugin**, select the file you just downloaded, and click **Install Now**.

![pre-img](https://axs.com.sg/wp-content/uploads/2025/10/woo_plugin_5.webp "pre-5")

5. Once installation is complete, click **Activate**.

![pre-img](https://axs.com.sg/wp-content/uploads/2025/10/woo_plugin_6.webp "pre-6")

## Configuration

1. Go to **WooCommerce > Settings > Payments**.

![pre-img](https://axs.com.sg/wp-content/uploads/2025/10/woo_plugin_7.webp "pre-7")

2. Find **AXS Checkout** in the list and click **Manage**.

3. Fill in the following fields using the credentials from your AXS Merchant Portal:
    - **Payment Link**
    - **Client ID**
    - **Secret Key**

![pre-img](https://axs.com.sg/wp-content/uploads/2025/10/woo_plugin_8.webp "pre-8")

4. Choose your mode:
    - **Live Mode:** Check this box *only* if you’re using real production credentials.
    - **Test Mode:** Leave this box unchecked if you’re using sandbox/test credentials.

5. Click **Save Changes**.


## 🎉 You’re All Set!

Your WooCommerce store is now ready to accept payments using AXS Checkout. Whether you're testing or going live, we’ve got you covered.


## Support

If you run into any issues or have questions, our support team is here to help.  
Contact us at [support@axscheckout.com](mailto:support@axscheckout.com).


## Terms & Conditions

By downloading and using this plugin, you agree to the [AXS Checkout WooCommerce Terms & Conditions](https://axs.com.sg/checkout-woocommerce-tnc).