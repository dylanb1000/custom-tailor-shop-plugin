<?php
?>

<div class="wrap">
<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

<div style="background:#ececec;border:1px solid #ccc;padding:0 10px;margin-top:5px;border-radius:5px;">
    <p>This page is used to delete orders.</p>

</div>
	<form id="" method="get">
          <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
          <?php $list_table->display() ?>
      </form>
</div>

