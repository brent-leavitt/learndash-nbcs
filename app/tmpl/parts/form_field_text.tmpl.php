<?php
/**
 * Text Field
 *
 * Available variables:
 *
 *  $this->get_type()
 *  $this->get_id()
 *  $this->get_val()
 *  $this->get_label()
 *  $this->get_extra()
 */
 
	$id = $this->get_id();
 
?>	<input type="<?php echo $this->get_type(); ?>" id="<?php echo $id; ?>" name="<?php echo $id; ?>" value="<?php echo $this->get_val(); ?>" />