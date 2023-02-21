<?php get_header();

$page = (get_query_var('paged')) ? get_query_var('paged') : 1;
$post_per_page = 3;
// $args = array(
//     'post_type' => 'directory_listing',
//     'post_status' => 'publish',
//     'meta_query' => array(
//        'relation' => 'OR',
//         array( //check to see if featured 
//                 'key' => 'check_box_featured',
//                 'value' => 'Featured',
//                 'compare' => 'EXISTS'
//             ),
//           array( //if no featured 
//                 'key' => 'check_box_featured',
//                 'value' => 'Featured',
//                 'compare' => 'NOT EXISTS'
//             )
//         ),
//     'orderby'  => 'meta_value_num post_date',
//     'order' => 'ASC',
//     'posts_per_page' => $post_per_page,
//     'paged' => $page
// );
$args = array(
    'post_type' => 'directory_listing',
    'post_status' => 'publish',
    'order' => 'ASC',
    'posts_per_page' => $post_per_page,
    'paged' => $page
);
$the_query = new WP_Query( $args );

$end = $post_per_page * $page;
$start = $end - $post_per_page + 1;
$totalPost = $the_query->found_posts;
if ($end > $totalPost) {
	$end = $totalPost;
}

?>
<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css"
integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA=="
crossorigin="anonymous"
referrerpolicy="no-referrer"
/>
<link
rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
crossorigin="anonymous"
/>
<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__);?>/assets/css/style.css" />
<style type="text/css">
  	.pagination a,
		.pagination a:hover,
		.pagination .current a,
		.pagination .disabled {
		    color: #fff;
		    text-decoration:none;
		}
		 
		.pagination {
		    display: inline;
		}
		 
		.pagination a,
		.pagination a:hover,
		.pagination .current a,
		.pagination.disabled {
		    background-color: #2acacf;;
		    border-radius: 3px;
		    cursor: pointer;
		    padding: 12px;
		    padding: 0.75rem;
		}

		.pagination span {		  
		    background-color: #1a6c7a;
		    border-radius: 3px;
		    padding: 12px;
		    padding: 0.75rem;
		    color: white;
		}
		 
		.pagination a:hover,
		.pagination.current a {
		    background-color: #1a6c7a;
		}
  </style>
