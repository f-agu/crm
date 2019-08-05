
> See https://keepachangelog.com/fr/1.0.0/

## [Unreleased]

Examples :

## [1.0.0] - 2019-08-05
### Added
- blabllas sdf qehqth qgh

### Changed
- djhfuihsf lh mkbjq mk jhsg sgkjfghghkj

### Fixed
- Crash with Spring Boot 2.1.0 (`mbeanExporter` already defined) 


## [1.4.0] - 2018-06-22
### Added
- Can disable

### Changed
- core3-app: 1.8.1 -> 1.9.0


## [1.3.5] - 2018-01-03
### Changed
- core3-app: 1.8.0 -> 1.8.1


## [1.3.4] - 2018-01-03
### Changed
- core3-app: 1.7.0 -> 1.8.0


## [1.3.3] - 2017-12-29
### Changed
- core3-app: 1.6.1 -> 1.7.0

### Fixed
- @EnableMBeanExport missing the local configuration


## [1.3.2] - 2017-12-27
### Changed
- core3-app: 1.5.0 -> 1.6.1


## [1.3.1] - 2017-12-07
### Fixed
- spring not being trully optional


## [1.3.0] - 2017-11-27
### Added
- MBeanRegister.with(String) to avoid catch MalformedObjectNameException
- ApplicationNameRegisterStrategy
- Spring ApplicationNameObjectNamingStrategy with autoconfig
- core3-app: 1.5.0

### Changed
- parent pom: 2.1.2 -> 2.1.9


## [1.2.2] - 2017-03-20
### Fixed
- nullable on CompositeData on each(...).value(...)


## [1.2.1] - 2017-03-20
### Changed
- parent pom: 2.1.1 -> 2.1.2


## [1.2.0] - 2017-02-17
### Added
- CompositeDataFactory: value(CompositeData)


## [1.1.0] - 2017-01-09
### Added
- Strategies : ignore, domainsuffix, sequence
- Strategies come from SPI


## [1.0.1] - 2017-01-03
### Changed
- Order to find strategy: property > set by dev > default


## [1.0.0] - 2017-01-03
### Added
- Move core3-jmx/CompositeDataFactory to this module
