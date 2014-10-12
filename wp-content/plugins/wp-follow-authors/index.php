<?php

/*
  Plugin Name:  WP Follow Authors
  Version: 1.0
  Plugin URI: http://wp.tutsplus.com/tutorials/simple-wordpress-plugin-to-follow-your-favorite-authors
  Description: Get email notifications when your favorite author publishes a post.
  Author URI: http://www.innovativephp.com
  Author: Rakhitha Nimesh
  License: GPL2
 */

function wp_authors_tbl_create() {

    global $wpdb;

    $wpdb->query("DROP TABLE IF EXISTS wp_author_subscribe");

    $sql1 = "CREATE TABLE wp_author_subscribe (
 id int(11) NOT NULL AUTO_INCREMENT,
 activation_code varchar(255) NOT NULL,
 email varchar(75) NOT NULL,
 status int(11) NOT NULL,
 followed_authors text NOT NULL,
 PRIMARY KEY (id)
)ENGINE=InnoDB AUTO_INCREMENT=1;";


    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($sql1);



    $sql2 = ("CREATE TABLE wp_author_followers (
 id int(11) NOT NULL AUTO_INCREMENT,
 author_id int(11) NOT NULL,
 followers_list text NOT NULL,
 PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1;");

    dbDelta($sql2);
}

register_activation_hook(__FILE__, 'wp_authors_tbl_create');