<div class="sectionbody">
  <div class="container col-md-12">
    <div class="row">
      <div class="sectionshowing col-lg-12">
        <div class="mainshowing mt-5">
          <div class="showing-left mb-3">
              <h1 class="showingtext">Showing <?php echo $start; ?> - <?php echo $end; ?> of <?php echo $totalPost;?> Results</h1>
              <br>
              <h1 class="manutext"><b>Manufacture Results</b></h1>
            </div>
           <!--  <div class="showing-right">
              <div class="iconselect">
                <a href="#">
                  <i class="baricon fa-sharp fa-solid fa-bars"></i>
                  <i class="baricon fa-sharp fa-solid fa-bars"></i>
                  <i class="baricon fa-solid fa-location-dot"></i>
                </a>
              </div>
            </div> -->
          </div>
          <div class="mainprimersection mt-4">
            <div class="mainglobal">
			<?php 
			if ( $the_query->have_posts() ) :
				$count = 1;
				while ( $the_query->have_posts() ) : $the_query->the_post();
					$post_id = get_the_id();
					$comments = wp_count_comments($post_id);
					$total_comments = $comments->total_comments;
					$average = directory_comment_rating_get_average_ratings( $post_id );
					$stars = "";
					$count = 0;
			    for ( $i = 1; $i <= $average + 1; $i++ ) {
			        
			        $width = intval( $i - $average > 0 ? 20 - ( ( $i - $average ) * 20 ) : 20 );

			        if ( 0 === $width ) {
			            continue;
			        }

			        $stars .= '<span style="overflow:hidden; width:' . $width . 'px" class="dashicons dashicons-star-filled"></span>';

			        if ( $i - $average > 0 ) {
			            $stars .= '<span style="overflow:hidden; position:relative; left:-' . $width .'px;" class="dashicons dashicons-star-empty"></span>';
			        }
			        $count++;
			    }
			    if (empty($stars)) {
			    	$average = 0;
			    }
		    	$remainStar = 5 - $count;
		    	for ( $i = 0; $i < $remainStar; $i++ ) {
		    		$stars .= '<span style="overflow:hidden; position:relative;left:-' . $width .'px;" class="dashicons dashicons-star-empty"></span>';
		    	}
					    
					// echo $count++;?>
					<div class="mainprimer mt-4">
						<?php 
						$beforclass ="";
						if (get_post_meta($post_id, 'check_box_featured',true) == "Featured"){
								$beforclass = "featured_tag";
						} ?>
		              <div class="global_top <?php echo $beforclass; ?> py-3 px-3 mb-3">
		                <div class="mainmain row">
		                  <div class="mainmain_left col-lg-8">
		                    <div class="mainmain_left--top py-2 d-flex align-items-center">
		                      <div class="mainmain_left--top-l">
		                        <h1 class="globaltext m-0"><a href="<?php echo get_permalink();?>"><b><?php echo get_the_title();?></b></a></h1>
		                      </div>
		                      <div class="mainmain_left--top-r d-flex align-items-center w-100">
		                        <?php echo $stars; ?>
		                        <div class="mainmain_left--top-r-l d-flex gap-1">
		                        
		                        <span class="ratedtext">Rated <?php echo $average; ?>/5 (<?php echo $total_comments; ?> Reviews)</span>
		                        <?php if (get_post_meta($post_id, 'check_box_verify',true) == "Verified"){ ?>
		                        <img src="<?php echo plugin_dir_url(__FILE__);?>/assets/images/tick.png" alt="">
		                      <?php } ?>
		                      </div>
		                      
		                      </div>
		                    </div>
		                  <div class="mainpalletsection d-flex gap-2">
		                    <div class="section_pallet_left">
		                    	<?php 
			                    	$imageUrl = get_the_post_thumbnail_url( $post_id );
			                    	$image = "";
			                    	if (!empty($imageUrl)) {
			                    		$image = $imageUrl;
			                    	}else{
			                    		$image = plugins_url('assets/images/directory2.png', __FILE__);
			                    	}
		                    	?>
		                      <img class="primerlogo" src="<?php echo $image; ?>" alt="">
		                    </div>
		                    <div class="pallet_text_right mt-3">
		                      <h1 class="pallettext"><b>Category: <?php $categories = get_the_terms( $post_id, 'directory_listing_cat' );
					                	if (!empty($categories)) {
					                	$categories_value = "";
					                	foreach ($categories as $category_key => $category_value) { 
					                	 			$categories_value .= $category_value->name.",";
										            	}
										            	echo "(".trim($categories_value,",").")";
										          }
								        	?></b></h1>
		                          <h1 class="pallettext"><b><?php echo substr(get_the_content(), 0, 300);?></b></h1>
		                          <?php if (get_post_meta($post_id, 'address',true)) { ?>
					                      <h1 class="pallettext">
					                        <i class="backcolor fa-solid fa-location-dot"></i>
					                        <?php echo get_post_meta($post_id, 'address',true);?>
					                      </h1>
					                    <?php } ?>
		                      <div class="sectionbox d-flex gap-1">
		                        <a href="#" class="seephonebtn d-flex align-items-center" type="button">
		                          <b>SEE PHONE NUMBER</b>
		                        </a>
		                      </div>
		                    </div>
		                  </div>
		                </div>
		                  <div class="mainverified col-lg-4">
		                    <div class="verifiedsection p-3">
		                    	<?php if (get_post_meta($post_id, 'check_box_verify',true) == "Verified"){ ?>
		                    				<img src="<?php echo plugin_dir_url(__FILE__);?>/assets/images/verifiedpic.png" alt="" class="my-2">	
		                    	<?php } ?>
		                      
														<a class="viewprofilebtn" href="<?php echo get_permalink();?>"><b>VIEW PROFILE</b></a>
		                        <a href="<?php echo get_site_url();?>/request-quote/?post_id=<?php echo $post_id; ?>" class="requestbtn"><b>REQUEST FREE QUOTES</b></a>
		                    </div>
		                  </div>
		                </div>
		              </div>
		            </div>

				<?php endwhile;
			wp_reset_postdata(); ?>
			<?php else:  ?>
				<h1><?php _e( 'Sorry, no posts matched your criteria.' ); ?></h1>
			<?php endif; ?>            	

            </div>
            </div>
             <div class="sectionbox pt-1 mb-3">
                <!-- <button class="loadbtn" type="button">
                  <b>LOAD MORE</b>
                </button> -->
                <?php if ( $the_query->have_posts() ) :
             ?>
             
	             <div class="pagination">
						    <?php 
						        echo paginate_links( array(
						            'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
						            'total'        => $the_query->max_num_pages,
						            'current'      => max( 1, get_query_var( 'paged' ) ),
						            'show_all'     => false,
						            'type'         => 'plain',
						            'end_size'     => 3,
						            'mid_size'     => 1,
						            'prev_next'    => true,
						            'prev_text'    => sprintf( '<i></i> %1$s', __( 'Previous', 'text-domain' ) ),
						            'next_text'    => sprintf( '%1$s <i></i>', __( 'Next', 'text-domain' ) ),
						            'add_args'     => false,
						            'add_fragment' => '',
						        ) );
						    ?>
						</div>
						    
						<?php else : ?>

							<?php _e( 'Sorry, no posts matched your criteria.' ); ?>

						<?php endif; ?>
              </div>

              
        </div>

      </div>
    </div>
  </div>
  <script src="<?php echo plugin_dir_url(__FILE__);?>/assets/js/jquery-latest.min.js"></script>
  <script>
  $(".mainprimer").show(); //showing 3 div
  // $(".mainprimer").slice(0, 3).show(); //showing 3 div
  // $(".loadbtn").on("click",function(){
  // 	$(".mainprimer:hidden").slice(0, 3).show(); //showing 3 hidden div on click
  // })
  </script>

  <script src="https://code.jquery.com/jquery-3.6.1.min.js"
  integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
  <script src="<?php echo plugin_dir_url(__FILE__);?>/assets/js/main.js"></script>
<?php get_footer(); ?>
