<?php
/**
 * Output HTML markup of template with passed args
 *
 * @param string $file File name without extension (.php)
 * @param array $args Array with args ($key=>$value)
 * @param string $default_folder Requested file folder
 *
 * */
function show_template( $file, $args = null, $default_folder = 'parts' ) {
	echo return_template( $file, $args, $default_folder );
}

/**
 * Return HTML markup of template with passed args
 *
 * @param string $file File name without extension (.php)
 * @param array $args Array with args ($key=>$value)
 * @param string $default_folder Requested file folder
 *
 * @return string template HTML
 * */
function return_template( $file, $args = null, $default_folder = 'parts' ) {
	$file = $default_folder . '/' . $file . '.php';
	if ( $args ) {
		extract( $args );
	}
	if ( locate_template( $file ) ) {
		ob_start();
		include( locate_template( $file ) ); //Theme Check free. Child themes support.
		$template_content = ob_get_clean();

		return $template_content;
	}

	return '';
}

/**
 * Get Post Featured image
 *
 * @return string Post featured image url
 * @var string $size = 'full' featured image size
 *
 * @var int $id Post id
 */
function get_attached_img_url( $id = 0, $size = "medium_large" ) {
	$img = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), $size );

	return $img[0];
}

/**
 * Dynamic admin function
 *
 * @return void
 * @var int $post_id Post id
 *
 * @var string $column_name Column id
 */
function template_detail_field_for_page( $column_name, $post_id ) {
	if ( $column_name == 'template' ) {
		$template_name = str_replace( '.php', '', get_post_meta( $post_id, '_wp_page_template', true ) );
		echo '<span style="text-transform: capitalize;">' . str_replace( array(
				'template-',
				'/',
			), '', substr( $template_name, strpos( $template_name, '/' ), strlen( $template_name ) ) ) . ' Page</span>';
	}

	return;
}

/**
 * Output background image style
 *
 * @param array|string $img Image array or url
 * @param string $size Image size to retrieve
 * @param bool $echo Whether to output the the style tag or return it.
 *
 * @return string|void String when retrieving.
 */
function bg( $img = '', $size = '', $echo = true ) {

	if ( empty( $img ) ) {
		return false;
	}

	if ( is_array( $img ) ) {
		$url = $size ? $img['sizes'][ $size ] : $img['url'];
	} else {
		$url = $img;
	}

	$string = 'style="background-image: url(' . $url . ')"';

	if ( $echo ) {
		echo $string;
	} else {
		return $string;
	}
}

/**
 * Format phone number, trim all unnecessary characters
 *
 * @param string $string Phone number
 *
 * @return string Formatted phone number
 */
function sanitize_number( $string ) {
	return preg_replace( '/[^+\d]+/', '', $string );
}

/**
 * Convert file url to path
 *
 * @param string $url Link to file
 *
 * @return bool|mixed|string
 */

function convert_url_to_path( $url ) {
	if ( ! $url ) {
		return false;
	}
	$url       = str_replace( array( 'https://', 'http://' ), '', $url );
	$home_url  = str_replace( array( 'https://', 'http://' ), '', site_url() );
	$file_part = WP_CONTENT_DIR . str_replace( $home_url . '/wp-content', '', $url );
	$file_part = str_replace( '//', '/', $file_part );

	if ( file_exists( $file_part ) ) {
		return $file_part;
	}

	return false;
}

/**
 * Return/Output SVG as html
 *
 * @param array|string $img Image link or array
 * @param string $class Additional class attribute for img tag
 * @param string $size Image size if $img is array
 *
 * @return void
 */
function display_svg( $img, $class = '', $size = 'medium' ) {
	echo return_svg( $img, $class, $size );
}

