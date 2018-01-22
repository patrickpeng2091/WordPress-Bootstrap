<?php 
	/*

	 * This file is used to generate footer section of theme.

	 */	
?>

		<?php 
          $cp_show_footer = get_option(THEME_NAME_S.'_show_footer','enable');
          $cp_show_copyright = get_option(THEME_NAME_S.'_show_copyright','enable');
          $cp_show_feed = get_option(THEME_NAME_S.'show_feedburner','enable'); 
         
         ?>

       <footer> 
        <?php if( $cp_show_footer == 'enable' ){ ?>
                  <?php if ($cp_show_feed=="enable") { ?>
                          <section class="newsletter-box-wrapper">                                        
                         	 <section class="container">
                         		 <?php cp_feedburner(); ?>
                          			 <section class="eight columns mt0"> 
										<?php if (function_exists('newsticker')) { newsticker( $group = "", $title = "", $direction = "", $type = "", $pause = "", $speed = "" ); } ?>
                					 </section>
                         	 </section>
                        </section>
 				 <?php } ?>
                
                  <section class="footer-wrapper container">
                      
                                            <?php
                                                    $cp_footer_class = array(
                                                    'footer-style1'=>array('1'=>'four columns', '2'=>'four columns', '3'=>'four columns', '4'=>'four columns'),
                                                    'footer-style2'=>array('1'=>'eight columns', '2'=>'four columns', '3'=>'four columns', '4'=>'display-none'),
                                                    'footer-style3'=>array('1'=>'four columns', '2'=>'four columns', '3'=>'eight columns', '4'=>'display-none'),
                                                    'footer-style4'=>array('1'=>'one-third column', '2'=>'one-third column', '3'=>'one-third column', '4'=>'display-none'),
                                                    'footer-style5'=>array('1'=>'two-thirds column', '2'=>'one-third column', '3'=>'display-none', '4'=>'display-none'),
                                                    'footer-style6'=>array('1'=>'one-third column', '2'=>'two-thirds column', '3'=>'display-none', '4'=>'display-none'),
                                                    );
													$cp_footer_style = get_option(THEME_NAME_S.'_footer_style', 'footer-style1');
													for( $i=1 ; $i<=4; $i++ ){
														echo '<figure class="' . $cp_footer_class[$cp_footer_style][$i] . '">';
                                                    dynamic_sidebar('Footer ' . $i);
                                                    echo '</figure>';
                                                    }
                                                ?>
                                                
                                         <br class="clear">
                                     
                                <?php } ?>
    
                              
                    </section> <!--footer-wrapper-end-->
                  <!-- Get Copyright Text -->
                                <?php if( $cp_show_copyright == 'enable' ){ ?>
                               			 <div class="copyright-wrapper">
                                 				 <section class="container">
                                                 	<div class="copyright-left">
                                   						 <p><?php  echo do_shortcode( __(get_option(THEME_NAME_S.'_copyright_left_area'), 'crunchpress') ); ?></p>
                                 				 	</div>
                                  		         	<div class="scroll-top"><a href="#"><?php _e('Back to top','crunchpress')?></a></div>
                                                 </section>
                                         </div>
                                  <?php }?>
                   
              <script type="text/javascript">
                    <?php get_template_part( 'cufon', 'replace' ); ?>
		      </script> 

				</footer> <!-- footer-wrapper -->
      <?php // wp_footer(); ?>
  </body>
</html>