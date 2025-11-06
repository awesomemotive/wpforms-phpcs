# WPForms PHPCS Rules

This document describes how WPForms coding standards differ from standard WordPress PHPCS rules. Use this as a reference when writing code for WPForms projects.

## Table of Contents

1. [Disabled WordPress PHPCS Rules](#disabled-wordpress-phpcs-rules)
2. [Modified WordPress PHPCS Rules](#modified-wordpress-phpcs-rules)
3. [Formatting Rules](#formatting-rules)
4. [PHP Rules](#php-rules)
5. [Comment & Documentation Rules](#comment--documentation-rules)

---

## Disabled WordPress PHPCS Rules

These WordPress PHPCS rules are **disabled** in WPForms projects:

### Spacing & Indentation
- **`Generic.WhiteSpace.ScopeIndent`** - Less strict indenting allowed
- **`Generic.Functions.FunctionCallArgumentSpacing`** - Less strict argument spacing
- **`WordPress.WhiteSpace.PrecisionAlignment`** - Precision alignment not enforced

### File Naming (PSR-4 Support)
- **`WordPress.Files.FileName.InvalidClassFileName`** - Allows PSR-4 class file naming
- **`WordPress.Files.FileName.NotHyphenatedLowercase`** - Allows PSR-4 naming conventions

### Comments
- **`Squiz.Commenting.InlineComment.SpacingAfter`** - Less strict inline comment spacing
- **`Squiz.Commenting.FileComment.*`** - File-level PHPDoc not required

### Yoda Conditions
- **`WordPress.PHP.YodaConditions.NotYoda`** - Yoda conditions are **NOT** required

**What this means:** Write conditions naturally (`$var === 'value'`), not in Yoda style (`'value' === $var`).

```php
// ✅ CORRECT - Natural style
if ( $status === 'active' ) {
    // ...
}

// ❌ WRONG - Yoda style not required
if ( 'active' === $status ) {
    // ...
}
```

### Array Syntax
- **`Universal.Arrays.DisallowShortArraySyntax`** - Short array syntax `[]` is **allowed and preferred**

```php
// ✅ CORRECT - Use short array syntax
$items = [ 'foo', 'bar', 'baz' ];

// ❌ WRONG - Long array syntax discouraged
$items = array( 'foo', 'bar', 'baz' );
```

### Other
- **`WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents`** - Direct `file_get_contents()` allowed

---

## Modified WordPress PHPCS Rules

These rules have different thresholds in WPForms:

### Cyclomatic Complexity
- **Complexity limit:** 6 (warning)
- **Absolute complexity limit:** 10 (error)

**What this means:** Keep functions simple with fewer conditional branches.

### Nesting Level
- **Absolute nesting level:** 3

**What this means:** Avoid deeply nested code. Maximum 3 levels of nesting.

```php
// ✅ CORRECT - 2 levels of nesting
if ( $condition ) {
    foreach ( $items as $item ) {
        process( $item );
    }
}

// ❌ WRONG - Too many nesting levels
if ( $condition ) {
    if ( $other ) {
        foreach ( $items as $item ) {
            if ( $item->valid ) {  // 4 levels!
                process( $item );
            }
        }
    }
}
```

---

## Formatting Rules

### Empty Line Before Return

**Rule:** Add an empty line before `return` statements (except when return is the only statement in function body).

```php
// ✅ CORRECT
public function get_name() {

    $name = $this->process_name();

    return $name;
}

// ✅ CORRECT - Single return statement, no empty line needed
public function get_id() {

    return $this->id;
}

// ❌ WRONG - Missing empty line before return
public function get_name() {

    $name = $this->process_name();
    return $name;
}
```

### Empty Line After Function Declaration

**Rule:** Add an empty line after the opening brace of a function (except for empty functions).

```php
// ✅ CORRECT
public function process() {

    $data = $this->get_data();
    return $data;
}

// ✅ CORRECT - Empty function, no empty line needed
public function __construct() {
}

// ❌ WRONG - Missing empty line after opening brace
public function process() {
    $data = $this->get_data();
    return $data;
}
```

### Empty Line After Assignment

**Rule:** Add an empty line after assignment statements (with exceptions).

**Exceptions:** No empty line needed when followed by:
- Closing brace `}`
- Another assignment statement
- Comments
- `break` in switch statement

```php
// ✅ CORRECT
$name = 'John';

$this->process( $name );

// ✅ CORRECT - Multiple assignments together
$first = 'John';
$last = 'Doe';

echo $first . ' ' . $last;

// ❌ WRONG - Missing empty line after assignment
$name = 'John';
$this->process( $name );
```

### Switch Statement Formatting

**Rules:**
1. Add empty line **before** `switch` statement
2. Add empty line **after** `switch` statement closing brace
3. No empty line between `case` statements
4. Add empty line before `case` after `break`
5. No empty line before `break`
6. No empty line before closing brace of `switch`

```php
// ✅ CORRECT
$result = some_function();

switch ( $status ) {
    case 'pending':
        do_pending();
        break;

    case 'active':
        do_active();
        break;

    default:
        do_default();
        break;
}

$next_step = process();

// ❌ WRONG - Missing empty lines
$result = some_function();
switch ( $status ) {
    case 'pending':
        do_pending();

        break;
    case 'active':
        do_active();
        break;
}
$next_step = process();
```

---

## PHP Rules

### Namespace & Use Statements

**Rule 1:** Prefer `use` statements over fully qualified names.

```php
// ✅ CORRECT
use WPForms\Admin\Settings;

$settings = new Settings();

// ❌ WRONG - Don't use fully qualified names
$settings = new \WPForms\Admin\Settings();
```

**Rule 2:** Remove leading backslash when only one level deep.

```php
// ✅ CORRECT
use DateTime;

// ❌ WRONG - Unnecessary leading backslash
use \DateTime;
```

**Rule 3:** Remove unused `use` statements.

### Yoda Conditions Disabled

**Rule:** Write conditions in natural order (variable on left, value on right).

```php
// ✅ CORRECT
if ( $count === 0 ) { }
if ( $item !== null ) { }
if ( $user->is_active() === true ) { }

// ❌ WRONG - Don't use Yoda conditions
if ( 0 === $count ) { }
if ( null !== $item ) { }
```

**Note:** The custom sniff actively enforces natural conditions.

### Hooks Organization

**Rule 1:** All `add_action()` and `add_filter()` calls must be inside a `hooks()` method within classes.

```php
// ✅ CORRECT
class MyClass {

    public function hooks() {

        add_action( 'init', [ $this, 'init' ] );
        add_filter( 'the_content', [ $this, 'filter_content' ] );
    }
}

// ❌ WRONG - Hooks outside of hooks() method
class MyClass {

    public function __construct() {

        add_action( 'init', [ $this, 'init' ] );
    }
}
```

### Hook Names

**Rule:** Hook names should start with the fully qualified class name converted to snake_case.

```php
// For class: WPForms\Admin\Settings

// ✅ CORRECT
do_action( 'wpforms_admin_settings_init' );
apply_filters( 'wpforms_admin_settings_data', $data );

// ❌ WRONG - Doesn't match class name pattern
do_action( 'my_custom_init' );
```

### Text Domain Validation

**Rule:** Use the correct text domain for i18n functions based on file location.

```php
// In wpforms-lite files
// ✅ CORRECT
__( 'Some text', 'wpforms-lite' );

// In wpforms pro files (src/Pro/)
// ✅ CORRECT
__( 'Some text', 'wpforms' );

// ❌ WRONG - Incorrect domain
__( 'Some text', 'wrong-domain' );

// ❌ WRONG - Missing domain
__( 'Some text' );
```

---

## Comment & Documentation Rules

### @since Tag

**Rule 1:** All functions, classes, interfaces, traits, and constants **must** have `@since` tag.

```php
// ✅ CORRECT
/**
 * Process user data.
 *
 * @since 1.5.0
 *
 * @param array $data User data.
 *
 * @return bool
 */
public function process( $data ) { }

// ❌ WRONG - Missing @since tag
/**
 * Process user data.
 *
 * @param array $data User data.
 *
 * @return bool
 */
public function process( $data ) { }
```

**Rule 2:** `@since` tag must have a valid version number (X.Y.Z format).

```php
// ✅ CORRECT
@since 1.5.0
@since 2.0.0

// ❌ WRONG
@since 1.5
@since v1.5.0
@since TBD
```

**Rule 3:** Add empty line after `@since` tag (except when followed by `@deprecated` or it's the last tag).

```php
// ✅ CORRECT
/**
 * Description.
 *
 * @since 1.5.0
 *
 * @param string $name Name.
 *
 * @return void
 */

// ✅ CORRECT - No empty line before @deprecated
/**
 * Description.
 *
 * @since 1.5.0
 * @deprecated 2.0.0
 *
 * @param string $name Name.
 */
```

### @deprecated Tag

**Rule 1:** `@deprecated` tag must have a valid version number.

**Rule 2:** Add empty line after `@deprecated` tag (unless it's the last tag).

### @param Tag Spacing

**Rule:** Only one space after `@param` tag.

```php
// ✅ CORRECT
@param string $name The name.

// ❌ WRONG - Multiple spaces
@param  string $name The name.
```

### Translator Comments

**Rules for `translators:` comments:**

1. Use colon after "translators"
2. Must have a description
3. Description must end with punctuation (`.`, `!`, `?`)
4. Use `/* */` style comments

```php
// ✅ CORRECT
/* translators: %s is the user's name. */
printf( __( 'Hello, %s!', 'wpforms' ), $name );

// ❌ WRONG - Missing colon
/* translators %s is the user's name. */
printf( __( 'Hello, %s!', 'wpforms' ), $name );

// ❌ WRONG - Missing description
/* translators: */
printf( __( 'Hello, %s!', 'wpforms' ), $name );

// ❌ WRONG - Missing punctuation
/* translators: %s is the user's name */
printf( __( 'Hello, %s!', 'wpforms' ), $name );

// ❌ WRONG - Wrong comment style
// translators: %s is the user's name.
printf( __( 'Hello, %s!', 'wpforms' ), $name );
```

### Hook Documentation

**Rule:** All `do_action()` and `apply_filters()` calls **must** have PHPDoc comment directly above them.

```php
// ✅ CORRECT
/**
 * Fires after settings are saved.
 *
 * @since 1.5.0
 *
 * @param array $settings The saved settings.
 */
do_action( 'wpforms_settings_saved', $settings );

// ❌ WRONG - Missing PHPDoc
do_action( 'wpforms_settings_saved', $settings );

// ❌ WRONG - PHPDoc not directly above
/**
 * Fires after settings are saved.
 */

do_action( 'wpforms_settings_saved', $settings );
```

### Language Injection Comments

**Special comments allowed (won't trigger PHPCS errors):**

```php
// ✅ CORRECT - PhpStorm/IntelliJ language injection
// language=JSON
$json = '{"foo": "bar"}';

// ✅ CORRECT - PHPDoc language injection
/**
 * @lang SQL
 */
$sql = "SELECT * FROM users";
```

---

## Quick Reference Summary

### Most Important Rules for Daily Coding

1. **Empty lines:**
   - After function opening brace
   - Before return statement (unless it's the only statement)
   - After assignments (with exceptions)
   - Before/after switch statements
   - Before case statements after break

2. **No Yoda conditions** - Write naturally: `$var === 'value'`

3. **Short array syntax** - Use `[]` not `array()`

4. **@since tag required** on all functions/classes with valid version

5. **Hooks in hooks() method** - Don't add hooks elsewhere in classes

6. **Text domain required** - Always specify domain in i18n functions

7. **Use statements over fully qualified names**

8. **Hook documentation required** - PHPDoc directly above all `do_action()`/`apply_filters()`

9. **Translator comments** - Must have colon, description, and punctuation

10. **Complexity limits** - Keep functions under 6 complexity, 3 nesting levels
