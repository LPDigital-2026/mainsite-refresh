<?php
/**
 * The areas Lily Paw Digital serves, by county. One list feeds both the
 * Areas We Cover page (tools/make-areas-page.py writes it into
 * inc/pages/areas-we-cover.html) and the business's areaServed in the
 * structured data, so they always agree. After editing, re-run
 * python3 tools/make-areas-page.py and the fingerprint script.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function lpd_service_areas() {
	return array(
		'Tyne and Wear'  => array( 'Newcastle upon Tyne', 'Gateshead', 'Sunderland', 'South Shields', 'North Shields', 'Tynemouth', 'Whitley Bay', 'Wallsend', 'Jarrow', 'Hebburn', 'Washington', 'Houghton-le-Spring', 'Ryton', 'Blaydon', 'Whickham' ),
		'Northumberland' => array( 'Hexham', 'Morpeth', 'Cramlington', 'Blyth', 'Ashington', 'Bedlington', 'Ponteland', 'Prudhoe', 'Alnwick', 'Amble', 'Berwick-upon-Tweed' ),
		'County Durham'  => array( 'Durham', 'Chester-le-Street', 'Consett', 'Stanley', 'Seaham', 'Peterlee', 'Spennymoor', 'Bishop Auckland', 'Newton Aycliffe', 'Barnard Castle' ),
		'Tees Valley'    => array( 'Middlesbrough', 'Stockton-on-Tees', 'Darlington', 'Hartlepool', 'Redcar', 'Billingham', 'Thornaby', 'Yarm', 'Guisborough', 'Saltburn-by-the-Sea' ),
	);
}

/**
 * areaServed for the structured data: the region, each county, and each town.
 */
function lpd_service_areas_schema() {
	$out = array( array( '@type' => 'AdministrativeArea', 'name' => 'North East England' ) );
	foreach ( lpd_service_areas() as $county => $towns ) {
		$out[] = array( '@type' => 'AdministrativeArea', 'name' => $county );
		foreach ( $towns as $town ) {
			$out[] = array( '@type' => 'City', 'name' => $town );
		}
	}
	return $out;
}
