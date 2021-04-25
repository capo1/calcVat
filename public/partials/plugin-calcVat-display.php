<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    calcVat
 * @subpackage calcVat/public/partials
 */

$vat_options = [];
parse_str(str_replace("&amp;", "&", $atts['vat_options']), $vat_options);

foreach ($vat_options as $key => $vat_item) :
  $vat_options[] = '<option value="' . $key . '">' . str_replace('_', '.', $key . $vat_item) . '</option>';
endforeach;

$errors = [];
if (isset($_GET['fields'])) :
  $errors = explode(',', $_GET['fields']);
endif;

$errorContainer = "<div class='error'>%s</div>";

?>
<div class="container">
  <noscript>
    <div class="error">
      <?= __('You have to have JavaScript enabled.', 'calcVat'); ?>
    </div>
  </noscript>
  <script>
    var cv_currency = "<?= $atts['currency']??['PLN']; ?>"
  </script>
  <div class="row">
    <div class="col-xs-12">
      <h3><?= __('Calculate VAT', 'calcVat'); ?></h3>
    </div>

    <form id="vat_form" method="post">
      <p class="col-xs-12">
        <label for="product_name"><?= __('Product name', 'calcVat'); ?></label><br />
        <input type="text" id="product_name" name="product_name" placeholder="<?= __("Enter product name", 'calcVat');?>" />
        <?php
        if (isset($errors) && in_array('empty_product_name', $errors)) :
          printf($errorContainer, __('Product name cant\'t be empty', 'calcVal'));
        endif;
        ?>
      </p>

      <div class="row no-margins">
        <p class="col-xs-12 col-md-9">
          <label for="ammount_netto"><?= __('Amount', 'calcVat'); ?></label>
          <br />
          <input min="0" required type="number" step="0.01" placeholder="<?= __("ex. 50.05", 'calcVat');?>" id="ammount_netto" name="ammount_netto" />

          <?php
          if (isset($errors) && in_array('empty_ammount_netto', $errors)) :
            printf($errorContainer, __('Set product price', 'calcVal'));
          endif;
          ?>
        </p>

        <p class="col-xs-12 col-md-3">
          <label for="currency"><?= __('Currency', 'calcVat'); ?></label><br />
          <input type="text" name="currency" id="currency" readonly placeholder="<?= $atts['currency']; ?>" value="<?= $atts['currency']; ?>" />
        </p>
      </div>

      <p class="col-xs-12">
        <label for="used_vat"><?= __('VAT', 'calcVat'); ?></label>

        <select required id="used_vat" name="used_vat">
          <option value=""><?= __("Select VAT", 'calcVat'); ?></option>
          <?php echo join('', $vat_options); ?>
        </select>

        <?php
        //select options from shortcode        
        if (isset($errors) && in_array('used_vat', $errors)) :
          __('You must choose vat option', 'calcVal');
        endif;
        ?>
      </p>

      <p class="right col-xs-12 no-padding">
        <input type="submit" id="" value="<?= __("Calculate", 'calcVat'); ?>" />
      </p>
      <?= wp_nonce_field('cpt_vat_action', 'cpt_vat_field'); ?>
      <div id="vat_result" class="col-xs-12">
      </div>
    </form>

  </div>
</div>