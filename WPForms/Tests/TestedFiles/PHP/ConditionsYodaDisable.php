<?php

// phpcs:disable Squiz.Operators.ValidLogicalOperators.NotAllowed
// phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
// phpcs:disable Generic.CodeAnalysis.EmptyStatement.DetectedIf
// phpcs:disable WPForms.Comments.EmptyLineAfterAssigmentVariables.AddEmptyBlank

// Used Yoda.

$foo === $bar;
$foo === 123;
$foo === true;
$foo === false;
$foo === null;
$foo === [];
$foo === $this->foo();
$this->foo() === $foo;
[ 123 ] === [ 123 ];
BAR === 123;
Foo::BAR === 123;
Foo::BAR === 123.0;
$e === \Foo\Bar\Baz::BAR;
$foo === Foo::BAR;
$foo + 2 === Foo::BAR;
$this->foo() === Foo::BAR;
$foo === - 1;
$foo === + 1;
count( $cartItem->getReservations() ) !== $neededReservationsAmount;
$optionalPartOpeningBracePosition !== strlen( $part ) - 1;
$optionalPartOpeningBracePosition !== \Nette\Utils\Strings::length( $part ) - 1;
foo() + 2 === Foo::BAR;

if (
	$foo( $bar ) === [ Foo::BAR, Foo::BAZ ] && (
		$bar === true ||
		$bar === null
	)
) {
}

if (
	$foo( $bar ) === [ Foo::BAR, Foo::BAZ ] && (
		$bar === true ||
		$bar === null
	)
) {
}

(int) $foo === $bar;
$foo === (int) $bar;
Foo::$bar === $foo;

switch ( true ) {
	case $parsedTime['is_localtime'] && $parsedTime['zone_type'] === self::TIMEZONE_PHP_TYPE_OFFSET:
		$timezoneOffsetInMinutes = ( - 1 ) * $parsedTime['zone'];
		$timezoneOffsetHours     = (int) floor( $timezoneOffsetInMinutes / 60 );
		$timezoneOffsetMinutes   = $timezoneOffsetInMinutes % 60;
		$timezone                = sprintf( '%+02d:%02d', $timezoneOffsetHours, $timezoneOffsetMinutes );
		break;
	case $parsedTime['is_localtime'] && $parsedTime['zone_type'] === self::TIMEZONE_PHP_TYPE_ABBREVIATION:
		$timezone = $parsedTime['tz_abbr'];
		break;
	case $parsedTime['is_localtime'] && $parsedTime['zone_type'] === self::TIMEZONE_PHP_TYPE_IDENTIFIER:
		$timezone = $parsedTime['tz_id'];
		break;
	default:
		$timezone = 'UTC';
}

[ $username === self::ADMIN_EMAIL ? self::ROLE_ADMIN : self::ROLE_CUSTOMER ];
[ $username === self::ADMIN_EMAIL ? self::ROLE_ADMIN : self::ROLE_CUSTOMER ];
[ $array === [] ];
[ $array === [] ];
$x = $username === [ $a, $b, $c ];

$param === A::TYPE_A and $param === A::TYPE_B;
$param === A::TYPE_A or $param === A::TYPE_B;
$param === A::TYPE_A xor $param === A::TYPE_B;
if (
	! empty( $contact_custom_fields ) &&
	(
		// If current value was set and equal with new value.
		(
			isset( $contact_custom_fields[ $ac_field_id ] ) &&
			$contact_custom_fields[ $ac_field_id ] === $ac_field_value
		) ||

		// If current value not set before (or empty) and new field value is empty too.
		(
			empty( $contact_custom_fields[ $ac_field_id ] ) &&
			empty( $ac_field_value )
		)
	)
) {
	return;
}

if ( $parent->ID === $child->{$ref_parent} ) {
}

/**
 * Errors
 */

123 === $foo;
true === $foo;
false === $foo;
null === $foo;
[] === $foo;
[] === $foo;
\Foo\Bar::BAR === $e;
Foo::BAR === foo();
Foo::BAR === foo() + 2;
Foo::BAR === $foo;
Foo::BAR === $foo + 2;
Foo::BAR === $this->foo();
- 1 === $foo;
+ 1 === $foo;
(int) FOO === $bar;
[ self::ADMIN_EMAIL === $username ? self::ROLE_ADMIN : self::ROLE_CUSTOMER ];
[ self::ADMIN_EMAIL === $username ? self::ROLE_ADMIN : self::ROLE_CUSTOMER ];
[ [] === $array ];
[ [] === $array ];
A::TYPE_A === $param and A::TYPE_B === $param;
$param === A::TYPE_A or A::TYPE_B === $param;
A::TYPE_A === $param xor $param === A::TYPE_B;
$x = [ $a, $b, $c ] === $username;
