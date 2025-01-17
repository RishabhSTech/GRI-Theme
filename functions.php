<?php
/**
 * OceanWP Child Theme Functions
 *
 * When running a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php100
 * file is included before the parent theme's file, so the child theme
 * functions will be used.
 *
 * Text Domain: oceanwp
 * @link http://codex.wordpress.org/Plugin_API
 *
 */

/**
 * Load the parent style.css file
 *
 * @link http://codex.wordpress.org/Child_Themes
 */

//* Coded by - Andrew Paul * //

function oceanwp_child_enqueue_parent_style() {

	// Dynamically get version number of the parent stylesheet (lets browsers re-cache your stylesheet when you update the theme).
	$theme   = wp_get_theme( 'OceanWP' );
	$version = $theme->get( 'Version' );

	// Load the stylesheet.
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'oceanwp-style' ), $version );
}	
	
add_action( 'wp_enqueue_scripts', 'oceanwp_child_enqueue_parent_style' );


add_shortcode( 'related_commentary_shortcode', 'related_commentary_relationship' );

function related_commentary_relationship() {
	ob_start();
	
	$posts = get_field('related_commentary'); // Add your ACF field in here

	if( $posts ): ?>
		<ul>
		<?php foreach( $posts as $p ): // variable must NOT be called $post (IMPORTANT) ?>
		    <li class="rc-style">
				<?php echo '<a target="_blank" href=" ' . get_permalink( $p->ID ); ?>"> <?php echo '<h6 class="rc-text">' . get_the_title( $p->ID ) . '</h6>' . '</a>'; echo wp_trim_words( $p->post_content, 40 ) . '<a target="_blank" class="rc-show"  href=" '  . get_permalink( $p->ID ) ;?>" style="color: #000000;"> Show more. </a> <br>		    
     <if ($p + 1 != $post): >
        <hr class='rc'>
    <endif ?>
				
		</li> 
		<?php endforeach; ?>
			
		</ul>
	<?php else : ?>
		<p>Coming Soon</p>
	<?php endif;

	return ob_get_clean();
}

add_filter('acf/fields/relationship/query', 'my_acf_fields_relationship_query', 10, 3);
function my_acf_fields_relationship_query( $args, $field, $post_id ) {

	$field['post_type'] = 'related-commentary-p';
	$args['orderby'] = 'post_date';
	$args['order'] = 'DESC';
	
    return $args;
}	


function get_post_count_term_id($term){
$term_by_id = get_term( $term );
return $term_by_id->count;
}

add_shortcode( 'footag', 'get_post_count_term_id2' );
function get_post_count_term_id2(){
	echo get_post_count_term_id('1651' );
}
add_shortcode( 'footag_insight', 'get_post_count_term_id73' );
function get_post_count_term_id73(){
	$term_id = get_queried_object_id();
	echo get_post_count_term_id('1657' );
}
function get_post_count_post_type($cpt) {
return wp_count_posts($cpt)->publish;
}

function get_post_count_slug_taxonomy($taxonomy) {
return wp_count_posts($taxonomy)->publish;
}

add_shortcode( 'jurisdiction_insight', 'get_post_count_term_taxonomy27' );
function get_post_count_term_taxonomy27(){
	echo get_post_count_slug_taxonomy('insight' );
}

add_shortcode( 'custom_mailto_title', 'custom_mailto_title' );

function custom_mailto_title( $atts ) {	
    return esc_attr( 'Title:&nbsp;&nbsp;' . get_the_title( get_the_ID() ) ) . '%20' .
	'&nbsp;&nbsp;|&nbsp;&nbsp;' . ( 'Link:&nbsp;&nbsp;' . get_permalink( get_the_ID() ) );	
}

function my_post_layout_class( $class ) {
	// Alter your layout
	if ( is_singular( 'insight' ) ) {
		$class = 'full-width';
	}
	// Return correct class
	return $class;
}
add_filter( 'ocean_post_layout_class', 'my_post_layout_class', 20 );

// Function to Count Taxonomy Easily Scaleable for Different Scenarios 
add_shortcode( 'taxonomy_count', 'get_taxonomy_count_shortcode' );
function get_taxonomy_count_shortcode($atts){
    $atts = shortcode_atts( array(
        'taxonomy' => '', // Will pass this value in shortcode to use this on big scale
    ), $atts );

    if (empty($atts['taxonomy'])) {
        return 'Please provide a taxonomy.';
    }

    $terms = get_terms( array(
        'taxonomy' => $atts['taxonomy'],
        'hide_empty' => false,
    ) );

    $term_count = count($terms);

    return $term_count;
}

