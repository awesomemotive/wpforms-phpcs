<?php
// phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound

/**
 * Class A.
 *
 * @since 1.0.0
 */
class A {

}



/**
 * Class B.
 *
 * @since      1.0.0
 * @deprecated 1.0.1
 */
class B {

}

/**
 * Class C.
 *
 * @since      1.0.0
 * @deprecated 1.0.1
 *
 * @package    Deprecate
 */
class C {
}

/**
 * Invalid examples.
 */

/**
 * Class D is invalid.
 *
 * @since      1.0.0
 * @deprecated 1.0.1
 * @package    Deprecate
 */
class D {
}

/**
 * Class E is invalid.
 *
 * @since      1.0.0
 *
 * @deprecated 1.0.1
 *
 * @package    Deprecate
 */
class E {

}

/**
 * Class E is invalid.
 *
 * @since      1.0.0
 * @deprecated {VER}
 *
 * @package    Deprecate
 */
class F {

}

// phpcs:enable Generic.Files.OneObjectStructurePerFile.MultipleFound
