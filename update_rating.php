<?php 

include 'wp-load.php';

//delete from `wp_comments` WHERE `comment_post_ID` not in ( select ID from wp_posts);

//delete from wp_commentmeta WHERE comment_id not in ( select `comment_ID` from wp_comments);

$query = "update wp_posts p SET `average_rating` = (
			select (sum(meta_value) / 5) as total from wp_comments c inner join wp_commentmeta cm
			on (c.comment_ID = cm.comment_id) where comment_post_ID = p.ID AND meta_key = 'edd_rating' AND comment_approved = '1'
		  )";

$wpdb->query($query);

echo "done";
?>