function add_custom_post_type_filters() {
    global $typenow;

    // Define each post type with its respective taxonomy IDs and labels
    $post_type_taxonomies = [
        'art' => [
            'authority' => ['id' => 'authority', 'label' => 'Select Authority'],
            'work_area' => ['id' => 'work-areas-art', 'label' => 'Select Work Area'],
            'language' => ['id' => 'languages-art', 'label' => 'Select Language'],
            'jurisdiction' => ['id' => 'jurisdiction-art', 'label' => 'Select Jurisdiction'],
            'category' => ['id' => 'category-art', 'label' => 'Select Category'],
        ],
        'bill-tracker' => [
            'authority' => ['id' => 'bill-authority', 'label' => 'Select Authority'],
            'work_area' => ['id' => 'bill-work-areas', 'label' => 'Select Work Area'],
            'jurisdiction' => ['id' => 'bill-jurisdiction', 'label' => 'Select Jurisdiction'],
            'category' => ['id' => 'bill-status-tracker', 'label' => 'Select Bill Status'],
        ],
        'webinar-tracker' => [
            'authority' => ['id' => 'authority-webinar', 'label' => 'Select Authority'],
            'work_area' => ['id' => 'work-area-webinar', 'label' => 'Select Work Area'],
            'language' => ['id' => 'languages-webinar', 'label' => 'Select Language'],
            'jurisdiction' => ['id' => 'jurisdiction-webinar', 'label' => 'Select Jurisdiction'],
            'category' => ['id' => 'category-webinar', 'label' => 'Select Category'],
        ],
        'legal-resources-post' => [
            'authority' => ['id' => 'authority_lr', 'label' => 'Select Authority'],
            'work_area' => ['id' => 'work-area', 'label' => 'Select Work Area'],
            'language' => ['id' => 'language', 'label' => 'Select Language'],
            'jurisdiction' => ['id' => 'jurisdiction', 'label' => 'Select Jurisdiction'],
            'category' => ['id' => 'topic', 'label' => 'Select Topic'],
        ],
        'library' => [
            'work_area' => ['id' => 'work-areas-library', 'label' => 'Select Work Area'],
            'language' => ['id' => 'language', 'label' => 'Select Language'],
            'jurisdiction' => ['id' => 'jurisdiction-library', 'label' => 'Select Jurisdiction'],
            'category' => ['id' => 'topic-library', 'label' => 'Select Topic'],
        ],
        'gdpr-et' => [
            'work_area' => ['id' => 'work-areas-gdpr', 'label' => 'Select Work Area'],
            'language' => ['id' => 'languages-gdpr', 'label' => 'Select Language'],
            'jurisdiction' => ['id' => 'juridiction-gdpr', 'label' => 'Select Jurisdiction'],
            'category' => ['id' => 'type-gdpr', 'label' => 'Select Type'],
        ]
    ];

    // Only proceed if the current post type has filters defined
    if (isset($post_type_taxonomies[$typenow])) {
        foreach ($post_type_taxonomies[$typenow] as $key => $taxonomy) {
            $terms = get_terms($taxonomy['id']);

            if ($terms && !is_wp_error($terms)) {
                echo '<select name="' . esc_attr($taxonomy['id']) . '" class="postform">';
                echo '<option value="">' . esc_html($taxonomy['label']) . '</option>';
                foreach ($terms as $term) {
                    printf(
                        '<option value="%1$s"%2$s>%3$s</option>',
                        esc_attr($term->slug),
                        (isset($_GET[$taxonomy['id']]) && $_GET[$taxonomy['id']] == $term->slug) ? ' selected="selected"' : '',
                        esc_html($term->name)
                    );
                }
                echo '</select>';
            }
        }
    }
}
add_action('restrict_manage_posts', 'add_custom_post_type_filters');


// Dashboard Work

function toggle_track_untrack() {
    // Ensure the user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'User is not logged in']);
        return;
    }

    // Validate and sanitize input
    $user_id = get_current_user_id();
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if (!$post_id || get_post_status($post_id) !== 'publish') {
        wp_send_json_error(['message' => 'Invalid post ID or post is not published']);
        return;
    }

    // Retrieve and update tracked posts
    $tracked_posts = get_user_meta($user_id, '_user_tracked_posts', true);

    if (!is_array($tracked_posts)) {
        $tracked_posts = [];
    }

    // Toggle the post
    $is_tracked = false;
    if (in_array($post_id, $tracked_posts)) {
        $tracked_posts = array_diff($tracked_posts, [$post_id]);
    } else {
        $tracked_posts[] = $post_id;
        $is_tracked = true;
    }

    // Optimize: Ensure unique, clean array and limit size
    $tracked_posts = array_slice(array_unique($tracked_posts), 0, 100); // Limit to 100 posts

    // Save updated tracked posts
    update_user_meta($user_id, '_user_tracked_posts', $tracked_posts);

    // Return success response
    wp_send_json_success([
        'tracked_posts' => $tracked_posts,
        'is_tracked'    => $is_tracked,
        'post_id'       => $post_id,
    ]);
}
add_action('wp_ajax_toggle_track_untrack', 'toggle_track_untrack');


// Add a custom field to the user profile to display tracked posts
function show_tracked_posts_in_user_profile( $user ) {
    $tracked_posts = get_user_meta( $user->ID, '_user_tracked_posts', true );

    // Display the tracked posts as a list
    echo '<h3>Tracked Posts</h3>';
    echo '<table class="form-table">';
    echo '<tr><th><label for="_user_tracked_posts">Tracked Posts</label></th>';
    echo '<td>';
    
    if ( !empty( $tracked_posts ) && is_array( $tracked_posts ) ) {
        echo '<ul>';
        foreach ( $tracked_posts as $post_id ) {
            $post_title = get_the_title( $post_id );
            echo '<li>' . esc_html( $post_title ) . ' (Post ID: ' . esc_html( $post_id ) . ')</li>';
        }
        echo '</ul>';
    } else {
        echo 'No tracked posts.';
    }

    echo '</td></tr></table>';
}
add_action( 'show_user_profile', 'show_tracked_posts_in_user_profile' );
add_action( 'edit_user_profile', 'show_tracked_posts_in_user_profile' );

// Save or remove the post ID in the user's tracked posts
function save_tracked_posts( $user_id ) {
    // Check if the data is being submitted correctly
    if ( !isset( $_POST['tracked_posts_nonce'] ) || !wp_verify_nonce( $_POST['tracked_posts_nonce'], 'save_tracked_posts' ) ) {
        return;
    }

    // Get the tracked posts from the form
    $tracked_posts = isset( $_POST['_user_tracked_posts'] ) ? array_map( 'intval', $_POST['_user_tracked_posts'] ) : [];

    // Update the user meta field
    update_user_meta( $user_id, '_user_tracked_posts', $tracked_posts );
}
add_action( 'personal_options_update', 'save_tracked_posts' );
add_action( 'edit_user_profile_update', 'save_tracked_posts' );


