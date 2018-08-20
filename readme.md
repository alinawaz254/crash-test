# Vehicle's Crash Test and Ratings Application

## Requirements

- PHP 7.1.3 or greater
- Laravel 5.6

## Installation

<p>Clone this repository by running: git clone https://github.com/alinawaz254/crash-test.git</p>
<p>Then install required dependencies by running: composer install</p>

## Sample Requests

### If you are using XAMPP/WAMP

```
- GET http://localhost/crash-test/public/api/vehicles/<MODEL YEAR>/<MANUFACTURER>/<MODEL>
- GET http://localhost/crash-test/public/api/vehicles/<MODEL YEAR>/<MANUFACTURER>/<MODEL>?withRating=true

Where:
* '<MODEL YEAR>', '<MANUFACTURER>' and '<MODEL>' are variables that are used when calling the NHTSA API.
* Example values for these are:
* '<MODEL YEAR>': 2015
* '<MANUFACTURER>': Audi
* '<MODEL>': A3

- POST http://localhost/crash-test/public/api/vehicles (with following JSON body)
{
    "modelYear": 2015,
    "manufacturer": "Audi",
    "model": "A3"
}
```

### If you are using Virtual Environment

```
- GET http://localhost:<port>/api/vehicles/<MODEL YEAR>/<MANUFACTURER>/<MODEL>
- GET http://localhost:<port>/api/vehicles/<MODEL YEAR>/<MANUFACTURER>/<MODEL>?withRating=true

Where:
* '<MODEL YEAR>', '<MANUFACTURER>' and '<MODEL>' are variables that are used when calling the NHTSA API.
* Example values for these are:
* '<MODEL YEAR>': 2015
* '<MANUFACTURER>': Audi
* '<MODEL>': A3

- POST http://localhost:<port>/api/vehicles (with following JSON body)
{
    "modelYear": 2015,
    "manufacturer": "Audi",
    "model": "A3"
}
```
Note: Please use /path-to-project/public as DocumentRoot for this application, otherwise you can prefix /api with /public so that it will be used as localhost:[port]/public/api/

## Response

### Example success output of API without 'withRating' parameter in query string:

```
{
    "Count": 4,
    "Results": [
        {
            "VehicleId": 9403,
            "Description": "2015 Audi A3 4 DR AWD"
        },
        {
            "VehicleId": 9408,
            "Description": "2015 Audi A3 4 DR FWD"
        },
        {
            "VehicleId": 9405,
            "Description": "2015 Audi A3 C AWD"
        },
        {
            "VehicleId": 9406,
            "Description": "2015 Audi A3 C FWD"
        }
    ]
}
```

### Example success output of API with 'withRating' parameter in query string:

```
{
    "Count": 4,
    "Results": [
        {
            "VehicleId": 9403,
            "Description": "2015 Audi A3 4 DR AWD",
            "CrashRating": "5"
        },
        {
            "VehicleId": 9408,
            "Description": "2015 Audi A3 4 DR FWD",
            "CrashRating": "5"
        },
        {
            "VehicleId": 9405,
            "Description": "2015 Audi A3 C AWD",
            "CrashRating": "Not Rated"
        },
        {
            "VehicleId": 9406,
            "Description": "2015 Audi A3 C FWD",
            "CrashRating": "Not Rated"
        }
    ]
}
```

### Example error (failure) output of API:

```
{
    "Count": 0,
    "Results": []
}
```
