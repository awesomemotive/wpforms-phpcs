# Changelog
All notable changes to this project will be documented in this file and formatted via [this recommendation](https://keepachangelog.com/).

## [1.0.7] - 2023-08-23
### Added
- Support of WP Coding Standard 3.0.

## [1.0.6] - 2022-09-20
### Fixed
- Validation of text domains in addons.

## [1.0.5] - 2022-09-12
### Fixed
- Lowercase classname without namespace.
- Since tag processing.
- Expected domain calculation for dirs not specified in the ValidateDomain property.
- Fix expected domain calculation for the same dir where phpcs.xml is.

### Added
- Respect PhpDoc comments in switch statement.

## [1.0.4] - 2022-03-09
### Added
- Allow comment before switch and case statements.

## [1.0.3] - 2022-02-24
### Added
- Exclude comment lines from consideration.

### Changed
- Improve skipping parenthesis in arguments.

### Fixed
- Wrong claiming of short syntax.
- Wrong counting of hook params with nested parenthesis.
- Fix InvalidParamTagsQuantity sniff.
- Respect /** This action is documented in some-class.php */ comment.
- Alignment of @param tags.

## [1.0.2] - 2022-01-11
### Fixed
- Prevent endless loop in HooksMethodSniff.

## [1.0.1] - 2022-01-11
### Fixed
- Validation of text domain via property in phpcs.xml.

## [1.0.0] - 2022-01-06
- Initial release.