add_action('init', 'fetch_api_on_custom_url');
function fetch_api_on_custom_url() {
    if (isset($_GET['run_api']) && $_GET['run_api'] === 'true') {
        if (isset($_GET['post'])) {
            fetch_and_create_posts_from_api($_GET['post']);
        }
    }
}

function fetch_and_create_posts_from_api($api_key) {
    $api_urls = [
        'art' => 'https://crawlergri.vercel.app/crawl_art',
        'lr'  => 'https://crawlergri.vercel.app/crawl_lr',
        'bill' => 'https://crawlergri.vercel.app/crawl_bt',
        'webinar' => 'https://crawlergri.vercel.app/crawl_webinar',
		'et' => 'https://crawlergri.vercel.app/crawl_et',
		'read' => 'https://crawlergri.vercel.app/crawl_read'
    ];

    // Initialize log
    $log = get_option('api_fetch_log', ['success' => [], 'error' => []]);

    // Check if the API key exists in the API URLs
    if (!array_key_exists($api_key, $api_urls)) {
        $log['error'][] = "Invalid API key: $api_key";
        update_option('api_fetch_log', $log);
        return;
    }

    $api_url = $api_urls[$api_key];

    $response = wp_remote_get($api_url, array('timeout' => 15));
    
    // Check for errors in the request
    if (is_wp_error($response)) {
        $logs[] = 'Error: ' . $response->get_error_message();
        update_option('api_fetch_logs', $logs);
        return;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    
    function ensure_term_exists($terms, $taxonomy) {
        $term_ids = [];
        if (is_array($terms)) {
            foreach ($terms as $term) {
                $slug = sanitize_title($term);
                $term_data = term_exists($slug, $taxonomy);
                if (!$term_data) {
                    $term_data = wp_insert_term($term, $taxonomy, ['slug' => $slug]);
                }
                if (!is_wp_error($term_data)) {
                    $term_ids[] = intval($term_data['term_id']);
                }
            }
        } else {
            $slug = sanitize_title($terms);
            $term_data = term_exists($slug, $taxonomy);
            if (!$term_data) {
                $term_data = wp_insert_term($terms, $taxonomy, ['slug' => $slug]);
            }
            if (!is_wp_error($term_data)) {
                $term_ids[] = intval($term_data['term_id']);
            }
        }
        return $term_ids;
    }

    // Process data if available
    if (!empty($data['crawl_data'])) {
        foreach ($data['crawl_data'] as $item) {
            // Determine post type and taxonomy setup based on the Tracker
            switch (strtolower($item['Tracker'])) {
                case 'art':
                    $post_type = 'art';
                    $taxonomies = [
                        'authority' => 'authority',
                        'work_area' => 'work-areas-art',
                        'language' => 'languages-art',
                        'jurisdiction' => 'jurisdiction-art',
                        'category' => 'category-art',
						'status' => 'art-status-tracker',
                    ];
                    $meta_field = 'art_link';
                    break;

                case 'bt':
                    $post_type = 'bill-tracker';
                    $taxonomies = [
                        'authority' => 'bill-authority',
                        'work_area' => 'bill-work-areas',
                        'jurisdiction' => 'bill-jurisdiction',
						'category' => 'category-bill',
						'status' => 'bill-status-tracker',
                    ];
                    $meta_field = 'bill-view-link';
                    break;

                case 'webinar':
                    $post_type = 'webinar-tracker';
                    $taxonomies = [
                        'authority' => 'authority-webinar',
                        'work_area' => 'work-area-webinar',
                        'language' => 'languages-webinar',
                        'jurisdiction' => 'jurisdiction-webinar',
                        'category' => 'category-webinar',
						'status' => 'webinar-status-tracker',
                    ];
                    $meta_field = 'webinar-view-link';
                    break;

                case 'lr':
                    $post_type = 'legal-resources-post';
                    $taxonomies = [
                        'authority' => 'authority_lr',
                        'work_area' => 'work-area',
                        'language' => 'language',
                        'jurisdiction' => 'jurisdiction',
                        'category' => 'topic',
						'status' => 'legal-status-tracker',
                    ];
                    $meta_field = 'link_lr';
                    break;

                case 'article':
                    $post_type = 'library';
                    $taxonomies = [
                        'work_area' => 'work-areas-library',
                        'language' => 'language',
                        'jurisdiction' => 'jurisdiction-library',
                        'category' => 'topic-library',
						'status' => 'library-status-tracker',
                    ];
                    break;

                case 'et':
                    $post_type = 'gdpr-et';
                    $taxonomies = [
                        'authority' => 'authority-gdpr',
                        'work_area' => 'work-areas-gdpr',
                        'language' => 'languages-gdpr',
                        'jurisdiction' => 'juridiction-gdpr',
                        'category' => 'type-gdpr',
						'status' => 'gdpr-status-tracker',
                    ];
                    $meta_field = 'source-et';
                    break;

                default:
                    continue;
            }

            // Prepare post content
           if ($item['Tracker'] === 'ARTICLE') {
				$post_content = !empty($item['article_post']) ? $item['article_post'] : '';
			} else {
				$post_content = !empty($item['extracted_doc']) ? $item['extracted_doc'] : '';
			}
			
            $post_title = !empty($item['title']) ? $item['title'] : 'Untitled Post';
            $released_date = '';

			if (!empty($item['date'])) {
				$date_parts = explode('/', $item['date']); 
				if (count($date_parts) === 3) {
					// Format the date to store in ACF as 'YYYY-MM-DD'
					$released_date = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
				}
			}

            // Check if a post with the same title exists to avoid duplicates
            $existing_post = get_page_by_title($post_title, OBJECT, $post_type);

            if (!$existing_post) {
                // Insert post into WordPress
                $post_id = wp_insert_post([
                    'post_title'   => $post_title,
                    'post_content' => $post_content,
                    'post_status'  => 'publish',
                    'post_date'    => current_time('mysql'),
                    'post_type'    => $post_type,
                    'meta_input'   => [
                        $meta_field => $item['link'],
                        'short_summary' => $item['short_summary'],
                        'indepth_summary' => $item['indepth_summary'],
						'released_date'      => $released_date,
                    ]
                ]);

                if ($post_id) {
                    $log['success'][] = "Post created: $post_title (ID: $post_id)";

                    // Assign taxonomies (terms)
                    foreach (['authority', 'category', 'jurisdiction', 'language', 'work_area', 'status'] as $tax) {
                        if (!empty($item[$tax])) {
                            $term_ids = ensure_term_exists($item[$tax], $taxonomies[$tax]);
                            wp_set_post_terms($post_id, $term_ids, $taxonomies[$tax], true);
                        }
                    }
					
					if (in_array($post_type, ['art', 'gdpr-et', 'bill-tracker', 'webinar-tracker'])) {
                        $tracker_term_map = [
                            'art' => 'News',
                            'gdpr-et' => 'Enforcement',
                            'bill-tracker' => 'Bill',
                            'webinar-tracker' => 'Webinar',
                        ];
                        $tracker_term_name = $tracker_term_map[$post_type];
                        $tracker_term_ids = ensure_term_exists($tracker_term_name, 'tracker-post-type');
                        wp_set_post_terms($post_id, $tracker_term_ids, 'tracker-post-type', true);
                    }
                } else {
                    $log['error'][] = "Failed to create post: $post_title";
                }
            } else {
                $log['error'][] = "Post already exists: $post_title";
            }
        }
    } else {
        $log['error'][] = 'No data found in API response.';
        update_option('api_fetch_log', $log);
    }

    // Save logs to the WordPress options table
    update_option('api_fetch_log', $log);
}

add_action('admin_menu', 'register_api_fetch_log_page');
function register_api_fetch_log_page() {
    add_options_page(
        'API Fetch Log',           // Page title
        'API Fetch Log',           // Menu title
        'manage_options',          // Capability required
        'api-fetch-log',           // Menu slug
        'display_api_fetch_log'    // Function to display the log
    );
}

function clear_api_fetch_log() {
    update_option('api_fetch_log', ['success' => [], 'error' => []]);
}

// Function to display the log
function display_api_fetch_log() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Check if the clear log button was clicked and verify nonce
    if (isset($_POST['clear_log']) && check_admin_referer('clear_log_action', 'clear_log_nonce')) {
        clear_api_fetch_log(); // Call the function to clear the log
        echo '<div class="updated notice is-dismissible"><p>Log cleared successfully.</p></div>';
    }

    // Retrieve the log data
    $log = get_option('api_fetch_log', ['success' => [], 'error' => []]);

    echo '<div class="wrap">';
    echo '<h1>API Fetch Log</h1>';

    // Display success logs
    if (!empty($log['success'])) {
        echo '<h2>Success</h2><ul>';
        foreach ($log['success'] as $success) {
            echo '<li>' . esc_html($success) . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No success logs found.</p>';
    }

    // Display error logs
    if (!empty($log['error'])) {
        echo '<h2>Errors</h2><ul>';
        foreach ($log['error'] as $error) {
            echo '<li>' . esc_html($error) . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No error logs found.</p>';
    }

    // Add form to clear the log with nonce field
    echo '<form method="post">';
    wp_nonce_field('clear_log_action', 'clear_log_nonce');
    echo '<input type="hidden" name="clear_log" value="true">';
    echo '<p><input type="submit" class="button button-primary" value="Clear Log"></p>';
    echo '</form>';

    echo '</div>';
}

function gri_styled_search_form_shortcode() {
    $query = isset($_GET['query']) ? sanitize_text_field($_GET['query']) : '';

    ob_start(); // Start output buffering
    ?>
    <form action="/dashboard/result/" method="get" class="gri-search-form">
        <input type="text" name="query" class="gri-search-input" placeholder='"ESG", "Data protection", "HealthCare", "Artificial Intelligence"' value="<?php echo esc_attr($query); ?>" required>
        <button type="submit" class="gri-search-button">
            Search
        </button>
    </form>
    <?php
    return ob_get_clean(); // Return the buffered content
}
add_shortcode('gri_search_form', 'gri_styled_search_form_shortcode');

function gri_legal_styled_search_form_shortcode() {
    ob_start(); // Start output buffering
    ?>
    <form action="/legal-resources-2/result-law/" method="get" class="gri-search-form">
		<input type="text" name="query" class="gri-search-input" placeholder='"Work Health and Safety Act", "Labour Code", "Protection of Personal Data Law"' required>
		<button type="submit" class="gri-search-button">
			Search
		</button>
	</form>
    <?php
    return ob_get_clean(); // Return the buffered content
}
add_shortcode('gri_legal_search_form', 'gri_legal_styled_search_form_shortcode');

function gri_read_styled_search_form_shortcode() {
    ob_start();
    ?>
    <form action="/read/result/" method="get" class="gri-search-form">
		<input type="text" name="query" class="gri-search-input" placeholder='"Work Health and Safety Act", "Labour Code", "Protection of Personal Data Law"' required>
		<button type="submit" class="gri-search-button">
			Search
		</button>
	</form>
    <?php
    return ob_get_clean(); // Return the buffered content
}
add_shortcode('gri_read_search_form', 'gri_read_styled_search_form_shortcode');

function gri_frequent_search_buttons() {
    // Fetch posts from the 'frequent-search' post type
    $args = array(
        'post_type' => 'frequent-search',
        'posts_per_page' => 6,
        'orderby' => 'date',
        'order' => 'DESC',
    );

    $frequent_search_posts = new WP_Query($args);

    if ($frequent_search_posts->have_posts()) {
        $output = '<div class="frequent-search-buttons">'; // Container for buttons
        
        while ($frequent_search_posts->have_posts()) {
            $frequent_search_posts->the_post();
            $title = get_the_title();
            
            $output .= '<a href="/dashboard/result/?query' . urlencode($title) . '" class="frequent-search-button">' . esc_html($title) . '</a>';
        }

        $output .= '</div>';
        wp_reset_postdata(); // Reset post data after the loop
        
        return $output; // Return the generated buttons
    } else {
        return '<p>No frequent searches available.</p>'; // If no posts are found
    }
}

add_shortcode('frequent_search_buttons', 'gri_frequent_search_buttons');

function gri_quick_insight_box() {
    // Check if the search query (_s parameter) exists in the URL
    if (isset($_GET['_s']) && !empty($_GET['_s'])) {
        $search_query = sanitize_text_field($_GET['_s']); // Sanitize the search query

        // Query for a post with the matching title
        $args = array(
            'post_type' => 'frequent-search',
            'title' => $search_query,
            'posts_per_page' => 1,
        );

        $matching_post = new WP_Query($args);

        if ($matching_post->have_posts()) {
            $output = '<div class="quick-insight-box">';

            // Quick Insight Header
            $output .= '<div class="quick-insight-header">';
            $output .= '<img src="https://globalregulatoryinsights.com/wp-content/uploads/2024/09/quick-insight.png" alt="Quick Insight" class="quick-insight-image" />';
            $output .= '<div class="quick-insight-title">Quick Insight</div>';
            $output .= '</div>'; // Close quick-insight-header

            // Loop through posts and append content
            while ($matching_post->have_posts()) {
                $matching_post->the_post(); // Setup post data

                // Get the full content of the post without displaying it twice
                $content = apply_filters('the_content', get_the_content());

                // Strip HTML tags for excerpt and limit to first 100 words
                $words = explode(' ', wp_strip_all_tags($content));
                $excerpt = implode(' ', array_slice($words, 0, 100)) . '...'; // Create the excerpt (first 100 words)

                // Only wrap content inside <p> tags without any additional HTML
                $output .= '<div class="quick-insight-content">';
                $output .= '<div class="insight-excerpt">' . wp_kses_post($excerpt) . '</div>';
                $output .= '<div class="insight-full-content" style="display: none;">' . wp_kses_post($content) . '</div>';
                $output .= '<button class="read-more-btn">Read More <i class="fas fa-chevron-down arrow-icon"></i></button>';
                $output .= '</div>';
            }

            $output .= '</div>'; // Close quick-insight-box

            // Add inline JavaScript for toggling content
            $output .= '<script>
                document.querySelectorAll(".read-more-btn").forEach(function(button) {
                    button.addEventListener("click", function() {
                        var fullContent = button.previousElementSibling; // Selects the full content <p>
                        var excerptContent = fullContent.previousElementSibling; // Selects the excerpt <p>

                        if (fullContent.style.display === "none") {
                            // Show the full content and hide the excerpt
                            fullContent.style.display = "block";
                            excerptContent.style.display = "none";
                            button.innerHTML = \'Read Less <i class="fas fa-chevron-up arrow-icon"></i>\';
                        } else {
                            // Hide the full content and show the excerpt
                            fullContent.style.display = "none";
                            excerptContent.style.display = "block";
                            button.innerHTML = \'Read More <i class="fas fa-chevron-down arrow-icon"></i>\';
                        }
                    });
                });
            </script>';

            wp_reset_postdata(); // Reset post data after the loop

            return $output;
        }
    }

    return ''; // Return empty string if no matching post is found or no search query is set
}

add_shortcode('quick_insight_box', 'gri_quick_insight_box');


/* Taxonomy Tracker */

function toggle_track_untrack_taxonomy() {
    // Ensure the user is logged in
    if ( !is_user_logged_in() ) {
        wp_send_json_error( array( 'message' => 'User is not logged in.' ) );
        return;
    }

    // Get the current user ID and the term ID being tracked/untracked
    $user_id = get_current_user_id();
    $term_id = isset($_POST['term_id']) ? intval($_POST['term_id']) : 0;

    if ( !$term_id ) {
        wp_send_json_error( array( 'message' => 'Invalid term ID.' ) );
        return;
    }

    // Get the term to determine its name and taxonomy
    $term = get_term( $term_id );

    if ( is_wp_error( $term ) || !$term ) {
        wp_send_json_error( array( 'message' => 'Invalid term.' ) );
        return;
    }

    // Get the current tracked terms from user meta
    $tracked_terms = get_user_meta( $user_id, '_user_tracked_terms', true );

    // If tracked_terms is not an array, initialize it
    if ( !is_array( $tracked_terms ) ) {
        $tracked_terms = [];
    }

    // Toggle the term: If it's already tracked, remove it; otherwise, add it
    $term_slug = $term->slug;

    // Track/Untrack all terms with the same slug
    $all_terms = get_terms( array(
        'slug'      => $term_slug,
        'hide_empty' => false,
    ));

    foreach ($all_terms as $term) {
        $term_id = $term->term_id;

        if ( in_array( $term_id, $tracked_terms ) ) {
            // Untrack: Remove the term from the array
            $tracked_terms = array_diff( $tracked_terms, [ $term_id ] );
        } else {
            // Track: Add the term ID to the array
            $tracked_terms[] = $term_id;
        }
    }

    // Ensure the array is unique
    $tracked_terms = array_unique( $tracked_terms );

    // Save the updated tracked terms
    update_user_meta( $user_id, '_user_tracked_terms', $tracked_terms );

    $is_tracked = in_array( $term_id, $tracked_terms );

    wp_send_json_success( array(
        'is_tracked' => $is_tracked,
        'term_id'    => $term_id,
    ));
}
add_action( 'wp_ajax_toggle_track_untrack_taxonomy', 'toggle_track_untrack_taxonomy' );

function show_tracked_terms_in_user_profile( $user ) {
    $tracked_terms = get_user_meta( $user->ID, '_user_tracked_terms', true );

    // Display the tracked terms as a list
    echo '<h3>Tracked Categories</h3>';
    echo '<table class="form-table">';
    echo '<tr><th><label for="_user_tracked_terms">Tracked Categories</label></th>';
    echo '<td>';
    
    if ( !empty( $tracked_terms ) && is_array( $tracked_terms ) ) {
        echo '<ul>';
        foreach ( $tracked_terms as $term_id ) {
            $term = get_term( $term_id );
            if ( !is_wp_error( $term ) && $term ) {
                echo '<li>' . esc_html( $term->name ) . ' (Term ID: ' . esc_html( $term_id ) . ')</li>';
            }
        }
        echo '</ul>';
    } else {
        echo 'No tracked categories.';
    }

    echo '</td></tr></table>';
}
add_action( 'show_user_profile', 'show_tracked_terms_in_user_profile' );
add_action( 'edit_user_profile', 'show_tracked_terms_in_user_profile' );

function save_tracked_terms( $user_id ) {
    // Check if the data is being submitted correctly
    if ( !isset( $_POST['tracked_terms_nonce'] ) || !wp_verify_nonce( $_POST['tracked_terms_nonce'], 'save_tracked_terms' ) ) {
        return;
    }

    // Get the tracked terms from the form
    $tracked_terms = isset( $_POST['_user_tracked_terms'] ) ? array_map( 'intval', $_POST['_user_tracked_terms'] ) : [];

    // Update the user meta field
    update_user_meta( $user_id, '_user_tracked_terms', $tracked_terms );
}
add_action( 'personal_options_update', 'save_tracked_terms' );
add_action( 'edit_user_profile_update', 'save_tracked_terms' );

function enqueue_suggested_authorities_script() {
    wp_enqueue_script(
        'suggested-authorities-script',
        get_template_directory_uri() . '/js/suggested-authorities.js', // Adjust the path to your JavaScript file
        array('jquery'),
        null,
        true
    );

    // Localize the script with the correct ajaxurl and nonce
    wp_localize_script(
        'suggested-authorities-script',
        'authorityTracker',
        array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('gri_nonce')
        )
    );
}
add_action('wp_enqueue_scripts', 'enqueue_suggested_authorities_script');

function display_work_area_title_shortcode() {
    // Get the current URL
    $current_url = home_url($_SERVER['REQUEST_URI']);
    $target_url_base = site_url('/legal-resources-2/result-law/');

    // Check if the current URL matches the desired pattern
    if (strpos($current_url, $target_url_base) !== 0) {
        return '';
    }

    // Check if the required query parameters are present
    if (isset($_GET['jsf'], $_GET['tax']) && $_GET['jsf'] === 'jet-engine') {
        // Parse the 'tax' parameter to extract the term ID
        $tax_parts = explode(':', $_GET['tax']);
        
        if (count($tax_parts) === 2 && $tax_parts[0] === 'work-area') {
            $term_id = intval($tax_parts[1]);
            
            // Get term information
            $term = get_term($term_id, 'work-area');
            if (!is_wp_error($term) && !empty($term)) {
                return '<h2 class="work-area-title">' . esc_html($term->name) . '</h2>';
            }
        }
    }

    return '';
}
add_shortcode('work_area_title', 'display_work_area_title_shortcode');

function gri_send_otp() {
    // Check nonce for security
    check_ajax_referer('gri_nonce', 'security');

    // Sanitize and validate email
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'gri_nonce')) {
        if (!$email || !is_email($email)) {
            wp_send_json_error(['message' => 'Invalid nonce.']);
            return;
        }
    
        $email = sanitize_email($_POST['email']);
    
    if (!is_email($email)) {
        wp_send_json_error(['message' => 'User with this email already exists.']);
        return;
    }

    // Generate OTP and store it in a transient
    $otp = wp_rand(1000, 9999); // Use wp_rand for better randomness
    set_transient('gri_otp_' . md5($email), $otp, 5 * MINUTE_IN_SECONDS);

    // Prepare email content
    $subject = "Your OTP Verification Code";
    $message = "Your OTP is: $otp (valid for 5 minutes)";
    $headers = ['Content-Type: text/plain; charset=UTF-8'];

    // Send the OTP via email
    if (wp_mail($email, $subject, $message, $headers)) {
        wp_send_json_success(['message' => 'OTP sent. Please check your inbox.']);
    } else {
        wp_send_json_error(['message' => 'Error sending email. Please try again later.']);
    }
}

