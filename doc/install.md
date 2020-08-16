## Requirement

- eZPlatform 3.1+
- PHP7.3

## Installation steps

### Use composer

```
composer require arfaram/ezplatform-hubspot
```

### Activate the Bundle in `bundles.php`

```
EzPlatform\HubSpotBundle\EzPlatformHubSpotBundle::class => ['all' => true],
```

### Add Routes `config/routes/ezplatform-hubspot.yaml`

```
ez_platform_hubspot:
    resource: "@EzPlatformHubSpotBundle/Resources/config/routing.yaml"
    prefix:   /
```

### Create the DB table:

```
php bin/console doctrine:schema:validate --em hubspot
php bin/console doctrine:schema:update --dump-sql --em hubspot
php bin/console doctrine:schema:update --dump-sql --em hubspot --force
```

or use the sql statement in `doc/sql/query.sql`

### Install bundles web assets under the public directory

```
php bin/console assets:install --symlink --relative
```

### Update the autoloader

```
composer dumpautoload 
```

### Clear caches (dev+prod)

```
php bin/console c:c -e dev
php bin/console c:c -e prod
```

### Run webpack

```
yarn encore dev
```