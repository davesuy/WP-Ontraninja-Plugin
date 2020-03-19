<div class="wrap">

  <div class="panel panel-default">
    <div class="panel-heading"> <h2>General Settings</h2></div>

    <div class="panel-body">

    	<?php settings_errors(); ?>

    	<form method="post" action="options.php">
    		<?php settings_fields('ontraninja-settings-group'); ?>
    		<?php do_settings_sections('ontraport-management-tool'); ?>
    		<?php submit_button(); ?>
    	</form>

    </div>
  </div>
</div>
 
  
