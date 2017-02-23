# localytics push sender

_this package is in development, for now it has only limited functionality_

**please if You do any modifications make pull request**
(_for now i use Parse SDK as reference for all criteria to allow fast migration from Parse to Localytics_)

there is one example to send iOS badge:

```php
        $data = [
            "device" => ["ios" => ["badge" => 1]]
        ];

        $query = new LocalyticsPush(
            config('localytics.api_key'), 
            config('localytics.api_sec'),
            config('localytics.app_key')
            );

        $query->equalTo('push_enabled', 1); //lookup for more criterias

        $query->send(str_slug('catalog-badge-brand-' . $catalogName), $data, "and");
```





next to to:
  - make code more abstract 
  - cover more criteria
  - `do some tests U lazy ***!` :angry:

contributors:
  - [urukalo](https://github.com/urukalo)
  - [You?](https://github.com/urukalo/localytics-push#fork-destination-box) (_just fork/change/make pull req_ )