<?php 
global $COLUMN_NUM, $SIDEBAR_FLOAT, $SIDEBAR2_FLOAT;

if ( is_active_sidebar('sidebar') ) {
	$col_class = ($COLUMN_NUM === 3) ? ' first three-col' : ' first';
	$sidebar_float = ($COLUMN_NUM === 3) ? $SIDEBAR2_FLOAT : $SIDEBAR_FLOAT; ?>
<aside id="sidebar" class="sidebar <?php echo $sidebar_float.$col_class; ?>"><?php
	dynamic_sidebar('sidebar');?>
</aside><?php
}