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