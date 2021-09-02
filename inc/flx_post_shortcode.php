<?php

/**
* Shortcode
*
* @package Naq
*/

function example_function() {
  wpshout_save_post_if_submitted();
  if ( is_user_logged_in() ) {
ob_start();
echo
  ('<div id="postbox">
    <div id="errormessage"></div>
    <div id="message"></div>
    <form id="new_post" name="new_post" method="post" onsubmit="return myPublishFunction();">

    <p><label for="title">Title</label><br />
        <input type="text" id="title"  tabindex="1" size="20" name="title"/>
    </p>

    <p>
        <label for="contentt">Post Content</label><br />
        <textarea id="contentt" tabindex="3" name="content" cols="50" rows="6"></textarea>
    </p>');


wp_nonce_field( 'wps-frontend-post' );

echo
('<p align="right"><input type="submit" value="Publish" tabindex="6" id="submit" disabled/></p>
</form>
</div>');
$output = ob_get_contents();
ob_end_clean();
return $output;






}
else {
ob_start();
  echo "<a href='".admin_url()."'>Admin Area</a>";
$output = ob_get_contents();
ob_end_clean();
return $output;
}

}

function wpshout_save_post_if_submitted() {
    // Stop running function if form wasn't submitted
    if ( !isset($_POST['title']) ) {
        return;
    }

    // Check that the nonce was set and valid
    if( !wp_verify_nonce($_POST['_wpnonce'], 'wps-frontend-post') ) {

        echo 'Did not save because your form seemed to be invalid. Sorry';

        //return;
    }

    // Do some minor form validation to make sure there is content
    if (strlen($_POST['title']) < 1) {

        echo 'Please enter a title.</br>';
    //  return;


    }
    if (strlen($_POST['content']) < 5) {

        echo 'Please enter content more than 5 characters in length.';
        return;
    }

    // load the post_exists function
    if ( ! function_exists( 'post_exists' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/post.php' );
}
    // Check if post title already exists
    if (post_exists($_POST['title']) != 0) {
      echo 'This title already exists. Please choose another title.';
      return;
    }

    // Add the content of the form to $post as an array
    $post = array(
        'post_title'    => $_POST['title'],
        'post_content'  => $_POST['content'],
        'post_status'   => 'publish',   // Could be: publish
        'post_type' 	=> 'post' // Could be: `page` or your CPT
    );

wp_insert_post($post);

    function Get_most_recent_permalink(){
        global $post;
        $tmp_post = $post;
        $args = array(
            'numberposts'     => 1,
            'offset'          => 0,
            'orderby'         => 'post_date',
            'order'           => 'DESC',
            'post_type'       => 'post',
            'post_status'     => 'publish' );
        $myposts = get_posts( $args );
        $permalink = get_permalink($myposts[0]->ID);
        $post = $tmp_post;
        return $permalink;
    }
    ob_start();
    echo ('<a href="');
    echo (Get_most_recent_permalink());
    echo('">View Post</a>');
    $outputr = ob_get_contents();

  //return $outputr;
}

function naq_posts() {
  $args = [
    'numberposts' => 99999,
    'post_type' => 'post'
  ];

  $posts = get_posts($args);
  $data = [];

  $i = 0;
  foreach($posts as $post) {
    // $data[$i]['id'] = $post->ID;
    //$data[$i]['title'] = $post->post_title;
    // $data[$i]['content'] = $post->post_content;
     $data[$i]['slug'] = $post->post_name;
    $i++;
  }

  return $data;
}

function naq_post( $slug ) {
  $args = [
    'name' => $slug['slug'],
    'post_type' => 'post'
  ];

  $post = get_posts($args);

  $data['slug'] = $post[0]->post_name;

  return $data;
}

add_action('rest_api_init', function() {
  register_rest_route('naq/v1', 'posts', [
    'methods' => 'GET',
    'callback' => 'naq_posts',
  ]);

register_rest_route ('naq/v1', 'posts/(?P<slug>[a-zA-Z0-9-]+)', array (
  'methods' => 'GET',
  'callback' => 'naq_post',

) );

});

add_shortcode ('flx_post_shortcode', 'example_function');

function my_handle_shortcode() {
  //wp_enqueue_script( 'my-jss', 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js', true );
  wp_enqueue_script( 'my-js', '/wp-content/plugins/FiloxPoster/inc/js/main.js', true );
  wp_localize_script('my-js', 'WPURLS', array( 'siteurl' => get_option('siteurl') ));
}
add_action( 'wp_enqueue_scripts', 'my_handle_shortcode' );
