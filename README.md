Rapid Application Development : Auto-Registration
=================================================
Auto register some common services

[![Build Status](https://travis-ci.org/KnpLabs/rad-auto-registration.svg?branch=master)](https://travis-ci.org/KnpLabs/rad-auto-registration)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/KnpLabs/rad-auto-registration/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/KnpLabs/rad-auto-registration/?branch=master)

#Installation

```bash
composer require knplabs/rad-auto-registration ~1.0
```

```php
class AppKernel
{
    function registerBundles()
    {
        $bundles = array(
            //...
            new Knp\Rad\AutoRegistration\Bundle\AutoRegistrationBundle(),
            //...
        );

        //...

        return $bundles;
    }
}
```

#Usages

##Doctrine repositories auto-registration
Just activate `doctrine' (or `doctrine_odm` depending of your needs) into your configuration.

```yaml
knp_rad_auto_registration:
    doctrine: true
    doctrine_odm: true
```

Now all repositories are auto-registred.

| Entity                                | Repository                                       |
| ------------------------------------- | ------------------------------------------------ |
| MyProjectBundle\Entity\User           | my_project_bundle.entity.user_repository         |
| TheOtherBundle\Entity\Model\Address   | the_other_bundle.model.user.address_repository   |
| MyProjectBunde\Document\User          | my_project_bundle.document.user_repository       |
