<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


get_template_part( 'template-parts/blocks/cols-2-intro-1' );

get_template_part(
	'template-parts/blocks/cols-4-feature-blocks',
	null,
	array(
		'pt' => 'pt-7',
		'pb' => 'pb-3',
		'gap' => 'gap-30',
		'colsSingle' => 'cols-single-sm',
	)
);

get_template_part(
	'template-parts/blocks/cols-4-feature-blocks',
	null,
	array(
		'pt' => 'pt-0',
		'pb' => 'pb-6',
		'gap' => 'gap-30',
		'colsSingle' => 'cols-single-sm',
	)
);

get_template_part( 'template-parts/blocks/media-text-2-cols', null, array(
	'section' => true,
	'pt' => 'pt-8',
	'pb' => 'pb-7',
	'background' => 'l m',
) );

get_template_part( 'template-parts/blocks/title-with-subtitle', null, array(
	'pt' => 'pt-3',
	'pb' => 'pb-1',
) );


get_template_part( 'template-parts/blocks/cols-4-progress', null, array(
	'pt' => 'pt-2',
	'pb' => 'pb-4',
) );

get_template_part(
	'template-parts/blocks/separator',
	null,
	array(
		'mt' => 'mt-1',
		'mb' => 'mb-1',
		'className' => 'is-style-wide',
	)
);

get_template_part( 'template-parts/blocks/cols-2-cta-side-boxes', null, array(
	'pt' => 'pt-5',
	'pb' => 'pb-4',
) );

get_template_part( 'template-parts/blocks/cover-call-to-action', null, array() );

get_template_part( 'template-parts/blocks/title-with-subtitle', null, array(
	'pt' => 'pt-3',
	'pb' => 'pb-1',
) );

get_template_part( 'template-parts/blocks/cols-4-contacts', null, array(
	'pb' => 'pb-5',
) );

get_template_part( 'template-parts/blocks/cols-2-call-to-action', null, array(
	'background' => 'i c',
	'pb' => 'pb-2',
) );
