# ACF for OffbeatWP

1. Adds getField functionality to the PostModel
2. Adds getField functionality to the TermModel
3. Registers relationships between posts in the OffbeatWP way.

Install by running this command from the root of your OffbeatWP Theme:

```bash
composer require offbeatwp/acf
```

Next add the following line to your `config/services.php` file:

```
OffbeatWP\Acf\Service::class,
```