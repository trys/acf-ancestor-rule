<?php
/*
Plugin Name: ACF Page Ancestor Rules
Description: Ancestor-based rules for ACF
Plugin URI: http://www.tomango.co.uk
Author: Tomango
Author URI: http://www.tomango.co.uk
Version: 1.0
*/

function acfancestors_acf_location_rules_types($choices) {
	$choices['Page']['ancestor'] = 'Ancestor';
	return $choices;
}
add_filter('acf/location/rule_types', 'acfancestors_acf_location_rules_types');


function acfancestors_acf_location_rules_values_ancestor($choices) {
	$groups = acf_get_grouped_posts(array(
		'post_type' => 'page'
	));
	
	if( !empty($groups) ) {
		foreach( array_keys($groups) as $group_title ) {
			$posts = acf_extract_var( $groups, $group_title );

			foreach( array_keys($posts) as $post_id ) {
				$posts[ $post_id ] = acf_get_post_title( $posts[ $post_id ] );
			};
			
			$choices = $posts;
		}
	}

	return $choices;
}
add_filter('acf/location/rule_values/ancestor', 'acfancestors_acf_location_rules_values_ancestor');


function acfancestors_acf_location_rules_match_ancestor($match, $rule, $options) {
	if ( ! empty( $options[ 'post_id' ] ) ) {
		$found = in_array( $rule[ 'value' ], get_ancestors( $options[ 'post_id' ], 'page', 'post_type' ) );
		$match = $rule[ 'operator' ] === '==' && $found || $rule[ 'operator' ] === '!=' && ! $found;
	}

	return $match;
}
add_filter('acf/location/rule_match/ancestor', 'acfancestors_acf_location_rules_match_ancestor', 10, 3);