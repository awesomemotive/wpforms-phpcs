# WPForms Coding Standards for PHP_CodeSniffer

Coding standards for WPForms team then allow keeping code strict and consistently high quality. WPForms coding standards based on the [WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards) and the [PHPCompatibility Coding Standards](https://github.com/PHPCompatibility/PHPCompatibility).

## Installation

```
composer require ... --dev
```

## Configuration

Create the `.phpcs.xml` or `phpcs.xml` file at the root of your project:

```xml
<?xml version="1.0"?>
<ruleset name="WPForms CS">
	<description>The code standard for WPForms.</description>

	<exclude-pattern>\vendor/*</exclude-pattern>
	<exclude-pattern>\.github/*</exclude-pattern>

	<config name="testVersion" value="5.5-"/>
	<config name="multi_domains" value="true"/>
	<config name="minimum_supported_wp_version" value="4.9"/>
    
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

### Validate Domain Sniff

The `WPForms.PHP.ValidateDomain` sniff validate that you're using the correct domain for i18n functions such as `__()`, `_e()`, `_n()`, etc.

This sniff has a lot of settings. By default, it works for 1 domain-based project. We get a directory name where based on the `vendor` directory or the `phpcs.xml`/`.phpcs.xml` file.

Also, you can install our package to the plugins directory and enable the multi-domain mode.

Also, you can install our package to the `plugins` directory and enable the multi-domain mode. Then domain will the next folder name in the path. Structure:

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

The next point if you have different domains for some directories (for example, for free and paid versions) inside your project and going to rewrite the domain for some paths:

```
../wp-content/plugins/          # → Root
├── wpforms/                    # → `wpforms-lite` domain.
│   ├── pro/                    # → `wpforms` domain.
│   └── src/                    # → `wpforms-lite` domain.
│       ├── Admin/              # → `wpforms-lite` domain.
│       └── Pro/                # → `wpforms` domain.
└── wpforms-stripe/             # → `wpforms-stripe` domain.
```

In your config, you should set the property with the `name` as a domain name and the `value` as a path. If this domain has a few paths, then pass them throw a comma.

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

