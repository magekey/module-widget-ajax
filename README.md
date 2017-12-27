# Magento 2 Widget Ajax

Load widgets via ajax

## Features:

- **Content** > **Widgets** > [Select widget] > **Ajax Options** > **Ajax Enabled** field

## Installing the Extension

    composer require magekey/module-widget-ajax

## Deployment

    php bin/magento maintenance:enable                  #Enable maintenance mode
    php bin/magento setup:upgrade                       #Updates the Magento software
    php bin/magento setup:di:compile                    #Compile dependencies
    php bin/magento setup:static-content:deploy         #Deploys static view files
    php bin/magento cache:flush                         #Flush cache
    php bin/magento maintenance:disable                 #Disable maintenance mode

## Versions tested
> 2.2.2
