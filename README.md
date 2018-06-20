# **V**oyager - Appcenter crashes report

A simple utility that can be used to see all crashes in app center of Microsoft.

## Installation Steps

### 1. Require the Package

it's so simple that you just need execute this command

```bash
php artisan hook:install voyager-appcenter-crashes
```
### 2. Config env


this package needs a .env file variable. You can find this information at https://appcenter.ms

```bash
APPCENTER_OWNER_APP=YOUR-OWNER-APP 
APPCENTER_APP_NAME=YOUR-APP-NAME,YOUR-APP-NAME,YOUR-APP-NAME... 
APPCENTER_API_TOKEN=YOUR-ACCESS-TOKEN
APPCENTER_LAST_OCCURRENCE_FROM=01-01-2000 
```

Example format date d-m-Y (default: 01-01-2000)


### 3. Check the view 

Example url:
http://localhost:8000/admin/crashes