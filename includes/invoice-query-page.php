<?php
// Invoice query page
add_shortcode('invoice-page', 'invoice_page_shortcode');

function invoice_page_shortcode($args)
{
    global $wpdb;
    $invoice_number = $_POST["invoice-number"];
    $meta_key = $wpdb->get_col("SELECT meta_key FROM wp_tailor_shop_data WHERE invoice_number='$invoice_number'");
    $meta_value = $wpdb->get_col("SELECT meta_value FROM wp_tailor_shop_data WHERE invoice_number='$invoice_number'");
    $form_count = (count($meta_key) - 8) / 7;
    $combined = array_combine($meta_key, $meta_value);
    $content = '<html>
      <head>
      <style>
          @import url("' . plugin_dir_url(__FILE__) . 'css/invoice-query-page.css");
      </style>
      </head>
      <body>
      <form id="GlobalData" method="post" data-design="default" data-grid="open" style="" novalidate="novalidate">
      <div class="flexbox">

      <b for="name" style="padding: 10px; font-size: 30px;">Name:</b>
      <label id="name" name="name" style="font-size: 30px;">' . $combined["form_name"] . '</label>

      <b for="email" style="padding: 10px; font-size: 30px;">Email:</b>
      <label id="email" name="email" style="font-size: 30px;">' . $combined["email"] . '</label>

    </div>
    <div class="flexbox">

      <b for="phone" style="padding: 10px; font-size: 30px;">Phone #:</b>
      <label id="phone" name="phone" style="font-size: 30px;">' . $combined["phone"] . '</label>

      <b for="estimate" style="padding: 10px; font-size: 30px;">Estimated Delivery:</b>
      <label id="estimate" name="estimate" style="font-size: 30px;">' . $combined["estimate"] . '</label>

    </div>
    <div class="flexbox">
    
      <b for="invoice" style="padding: 10px; font-size: 30px;">Invoice #:</b>
      <label id="invoice" name="invoice" style="font-size: 30px;">' . $invoice_number . '</label>

    </div>
    </form>';

    $content .= '<form action="/update-data" method="post"><input type="hidden" value="' . $invoice_number . '" name="invoice"><input type="hidden" value="' . $form_count . '" name="form_count"><table style="background-color:white";>';
    $content .= '<tr><th nowrap="nowrap">Garment</th><th nowrap="nowrap">Description</th><th nowrap="nowrap">Status</th><th nowrap="nowrap">Photo</th><th nowrap="nowrap">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspNotes&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>';
    if (wp_get_current_user()->user_login == "admin")
    {
        $content .= '<th nowrap="nowrap">Last Changed</th>';
    }
    $content .= '</tr>';
    for ($i = 0;$i < $form_count;$i++)
    {
        $content .= '<tr>';
        $content .= '<td nowrap="nowrap">' . $combined['garment-' . $i] . '</td>';
        $content .= '<td nowrap="nowrap">' . $combined['description-' . $i] . '</td>';
        if ($combined['status-' . $i] == "true")
        {
            $content .= '<td nowrap="nowrap"> <input type="checkbox" name=' . 'status-' . $i . ' checked></td>';
        }
        else
        {
            $content .= '<td nowrap="nowrap"> <input type="checkbox" name="status-' . $i . '"> </td>';
        }
        $content .= '<td>' . '<a href=' . $combined['photo-' . $i] . '>' . '<img  style="margin-left: auto;margin-right: auto;" width="50" height="50" src="' . $combined['photo-' . $i] . '">' . '</a>' . '</td>';
        $content .= '<td>' . $combined['notes-' . $i] . '</td>';
        if (wp_get_current_user()->user_login == "admin")
        {
            $content .= '<td nowrap="nowrap">' . $combined['last-changed-' . $i] . '</td>';
        }
        $content .= '<tr>';
    }
    $content .= '</table>';
    $content .= '<input type="submit" value="UPDATE" name="submit_btn" style="float: right;"></form>';
    $content .= '</body>';

    return $content;
}

