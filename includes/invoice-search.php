<?php
// Search Requests by invoice number
add_shortcode('invoice_search', 'invoice_search_shortcode');

function invoice_search_shortcode($args)
{
    global $wpdb;
    $content = '<html>
   <head>
   <style>
      @import url("' . plugin_dir_url(__FILE__) . 'css/invoice-search.css");
   </style>
   </head>
   <body>
   <form action="/invoice-page" id="invoice_q" name=postlink method="post">
    <table style="width:100%;">
    <tr>
      <th style="font-size: 30px;">Invoice #</th>
      <th></th>
    </tr>
    <tr>
      <td><input type="number" name="invoice-number" value=""></td>
      <td>
        <div style="padding-right: 30px;">
        <input type="submit" value="SUBMIT" name="submit_btn" style="float: right;"">
        </div>
      </td>
    </tr>
  </table>
   </form>
   </body>
   </html>';
    return $content;
}