<?php
/*
 * Bookmark Template
 *
 */

if ( ! $cite ) {
	return;
}

?>


<section class="response <?php echo empty( $url ) ? 'p-bookmark-of' : 'u-bookmark-of'; ?> h-cite">
<header>
<?php
echo Kind_Taxonomy::get_before_kind( 'bookmark' );
if ( ! $embed ) {
	if ( ! empty( $cite['url'] ) ) {
		echo sprintf( '<a href="%1s" class="p-name u-url">%2s</a>', $cite['url'], $cite['name'] );
	} else {
		echo sprintf( '<span class="p-name">%1s</span>', esc_html( $cite['name'] ) );
	}
	if ( $author ) {
		echo ' ' . __( 'par', 'indieweb-post-kinds' ) . ' ' . $author;
	}
	if ( ! empty( $cite['publication'] ) ) {
		echo sprintf( ' <em>(<span class="p-publication">%1s</span>)</em>', esc_html( $cite['publication'] ) );
	}
}
?>
</header>
</section>

<?php
