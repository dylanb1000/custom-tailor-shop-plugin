<?php

// Update data/email notification
add_shortcode('update-data', 'update_data_shortcode');

function update_data_shortcode($args)
{
  global $wpdb;
  $invoice_number = $_POST['invoice'];
  $form_count = $_POST['form_count'];
  $current_status = array();
  $email = $wpdb->get_var("SELECT meta_value FROM wp_tailor_shop_data WHERE invoice_number='$invoice_number' AND meta_key='email'");

  for ($i = 0; $i < $form_count; $i++) {
    $temp = "status-" . $i;
    $temp_past = $wpdb->get_var("SELECT meta_value FROM wp_tailor_shop_data WHERE invoice_number='$invoice_number' AND meta_key='$temp'");

    if (!is_null($_POST['status-' . $i])) {
      if ($temp_past == "false") {
        $user = wp_get_current_user()->user_login;
        $temp_user = "last-changed-" . $i;
        $wpdb->query($wpdb->prepare("UPDATE wp_tailor_shop_data SET meta_value='$user' WHERE invoice_number='$invoice_number' AND meta_key='$temp_user'"));
      }
      $wpdb->query($wpdb->prepare("UPDATE wp_tailor_shop_data SET meta_value='true' WHERE invoice_number='$invoice_number' AND meta_key='$temp'"));
    } else if (is_null($_POST['status-' . $i])) {
      if ($temp_past == "true") {
        $user = wp_get_current_user()->user_login;
        $temp_user = "last-changed-" . $i;
        $wpdb->query($wpdb->prepare("UPDATE wp_tailor_shop_data SET meta_value='$user' WHERE invoice_number='$invoice_number' AND meta_key='$temp_user'"));
      }
      $wpdb->query($wpdb->prepare("UPDATE wp_tailor_shop_data SET meta_value='false' WHERE invoice_number='$invoice_number' AND meta_key='$temp'"));
    }
  }
  //collect data
  for ($i = 0; $i < $form_count; $i++) {
    $temp = "status-" . $i;
    array_push($current_status, $wpdb->get_var("SELECT meta_value FROM wp_tailor_shop_data WHERE invoice_number='$invoice_number' AND meta_key='$temp'"));
  }

  //send email if all pieces of clothing are finished
  $finished_count = 0;
  for ($i = 0; $i < $form_count; $i++) {
    if ($current_status[$i] == "true") {
      $finished_count += 1;
    }
  }

  if ($finished_count == $form_count) {
    add_filter('wp_mail_content_type', function ($content_type) {
      return 'text/html';
    });

    $message = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD XHTML 1.0 Transitional //EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
  <html xmlns='http://www.w3.org/1999/xhtml' xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office'>
  <head>
  <!--[if gte mso 9]>
  <xml>
    <o:OfficeDocumentSettings>
      <o:AllowPNG/>
      <o:PixelsPerInch>96</o:PixelsPerInch>
    </o:OfficeDocumentSettings>
  </xml>
  <![endif]-->
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta name='x-apple-disable-message-reformatting'>
    <!--[if !mso]><!--><meta http-equiv='X-UA-Compatible' content='IE=edge'><!--<![endif]-->
    <title></title>
    
      <style type='text/css'>
        @media only screen and (min-width: 620px) {
    .u-row {
      width: 600px !important;
    }
    .u-row .u-col {
      vertical-align: top;
    }
  
    .u-row .u-col-100 {
      width: 600px !important;
    }
  
  }
  
  @media (max-width: 620px) {
    .u-row-container {
      max-width: 100% !important;
      padding-left: 0px !important;
      padding-right: 0px !important;
    }
    .u-row .u-col {
      min-width: 320px !important;
      max-width: 100% !important;
      display: block !important;
    }
    .u-row {
      width: calc(100% - 40px) !important;
    }
    .u-col {
      width: 100% !important;
    }
    .u-col > div {
      margin: 0 auto;
    }
  }
  body {
    margin: 0;
    padding: 0;
  }
  
  table,
  tr,
  td {
    vertical-align: top;
    border-collapse: collapse;
  }
  
  p {
    margin: 0;
  }
  
  .ie-container table,
  .mso-container table {
    table-layout: fixed;
  }
  
  * {
    line-height: inherit;
  }
  
  a[x-apple-data-detectors='true'] {
    color: inherit !important;
    text-decoration: none !important;
  }
  
  table, td { color: #000000; } @media (max-width: 480px) { #u_column_1 .v-col-padding { padding: 0px 0px 30px !important; } #u_content_heading_1 .v-font-size { font-size: 24px !important; } #u_content_heading_7 .v-font-size { font-size: 24px !important; } #u_column_4 .v-col-padding { padding: 30px 0px !important; } }
      </style>
    
    
  
  <!--[if !mso]><!--><link href='https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap' rel='stylesheet' type='text/css'><link href='https://fonts.googleapis.com/css?family=Playfair+Display:400,700&display=swap' rel='stylesheet' type='text/css'><!--<![endif]-->
  
  </head>
  
  <body class='clean-body u_body' style='margin: 0;padding: 0;-webkit-text-size-adjust: 100%;background-color: #ffffff;color: #000000'>
    <!--[if IE]><div class='ie-container'><![endif]-->
    <!--[if mso]><div class='mso-container'><![endif]-->
    <table style='border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 320px;Margin: 0 auto;background-color: #ffffff;width:100%' cellpadding='0' cellspacing='0'>
    <tbody>
    <tr style='vertical-align: top'>
      <td style='word-break: break-word;border-collapse: collapse !important;vertical-align: top'>
      <!--[if (mso)|(IE)]><table width='100%' cellpadding='0' cellspacing='0' border='0'><tr><td align='center' style='background-color: #ffffff;'><![endif]-->
      
  
  <div class='u-row-container' style='padding: 0px;background-color: #fbf4ec'>
    <div class='u-row' style='Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;'>
      <div style='border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;'>
        <!--[if (mso)|(IE)]><table width='100%' cellpadding='0' cellspacing='0' border='0'><tr><td style='padding: 0px;background-color: #fbf4ec;' align='center'><table cellpadding='0' cellspacing='0' border='0' style='width:600px;'><tr style='background-color: transparent;'><![endif]-->
        
  <!--[if (mso)|(IE)]><td align='center' width='600' class='v-col-padding' style='width: 600px;padding: 0px 0px 40px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;' valign='top'><![endif]-->
  <div id='u_column_1' class='u-col u-col-100' style='max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;'>
    <div style='height: 100%;width: 100% !important;'>
    <!--[if (!mso)&(!IE)]><!--><div class='v-col-padding' style='padding: 0px 0px 40px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;'><!--<![endif]-->
    
  <table style='font-family:arial,helvetica,sans-serif;' role='presentation' cellpadding='0' cellspacing='0' width='100%' border='0'>
    <tbody>
      <tr>
        <td style='overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;' align='left'>
          
  <table width='100%' cellpadding='0' cellspacing='0' border='0'>
    <tr>
      <td style='padding-right: 0px;padding-left: 0px;' align='center'>
        
        <img align='center' border='0' src='https://cedarhilltailor.com/wp-content/plugins/custom-tailor-shop/images/image-1.png?_t=1659223786' alt='image' title='image' style='outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 100%;max-width: 544px;' width='544'/>
        
      </td>
    </tr>
  </table>
  
        </td>
      </tr>
    </tbody>
  </table>
  
  <table id='u_content_heading_1' style='font-family:arial,helvetica,sans-serif;' role='presentation' cellpadding='0' cellspacing='0' width='100%' border='0'>
    <tbody>
      <tr>
        <td style='overflow-wrap:break-word;word-break:break-word;padding:10px 10px 4px;font-family:arial,helvetica,sans-serif;' align='left'>
          
    <h1 class='v-font-size' style='margin: 0px; line-height: 140%; text-align: center; word-wrap: break-word; font-weight: normal; font-family: 'Playfair Display',serif; font-size: 32px;'>
      <strong>Order is ready for pickup</strong>
    </h1>
  
        </td>
      </tr>
    </tbody>
  </table>
  
  <table id='u_content_heading_7' style='font-family:arial,helvetica,sans-serif;' role='presentation' cellpadding='0' cellspacing='0' width='100%' border='0'>
    <tbody>
      <tr>
        <td style='overflow-wrap:break-word;word-break:break-word;padding:0px 10px 10px;font-family:arial,helvetica,sans-serif;' align='left'>
          
    <h1 class='v-font-size' style='margin: 0px; line-height: 100%; text-align: center; word-wrap: break-word; font-weight: normal; font-family: 'Playfair Display',serif; font-size: 32px;'>
      <strong>Thanks for doing business with us!</strong>
    </h1>
  
        </td>
      </tr>
    </tbody>
  </table>
  
  <table style='font-family:arial,helvetica,sans-serif;' role='presentation' cellpadding='0' cellspacing='0' width='100%' border='0'>
    <tbody>
      <tr>
        <td style='overflow-wrap:break-word;word-break:break-word;padding:10px 20px;font-family:arial,helvetica,sans-serif;' align='left'>
          
    <h1 class='v-font-size' style='margin: 0px; line-height: 140%; text-align: center; word-wrap: break-word; font-weight: normal; font-family: 'Montserrat',sans-serif; font-size: 14px;'>
      
    </h1>
  
        </td>
      </tr>
    </tbody>
  </table>
  
    <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
    </div>
  </div>
  <!--[if (mso)|(IE)]></td><![endif]-->
        <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
      </div>
    </div>
  </div>
  
  
  
  <div class='u-row-container' style='padding: 0px;background-color: #000000'>
    <div class='u-row' style='Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;'>
      <div style='border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;'>
        <!--[if (mso)|(IE)]><table width='100%' cellpadding='0' cellspacing='0' border='0'><tr><td style='padding: 0px;background-color: #000000;' align='center'><table cellpadding='0' cellspacing='0' border='0' style='width:600px;'><tr style='background-color: transparent;'><![endif]-->
        
  <!--[if (mso)|(IE)]><td align='center' width='600' class='v-col-padding' style='width: 600px;padding: 35px 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;' valign='top'><![endif]-->
  <div id='u_column_4' class='u-col u-col-100' style='max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;'>
    <div style='height: 100%;width: 100% !important;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;'>
    <!--[if (!mso)&(!IE)]><!--><div class='v-col-padding' style='padding: 35px 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;'><!--<![endif]-->
    
  <table style='font-family:arial,helvetica,sans-serif;' role='presentation' cellpadding='0' cellspacing='0' width='100%' border='0'>
    <tbody>
      <tr>
        <td style='overflow-wrap:break-word;word-break:break-word;padding:10px 45px;font-family:arial,helvetica,sans-serif;' align='left'>
          
    <div style='color: #ffffff; line-height: 140%; text-align: center; word-wrap: break-word;'>
      <p style='font-size: 14px; line-height: 140%;'>444 FM1382 Ste A, Cedar Hill, TX 75104</p>
    </div>
  
        </td>
      </tr>
    </tbody>
  </table>
  
    <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
    </div>
  </div>
  <!--[if (mso)|(IE)]></td><![endif]-->
        <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
      </div>
    </div>
  </div>
  
  
      <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
      </td>
    </tr>
    </tbody>
    </table>
    <!--[if mso]></div><![endif]-->
    <!--[if IE]></div><![endif]-->
  </body>
  
  </html>";
    wp_mail($email, "Cedar Hill Tailor & Alteration Order Status", $message);
  }

  wp_safe_redirect(site_url("invoice-query"));
}