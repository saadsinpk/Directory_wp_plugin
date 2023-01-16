<?php
add_shortcode('directory_search', 'search_page_view');

function search_page_view()
{
    if (!is_admin() AND !wp_is_json_request()) {
        return include 'template/search.php';
        // return $html;
    }
}

add_shortcode('directory_request_quote', 'request_quote_view');

function request_quote_view()
{
    if (!is_admin() AND !wp_is_json_request()) {
        return include 'template/request_quote.php';
        // return $html;
    }
}

add_shortcode('directory_suggest_form', 'suggest_form_view');

function suggest_form_view()
{
    if (!is_admin() AND !wp_is_json_request()) {
        return include 'template/suggest_form.php';
    }
}

function render_search_template( $template ) {
   global $wp_query;
   $post_type = get_query_var( 'post_type' );
   if ($post_type == 'directory_listing') {
        return plugin_dir_path( __FILE__ ).'template/search_result.php';
   }
   return $template;
}

add_filter( 'template_include', 'render_search_template' );

add_shortcode('sid_directory_account', 'sid_directory_account');
function sid_directory_account()
{
    if (!is_admin() AND !wp_is_json_request()) {
        return include 'template/account.php';
        // return $html;
    }
}
add_shortcode('claim_listing', 'sid_claim_listing');
function sid_claim_listing()
{
    if (!is_admin() AND !wp_is_json_request()) {
        return include 'template/claim_listing.php';
        // return $html;
    }
}


?>