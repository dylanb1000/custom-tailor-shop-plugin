<?php
// Individual clothing form
add_shortcode('individual_clothing', 'individual_clothing_shortcode');

function individual_clothing_shortcode($atts)
{
    $custom_atts = shortcode_atts(array(
        'form_number' => ''
    ) , $atts);
    $doc_price = new DOMDocument();
    $doc_garment = new DOMDocument();
    $doc_price->loadHTMLFile(plugin_dir_url(__FILE__)."Modifiable/prices.html");
    $doc_garment->loadHTMLFile(plugin_dir_url(__FILE__)."Modifiable/garment.html");
    $form_number = $custom_atts['form_number'];
    $content = '
  <head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    @import url("' . plugin_dir_url(__FILE__) . 'css/individual-form.css");
  </style>
  </head>
  <body>
  <form id="individual_data" action="/change-data" method="post" enctype="multipart/form-data" data-ajax="false">
    <div class="flexbox" id="individual_data" style="background-color: #ffffff;">
      <label for="garment" style="padding: 12px; font-size: 30px; background-color: #ffffff;">Garment:</label>
      <select id="garment" class="garment-select" data-required="1" name="garment" data-default-value="" data-placeholder="" data-search="false" tabindex="-1" aria-hidden="true">   
        '.$doc_garment->saveHTML().'
      </select>
      <label for="description" style="padding: 12px; font-size: 30px; background-color: #ffffff;">Description:</label>
      <select id="description-' . $form_number . '" class="description-select" data-required="1" name="description" data-default-value="" data-placeholder="" data-search="false" tabindex="-1" aria-hidden="true">   
			  '.$doc_price->saveHTML().'
      </select>
      <div class="flexbox-r">
        <label for="price" style=" font-size: 30px;">Price: $</label>
        <input type="number" class="price-data" style=" width:100px;"id="price-' . $form_number . '" name="price">
      </div>
    </div>
    <div class="flexbox"  id="photo-box" style="background-color: #ffffff;">
      <label for="photo" style="padding: 12px; font-size: 30px;">Photo:</label>
      <input type="file" id="photo" class="photo-file" name="photo" accept="image/gif, image/jpeg, image/png">
    </div>
    <div class="flexbox"  id="notes-box" style="background-color: #ffffff;">
      <label for="notes" style="padding: 12px; font-size: 30px;" rows="5">Notes:</label>
      <textarea id="notes" class="notes" cols="86" rows ="20" name="text"></textarea>
    </div>
  </form>
  <script>
    var selector = document.getElementById("description-' . $form_number . '");
    document.getElementById("price-' . $form_number . '").value=selector.options[selector.selectedIndex].value;
    selector.addEventListener("change",function handleChange(event){
      document.getElementById("price-' . $form_number . '").value=event.target.value;
    });
  </script>
  </body>';

    return $content;
}