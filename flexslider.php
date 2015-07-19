<?php
/*
Plugin Name: FlexSlider
Plugin URI: http://atmoz.org/flexslider/
Description: Show large slider on the homepage.
Version: 0.0.1
Author: Nathan Johnson
Author URI: http://atmoz.org/
*/

//include_once( plugin_dir_path( __FILE__ ). 'includes/options.php' );

function cpflex_theme_exists() {
	/**
	 * This requires the use of the ClassiPress theme
	 */
	$my_theme = wp_get_theme();
	if( $my_theme->get( 'Name' ) == 'ClassiPress' || $my_theme->get( 'Template' ) == 'classipress' ){
		return true;
	}
	return false;
}

function cpflex_flexslider_enqueue() {
	if ( cpflex_theme_exists() ) {
		if( is_home() || is_front_page() || is_tax( array( APP_TAX_CAT, APP_TAX_TAG ) ) ) {

			// Deregister jquery to avoid conflicts with jquery hosted on googleapis.com
			wp_deregister_script('jquery');

			wp_enqueue_style( 'flexslider-css', plugin_dir_url( __FILE__ ) . 'css/flexslider.css' );
			wp_enqueue_style( 'classipress-slider-css', plugin_dir_url( __FILE__ ) . 'css/demo.css' );

			wp_enqueue_script( 'g-jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js', array() );
			wp_enqueue_script( 'flexslider-js', plugin_dir_url( __FILE__ ) . 'js/jquery.flexslider.js', array( 'g-jquery' ) );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'cpflex_flexslider_enqueue' );

function cpflex_flexslider_slider() {

	global $wpdb, $post, $cp_options;
	if ( $featured = cp_get_featured_slider_ads() ) : ?>	
		<?php $featured_number = count( $featured->posts ); 
			$cw = array();	
			$cw['wide'] = min(100, $featured_number / 4 * 100 );
			$cw['mid'] = min(100, ($featured_number + 1) / 4 * 100 );
			$cw['small'] = 100;
		?>

<!-- featured slider -->
<style>
@media only screen and (min-width:960px){
  .control-width {
    width: <?php echo $cw['wide'];?>%;
  }
}  
@media screen and (min-width: 768px and max-width: 960px) {
  .control-width {
    width: <?php echo $cw['mid'];?>%;
  }
}
@media screen and (max-width: 768px) {
  .control-width {
    width: <?php echo $cw['small'];?>%;
  }
}
</style>
<div id="featured-slider" class="loading">
  <div id="container" class="cf">
    <div id="main" role="main">
      <div id="cwidth" class="control-width">
        <section class="slides">
          <div id="slider" class="flexslider carousel">
            <ul class="slides photogrid">
              <?php while ( $featured->have_posts() ) : $featured->the_post(); ?>
              <li>
                <a class="featured-header" href="<?php the_permalink(); ?>">
                  <div class="ellipsis"><h3 class="listing-header"><?php if ( mb_strlen( get_the_title() ) >= $cp_options->featured_trim ) echo mb_substr( get_the_title(), 0, $cp_options->featured_trim ).'...'; else the_title(); ?></h3></div>
                  <figure class="listing-content">
                    <?php echo wp_get_attachment_image( cp_get_featured_image_id( get_the_ID() ), 'bsc_featured' ); ?>
                    <figcaption><p><?php echo cp_get_content_preview( 350 ); ?></p></figcaption>
                  </figure>
                  <p class="tag">$<?php if ( is_numeric( get_post_meta($post->ID, 'cp_price', true) ) ) { echo number_format( get_post_meta($post->ID, 'cp_price', true) ); } ?></p>
                </a>
              </li>
              <?php endwhile; ?>
            </ul>
          </div> <!-- #slider -->
        </section>
      </div> <!-- #cwidth -->
    </div> <!-- #main -->
  </div> <!-- #container -->
</div> <!-- #featured-slider -->
<script type='text/javascript'>
(function($) {
    // Inside of this function, $() will work as an alias for jQuery()
    // and other libraries also using $ will not be accessible under this shortcut
      var $window = $(window),
          flexslider;
      // tiny helper function to add breakpoints
      function getGridSize() {
        return (window.innerWidth < 450) ? 1 :
               (window.innerWidth < 700) ? <?php echo min(array($featured_number,2));?> :
               (window.innerWidth < 900) ? <?php echo min(array($featured_number,3));?> : <?php echo min(array($featured_number,4));?>;
      }
      $window.load(function() {
        $('.flexslider').flexslider({
          animation: "slide",
          animationSpeed: 400,
          animationLoop: true,
          prevText: '',
          nextText: '',
          itemWidth: 210,
          itemMargin: 5,
          pauseOnHover: true,
          controlNav: false,
          minItems: getGridSize(), // use function to pull in initial value
          maxItems: getGridSize(), // use function to pull in initial value
          start: function(slider){
            $('#featured-slider').removeClass('loading'); //$('body').removeClass('loading');
            flexslider = slider;
          }
        });
      });
      // check grid size on resize event
      $window.resize(function() {
        var gridSize = getGridSize();
        flexslider.vars.minItems = gridSize;
        flexslider.vars.maxItems = gridSize;
      });
})(jQuery);
</script>
<!-- end featured slider -->
	<?php endif; ?>
    <?php wp_reset_query(); ?>
<?php 
}

/**
 * Add custom image size
 */
function cpflex_featured_size() {
  add_image_size( 'cpflex_featured', 400, 300, true );
}
add_action( 'after_setup_theme', 'cpflex_featured_size' );
