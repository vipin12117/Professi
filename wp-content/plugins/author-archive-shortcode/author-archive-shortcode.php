<?php
/*
 Plugin Name: Author Archive Short Code
 Plugin URI: http://pippinsplugins.com/creating-a-short-code-to-show-a-detailed-list-of-blog-authors
 Description: Adds a short code that allows you to display a list of all authors on your site, along with their gravatars, bios and author archive links
 Author: Pippin Williamson
 Contributors: mordauk
 Author URI: http://pippinsplugins.com
 Version: 1.0
 */

function pippin_list_authors() {
	$authors = get_users(array(
			'orderby' => 'display_name',
			'count_totals' => false,
			'who' => 'shop_vendor'));

	$list = '';

	if($authors) {
		wp_enqueue_style('author-list', plugin_dir_url(__FILE__) . '/css/author-list.css');

		$list .= '<div class="edd_downloads_list row download-grid-wrapper columns-3 edd_download_columns_3" data-columns="3">';

		foreach($authors as $author) {
			$list .= '<div class="col-md-4"><div id="edd_download_65" class="edd_download content-grid-download" style="" itemtype="http://schema.org/Product" itemscope="">';

			$archive_url = str_replace( 'vendor', 'fes-vendor', marketify_edd_fes_author_url( $author->ID ) );
			$list .= '<div class="edd_download_inner"><div class="entry-image">';
			$list .= get_avatar($author->user_email , 1024);
			$list .= "</div>";
			$list .= '<header class="entry-header">
						<h1 class="entry-title"><a rel="bookmark" href="'.$archive_url.'">'.$author->display_name.'</a></h1>
						<div class="entry-meta">
							<span class="byline"> by <span class="user"><span class="author vcard"><a title="View all Downloads by '.$author->display_name.'" href="'.$archive_url.'" class="url fn n">'.$author->display_name.' <img width="50" height="50" class="avatar avatar-50 photo" src="http://1.gravatar.com/avatar/ff900f2f6ab5c8627682b9f497672858?s=50&amp;d=http%3A%2F%2F1.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536%3Fs%3D50&amp;r=G" alt=""></a></span></span></span>							
						</div>
					 </header></div>';
			$list .= '</div><div style="clear:both;"></div></div>';
		}

		$list .= '</div>';
	}

	return $list;
}

add_shortcode('authors', 'pippin_list_authors');