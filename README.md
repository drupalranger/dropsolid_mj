## Overview
This module contains basic implementation of [Mailjet](https://mailjet.com) API. For now it provides a customizable block with Mailjet subscription form.   
## Requirements 
1. Module files 
2. Mailjet account & API keys - log in into Mailjet dashboard and visit [API Key Management page](https://app.mailjet.com/account/api_keys) to get your keys
3. [Mailjet PHP library v3](https://github.com/mailjet/mailjet-apiv3-php)
## Installation
1. Extract module to **/modules** directory in your docroot, or any other subdirectory where you store your contrib modules
2. Using composer, add Mailjet PHP library as a dependency, to do so type **composer require mailjet/mailjet-apiv3-php**
3. Visit **/admin/extend** page and install a module 
## Configuration 
1. Visit **/admin/config/services/mailjet** and paste your API keys and submit the form 
2. If your API keys are valid, you should see new select list widget, containing all of your Contact lists from Mailjet account, pick Contact list you want to use, and save configuration. You can also customize block settings ( title, description, submit button text ) if you want. 
3. Navigate to **/admin/structure/block** and place Mailjet subscription block in any region, save configuration, you're done. 