add_shortcode("contributors", "contributors");
function contributors() {
    global $wpdb;

    $confirmedEmail = '';

    if (isset($_GET['confirm-follow'])) {

        $activationCode = $_GET['confirm-follow'];

        $activationCheck = $wpdb->get_results($wpdb->prepare("select * from wp_author_subscribe where
                activation_code='$activationCode' and status=0 "));



        if (count($activationCheck) != 0) {
            $activationResult = $wpdb->query($wpdb->prepare("update wp_author_subscribe set status=1 where activation_code='$activationCode'"));
            if ($activationResult) {

                $confirmedEmail = $activationCheck[0]->email;

                $actStatus = "Activation Successfull";
                $actClass = "success";
            } else {
                $actStatus = "Activation Failed";
                $actClass = "error";
            }
        }
    }


    $authors = get_users();

    $authorsList = '<div id="wp_authors_list">';
    foreach ($authors as $author) {
        if (user_can($author->ID, 'publish_posts')) {
            $authorsList .= '<div class="auth_row">
                            <div class="auth_image">' . get_avatar($author->ID) . '</div>
                            <div class="auth_info">
                                <p class="title">' . get_the_author_meta('display_name', $author->ID) . '</p>
                                <p class="desc">' . get_the_author_meta('description', $author->ID) . '</p>
                            </div>
                            <div class="auth_follow"><input type="button" class="follow" value="Follow" data-author="' . $author->ID . '" /></div>
                            <div class="frm_cls"></div>
                        </div>';
        }
    }

    $authorsList .= '</div>';

    $output = '<div id="wp_authors_panel">
                    <div id="wp_authors_head">Follow WP Authors</div>
                    <div id="wp_authors_form">
                        <div class="frm_row">
                            <div id="frm_msg" class="' . $actClass . '">' . $actStatus . '</div>
                            <div class="frm_label">Enter Your Email</div>
                            <div class="frm_field"><input type="text" name="user_email" id="user_email" value="' . $confirmedEmail . '" /></div>
                            <div class="frm_cls"></div>
                        </div>
                        <div class="frm_row">
                            <div class="frm_label">&nbsp;</div>
                            <div class="frm_control"><input type="button" value="Subscribe" id="subscribeAuthors" /></div>
                            <div class="frm_control"><input type="button" value="Load" id="loadFollowers" /></div>
                            <div class="frm_cls"></div>
                        </div>
                    </div>
                    
                    ' . $authorsList . '


               </div>';

    echo $output;
}


add_action('wp_enqueue_scripts', 'apply_wp_author_scripts');
function apply_wp_author_scripts() {

    wp_enqueue_script('jquery');
    /* needs to be fixed plugin url and function name */
    wp_register_script('followjs', plugins_url('follow.js', __FILE__));
    wp_enqueue_script('followjs');

    wp_register_style('followCSS', plugins_url('follow.css', __FILE__));
    wp_enqueue_style('followCSS');


    $config_array = array('ajaxUrl' => admin_url('admin-ajax.php'), 'ajaxNonce' => wp_create_nonce('follow-nonce'),
        'currentURL' => $_SERVER['REQUEST_URI']);
    wp_localize_script('followjs', 'ajaxData', $config_array);
}


add_action('wp_ajax_nopriv_subscribe_to_wp_authors', 'subscribe_to_wp_authors');
add_action('wp_ajax_subscribe_to_wp_authors', 'subscribe_to_wp_authors');
function subscribe_to_wp_authors() {
    global $wpdb;

    $ajaxNonce = $_POST['nonce'];

    if (wp_verify_nonce($ajaxNonce, 'follow-nonce')) {

        $subscriber_email = isset($_POST['email']) ? $_POST['email'] : '';
        if (is_email($subscriber_email)) {

            $email_result = $wpdb->get_results($wpdb->prepare("select * from wp_author_subscribe where
                email='$subscriber_email'"));

            if (count($email_result) == '0') {
                $activation_code = generate_random_act_code();

                $result = $wpdb->query($wpdb->prepare("INSERT INTO wp_author_subscribe (email,activation_code,status)
                                        VALUES ( %s, %s, %s )", $subscriber_email, $activation_code, "unsubscribed"));

                $activation_link = add_query_arg('confirm-follow', $activation_code, get_site_url() . $_POST['url']);


                if ($result) {

                    if (wp_mail($subscriber_email, "WP Author Subscription", "Click $activation_link to activate.")) {
                        echo json_encode(array("success" => "Please check email for activation link."));
                    } else {
                        echo json_encode(array("error" => "Email Error."));
                    }
                }
            } else {
                echo json_encode(array("error" => "Email already exists."));
            }
        } else {
            echo json_encode(array("error" => "Please enter valid Email"));
        }
    }
    die();
}

function generate_random_act_code() {

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $actCode = '';
    for ($i = 0; $i < 15; $i++) {
        $actCode .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $actCode;
}


add_action('wp_ajax_nopriv_follow_wp_authors', 'follow_wp_authors');
add_action('wp_ajax_follow_wp_authors', 'follow_wp_authors');
function follow_wp_authors() {
    global $wpdb;

    $ajaxNonce = $_POST['nonce'];

    if (wp_verify_nonce($ajaxNonce, 'follow-nonce')) {

        $subscriberEmail = isset($_POST['email']) ? $_POST['email'] : '';
        if (is_email($subscriberEmail)) {

            $emailResult = $wpdb->get_results($wpdb->prepare("select * from wp_author_subscribe where
                email='$subscriberEmail' and status=1",""));

            if (count($emailResult) == '1') {

                $subscriberID = $emailResult[0]->id;
                $authorId = isset($_POST['author_id']) ? $_POST['author_id'] : '';

                /*
                 * Check if author exists and insert if not already available to follow
                 */
                $authorResult = $wpdb->get_results($wpdb->prepare("select * from wp_author_followers where
                author_id='$authorId'"));


                if (count($authorResult) == '0') {
                    $result = $wpdb->query($wpdb->prepare("INSERT INTO wp_author_followers (author_id)
                                        VALUES ( %d)", $authorId));
                }

                /*
                 * Get Author List of the Current User
                 */
                $subscribedAuthorList = $emailResult[0]->followed_authors;
                if ($subscribedAuthorList != '') {
                    $subscribedAuthorList = explode(",", $subscribedAuthorList);
                } else {
                    $subscribedAuthorList = array();
                }



                if (!(in_array($authorId, $subscribedAuthorList))) {
                    array_push($subscribedAuthorList, $authorId);
                }






                /*
                 * Get current author info with authers subscribers
                 */
                $authorResult = $wpdb->get_results($wpdb->prepare("select * from wp_author_followers where
                author_id='$authorId'"));

                if (count($authorResult) == '1') {

                    if ($authorResult[0]->followers_list != '') {
                        $authorSubscribersArray = explode(",", $authorResult[0]->followers_list);
                    } else {
                        $authorSubscribersArray = array();
                    }

                    if (!(in_array($subscriberID, $authorSubscribersArray))) {
                        array_push($authorSubscribersArray, $subscriberID);
                    }

                    // User list who follows specific author
                    $followersList = implode(",", $authorSubscribersArray);

                    // Author list followed by specific user
                    $subscribedAuthorList = implode(",", $subscribedAuthorList);

                    $result = $wpdb->query($wpdb->prepare("update wp_author_followers set followers_list='$followersList' where
                                        author_id='$authorId'"));

                    $result = $wpdb->query($wpdb->prepare("update wp_author_subscribe set followed_authors='$subscribedAuthorList' where
                                        email='$subscriberEmail'"));

                    echo json_encode(array("status" => "success"));
                }
            } else {
                echo json_encode(array("error" => "Email already exists."));
            }
        } else {
            echo json_encode(array("error" => "Please enter valid Email"));
        }
    }

    die();
}


add_action('wp_ajax_nopriv_load_subscribed_authors', 'load_subscribed_authors');
add_action('wp_ajax_load_subscribed_authors', 'load_subscribed_authors');
function load_subscribed_authors() {
    global $wpdb;

    $ajaxNonce = $_POST['nonce'];

    if (wp_verify_nonce($ajaxNonce, 'follow-nonce')) {

        $subscriber_email = isset($_POST['email']) ? $_POST['email'] : '';
        if (is_email($subscriber_email)) {

            $email_result = $wpdb->get_results($wpdb->prepare("select * from wp_author_subscribe where
                email='$subscriber_email' and status=1 "));



            if (count($email_result) == '1') {

                $subscriberID = $email_result[0]->id;


                $authorResult = $wpdb->get_results($wpdb->prepare("select * from wp_author_subscribe where
                id=$subscriberID"));


                if (count($authorResult) != '0') {
                    $userFollowedAuthors = $authorResult[0]->followed_authors;
                    $userFollowedAuthors = explode(",", $userFollowedAuthors);
                    echo json_encode(array("authors" => $userFollowedAuthors));
                }
            }
        } else {
            echo json_encode(array("error" => "Please enter valid Email"));
        }
    }


    die();
}



add_action('wp_ajax_nopriv_unfollow_wp_authors', 'unfollow_wp_authors');
add_action('wp_ajax_unfollow_wp_authors', 'unfollow_wp_authors');
function unfollow_wp_authors() {

    global $wpdb;

    $ajaxNonce = $_POST['nonce'];

    if (wp_verify_nonce($ajaxNonce, 'follow-nonce')) {

        $subscriberEmail = isset($_POST['email']) ? $_POST['email'] : '';
        if (is_email($subscriberEmail)) {

            $emailResult = $wpdb->get_results($wpdb->prepare("select * from wp_author_subscribe where
                email='$subscriberEmail' and status=1 "));



            if (count($emailResult) == '1') {

                $subscriberID = $emailResult[0]->id;
                $authorId = isset($_POST['author_id']) ? $_POST['author_id'] : '';

                /*
                 * Get Author List of the Current User
                 */
                $subscribedAuthorList = $emailResult[0]->followed_authors;
                if ($subscribedAuthorList != '') {
                    $subscribedAuthorList = explode(",", $subscribedAuthorList);
                } else {
                    $subscribedAuthorList = array();
                }



                foreach ($subscribedAuthorList as $key => $value) {
                    if ($authorId == $value) {
                        unset($subscribedAuthorList[$key]);
                    }
                }


                /*
                 * Get current author info with authers subscribers
                 */
                $authorResult = $wpdb->get_results($wpdb->prepare("select * from wp_author_followers where
                author_id='$authorId'"));

                if (count($authorResult) == '1') {

                    if ($authorResult[0]->followers_list != '') {
                        $authorSubscribersArray = explode(",", $authorResult[0]->followers_list);
                    } else {
                        $authorSubscribersArray = array();
                    }


                    foreach ($authorSubscribersArray as $key => $value) {
                        if ($subscriberID == $value) {
                            unset($authorSubscribersArray[$key]);
                        }
                    }




                    // User list who follows specific author
                    $followersList = implode(",", $authorSubscribersArray);

                    // Author list followed by specific user
                    $subscribedAuthorList = implode(",", $subscribedAuthorList);

                    $result = $wpdb->query($wpdb->prepare("update wp_author_followers set followers_list='$followersList' where
                                        author_id='$authorId'"));

                    $result = $wpdb->query($wpdb->prepare("update wp_author_subscribe set followed_authors='$subscribedAuthorList' where
                                        email='$subscriberEmail'"));

                    echo json_encode(array("status" => "success"));
                }
            } else {
                echo json_encode(array("error" => "Email already exists."));
            }
        } else {
            echo json_encode(array("error" => "Please enter valid Email"));
        }
    }

    die();
}



/*
 * Send Emails when a post is published
 */
add_action('new_to_publish', 'notify_author_followers');
add_action('draft_to_publish', 'notify_author_followers');
add_action('pending_to_publish', 'notify_author_followers');
add_action('future_to_publish', 'notify_author_followers');
function notify_author_followers($post) {
    global $wpdb;

    $publishedPostAuthor = $post->post_author;

    $authorDisplayName = get_the_author_meta('display_name', $publishedPostAuthor);


    $authorsFollowers = $wpdb->get_results($wpdb->prepare("select * from wp_author_followers where
                author_id='$publishedPostAuthor'"));

    if (count($authorsFollowers) == '1') {
        $authorsFollowersList = $authorsFollowers[0]->followers_list;

        if ($authorsFollowersList != '') {
            $sql = "select email from wp_author_subscribe where id in($authorsFollowersList)";
            $authorsFollowersEmails = $wpdb->get_results($wpdb->prepare($sql));

            $bccList = '';

            foreach ($authorsFollowersEmails as $key => $emailObject) {
                $bccList .= $emailObject->email . ",";
            }

            $bccList = substr($bccList, 0, -1);

            $postMessage = "<a href='$post->guid'><h2>$post->post_title</h2>";


            $emailHeaders .= "From: WP Follow Authors <example@wpauthorplugins.com>" . "\r\n";
            $emailHeaders .= "Bcc: $bccList" . "\r\n";

            add_filter("wp_mail_content_type", create_function("", 'return "text/html";'));
            if (wp_mail("admin@example.com", "New Post From $authorDisplayName", $postMessage, $emailHeaders)) {
                
            }
        }
    }

    die();
}

