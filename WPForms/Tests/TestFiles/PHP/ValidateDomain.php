<?php

// phpcs:disable WordPress.Security.EscapeOutput.UnsafePrintingFunction

__( 'Valid', 'wpforms-phpcs' );
_e( 'Valid', 'wpforms-phpcs' );
_x( 'Valid', 'context', 'wpforms-phpcs' );
esc_html__( 'Valid', 'wpforms-phpcs' );
esc_html_e( 'Valid', 'wpforms-phpcs' );
esc_html_x( 'Valid', 'context', 'wpforms-phpcs' );
esc_attr__( 'Valid', 'wpforms-phpcs' );
esc_attr_e( 'Valid', 'wpforms-phpcs' );
esc_attr_x( 'Valid', 'context', 'wpforms-phpcs' );
_n( 'Valid', 'Valid', 10, 'wpforms-phpcs' );
_ex( 'Valid', 'Valid', 'wpforms-phpcs' );
_nx( 'Valid', 'Valid', 10, 'Valid', 'wpforms-phpcs' );


__( 'Invalid', 'wpforms' );
_e( 'Invalid', 'wpforms-geolocation' );
_x( 'Invalid', 'context', 'wpforms-lite' );
esc_html__( 'Invalid', 'wpforms-lite' );
esc_html_e( 'Invalid', 'wpforms-lite' );
esc_html_x( 'Invalid', 'context', 'wpforms-lite' );
esc_attr__( 'Invalid', 'wpforms-lite' );
esc_attr_e( 'Invalid', 'wpforms-lite' );
esc_attr_x( 'Invalid', 'context', 'wpforms' );
_n( 'Invalid', 'Invalid', 10, 'wpforms' );
_ex( 'Invalid', 'Invalid', 'wpforms' );
_nx( 'Invalid', 'Invalid', 10, 'Invalid', 'wpforms' );


// Additional two cases of valid syntax when text domain is not specified.
esc_html__( 'Valid' );

// Not a string as the last argument.
$bulk_counts['read'] = 25;
_n( '%d entry.', '%d entries.', $bulk_counts['read'] );

// Nested parenthesis with domain specified as the last argument.
_n(
	'Found <strong>%s entry</strong>',
	'Found <strong>%s entries</strong>',
	absint( count( $this->entries->items ) ),
	'wpforms'
);

// phpcs:enable WordPress.Security.EscapeOutput.UnsafePrintingFunction