function return_svg( $img, $class = '', $size = 'medium' ) {
	if ( ! $img ) {
		return '';
	}

	$file_url = is_array( $img ) ? $img['url'] : $img;

	$file_info = pathinfo( $file_url );
	if ( $file_info['extension'] == 'svg' ) {
		$file_path = convert_url_to_path( $file_url );

		if ( ! $file_path ) {
			return '';
		}


		$image = get_file_content( $file_path );
		if ( $class ) {
			$image = str_replace( '<svg ', '<svg class="' . esc_attr( $class ) . '" ', $image );
		}
		$image = preg_replace( '/^(.*)?(<svg.*<\/svg>)(.*)?$/is', '$2', $image );

	} elseif ( is_array( $img ) ) {
		$image = wp_get_attachment_image( $img['id'], $size, false, array( 'class' => $class ) );
	} else {
		$image = '<img class="' . esc_attr( $class ) . '" src="' . esc_url( $img ) . '" alt="' . esc_attr( $file_info['filename'] ) . '"/>';
	};

	return $image;
}

/**
 * @param $file_path
 *
 * @return false|string
 */
function get_file_content( $file_path ) {
	$arrContextOptions = array(
		"ssl" => array(
			"verify_peer"      => false,
			"verify_peer_name" => false,
		),
	);

	return file_get_contents( $file_path, false, stream_context_create( $arrContextOptions ) );
}

/**
 * Check if URL is YouTube or Vimeo video
 *
 * @param string $url Link to video
 *
 * @return bool
 */
function is_embed_video( $url ) {
	if ( ! $url ) {
		return false;
	}
	$protocol   = '(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?';
	$url_path   = '(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))';
	$video_id   = "([^\?&\"'>]+)";
	$yt_pattern = "#^{$protocol}{$url_path}{$video_id}#";

	$vimeo_pattern = '#^https?://(.+\.)?vimeo\.com/.*#';

	$is_vimeo   = ( preg_match( $vimeo_pattern, $url ) );
	$is_youtube = ( preg_match( $yt_pattern, $url ) );

	return ( $is_vimeo || $is_youtube );
}

/**
 * Get SVG real size (width+height / viewbox) and use it in <img> width, height attr
 *
 * @param array|false $image Either array with src, width & height, icon src, or false.
 * @param int $attachment_id Image attachment ID.
 * @param string|array $size Size of image. Image size or array of width and height values
 *                                    (in that order). Default 'thumbnail'.
 * @param bool $icon Whether the image should be treated as an icon. Default false.
 *
 * @return array
 */
function fix_wp_get_attachment_image_svg( $image, $attachment_id, $size, $icon ) {
	if ( is_array( $image ) && preg_match( '/\.svg$/i', $image[0] ) ) {
		if ( is_array( $size ) ) {
			$image[1] = $size[0];
			$image[2] = $size[1];
		} elseif ( ( $xml = simplexml_load_file( $image[0] ) ) !== false ) {
			$attr     = $xml->attributes();
			$viewbox  = explode( ' ', $attr->viewBox );
			$image[1] = isset( $attr->width ) && preg_match( '/\d+/', $attr->width, $value ) ? (int) $value[0] : ( count( $viewbox ) == 4 ? (int) $viewbox[2] : null );
			$image[2] = isset( $attr->height ) && preg_match( '/\d+/', $attr->height, $value ) ? (int) $value[0] : ( count( $viewbox ) == 4 ? (int) $viewbox[3] : null );
		} else {
			$image[1] = $image[2] = null;
		}
	}

	return $image;
}

add_filter( 'wp_get_attachment_image_src', 'fix_wp_get_attachment_image_svg', 10, 4 );

/**
 * Show link from acf link field
 *
 * @param $acf_link
 * @param string $class
 * @param array $atts
 */
