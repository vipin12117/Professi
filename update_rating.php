<?php 

include 'wp-load.php';

$query = "update wp_posts p SET `average_rating` = (
			select sum(meta_value) / 5 from wp_comments c inner join wp_commentmeta cm
			on (c.comment_ID = cm.comment_id) where comment_post_ID = p.ID
			where meta_key = 'edd_rating'
		  )";

$wpdb->query($query);

echo "done";
?>