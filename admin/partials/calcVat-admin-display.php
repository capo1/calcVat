<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    calcVat
 * @subpackage calcVat/admin/partials
 */



function cvrender_plugin_settings_page() {
    ?>
    <h2><?= __("CalcVat setting page", "calcVat"); ?> </h2>
    <form action="options.php" method="post">
        <?php 
        settings_fields( 'cv_plugin_options' );
        do_settings_sections( 'cvexample_plugin' ); ?>
        <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
    </form>
    <?php
}

function cvregister_settings() {

  register_setting( 'cv_plugin_options', 'cv_plugin_options', 'cv_plugin_options_validate' );
  add_settings_section( 'cv_settings', 'API Settings', 'cvplugin_section_text', 'cvexample_plugin' );

  add_settings_field( 'cv_currency', __("Currency", "calcVat"), 'cv_currency', 'cvexample_plugin', 'cv_settings');

}


function cvplugin_section_text() {
  echo '<p>Here you can set all the options for CalcVat</p>';
}

function cv_currency() {
  $options = get_option( 'cv_plugin_options' );
  echo "<input id='cvplugin_setting_currency' name='cv_plugin_options[cv_currency]' type='text' value='" . esc_attr( $options['cv_currency'] ) . "' />";
}


?>