function gri_verify_otp() {
    check_ajax_referer('gri_nonce', 'security');

    // Sanitize and validate input
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $otp = isset($_POST['otp']) ? sanitize_text_field($_POST['otp']) : '';

    if (!$email || !is_email($email)) {
        wp_send_json_error(['message' => 'Invalid email address.']);
        return;
    }

    if (!$otp || !ctype_digit($otp)) {
        wp_send_json_error(['message' => 'Invalid OTP format.']);
        return;
    }

    // Retrieve and validate OTP
    $transient_key = 'gri_otp_' . md5($email);
    $stored_otp = get_transient($transient_key);

    if ($stored_otp && $otp == $stored_otp) {
        delete_transient($transient_key); // Clear OTP after successful verification
        wp_send_json_success(['message' => 'OTP verified successfully.']);
    } else {
        wp_send_json_error(['message' => 'Incorrect or expired OTP.']);
    }
}

// Add AJAX actions
add_action('wp_ajax_gri_send_otp', 'gri_send_otp');
add_action('wp_ajax_nopriv_gri_send_otp', 'gri_send_otp');
add_action('wp_ajax_gri_verify_otp', 'gri_verify_otp');
add_action('wp_ajax_nopriv_gri_verify_otp', 'gri_verify_otp');

function gri_register_user() {
    $email = sanitize_email($_POST['email']);
    $full_name = sanitize_text_field($_POST['full_name']);
    $organization = sanitize_text_field($_POST['organization']);
    $work_area = isset($_POST['work_area']) ? array_map('sanitize_text_field', (array) $_POST['work_area']) : [];
    $country = sanitize_text_field($_POST['country']);

    $user_id = wp_create_user($email, wp_generate_password(), $email);
    if (is_wp_error($user_id)) {
        wp_send_json_error(['message' => $user_id->get_error_message()]);
    }
	
	$name_parts = explode(' ', $full_name);
    $first_name = isset($name_parts[0]) ? $name_parts[0] : '';
    $last_name = isset($name_parts[1]) ? implode(' ', array_slice($name_parts, 1)) : '';

    // Add user meta data
    update_user_meta($user_id, 'first_name', $first_name);
    update_user_meta($user_id, 'last_name', $last_name);
    update_user_meta($user_id, 'organization', $organization);
    update_user_meta($user_id, 'work_area', $work_area);
    update_user_meta($user_id, 'country', $country);

    // Sign the user in
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);

    wp_send_json_success();
}
add_action('wp_ajax_gri_register_user', 'gri_register_user');
add_action('wp_ajax_nopriv_gri_register_user', 'gri_register_user');