function acf_link( $acf_link, $class = '', $atts = array(), $echo = true ) {
	if ( ! $acf_link || empty( $acf_link['url'] ) ) {
		return null;
	}
	$attr_str = '';
	if ( $atts ) {
		foreach ( $atts as $k => $v ) {
			if ( $k == 'class' ) {
				$v .= ' ' . $class;
			}
			$attr_str .= $k . '="' . $v . '"';
		}
	}

	if ( empty( $atts['class'] ) && $class ) {
		$attr_str .= 'class="' . $class . '" ';
	}

	if ( $acf_link['target'] == '_blank' ) {
		$attr_str .= 'target="_blank" ';
	}

	$link = "<a href='{$acf_link['url']}' {$attr_str} >{$acf_link['title']}</a>";

	if ( $echo ) {
		echo $link;
	} else {
		return $link;
	}
}

/**
 * Get link URL for share button in social media
 *
 * @param string $network Social network name: facebook, twitter, linkedin, pinterest,
 * @param int $post_id Post ID that need to be shared
 *
 * @return string
 */

function get_share_link_url( $post_id = null, $network = 'facebook' ) {
	if ( empty( $post_id ) ) {
		return false;
	}
	$raw_post_title = get_the_title( $post_id );
	$raw_post_url   = get_the_permalink( $post_id );
	$post_title     = urlencode( $raw_post_title );
	$post_url       = urlencode( $raw_post_url );
	$thumb_url      = has_post_thumbnail( $post_id ) ? urlencode( wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'medium_large' )[0] ) : '';
	$share_url      = '';

	switch ( $network ) {
		case 'facebook':
			$share_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $post_url;
			break;
		case 'twitter':
			$share_url = "https://twitter.com/intent/tweet?url={$post_url}&text={$post_title}";
			break;
		case 'linkedin':
			$share_url = 'https://www.linkedin.com/cws/share?url=' . $post_url;
			break;
		case 'pinterest':
			$share_url = "http://pinterest.com/pin/create/button/?url={$post_url}&media={$thumb_url}&description={$post_title}";
			break;
		case 'tumblr':
			$share_url = "https://www.tumblr.com/share/link?url={$post_url}&name={$post_title}";
			break;
		case 'reddit':
			$share_url = "https://reddit.com/submit?url={$post_url}&title={$post_title}";
			break;

	}

	return $share_url;
}

/**
 * Get link to google map by location address
 *
 * @param $address
 *
 * @return string
 */
function get_address_url( $address = null ) {
	if( empty( $address ) ) {
		return '';
	}
	$address_format = urlencode( str_replace( array( "\r", "\n", ), '', wp_strip_all_tags( $address ) ) );

	return 'https://www.google.com/maps/place/' . $address_format;
}

/**
 * Get image markup with fallback to the placeholder image
 *
 * @param int $id Image ID
 * @param string $size Image size (thumbnail, medium, medium_large, large, large_high, 1536x1536)
 * @param array $atts List of additional image attributes
 */
function get_attachment_fallback( $id = '', $size = 'medium_large', $attr = [] ) {
	if ( is_array( $id ) ) {
		$id = $id['id'];
	}

	$wp_img = wp_get_attachment_image( $id, $size, false, $attr );

	if ( ! $wp_img ) {
		$wp_img = '<img src="' . IMAGE_PLACEHOLDER . '" width="1280" height="800" alt=""';

		if ( $attr ) {
			$remove_keys = array( 'width', 'height', 'alt' );
			foreach ( $remove_keys as $key ) {
				if ( ! empty( $attr[ $key ] ) ) {
					unset( $attr[ $key ] );
				}
			}

			$attr = array_map( 'esc_attr', $attr );
			foreach ( $attr as $name => $value ) {
				$wp_img .= " $name=" . '"' . $value . '"';
			}
		}

		$wp_img .= ' />';
	}

	return $wp_img;
}

/**
 * Permalink ADA label
 *
 * @param WP_Post|int $post_id
 *
 * @return string|void
 */
function permalink_label( $post_id ) {
	return esc_attr( sprintf( __( 'Permalink to %s', 'default' ), the_title_attribute( [ 'echo' => false, 'post' => $post_id ] ) ) );
}

