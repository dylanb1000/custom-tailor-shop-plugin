<?php

//handles data from new order form/print form
add_shortcode('new_order_change', 'new_order_change_shortcode');

function new_order_change_shortcode($args)
{
    global $wpdb;
    //assist function
    function variable_Name($var, $post,$default_index)
    {
        $count=0;
        foreach ($post as $varName => $value)
        {
            if ($count==$default_index)
            {
                return $varName;
            }
            $count+=1;
        }
        return ;
    }
    function upload_user_file($file = array())
    {

        require_once (ABSPATH . 'wp-admin/includes/admin.php');

        $file_return = wp_handle_upload($file, array(
            'test_form' => false
        ));

        if (isset($file_return['error']) || isset($file_return['upload_error_handler']))
        {
            return false;
        }
        else
        {

            $filename = $file_return['file'];

            $attachment = array(
                'post_mime_type' => $file_return['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)) ,
                'post_content' => '',
                'post_status' => 'inherit',
                'guid' => $file_return['url']
            );

            $attachment_id = wp_insert_attachment($attachment, $file_return['url']);

            require_once (ABSPATH . 'wp-admin/includes/image.php');
            $attachment_data = wp_generate_attachment_metadata($attachment_id, $filename);
            wp_update_attachment_metadata($attachment_id, $attachment_data);

            if (0 < intval($attachment_id))
            {
                return $attachment_id;
            }
        }

        return false;
    }
    //assist functions^
    $table_name = $wpdb->prefix . 'tailor_shop_data';
    $form_count = (count($_POST) - 9) / 4;
    $invoice = $_POST["invoice"];
    //global order variables
    $form_name = $_POST["form_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $estimate = $_POST["estimate"];
    $subtotal = $_POST["subtotal"];
    $taxes = $_POST["taxes"];
    $total = $_POST["total"];
    $tender = $_POST["tender"];
    $vars = array(
        $form_name,
        $email,
        $phone,
        $estimate,
        $subtotal,
        $taxes,
        $total,
        $tender
    );
    for ($i = 0;$i < count($vars);$i++)
    {
        $wpdb->insert($table_name, array(
            'invoice_number' => $invoice,
            'meta_key' => variable_Name($vars[$i], $_POST,$i) ,
            'meta_value' => $vars[$i],
        ));
    }
    //upload file/get location
    for ($i = 0;$i < $form_count;$i++)
    {
        $file = $_FILES['photo-' . $i];
        if($file['size']==0){
            $wpdb->insert($table_name, array(
                'invoice_number' => $invoice,
                'meta_key' => "photo-" . $i,
                'meta_value' => "",
            ));
        }
        else{
            $attachment_id = upload_user_file($file);
            //photo
            $wpdb->insert($table_name, array(
                'invoice_number' => $invoice,
                'meta_key' => "photo-" . $i,
                'meta_value' => wp_get_attachment_image_src($attachment_id) ['0'],
            ));
        }   
        //garment
        $wpdb->insert($table_name, array(
            'invoice_number' => $invoice,
            'meta_key' => "garment-" . $i,
            'meta_value' => $_POST["garment-" . $i],
        ));
        //description
        $wpdb->insert($table_name, array(
            'invoice_number' => $invoice,
            'meta_key' => "description-" . $i,
            'meta_value' => $_POST["description-" . $i],
        ));
        //price
        $wpdb->insert($table_name, array(
            'invoice_number' => $invoice,
            'meta_key' => "price-" . $i,
            'meta_value' => $_POST["price-" . $i],
        ));
        //notes
        $wpdb->insert($table_name, array(
            'invoice_number'     => $invoice,
            'meta_key'     => "notes-".$i,
            'meta_value'   => $_POST["notes-".$i],
        ));
        //status
        $wpdb->insert($table_name, array(
            'invoice_number' => $invoice,
            'meta_key' => "status-" . $i,
            'meta_value' => "false",
        ));
        //last-changed
        $wpdb->insert($table_name, array(
            'invoice_number' => $invoice,
            'meta_key' => "last-changed-" . $i,
            'meta_value' => "",
        ));
    }
    $content = '<div class="contentSection">
  <div class="contentToPrint">
            <style>
              @import url("' . plugin_dir_url(__FILE__) . 'css/new-order-processing.css");
            </style>
            <body>
            <form id="GlobalData" method="post" data-design="default" data-grid="open" style="" novalidate="novalidate">
            <div class="flexbox">

            <b for="name" style="padding: 10px; font-size: 30px;">Name:</b>
            <label id="name" name="name" style="padding: 10px; font-size: 30px;">' . $form_name . '</label>

            <b for="email" style="padding: 10px; font-size: 30px;">Email:</b>
            <label id="email" name="email" style="padding: 10px; font-size: 30px;">' . $email . '</label>

          </div>
          <div class="flexbox">

            <b for="phone" style="padding: 10px; font-size: 30px;">Phone #:</b>
            <label id="phone" name="phone" style="padding: 10px; font-size: 30px;">' . $phone . '</label>

            <b for="estimate" style="padding: 10px; font-size: 30px;">Estimated Delivery:</b>
            <label id="estimate" name="estimate" style="padding: 10px; font-size: 30px;">' . $estimate . '</label>

          </div>
          <div class="flexbox">

            <b for="invoice" style="padding: 10px; font-size: 30px;">Invoice #:</b>
            <label id="invoice" name="invoice" style="padding: 10px; font-size: 30px;">' . $invoice . '</label>

          </div>
          </form>';

    //insert item info
    $content .= '<table>';
    $content .= '<tr><th nowrap="nowrap" style=" font-size: 30px;">Garment</th><th nowrap="nowrap" style=" font-size: 30px;">Description</th><th nowrap="nowrap" style=" font-size: 30px;">Price</th></tr>';
    for ($i = 0;$i < $form_count;$i++)
    {
        $content .= '<tr>';
        //database structure
        $content .= '<td nowrap="nowrap" style=" font-size: 30px;">' . $_POST["garment-" . $i] . '</td>';
        $content .= '<td nowrap="nowrap" style=" font-size: 30px;">' . $_POST["description-" . $i] . '</td>';
        $content .= '<td nowrap="nowrap" style=" font-size: 30px;">' . $_POST["price-" . $i] . '</td>';
        $content .= '</tr>';
    }
    $content .= '</table>';

    $content .= '<div class="flexbox-s">
          <div class="flexbox-r">
            <label for="subtotal" style=" font-size: 30px;">Subtotal: </label>
            <label  id="subtotal" style="padding: 5px; font-size: 30px;">' . $subtotal . '</label>
          </div>  
          <div class="flexbox-r">
            <label for="taxes" style=" font-size: 30px;">Taxes: </label>
            <label  id="taxes" style="padding: 5px; font-size: 30px;">' . $taxes . '</label>
          </div> 
          <div class="flexbox-r">
            <label for="total" style=" font-size: 30px;">Total: </label>
            <label id="total" style="padding: 5px; font-size: 30px;">' . $total . '</label>
          </div>
          <div class="flexbox-r">
            <label for="tender" style="padding: 5px; font-size: 30px;">Tender:</label>
            <label id="tender" style=" font-size: 30px;">' . $tender . '</label>
          </div>
        </div>';

    $content .= '</body>
  </div>
</div>
<div class="contentSection termsToPrint">';

    $content .= '<table>
<tr><th nowrap="nowrap">Invoice Number Tags</th></tr>';
    for ($i = 0;$i < $form_count;$i++)
    {
        $content .= '<tr><td nowrap="nowrap"><label style="padding: 10px; font-size: 50px;">' . $invoice . '</label></td></tr>';
    }

    $content .= '</table></div>
<div class="contentSection">
  <a href="#" id="printOut">Print This</a>
</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>

<script type="text/javascript" src="' . plugin_dir_url(__FILE__) . 'js/new-order-processing.js"></script>';

    return $content;
}
