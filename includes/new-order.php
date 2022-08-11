<?php
// Main order form
add_shortcode('new-order', 'new_order_shortcode');

function new_order_shortcode($atts)
{
    $form = do_shortcode('[individual_clothing form_number="1"]');
    $content = '
  <head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    @import url("' . plugin_dir_url(__FILE__) . 'css/new-order.css");
  </style>
  </head>
  <body>
  <div>
  <label id="current_date" style="float: right; font-size: 20px;">
  </label>
  </div>
  <form id="GlobalData" method="post" data-design="default" data-grid="open" style="" novalidate="novalidate">
    <div class="flexbox" style="background-color: #ffffff;">
      <label for="name" style="padding: 12px; font-size: 30px; background-color: #ffffff;">Name:</label>
      <input type="text" id="name" name="name">
      <label for="email" style="padding: 12px; font-size: 30px;">Email:</label>
      <input type="text" id="email" name="email">
    </div>
    <div class="flexbox"  style="background-color: #ffffff;">
      <label for="phone" style="padding: 12px; font-size: 30px;">Phone #:</label>
      <input type="number" id="phone" name="phone">
      <label for="estimate" style="padding: 12px; font-size: 30px;">Estimated Delivery:</label>
      <input type="date" id="estimate" name="estimate">
    </div>
    <div class="flexbox"  style="background-color: #ffffff;">
      <label for="invoice" style="padding: 12px; font-size: 30px;">Invoice #:</label>
      <input type="number" id="invoice" name="invoice" >
      </input>
    </div>
  </form>
  <div id="slideshow-container" class="slideshow-container">
  <button id="button" onclick="add_individual_form()" style="float: right;">+</button>
  <div id="replicate" class="mySlides">
  ' . $form . '
  </div>
  

  <a class="prev" id="insertBefore" onclick="plusSlides(-1)">❮</a>
  <a class="next" onclick="plusSlides(1)">❯</a>
  
  <div class="dot-container" id="dot-container">
    <span class="dot" onclick="currentSlide(1)"></span> 
  </div>
  </div>
  <div class="flexbox-s">
      <div class="flexbox-r">
        <label for="subtotal" style=" font-size: 30px;">Subtotal: </label>
        <label  id="subtotal" style=" font-size: 30px;"></label>
      </div>  
      <div class="flexbox-r">
        <label for="taxes" style=" font-size: 30px;">Taxes: </label>
        <label  id="taxes" style=" font-size: 30px;"></label>
      </div> 
      <div class="flexbox-r">
        <label for="total" style=" font-size: 30px;">Total: </label>
        <label id="total" style=" font-size: 30px;"></label>
      </div>
      <div class="flexbox-r">
        <label for="tender" style="padding: 12px; font-size: 30px;">Tender:</label>
        <select id="tender" class="tender-select" data-required="1" name="description" data-default-value="" data-placeholder="" data-search="false" tabindex="-1" aria-hidden="true">   
          <option value="Cash">Cash</option>
          <option value="Credit Card">Credit Card</option>
          <option value="Check">Check</option>
        </select>
      </div>
    </div>
    <div id="submit" class="flexbox-r">
      <button onclick="generate_submit_form_data()">SUBMIT</button>
    </div>
  </body>
  <script type="text/javascript" src="' . plugin_dir_url(__FILE__) . 'js/new-order.js"></script>';

    return $content;
}