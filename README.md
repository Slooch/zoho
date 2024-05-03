<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Requirements

* PHP >= 8.0
* Laravel >= 8.0

## Registering a Zoho Client

Since Zoho CRM APIs are authenticated with OAuth2 standards, you should register your client app with Zoho. To register
your app:

1. Visit this page [https://api-console.zoho.com/](https://api-console.zoho.com)
2. Click on `ADD CLIENT`.
3. Choose a `Self Client`.
4. Create grant token by providing the necessary scopes, time duration (the duration for which the generated token is
   valid) and Scope Description.
5. Your Client app would have been created and displayed by now.
6. Select the created OAuth client.
7. User this scope `aaaserver.profile.READ,ZohoCRM.modules.ALL,ZohoCRM.settings.ALL` when you create the grant token.

## Installation

You can install the package via `composer require`:

```bash
composer require asciisd/zoho-v3
```
After installing the package you can publish the config file with:

```bash
php artisan vendor:publish --tag="zoho-v3-config"
```

after that you need to create the OAuth client and get the credentials from Zoho by run the following command:

```bash
php artisan zoho:install
```

You'll need to add the following variables to your .env file. Use the credentials previously obtained registering your
application.

```dotenv
ZOHO_AUTH_FLOW_TYPE=grantToken
ZOHO_CLIENT_ID="Code from Client Secrit Section"
ZOHO_CLIENT_SECRET="Code from Client Secrit Section"
ZOHO_REDIRECT_URI="${APP_URL}/zoho/oauth2callback"
ZOHO_CURRENT_USER_EMAIL=admin@example.com
ZOHO_TOKEN="Code Generated from last step"

# available datacenters (USDataCenter, EUDataCenter, INDataCenter, CNDataCenter, AUDataCenter)
ZOHO_DATACENTER=USDataCenter
ZOHO_SANDBOX=true
```

After that you need to run the following command to add token and refresh token to your storage

```bash
php artisan zoho:grant
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="zoho-v3-config"
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="zoho-v3-migrations"
php artisan migrate
```

### Environments
maybe in some cases you wish to enforce zoho to use one of zoho's environments, so you can go to `AppServiceProvider`
and use `Zoho::useEnvironment()` method

```php
Zoho::useEnvironment(EUDataCenter::DEVELOPER());
```

So that will override config settings.

## Usage

Imagine that you need to get all modules from Zoho system.

```php
use Asciisd\Zoho\ZohoManager;

$response = ZohoManager::make(self::TESTING_MODULE);
$modules  = $response->getAllModules();
```
