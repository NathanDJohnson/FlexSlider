<?php

/**
 * CSS for the width of the slider
 */

/*
function flexslider_control_width( $featured_number ) {
    ob_start(); ?>

@media screen and (max-width: 960px) {
	.control-width {
	<?php
		switch ($featured_number) {
			case: 1:
				echo 25;
				break;
			case: 2:
				echo 50;
				break;
			case: 3:
				echo 75;
				break;
			default:
				echo 100;
				break;
		}
	?>%;}
}
@media screen and (max-width: 960px) {
	.control-width {
}
@media screen and (min-width: 768px and max-width: 960px) {
  .control-width: 100%;
}
@media screen and (max-width: 768px) {
	.control-width: 100%;
}

<?php
	$style = ob_get_contents();
	ob_end_clean();
	
	return $style;
}
*/

function flexslider_calc_cw( $featured_number, $width ){

//if max-width > 960; .control_width = $featured_number / 4 * 100
//if 768 < max-width < 960; .control_width = $feature_number / 4 * 100 + 25 
//if max-width < 768; .control_width = 100

	$BREAKPOINTS = array( 768, 960 );

	if ( $width > $BREAKPOINTS[1] ) { 
		$control_width = $featured_number / .25;
	}
	elseif ( $width > $BREAKPOINTS[0] ) { 
		;//	$control_width = ( $featured_number + 1 ) / .25;
	}
	else ( $width > $BREAKPOINTS[1] ) { 
		$control_width = 100;
	}

	return $control_width;
}

function flexslider_controlwidth( $featured_number ) {
    ob_start(); ?>
@media screen and (max-width: 960px) {
	.control-width: 100%;
}  
@media screen and (min-width: 768px and max-width: 960px) {
  .control-width: 100%;
}
@media screen and (max-width: 768px) {
	.control-width: 100%;
}
<?php
	$style = ob_get_contents();
	ob_end_clean();
	return $style;
}

?>