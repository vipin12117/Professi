<?php

include "./wp-load.php";

//fetch all products download
$posts = $wpdb->get_results("select id from wp_posts where post_type = 'download'");
if($posts){
	foreach($posts as $post){
		$post_id = $post->id;
		
		print $post_id . "<br />";

		//get attchments
		$attchment = $wpdb->get_row("select id from wp_posts where post_type = 'attachment' and post_parent = '$post_id'");
		if($attchment){
			$post_meta = $wpdb->get_row("select  * from wp_postmeta where meta_key = '_thumbnail_id' and  post_id  = '$post_id'");
			
			if(!$post_meta){
				$post_attachmeta = $wpdb->get_row("select  * from wp_postmeta where meta_key = '_wp_attached_file' and  post_id  = '".$attchment->id."'");
				
				//Build an array of the POST data you want to send to the thumbifier.
				$data = array(
					  "token"     => "24D4453659B64C12E5A622A378AE512B",
					  "url"       => "http://profesi.growthlabs.ca/wp-content/uploads/".$post_attachmeta->meta_value,
					  "quality"   => "90",
					  "size"      => "600x600",
					  "reference" => "my_internal_file_db_id",
					  "page"      => "0",
					  "callback"  => "http://profesi.growthlabs.ca/save_preview.php",
				);

				//Build a HTTP Query from with the data you want to post (the above array)
				$postdata = http_build_query($data);
				//Set the options for the HTTP Stream to be a POST method and to contain your HTTP Query data (your POST data)
				$opts = array("http" =>
					array(
					    "method"  => "POST",
					    "header"  => "Content-type: application/x-www-form-urlencoded",
					    "content" => $postdata
					)
				);

				//Create a stream with the above options, which will make the file_get_contents perform a POST to the web service.
				$context  = stream_context_create($opts);
				//Now call the thumbify.me service as a POST with your given data.
				$result = file_get_contents("http://www.thumbify.me", false, $context);
				$data   = json_decode($result,true);
				
				if($data['status'] = 'success'){
					$wpdb->query("insert into wp_postmeta SET post_id = '$post_id' , meta_key = '_thumbnail_id'");
					$post_meta_id = mysql_insert_id();
					
					print $post_meta_id . "<br />";
					
					$guid = $data['payload'];
					$guid = str_ireplace(array("{","}"),"",$guid);
					$insert_query = "Insert into preview_requests SET post_id = '$post_id' , post_meta_id = '$post_meta_id' , guid = '$guid' , status = 0 , dateofmodification = now();";
					$wpdb->query($insert_query);
				}
			}
		}
	}
}

echo "done";