# B2BackblazeBundle

This bundle is a Symfony2 wrapper for [b2backblaze library](http://github.com/kamilZ/b2backblaze)

### About B2 Cloud Storage

[B2 Cloud Storage](https://www.backblaze.com/b2/cloud-storage.html) is a cloud service for storing files in the cloud. Files are available for download at any time, either through the API or through a browser-compatible URL.

The documentation for B2 Cloud storage can be found at
[https://www.backblaze.com/b2/docs/](https://www.backblaze.com/b2/docs/).

Installation
------------

The recommended way to install this bundle is through [Composer](http://getcomposer.org).


# Install Composer
curl -sS https://getcomposer.org/installer | php

# Add B2BackblazeBundle as a dependency

    php composer.phar require "kamilz/b2backblaze-bundle":"dev-master"

# Enable the bundle in the kernel

    <?php
    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new KamilZ\B2BackblazeBundle\B2BackblazeBundle(),
            // ...
        );
    }
# Add required configuration
    ##config.yml

    b2_backblaze:
        account_id: YOUR_ID
        account_key: YOUR_ACCCOUNT_SECRET_KEY
        bucket_id: YOUR_BUCKET_ID
        bucket_region: 'https://fxxx.backblaze.com/file/'
        timeout: 2000

# Usage

Upload File:

    <?php
        (...)
        $b2 = $this->get("backblaze.b2");

        $b2->uploadFile("...test.png","test.png");
        or
        $b2->uploadFile($file,"test.png");
        (...)


Twig image filter:

    {# twig template #}
        (..)

        <img src="{{ app.user.avatar|b2 }}" alt="">

        (..)



# License

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE
