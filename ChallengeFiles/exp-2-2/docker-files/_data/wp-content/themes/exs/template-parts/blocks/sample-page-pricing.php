<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_template_part( 'template-parts/blocks/title-with-subtitle', null, array(
	'pt' => 'pt-3',
	'pb' => 'pb-1',
) );

get_template_part( 'template-parts/blocks/pricing-plan-columns',
	null,
	array(
		'pt' => 'pt-2',
		'pb' => 'pb-2',
	)
);

get_template_part(
	'template-parts/blocks/separator',
	null,
	array(
		'align'=> 'full',
		'mt' => 'mt-5',
		'mb' => 'mb-5',
		'className' => 'is-style-wide',
	)
);

get_template_part(
	'template-parts/blocks/cols-2-faq',
	null,
	array(
		'pt' => 'pt-1',
		'pb' => 'pb-4',
	)
);


