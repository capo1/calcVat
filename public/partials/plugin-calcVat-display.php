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

// why there is nod GET?
var_dump($_GET);

$errors =[];
if(isset($_GET['error'])):
  $errors = explode(',', $_GET['error']);  
endif;

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="container">
  <div class="row">
    <div class="col-xs-12">
      <h3><?= __('Count VAT', 'calcVat'); ?></h3>
    </div>

    <form id="vat-form" method="post">
      <p class="col-xs-12">
        <label for="product_name"><?= __('Product name', 'calcVat'); ?></label><br />
        <input  type="text" id="product_name" name="product_name" />
        <?php
          if(isset($errors) && in_array('product_name', $errors)):
              __('empty_product_name', 'calcVal');
          endif;
        ?>
      </p>

      <div class="row no-margins">
        <p class="col-xs-12 col-md-9">
          <label for="ammount_netto"><?= __('Amount', 'calcVat'); ?></label>
          <br />
          <input  type="number" step="0.01" id="ammount_netto" name="ammount_netto" />

          <?php

          if(isset($errors) && in_array('ammount_netto', $errors)):
              __('empty_ammount_netto', 'calcVal');
          endif;

          ?>
        </p>

        <p class="col-xs-12 col-md-3">
          <label for="currency"><?= __('Currency', 'calcVat'); ?></label><br />
          <input type="text" name="currency" id="currency" readonly placeholder="<?= $atts['currency']; ?>" value="<?= $atts['currency']; ?>" />
        </p>
      </div>

      <p class="col-xs-12">
        <label for="used_vat"><?= __('Vat', 'calcVat'); ?></label>

        <select  id="used_vat" name="used_vat">
          <option value=""><?= __("Select vat", 'calcVat'); ?></option>
          <?php echo join('', $vat_options); ?>
        </select>

        <?php
          if(isset($errors) && in_array('used_vat', $errors)):
              __('empty_used_vat', 'calcVal');
          endif;
        ?>
      </p>

      <p class="right col-xs-12 no-padding">
        <input type="submit" id="submit" value="<?= __("Calculate",'calcVat');?>" />
      </p>
      <?= wp_nonce_field( 'cpt_vat_action', 'cpt_vat_field'  ); ?>
    </form>
  </div>
</div>