function enqueue_slick() {
    wp_enqueue_style('slick-style', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css');
    wp_enqueue_style('slick-theme-style', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css');
    wp_enqueue_script('slick-script', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_slick');

add_action('init', 'fetch_api_on_custom_url_update');
function fetch_api_on_custom_url_update() {
    if (isset($_GET['run_api_update']) && $_GET['run_api_update'] === 'true') {
        fetch_and_update_posts_from_api();
    }
}

function fetch_and_update_posts_from_api() {
    $api_url = 'https://crawlergri.vercel.app/crawl_re_gen';
    $log = get_option('api_fetch_log', ['success' => [], 'error' => []]);

    $response = wp_remote_get($api_url, ['timeout' => 15]);

    if (is_wp_error($response)) {
        $log['error'][] = 'Error: ' . $response->get_error_message();
        update_option('api_fetch_log', $log);
        return;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if (!is_array($data) || empty($data['crawl_data'])) {
        $log['error'][] = 'Invalid or empty API response.';
        update_option('api_fetch_log', $log);
        return;
    }

    foreach ($data['crawl_data'] as $item) {
        $post_title = isset($item['title']) ? sanitize_text_field($item['title']) : 'Untitled Post';
        $post_content = isset($item['extracted_doc']) ? wp_kses_post($item['extracted_doc']) : '';
        $short_summary = isset($item['short_summary']) ? sanitize_text_field($item['short_summary']) : '';
        $indepth_summary = isset($item['indepth_summary']) ? sanitize_textarea_field($item['indepth_summary']) : '';
		
		$post_types = ['library', 'gdpr-et', 'art', 'legal-resources-post', 'bill-tracker', 'webinar-tracker'];

        $existing_post = get_posts([
            'post_type'      => $post_types,
            'title'          => $post_title,
            'posts_per_page' => 1,
            'post_status'    => 'any',
        ]);

        if (!empty($existing_post)) {
            $post_id = $existing_post[0]->ID;

            $update_data = [
                'ID' => $post_id,
                'post_content' => $post_content,
                'meta_input'   => [
                    'short_summary' => $short_summary,
                    'indepth_summary' => $indepth_summary,
                ],
            ];

            $updated_post_id = wp_update_post($update_data);

            if (is_wp_error($updated_post_id)) {
                $log['error'][] = "Failed to update post: $post_title (ID: $post_id)";
            } else {
                $log['success'][] = "Post updated: $post_title (ID: $updated_post_id)";
            }
        } else {
            $log['error'][] = "Post not found for update: $post_title";
        }
    }

    update_option('api_fetch_log', $log);
}

add_action('wp_ajax_load_more_suggestions', 'load_more_suggestions');
add_action('wp_ajax_nopriv_load_more_suggestions', 'load_more_suggestions');

function load_more_suggestions() {
	// Verify the request method
	if (!isset($_POST['offset'])) {
		wp_send_json_error(['message' => 'Invalid request.']);
	}

	// Sanitize input
	$offset = intval($_POST['offset']);

	// Adjust query arguments to fetch additional suggestions
	$args = array(
		'post_type'      => 'frequent-search',
		'posts_per_page' => 6, // Number of items to load per request
		'offset'         => $offset,
		'tax_query'      => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'work-areas-art',
				'field'    => 'slug',
				'terms'    => $_POST['work_area_slug'], // Pass these dynamically via JS if needed
			),
			array(
				'taxonomy' => 'justisdiction-insight',
				'field'    => 'slug',
				'terms'    => $_POST['jurisdiction_slug'], // Pass these dynamically via JS if needed
			),
		),
	);

	// Execute the query
	$query = new WP_Query($args);

	if ($query->have_posts()) {
		$suggestions = [];
		while ($query->have_posts()) {
			$query->the_post();
			$suggestions[] = [
				'id'    => get_the_ID(),
				'title' => get_the_title(),
			];
		}
		wp_reset_postdata();

		// Send response back to JavaScript
		wp_send_json_success(['suggestions' => $suggestions, 'loaded' => count($suggestions)]);
	} else {
		wp_send_json_error(['message' => 'No more suggestions to load.']);
	}
}

function enqueue_chatbot_script() {
    wp_enqueue_script('chatbot-script', get_template_directory_uri() . '/js/chatbot.js', array('jquery'), '1.0', true);

    $current_user = wp_get_current_user();
    $user_email = $current_user->user_email;

    $user_work_area = get_user_meta($current_user->ID, 'work_area', true);
    $user_work_area = is_array($user_work_area) ? $user_work_area : explode(', ', $user_work_area);
    $user_country = get_user_meta($current_user->ID, 'country', true);

    global $post;
    if (!$post) {
        wp_localize_script('chatbot-script', 'chatbotData', array(
            'userEmail' => $user_email,
            'workAreaSlug' => !empty($user_work_area) ? implode(', ', $user_work_area) : '',
            'jurisdictionSlug' => !empty($user_country) ? urlencode($user_country) : '',
        ));
        return;
    }

    $post_type = $post->post_type;

    $taxonomies = [];
    switch ($post_type) {
        case 'art':
            $taxonomies = [
                'work_area' => 'work-areas-art',
                'jurisdiction' => 'jurisdiction-art',
            ];
            break;

        case 'bill-tracker':
            $taxonomies = [
                'work_area' => 'bill-work-areas',
                'jurisdiction' => 'bill-jurisdiction',
            ];
            break;

        case 'webinar-tracker':
            $taxonomies = [
                'work_area' => 'work-area-webinar',
                'jurisdiction' => 'jurisdiction-webinar',
            ];
            break;

        case 'legal-resources-post':
            $taxonomies = [
                'work_area' => 'work-area',
                'jurisdiction' => 'jurisdiction',
            ];
            break;

        case 'library':
            $taxonomies = [
                'work_area' => 'work-areas-library',
                'jurisdiction' => 'jurisdiction-library',
            ];
            break;

        case 'gdpr-et':
            $taxonomies = [
                'work_area' => 'work-areas-gdpr',
                'jurisdiction' => 'juridiction-gdpr',
            ];
            break;
    }

    // Fetch term titles and handle errors
    $work_area_term = get_the_terms($post->ID, $taxonomies['work_area']);
    $jurisdiction_term = get_the_terms($post->ID, $taxonomies['jurisdiction']);

    $work_area_title = ($work_area_term && !is_wp_error($work_area_term) && !empty($work_area_term)) 
        ? urlencode($work_area_term[0]->name) 
        : '';
    $jurisdiction_title = ($jurisdiction_term && !is_wp_error($jurisdiction_term) && !empty($jurisdiction_term)) 
        ? urlencode($jurisdiction_term[0]->name) 
        : '';

    wp_localize_script('chatbot-script', 'chatbotData', array(
        'userEmail' => $user_email,
        'workAreaSlug' => $work_area_title,
        'jurisdictionSlug' => $jurisdiction_title,
        'profileWorkAreas' => !empty($user_work_area) ? implode(', ', $user_work_area) : '',
        'profileCountry' => !empty($user_country) ? urlencode($user_country) : '',
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_chatbot_script');

function gri_send_login_otp() {
    check_ajax_referer('gri_nonce', 'security');

    $identifier = sanitize_text_field($_POST['identifier']);
    $user = is_email($identifier) ? get_user_by('email', $identifier) : get_user_by('login', $identifier);

    if (!$user) {
        wp_send_json_error(['message' => 'Invalid username or email.']);
    }

    $email = $user->user_email;
    $otp = rand(1000, 9999);
    set_transient('gri_otp_' . md5($email), $otp, 5 * MINUTE_IN_SECONDS);

    // Prepare email content
    $subject = "Your Login OTP Code";
    $message = "Your OTP is: $otp (valid for 5 minutes)";
    $headers = ['Content-Type: text/plain; charset=UTF-8'];

    if (wp_mail($email, $subject, $message, $headers)) {
        wp_send_json_success(['message' => 'OTP sent.']);
    } else {
        wp_send_json_error(['message' => 'Error sending email.']);
    }
}
add_action('wp_ajax_gri_send_login_otp', 'gri_send_login_otp');
add_action('wp_ajax_nopriv_gri_send_login_otp', 'gri_send_login_otp');

// Function to verify OTP and log in the user
function gri_verify_login_otp() {
    check_ajax_referer('gri_nonce', 'security');

    $identifier = sanitize_text_field($_POST['identifier']);
    $otp = sanitize_text_field($_POST['otp']);
    $user = is_email($identifier) ? get_user_by('email', $identifier) : get_user_by('login', $identifier);

    if (!$user) {
        wp_send_json_error(['message' => 'Invalid username or email.']);
    }

    $email = $user->user_email;
    $stored_otp = get_transient('gri_otp_' . md5($email));

    if ($stored_otp && $otp == $stored_otp) {
        delete_transient('gri_otp_' . md5($email));
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID, true, time() + YEAR_IN_SECONDS);
        wp_send_json_success(['redirect_url' => '/dashboard/']);
    } else {
        wp_send_json_error(['message' => 'Incorrect or expired OTP.']);
    }
}
add_action('wp_ajax_gri_verify_login_otp', 'gri_verify_login_otp');
add_action('wp_ajax_nopriv_gri_verify_login_otp', 'gri_verify_login_otp');

function gri_extend_login_session($expiration, $user_id, $remember) {
    return YEAR_IN_SECONDS;
}
add_filter('auth_cookie_expiration', 'gri_extend_login_session', 10, 3);
