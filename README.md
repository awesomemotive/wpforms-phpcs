# WPForms Coding Standards

Maintainers: The WPForms team

License: GPLv2 any later version.<br/>
License URI: http://www.gnu.org/licenses/gpl-2.0.html

## Description

WPForms coding standards are based on the [WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards) and the [PHPCompatibility Coding Standards](https://github.com/PHPCompatibility/PHPCompatibility) and help create strict and high-quality code.

## Installation

```
composer config repositories.repo-name vcs https://github.com/awesomemotive/wpforms-phpcs.git
composer require awesomemotive/wpforms-phpcs --dev
```

## Configuration

Create the `.phpcs.xml` or `phpcs.xml` file at the root of your project:

```xml
<?xml version="1.0"?>
<ruleset name="WPForms CS">
	<description>The WPForms coding standard.</description>

	<exclude-pattern>\vendor/*</exclude-pattern>
	<exclude-pattern>\.github/*</exclude-pattern>

	<config name="testVersion" value="7.2-"/>
	<config name="multi_domains" value="true"/>
	<config name="minimum_supported_wp_version" value="5.5"/>

	<rule ref="WPForms"/>

	<rule ref="WPForms.PHP.ValidateDomain">
		<properties>
			<property name="wpforms-lite" value="wpforms"/>
			<property name="wpforms" value="wpforms/pro/,wpforms/src/Pro/"/>
		</properties>
	</rule>
</ruleset>
```

## Sniffs detail

### Validate Text Domain Sniff

The `WPForms.PHP.ValidateDomain` sniff validates that you are using the correct text domain for `i18n` functions such as `__()`, `_e()`, `_n()`, etc.

By default, this sniff works for one domain in the project. We get a directory name based on the `vendor` directory or the location of the `phpcs.xml`/`.phpcs.xml` file.

You can install our package to the `plugins` directory and enable the multi-domain mode. in this case, the text domain will be the next folder name in the path. Structure:

```
../wp-content/plugins/          # → Root
├── wpforms/                    # → `wpforms` domain.
└── wpforms-stripe/             # → `wpforms-stripe` domain.
```

In your config you should enable the `multi_domains` property:

```xml
<?xml version="1.0"?>
<ruleset name="WPForms CS">
	<!-- ... -->
	<config name="multi_domains" value="true"/>
	<!-- ... -->
</ruleset>
```

If you have different domains for directories inside your project (for example, for free and paid versions) and want to redefine the text domain for some paths:
```
../wp-content/plugins/          # → Root
├── wpforms/                    # → `wpforms-lite` domain.
│   ├── pro/                    # → `wpforms` domain.
│   └── src/                    # → `wpforms-lite` domain.
│       ├── Admin/              # → `wpforms-lite` domain.
│       └── Pro/                # → `wpforms` domain.
└── wpforms-stripe/             # → `wpforms-stripe` domain.
```

In this case, you should add to the config file the property with `name` as a text domain and `value` as a path. If a domain has several paths, then list them via commas.

```xml
<?xml version="1.0"?>
<ruleset name="WPForms CS">
	<!-- ... -->
	<config name="multi_domains" value="true"/>
	<rule ref="WPForms.PHP.ValidateDomain">
		<properties>
			<property name="wpforms-lite" value="wpforms"/>
			<property name="wpforms" value="wpforms/pro/,wpforms/src/Pro/"/>
		</properties>
	</rule>
	<!-- ... -->
</ruleset>
```
