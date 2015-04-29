Rapid Application Development : Auto-Registration
=================================================
Auto register some common services

[![Build Status](https://travis-ci.org/KnpLabs/rad-auto-registration.svg?branch=master)](https://travis-ci.org/KnpLabs/rad-auto-registration)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/KnpLabs/rad-auto-registration/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/KnpLabs/rad-auto-registration/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/knplabs/rad-auto-registration/v/stable)](https://packagist.org/packages/knplabs/rad-auto-registration) [![Total Downloads](https://poser.pugx.org/knplabs/rad-auto-registration/downloads)](https://packagist.org/packages/knplabs/rad-auto-registration) [![Latest Unstable Version](https://poser.pugx.org/knplabs/rad-auto-registration/v/unstable)](https://packagist.org/packages/knplabs/rad-auto-registration) [![License](https://poser.pugx.org/knplabs/rad-auto-registration/license)](https://packagist.org/packages/knplabs/rad-auto-registration)

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
            new Knp\Rad\AutoRegistration\Bundle\AutoRegistrationBundle($this), // !! Do not forget to inject the kernel !!
            //...
        );

        //...

        return $bundles;
    }
}
```

#Usages

##Doctrine repositories auto-registration
Just activate `doctrine` (or `doctrine_mongodb` ou `doctrine_couchdb` depending on your needs) into your configuration.

```yaml
knp_rad_auto_registration:
    enable:
        doctrine: ~
        doctrine_mongodb: ~
        doctrine_couchdb: ~
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
Just activate `form_type` and/or `form_type_extension` into your configuration.

```yaml
knp_rad_auto_registration:
    enable:
        form_type: ~
        form_type_extension: ~
```

Now all form types and form type extensions are auto-registred.


###Requirements

Your form types and form type extensions should be stored under `Form` sub-namespace.

##Security voters
Just activate `security_voter` into your configuration.

```yaml
knp_rad_auto_registration:
    enable:
        security_voter: ~
```

Now all the voters are auto-registered.

###Requirements

Your security voters should be stored under `Security` sub-namespace.

##Twig extensions
Just activate `twig_extension` into your configuration.

```yaml
knp_rad_auto_registration:
    enable:
        twig_extension: ~
```

Now all Twig extensions are auto-registered.

###Requirements

Your Twig extensions should be stored under `Twig` or `Templating` sub-namespace.

#Restrictions

##Autoloading

- Only services without constructor or without required parameters into the constructor will be auto-registred.
- If a service already exists, it will not be erased.

##Bundle

You can apply auto-registration on only certains bundle. You just have to add a `bundles` configuration:

```yaml
knp_rad_auto_registration:
    enable:
        # ...
    bundles: [ App, ProductBundle ]
```
