<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//new advanced export-import demo contents
if ( ! function_exists( 'exs_demo_import_lists' ) ) :
	function exs_demo_import_lists(){

		//if the main plugin file is different from plugin slug
		//'main_file' => 'wp-seo.php',
		$demo_lists = array(
			'app'             => array(
				'title'          => esc_html__( 'App', 'exs' ),
				'template_url'   => array(
					'content' => 'https://cdn.exsthemewp.com/demos-ai/app/content.json',
					'options' => 'https://cdn.exsthemewp.com/demos-ai/app/options.json',
					'widgets' => 'https://cdn.exsthemewp.com/demos-ai/app/widgets.json',
				),
				'screenshot_url' => 'https://exsthemewp.com/wp-content/uploads/2022/01/exs-app-fastest-wordpress-theme.jpg',
				'demo_url'       => 'https://demos.exsthemewp.com/parent-app/',
				'is_pro'         => false,
				'type'           => 'gutenberg',
				'author'         => esc_html__( 'ExS', 'exs' ),
				'keywords'       => array( 'shop', 'ecommerce', 'woocommerce' ),
				'categories'     => array( 'ecommerce' ),
				'plugins'        => array(
					array(
						'name' => esc_html__( 'ExS Widgets', 'exs' ),
						'slug' => 'exs-widgets',
					),
					array(
						'name' => esc_html__( 'WooCommerce', 'exs' ),
						'slug' => 'woocommerce',

					),
					array(
						'name'      => esc_html__( 'YITH WooCommerce Wishlist', 'exs' ),
						'slug'      => 'yith-woocommerce-wishlist',
						'main_file' => 'init.php',
					),
					array(
						'name'      => esc_html__( 'YITH WooCommerce Quick View', 'exs' ),
						'slug'      => 'yith-woocommerce-quick-view',
						'main_file' => 'init.php',
					),
					array(
						'name'      => esc_html__( 'Menu Icons', 'exs' ),
						'slug'      => 'menu-icons',
					),
					array(
						'name'      => esc_html__( 'WordPress SEO by Yoast', 'exs' ),
						'slug'      => 'wordpress-seo',
						'main_file' => 'wp-seo.php',
					),
				),
			),
			'knowledgebase'    => array(
				'title'          => esc_html__( 'Knowledgebase', 'exs' ),
				'template_url'   => array(
					'content' => 'https://cdn.exsthemewp.com/demos-ai/knowledgebase/content.json',
					'options' => 'https://cdn.exsthemewp.com/demos-ai/knowledgebase/options.json',
					'widgets' => 'https://cdn.exsthemewp.com/demos-ai/knowledgebase/widgets.json',
				),
				'screenshot_url' => 'https://exsthemewp.com/wp-content/uploads/2020/08/knowledgebase-demo.jpg',
				'demo_url'       => 'https://demos.exsthemewp.com/knowledgebase/',
				'is_pro'         => false,
				'type'           => 'gutenberg',
				'author'         => esc_html__( 'ExS', 'exs' ),
				'keywords'       => array( 'docs', 'documentation' ),
				'categories'     => array( 'documentation' ),
				'plugins'        => array(
					array(
						'name' => esc_html__( 'ExS Widgets', 'exs' ),
						'slug' => 'exs-widgets',
					),
					array(
						'name'      => esc_html__( 'WordPress SEO by Yoast', 'exs' ),
						'slug'      => 'wordpress-seo',
						'main_file' => 'wp-seo.php',
					),
				),
			),
			'news'             => array(
				'title'          => esc_html__( 'News', 'exs' ),
				'template_url'   => array(
					'content' => 'https://cdn.exsthemewp.com/demos-ai/news/content.json',
					'options' => 'https://cdn.exsthemewp.com/demos-ai/news/options.json',
					'widgets' => 'https://cdn.exsthemewp.com/demos-ai/news/widgets.json',
				),
				'screenshot_url' => 'https://exsthemewp.com/wp-content/uploads/2020/05/demo-blog.jpg',
				'demo_url'       => 'https://demos.exsthemewp.com/news/',
				'is_pro'         => false,
				'type'           => 'gutenberg',
				'author'         => esc_html__( 'ExS', 'exs' ),
				'keywords'       => array( 'news', 'blog' ),
				'categories'     => array( 'news', 'blog' ),
				'plugins'        => array(
					array(
						'name' => esc_html__( 'ExS Widgets', 'exs' ),
						'slug' => 'exs-widgets',
					),
					array(
						'name'      => esc_html__( 'WordPress SEO by Yoast', 'exs' ),
						'slug'      => 'wordpress-seo',
						'main_file' => 'wp-seo.php',
					),
				),
			),
			'exs-tech'         => array(
				'title'          => esc_html__( 'ExS Tech', 'exs' ),
				'template_url'   => array(
					'content' => 'https://cdn.exsthemewp.com/demos-ai/news/content.json',
					'options' => 'https://cdn.exsthemewp.com/demos-ai/news/options.json',
					'widgets' => 'https://cdn.exsthemewp.com/demos-ai/news/widgets.json',
				),
				'screenshot_url' => 'https://exsthemewp.com/wp-content/uploads/2022/03/screenshot.png',
				'demo_url'       => 'https://demos2.exsthemewp.com/parent-tech/',
				'is_pro'         => true,
				'pro_url'        => 'https://exsthemewp.com/download/',
				'type'           => 'gutenberg',
				'author'         => esc_html__( 'ExS', 'exs' ),
				'keywords'       => array( 'shop', 'tech', 'news', 'ecommerce' ),
				'categories'     => array( 'tech', 'technical', 'gadget' ),
				'plugins'        => array(
					array(
						'name' => esc_html__( 'ExS Widgets', 'exs' ),
						'slug' => 'exs-widgets',
					),
					array(
						'name'      => esc_html__( 'Menu Icons', 'exs' ),
						'slug'      => 'menu-icons',
					),
					array(
						'name'      => esc_html__( 'WordPress SEO by Yoast', 'exs' ),
						'slug'      => 'wordpress-seo',
						'main_file' => 'wp-seo.php',
					),
					array(
						'name' => esc_html__( 'WooCommerce', 'exs' ),
						'slug' => 'woocommerce',

					),
					array(
						'name'      => esc_html__( 'YITH WooCommerce Wishlist', 'exs' ),
						'slug'      => 'yith-woocommerce-wishlist',
						'main_file' => 'init.php',
					),
					array(
						'name'      => esc_html__( 'YITH WooCommerce Quick View', 'exs' ),
						'slug'      => 'yith-woocommerce-quick-view',
						'main_file' => 'init.php',
					),
				),
			),
			'business'         => array(
				'title'          => esc_html__( 'Business', 'exs' ),
				'template_url'   => array(
					'content' => 'https://cdn.exsthemewp.com/demos-ai/business/content.json',
					'options' => 'https://cdn.exsthemewp.com/demos-ai/business/options.json',
					'widgets' => 'https://cdn.exsthemewp.com/demos-ai/business/widgets.json',
				),
				'screenshot_url' => 'https://exsthemewp.com/wp-content/uploads/2020/05/demo-business.jpg',
				'demo_url'       => 'https://demos.exsthemewp.com/business/',
				'is_pro'         => false,
				'type'           => 'gutenberg',
				'author'         => esc_html__( 'ExS', 'exs' ),
				'keywords'       => array( 'business', 'agency', 'company', 'personal' ),
				'categories'     => array( 'business' ),
				'plugins'        => array(
					array(
						'name' => esc_html__( 'ExS Widgets', 'exs' ),
						'slug' => 'exs-widgets',
					),
					array(
						'name'      => esc_html__( 'WordPress SEO by Yoast', 'exs' ),
						'slug'      => 'wordpress-seo',
						'main_file' => 'wp-seo.php',
					),
				),
			),
			'agency'         => array(
				'title'          => esc_html__( 'Agency', 'exs' ),
				'template_url'   => array(
					'content' => 'https://cdn.exsthemewp.com/demos-ai/business/content.json',
					'options' => 'https://cdn.exsthemewp.com/demos-ai/business/options.json',
					'widgets' => 'https://cdn.exsthemewp.com/demos-ai/business/widgets.json',
				),
				'screenshot_url' => 'https://cdn.exsthemewp.com/demos-ai/tm-agency/screenshot.png',
				'demo_url'       => 'https://demos.exsthemewp.com/tm-agency/',
				'is_pro'         => true,
				'pro_url'        => 'https://exsthemewp.com/download/',
				'type'           => 'gutenberg',
				'author'         => esc_html__( 'ExS', 'exs' ),
				'keywords'       => array( 'business', 'agency', 'services' ),
				'categories'     => array( 'business', 'agency', 'services' ),
				'plugins'        => array(
					array(
						'name' => esc_html__( 'ExS Widgets', 'exs' ),
						'slug' => 'exs-widgets',
					),
					array(
						'name'      => esc_html__( 'WordPress SEO by Yoast', 'exs' ),
						'slug'      => 'wordpress-seo',
						'main_file' => 'wp-seo.php',
					),
				),
			),
			'shop'             => array(
				'title'          => esc_html__( 'Shop', 'exs' ),
				'template_url'   => array(
					'content' => 'https://cdn.exsthemewp.com/demos-ai/shop/content.json',
					'options' => 'https://cdn.exsthemewp.com/demos-ai/shop/options.json',
					'widgets' => 'https://cdn.exsthemewp.com/demos-ai/shop/widgets.json',
				),
				'screenshot_url' => 'https://exsthemewp.com/wp-content/uploads/2020/05/demo-shop.jpg',
				'demo_url'       => 'https://demos.exsthemewp.com/shop/',
				'is_pro'         => false,
				'type'           => 'gutenberg',
				'author'         => esc_html__( 'ExS', 'exs' ),
				'keywords'       => array( 'shop', 'ecommerce', 'woocommerce' ),
				'categories'     => array( 'ecommerce' ),
				'plugins'        => array(
					array(
						'name' => esc_html__( 'ExS Widgets', 'exs' ),
						'slug' => 'exs-widgets',
					),
					array(
						'name'      => esc_html__( 'WordPress SEO by Yoast', 'exs' ),
						'slug'      => 'wordpress-seo',
						'main_file' => 'wp-seo.php',
					),
					array(
						'name' => esc_html__( 'WooCommerce', 'exs' ),
						'slug' => 'woocommerce',

					),
					array(
						'name'      => esc_html__( 'YITH WooCommerce Wishlist', 'exs' ),
						'slug'      => 'yith-woocommerce-wishlist',
						'main_file' => 'init.php',
					),
					array(
						'name'      => esc_html__( 'YITH WooCommerce Quick View', 'exs' ),
						'slug'      => 'yith-woocommerce-quick-view',
						'main_file' => 'init.php',
					),
				),
			),
			'agency-dark'         => array(
				'title'          => esc_html__( 'Agency - Dark', 'exs' ),
				'template_url'   => array(
					'content' => 'https://cdn.exsthemewp.com/demos-ai/business/content.json',
					'options' => 'https://cdn.exsthemewp.com/demos-ai/business/options.json',
					'widgets' => 'https://cdn.exsthemewp.com/demos-ai/business/widgets.json',
				),
				'screenshot_url' => 'https://cdn.exsthemewp.com/demos-ai/parent-dark/screenshot.png',
				'demo_url'       => 'https://demos.exsthemewp.com/parent-dark/',
				'is_pro'         => true,
				'pro_url'        => 'https://exsthemewp.com/download/',
				'type'           => 'gutenberg',
				'author'         => esc_html__( 'ExS', 'exs' ),
				'keywords'       => array( 'business', 'agency', 'services' ),
				'categories'     => array( 'business', 'agency', 'services' ),
				'plugins'        => array(
					array(
						'name' => esc_html__( 'ExS Widgets', 'exs' ),
						'slug' => 'exs-widgets',
					),
					array(
						'name'      => esc_html__( 'WordPress SEO by Yoast', 'exs' ),
						'slug'      => 'wordpress-seo',
						'main_file' => 'wp-seo.php',
					),
				),
			),
			'edd'              => array(
				'title'          => esc_html__( 'Easy Digital Downloads', 'exs' ),
				'template_url'   => array(
					'content' => 'https://cdn.exsthemewp.com/demos-ai/edd/content.json',
					'options' => 'https://cdn.exsthemewp.com/demos-ai/edd/options.json',
					'widgets' => 'https://cdn.exsthemewp.com/demos-ai/edd/widgets.json',
				),
				'screenshot_url' => 'https://exsthemewp.com/wp-content/uploads/2020/08/edd-demo.jpg',
				'demo_url'       => 'https://demos.exsthemewp.com/edd/',
				'is_pro'         => false,
				'type'           => 'gutenberg',
				'author'         => esc_html__( 'ExS', 'exs' ),
				'keywords'       => array( 'edd', 'shop', 'ecommerce' ),
				'categories'     => array( 'ecommerce' ),
				'plugins'        => array(
					array(
						'name' => esc_html__( 'ExS Widgets', 'exs' ),
						'slug' => 'exs-widgets',
					),
					array(
						'name'      => esc_html__( 'WordPress SEO by Yoast', 'exs' ),
						'slug'      => 'wordpress-seo',
						'main_file' => 'wp-seo.php',
					),
					array(
						'name' => esc_html__( 'Easy Digital Downloads', 'exs' ),
						'slug' => 'easy-digital-downloads',
					),
				),
			),
			'bbpress'          => array(
				'title'          => esc_html__( 'bbPress', 'exs' ),
				'template_url'   => array(
					'content' => 'https://cdn.exsthemewp.com/demos-ai/bbpress/content.json',
					'options' => 'https://cdn.exsthemewp.com/demos-ai/bbpress/options.json',
					'widgets' => 'https://cdn.exsthemewp.com/demos-ai/bbpress/widgets.json',
				),
				'screenshot_url' => 'https://exsthemewp.com/wp-content/uploads/2020/08/bbpress-demo.jpg',
				'demo_url'       => 'https://demos.exsthemewp.com/bbpress/',
				'is_pro'         => false,
				'type'           => 'gutenberg',
				'author'         => esc_html__( 'ExS', 'exs' ),
				'keywords'       => array( 'social', 'bbpress', 'forum' ),
				'categories'     => array( 'social' ),
				'plugins'        => array(
					array(
						'name' => esc_html__( 'ExS Widgets', 'exs' ),
						'slug' => 'exs-widgets',
					),
					array(
						'name'      => esc_html__( 'WordPress SEO by Yoast', 'exs' ),
						'slug'      => 'wordpress-seo',
						'main_file' => 'wp-seo.php',
					),
					array(
						'name' => esc_html__( 'bbPress', 'exs' ),
						'slug' => 'bbpress',
					),
				),
			),
			'wp-job-manager'   => array(
				'title'          => esc_html__( 'WP Job Manager', 'exs' ),
				'template_url'   => array(
					'content' => 'https://cdn.exsthemewp.com/demos-ai/wp-job-manager/content.json',
					'options' => 'https://cdn.exsthemewp.com/demos-ai/wp-job-manager/options.json',
					'widgets' => 'https://cdn.exsthemewp.com/demos-ai/wp-job-manager/widgets.json',
				),
				'screenshot_url' => 'https://exsthemewp.com/wp-content/uploads/2020/09/exs-wp-job-manager-demo.jpg',
				'demo_url'       => 'https://demos.exsthemewp.com/wp-job-manager/',
				'is_pro'         => false,
				'type'           => 'gutenberg',
				'author'         => esc_html__( 'ExS', 'exs' ),
				'keywords'       => array( 'jobs', 'job manager', 'wp job manager' ),
				'categories'     => array( 'jobs' ),
				'plugins'        => array(
					array(
						'name' => esc_html__( 'ExS Widgets', 'exs' ),
						'slug' => 'exs-widgets',
					),
					array(
						'name'      => esc_html__( 'WordPress SEO by Yoast', 'exs' ),
						'slug'      => 'wordpress-seo',
						'main_file' => 'wp-seo.php',
					),
					array(
						'name' => esc_html__( 'WP Job Manager', 'exs' ),
						'slug' => 'wp-job-manager',
					),
				),
			),
			'buddypress'       => array(
				'title'          => esc_html__( 'BuddyPress', 'exs' ),
				'template_url'   => array(
					'content' => 'https://cdn.exsthemewp.com/demos-ai/buddypress/content.json',
					'options' => 'https://cdn.exsthemewp.com/demos-ai/buddypress/options.json',
					'widgets' => 'https://cdn.exsthemewp.com/demos-ai/buddypress/widgets.json',
				),
				'screenshot_url' => 'https://exsthemewp.com/wp-content/uploads/2020/09/buddypress-exs-theme-demo.jpg',
				'demo_url'       => 'https://demos.exsthemewp.com/buddypress/',
				'is_pro'         => false,
				'type'           => 'gutenberg',
				'author'         => esc_html__( 'ExS', 'exs' ),
				'keywords'       => array( 'social', 'buddypress', 'social' ),
				'categories'     => array( 'social' ),
				'plugins'        => array(
					array(
						'name' => esc_html__( 'ExS Widgets', 'exs' ),
						'slug' => 'exs-widgets',
					),
					array(
						'name'      => esc_html__( 'WordPress SEO by Yoast', 'exs' ),
						'slug'      => 'wordpress-seo',
						'main_file' => 'wp-seo.php',
					),
					array(
						'name' => esc_html__( 'bbPress', 'exs' ),
						'slug' => 'bbpress',
					),
					array(
						'name' => esc_html__( 'BuddyPress', 'exs' ),
						'slug' => 'buddypress',
						'main_file' => 'bp-loader.php',
					),
				),
			),
			'simple-job-board' => array(
				'title'          => esc_html__( 'Simple Job Board', 'exs' ),
				'template_url'   => array(
					'content' => 'https://cdn.exsthemewp.com/demos-ai/simple-job-board/content.json',
					'options' => 'https://cdn.exsthemewp.com/demos-ai/simple-job-board/options.json',
					'widgets' => 'https://cdn.exsthemewp.com/demos-ai/simple-job-board/widgets.json',
				),
				'screenshot_url' => 'https://exsthemewp.com/wp-content/uploads/2020/09/simple-job-board-exs-demo.jpg',
				'demo_url'       => 'https://demos.exsthemewp.com/simple-job-board/',
				'is_pro'         => false,
				'type'           => 'gutenberg',
				'author'         => esc_html__( 'ExS', 'exs' ),
				'keywords'       => array( 'jobs', 'job manager', 'simple job board' ),
				'categories'     => array( 'jobs' ),
				'plugins'        => array(
					array(
						'name' => esc_html__( 'ExS Widgets', 'exs' ),
						'slug' => 'exs-widgets',
					),
					array(
						'name'      => esc_html__( 'WordPress SEO by Yoast', 'exs' ),
						'slug'      => 'wordpress-seo',
						'main_file' => 'wp-seo.php',
					),
					array(
						'name' => esc_html__( 'Simple Job Board', 'exs' ),
						'slug' => 'simple-job-board',
					),
				),
			),
			'likes-views'      => array(
				'title'          => esc_html__( 'Likes and Views', 'exs' ),
				'template_url'   => array(
					'content' => 'https://cdn.exsthemewp.com/demos-ai/likes-views/content.json',
					'options' => 'https://cdn.exsthemewp.com/demos-ai/likes-views/options.json',
					'widgets' => 'https://cdn.exsthemewp.com/demos-ai/likes-views/widgets.json',
				),
				'screenshot_url' => 'https://exsthemewp.com/wp-content/uploads/2020/09/likes-dislikes-exs-demo.jpg',
				'demo_url'       => 'https://demos.exsthemewp.com/likes-views/',
				'is_pro'         => false,
				'type'           => 'gutenberg',
				'author'         => esc_html__( 'ExS', 'exs' ),
				'keywords'       => array( 'blog', 'news', 'video', 'likes', 'views' ),
				'categories'     => array( 'blog' ),
				'plugins'        => array(
					array(
						'name' => esc_html__( 'ExS Widgets', 'exs' ),
						'slug' => 'exs-widgets',
					),
					array(
						'name'      => esc_html__( 'WordPress SEO by Yoast', 'exs' ),
						'slug'      => 'wordpress-seo',
						'main_file' => 'wp-seo.php',
					),
					array(
						'name' => esc_html__( 'Comments Like Dislike', 'exs' ),
						'slug' => 'comments-like-dislike',
					),
					array(
						'name' => esc_html__( 'Post Views Counter', 'exs' ),
						'slug' => 'post-views-counter',
					),
					array(
						'name' => esc_html__( 'Posts Like Dislike', 'exs' ),
						'slug' => 'posts-like-dislike',
					),
				),
			),
			'ultimate-member'  => array(
				'title'          => esc_html__( 'Ultimate Member', 'exs' ),
				'template_url'   => array(
					'content' => 'https://cdn.exsthemewp.com/demos-ai/ultimate-member/content.json',
					'options' => 'https://cdn.exsthemewp.com/demos-ai/ultimate-member/options.json',
					'widgets' => 'https://cdn.exsthemewp.com/demos-ai/ultimate-member/widgets.json',
				),
				'screenshot_url' => 'https://exsthemewp.com/wp-content/uploads/2020/09/exs-ultimate-member-demo.jpg',
				'demo_url'       => 'https://demos.exsthemewp.com/ultimate-member/',
				'is_pro'         => false,
				'type'           => 'gutenberg',
				'author'         => esc_html__( 'ExS', 'exs' ),
				'keywords'       => array( 'social', 'membership', 'member', 'ultimate member' ),
				'categories'     => array( 'social' ),
				'plugins'        => array(
					array(
						'name' => esc_html__( 'ExS Widgets', 'exs' ),
						'slug' => 'exs-widgets',
					),
					array(
						'name'      => esc_html__( 'WordPress SEO by Yoast', 'exs' ),
						'slug'      => 'wordpress-seo',
						'main_file' => 'wp-seo.php',
					),
					array(
						'name' => esc_html__( 'Ultimate Member', 'exs' ),
						'slug' => 'ultimate-member',
					),
				),
			),
			'events-calendar'  => array(
				'title'          => esc_html__( 'The Events Calendar', 'exs' ),
				'template_url'   => array(
					'content' => 'https://cdn.exsthemewp.com/demos-ai/events-calendar/content.json',
					'options' => 'https://cdn.exsthemewp.com/demos-ai/events-calendar/options.json',
					'widgets' => 'https://cdn.exsthemewp.com/demos-ai/events-calendar/widgets.json',
				),
				'screenshot_url' => 'https://exsthemewp.com/wp-content/uploads/2020/12/exs-theme-events-calendar.jpg',
				'demo_url'       => 'https://demos.exsthemewp.com/events-calendar/',
				'is_pro'         => false,
				'type'           => 'gutenberg',
				'author'         => esc_html__( 'ExS', 'exs' ),
				'keywords'       => array( 'events', 'calendar', 'event calendar' ),
				'categories'     => array( 'events' ),
				'plugins'        => array(
					array(
						'name' => esc_html__( 'ExS Widgets', 'exs' ),
						'slug' => 'exs-widgets',
					),
					array(
						'name'      => esc_html__( 'WordPress SEO by Yoast', 'exs' ),
						'slug'      => 'wordpress-seo',
						'main_file' => 'wp-seo.php',
					),
					array(
						'name' => esc_html__( 'The Events Calendar', 'exs' ),
						'slug' => 'the-events-calendar',
					),
				),
			),
			'video'            => array(
				'title'          => esc_html__( 'Video', 'exs' ),
				'template_url'   => array(
					'content' => 'https://cdn.exsthemewp.com/demos-ai/news/content.json',
					'options' => 'https://cdn.exsthemewp.com/demos-ai/news/options.json',
					'widgets' => 'https://cdn.exsthemewp.com/demos-ai/news/widgets.json',
				),
				'screenshot_url' => 'https://exsthemewp.com/wp-content/uploads/2021/01/exs-video-demo-preview.jpg',
				'demo_url'       => 'https://demos.exsthemewp.com/video/',
				'is_pro'         => true,
				'pro_url'        => 'https://exsthemewp.com/download/',
				'type'           => 'gutenberg',
				'author'         => esc_html__( 'ExS', 'exs' ),
				'keywords'       => array( 'blog', 'news', 'video', 'likes', 'views' ),
				'categories'     => array( 'blog' ),
				'plugins'        => array(
					array(
						'name' => esc_html__( 'ExS Widgets', 'exs' ),
						'slug' => 'exs-widgets',
					),
					array(
						'name'      => esc_html__( 'WordPress SEO by Yoast', 'exs' ),
						'slug'      => 'wordpress-seo',
						'main_file' => 'wp-seo.php',
					),
					array(
						'name' => esc_html__( 'Comments Like Dislike', 'exs' ),
						'slug' => 'comments-like-dislike',
					),
					array(
						'name' => esc_html__( 'Post Views Counter', 'exs' ),
						'slug' => 'post-views-counter',
					),
					array(
						'name' => esc_html__( 'Posts Like Dislike', 'exs' ),
						'slug' => 'posts-like-dislike',
					),
				),
			),
			'learnpress'       => array(
				'title'          => esc_html__( 'LearnPress', 'exs' ),
				'template_url'   => array(
					'content' => 'https://cdn.exsthemewp.com/demos-ai/learnpress/content.json',
					'options' => 'https://cdn.exsthemewp.com/demos-ai/learnpress/options.json',
					'widgets' => 'https://cdn.exsthemewp.com/demos-ai/learnpress/widgets.json',
				),
				'screenshot_url' => 'https://exsthemewp.com/wp-content/uploads/2021/02/exs-learnpress-demo-preview.jpg',
				'demo_url'       => 'https://demos.exsthemewp.com/learnpress/',
				'is_pro'         => false,
				'type'           => 'gutenberg',
				'author'         => esc_html__( 'ExS', 'exs' ),
				'keywords'       => array( 'courses', 'learning', 'learnpress', 'online courses' ),
				'categories'     => array( 'courses' ),
				'plugins'        => array(
					array(
						'name' => esc_html__( 'ExS Widgets', 'exs' ),
						'slug' => 'exs-widgets',
					),
					array(
						'name'      => esc_html__( 'WordPress SEO by Yoast', 'exs' ),
						'slug'      => 'wordpress-seo',
						'main_file' => 'wp-seo.php',
					),
					array(
						'name' => esc_html__( 'LearnPress', 'exs' ),
						'slug' => 'learnpress',
					),
				),
			),
			'exs-shop'         => array(
				'title'          => esc_html__( 'ExS Shop', 'exs' ),
				'template_url'   => array(
					'content' => 'https://cdn.exsthemewp.com/demos-ai/shop/content.json',
					'options' => 'https://cdn.exsthemewp.com/demos-ai/shop/options.json',
					'widgets' => 'https://cdn.exsthemewp.com/demos-ai/shop/widgets.json',
				),
				'screenshot_url' => 'https://exsthemewp.com/wp-content/uploads/2021/06/exs-shop-screenshot.jpg',
				'demo_url'       => 'https://demos.exsthemewp.com/parent-shop/',
				'is_pro'         => true,
				'pro_url'        => 'https://exsthemewp.com/download/',
				'type'           => 'gutenberg',
				'author'         => esc_html__( 'ExS', 'exs' ),
				'keywords'       => array( 'shop', 'ecommerce', 'woocommerce' ),
				'categories'     => array( 'ecommerce' ),
				'plugins'        => array(
					array(
						'name' => esc_html__( 'ExS Widgets', 'exs' ),
						'slug' => 'exs-widgets',
					),
					array(
						'name'      => esc_html__( 'WordPress SEO by Yoast', 'exs' ),
						'slug'      => 'wordpress-seo',
						'main_file' => 'wp-seo.php',
					),
					array(
						'name' => esc_html__( 'WooCommerce', 'exs' ),
						'slug' => 'woocommerce',
					),
					array(
						'name'      => esc_html__( 'YITH WooCommerce Wishlist', 'exs' ),
						'slug'      => 'yith-woocommerce-wishlist',
						'main_file' => 'init.php',
					),
					array(
						'name'      => esc_html__( 'YITH WooCommerce Quick View', 'exs' ),
						'slug'      => 'yith-woocommerce-quick-view',
						'main_file' => 'init.php',
					),
				),
			),
			'exs-energy'         => array(
				'title'          => esc_html__( 'ExS Energy', 'exs' ),
				'template_url'   => array(
					'content' => 'https://cdn.exsthemewp.com/demos-ai/energy/content.json',
					'options' => 'https://cdn.exsthemewp.com/demos-ai/energy/options.json',
					'widgets' => 'https://cdn.exsthemewp.com/demos-ai/energy/widgets.json',
				),
				'screenshot_url' => 'https://exsthemewp.com/wp-content/uploads/2022/07/screenshot.png',
				'demo_url'       => 'https://demos2.exsthemewp.com/parent-energy/',
				'type'           => 'gutenberg',
				'author'         => esc_html__( 'ExS', 'exs' ),
				'keywords'       => array( 'shop', 'ecommerce', 'woocommerce', 'solar', 'energy' ),
				'categories'     => array( 'ecommerce', 'business', 'energy' ),
				'plugins'        => array(
					array(
						'name' => esc_html__( 'ExS Widgets', 'exs' ),
						'slug' => 'exs-widgets',
					),
					array(
						'name'      => esc_html__( 'WordPress SEO by Yoast', 'exs' ),
						'slug'      => 'wordpress-seo',
						'main_file' => 'wp-seo.php',
					),
					array(
						'name' => esc_html__( 'WooCommerce', 'exs' ),
						'slug' => 'woocommerce',
					),
					array(
						'name'      => esc_html__( 'YITH WooCommerce Wishlist', 'exs' ),
						'slug'      => 'yith-woocommerce-wishlist',
						'main_file' => 'init.php',
					),
					array(
						'name'      => esc_html__( 'YITH WooCommerce Quick View', 'exs' ),
						'slug'      => 'yith-woocommerce-quick-view',
						'main_file' => 'init.php',
					),
				),
			),
			'exs-medic'         => array(
				'title'          => esc_html__( 'ExS Medic', 'exs' ),
				'template_url'   => array(
					'content' => 'https://cdn.exsthemewp.com/demos-ai/medic/content.json',
					'options' => 'https://cdn.exsthemewp.com/demos-ai/medic/options.json',
					'widgets' => 'https://cdn.exsthemewp.com/demos-ai/medic/widgets.json',
				),
				'screenshot_url' => 'https://exsthemewp.com/wp-content/uploads/2022/10/screenshot.png',
				'demo_url'       => 'https://demos2.exsthemewp.com/parent-medic/',
				'type'           => 'gutenberg',
				'author'         => esc_html__( 'ExS', 'exs' ),
				'keywords'       => array( 'shop', 'ecommerce', 'woocommerce', 'medic', 'medicine', 'doctor' ),
				'categories'     => array( 'ecommerce', 'business', 'medic', 'doctor' ),
				'plugins'        => array(
					array(
						'name' => esc_html__( 'ExS Widgets', 'exs' ),
						'slug' => 'exs-widgets',
					),
					array(
						'name'      => esc_html__( 'WordPress SEO by Yoast', 'exs' ),
						'slug'      => 'wordpress-seo',
						'main_file' => 'wp-seo.php',
					),
					array(
						'name' => esc_html__( 'WooCommerce', 'exs' ),
						'slug' => 'woocommerce',
					),
					array(
						'name'      => esc_html__( 'YITH WooCommerce Wishlist', 'exs' ),
						'slug'      => 'yith-woocommerce-wishlist',
						'main_file' => 'init.php',
					),
					array(
						'name'      => esc_html__( 'YITH WooCommerce Quick View', 'exs' ),
						'slug'      => 'yith-woocommerce-quick-view',
						'main_file' => 'init.php',
					),
				),
			),
			'exs-music'         => array(
				'title'          => esc_html__( 'ExS Music', 'exs' ),
				'template_url'   => array(
					'content' => 'https://cdn.exsthemewp.com/demos-ai/music/content.json',
					'options' => 'https://cdn.exsthemewp.com/demos-ai/music/options.json',
					'widgets' => 'https://cdn.exsthemewp.com/demos-ai/music/widgets.json',
				),
				'screenshot_url' => 'https://exsthemewp.com/wp-content/uploads/2022/04/screenshot.png',
				'demo_url'       => 'https://demos2.exsthemewp.com/parent-music/',
				'type'           => 'gutenberg',
				'author'         => esc_html__( 'ExS', 'exs' ),
				'keywords'       => array( 'shop', 'ecommerce', 'woocommerce', 'music', 'band' ),
				'categories'     => array( 'ecommerce', 'business', 'events', 'music' ),
				'plugins'        => array(
					array(
						'name' => esc_html__( 'ExS Widgets', 'exs' ),
						'slug' => 'exs-widgets',
					),
					array(
						'name'      => esc_html__( 'WordPress SEO by Yoast', 'exs' ),
						'slug'      => 'wordpress-seo',
						'main_file' => 'wp-seo.php',
					),
					array(
						'name' => esc_html__( 'Easy Digital Downloads', 'exs' ),
						'slug' => 'easy-digital-downloads',
					),
				),
			),
			'exs-news'         => array(
				'title'          => esc_html__( 'ExS News', 'exs' ),
				'template_url'   => array(
					'content' => 'https://cdn.exsthemewp.com/demos-ai/news/content.json',
					'options' => 'https://cdn.exsthemewp.com/demos-ai/news/options.json',
					'widgets' => 'https://cdn.exsthemewp.com/demos-ai/news/widgets.json',
				),
				'screenshot_url' => 'https://exsthemewp.com/wp-content/uploads/2021/04/exs-news-screenshot.png',
				'demo_url'       => 'https://demos.exsthemewp.com/parent-news/',
				'is_pro'         => true,
				'pro_url'        => 'https://exsthemewp.com/download/',
				'type'           => 'gutenberg',
				'author'         => esc_html__( 'ExS', 'exs' ),
				'keywords'       => array( 'blog', 'news', 'video' ),
				'categories'     => array( 'news' ),
				'plugins'        => array(
					array(
						'name' => esc_html__( 'ExS Widgets', 'exs' ),
						'slug' => 'exs-widgets',
					),
					array(
						'name'      => esc_html__( 'WordPress SEO by Yoast', 'exs' ),
						'slug'      => 'wordpress-seo',
						'main_file' => 'wp-seo.php',
					),
				),
			),
		);
		return apply_filters( 'exs_demo_import_lists_filter', $demo_lists );
	}
endif;
add_filter('advanced_import_demo_lists','exs_demo_import_lists');
