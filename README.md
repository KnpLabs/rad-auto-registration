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
            new Knp\Rad\AutoRegistration\Bundle\AutoRegistrationBundle($this), // !! Do not forgot to inject the kernel !!
            //...
        );

        //...

        return $bundles;
    }
}
```

#Usages

##Doctrine repositories auto-registration
Just activate `doctrine' (or `doctrine_mongodb` ou `doctrine_couchdb` depending of your needs) into your configuration.

```yaml
knp_rad_auto_registration:
    services:
        doctrine: true
        doctrine_mongodb: true
        doctrine_couchdb: true
```

Now all repositories are auto-registred.

| Entity                                | Repository                                       |
| ------------------------------------- | ------------------------------------------------ |
| MyProjectBundle\Entity\User           | my_project_bundle.entity.user_repository         |
| TheOtherBundle\Entity\Model\Address   | the_other_bundle.model.user.address_repository   |
| MyProjectBunde\Document\User          | my_project_bundle.document.user_repository       |

###Requirements

Your doctrine entity (or documents) should be stored under `Entity`, `Document` or `Model` sub-namespace.

##Form types and form type extensions
Just activate `form_type' and/or `form_type_extension` into your configuration.

```yaml
knp_rad_auto_registration:
    services:
        form_type: true
        form_type_extension: true
```

Now all form types and form type extensions are auto-registred.

###Requirements

Your form types and form type extensions should be stored under `Form` sub-namespace.

#Restrictions

##Autoloading

- Only services without constructor ou without required parameters into the constructor will be auto-registred.
- If a service already exists, it will not be erased.

##Bundle

You can apply auto-registration on only certains bundle. You just have to add a `bundles` configuration:

```yaml
knp_rad_auto_registration:
    services:
        # ...
    bundles: [ App, ProductBundle ]
```