/**
 * Generate ACF gutenberg block css classes
 *
 * @param string $class Custom block CSS Classes
 * @param array $block ACF Block
 *
 * @return string
 */
function starter_section_class( $class, $block ) {
	$class = empty( $class ) ? '' : $class;

	if ( empty( $block ) ) {
		return $class;
	}
	// Additional CSS class
	$class .= ! empty( $block['className'] ) ? $block['className'] : '';
	// Block alignment (row width)
	$class .= ! empty( $block['align'] ) ? ' align' . $block['align'] : '';
	// Vertical alignment
	$class .= ! empty( $block['alignContent'] ) ? ' is-vertically-aligned-' . $block['alignContent'] : '';
	// Text alignment
	$class .= ! empty( $block['alignText'] ) ? ' has-text-align-' . $block['alignText'] : '';
	// Toggle 100vh block height
	$class .= ! empty( $block['fullHeight'] ) ? ' full-height' : '';
	// Block background color
	$class .= ! empty( $block['backgroundColor'] ) ? ' has-' . $block['backgroundColor'] . '-background-color' : '';
	// Block text color
	$class .= ! empty( $block['textColor'] ) ? ' has-' . $block['textColor'] . '-color' : '';
	// Block main text size
	$class .= ! empty( $block['fontSize'] ) ? ' has-' . $block['fontSize'] . '-font-size' : '';

	return $class;
}

/**
 * Generate ACF gutenberg block css styles
 *
 * @param array $block ACF Block
 *
 * @return string
 */
function starter_section_style( $block ) {
	if ( empty( $block ) ) {
		return '';
	}

	$styles     = '';
	$block_type = WP_Block_Type_Registry::get_instance()->get_registered( $block['name'] );
	$padding    = starter_wp_spacing_get_css_variable_inline_style( $block_type, $block, 'padding' );
	$margin     = starter_wp_spacing_get_css_variable_inline_style( $block_type, $block, 'margin' );

	// Block Padding 
	if ( $padding ) {
		$styles .= implode( ' ', $padding );
	}

	// Block margin
	if ( $margin ) {
		$styles .= implode( ' ', $margin );
	}

	return $styles ?? '';
}

/**
 * Generate spacing styles for custom block
 * 
 * @param WP_Block_Type $block_type
 * @param array $attributes
 * @param string $css_property
 *
 * @return array|string|void
 */

function starter_wp_spacing_get_css_variable_inline_style( $block_type, $attributes, $css_property ) {
	$feature             = 'spacing';
	$has_spacing_support = block_has_support( $block_type, array( $feature, $css_property ), false );

	if ( $has_spacing_support ) {
		$style_value = _wp_array_get( $attributes, array( 'style', $feature, $css_property ), null );

		if ( ! $style_value ) {
			return;
		}

		if ( is_array( $style_value ) ) {
			foreach ( $style_value as $key => $value ) {
				if ( strpos( $value, "var:preset|{$feature}|" ) === false ) {
					$styles[] = sprintf( '%s-%s:%s;', $css_property, $key, $value );
				} else {
					$index_to_splice = strrpos( $value, '|' ) + 1;
					$slug            = substr( $value, $index_to_splice );

					$styles[] = sprintf( '%s-%s:var(--wp--preset--%s--%s);', $css_property, $key, $feature, $slug );
				}
			}
		}

		return $styles;
	}

	return '';
}

/**
 * Generate ACF gutenberg block attributes
 *
 * @param string $class Custom block CSS Classes
 * @param array $block ACF Block
 *
 * @return string
 */
function starter_block_attributes( $class, $block ) {
	if ( empty( $class ) || empty( $block ) ) {
		return '';
	}

	$atts = [
		'id'    => $block['anchor'] ?? '',
		'class' => starter_section_class( $class, $block ),
		'style' => starter_section_style( $block ),
	];

	$atts_string = '';
	foreach ( $atts as $name => $val ) {
		$atts_string .= "{$name}='{$val}' ";
	}

	return $atts_string;
}