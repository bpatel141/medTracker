# Drug Search and Tracker API

A Laravel 12-based RESTful API that allows users to register, log in, search for drug information via the RxNorm API, and manage their personal medication list.

## 🚀 Features

- User Registration and Login (Authentication with Sanctum)
- Public drug search using RxNorm API
- View ingredients and dosage information
- Add/Delete drugs to/from a personal medication list
- Rate limiting and caching for optimized performance
- Unit testing with over 90% coverage
- Postman Collection for testing endpoints

---

## 📂 Installation

1. **Clone the repository**
`git clone https://github.com/your-username/drug-tracker-api.git`
`cd drug-tracker-api`

2. **composer install**
`composer install`

3. **Set up database**
`Update your .env file with your database credentials, then run:`
`php artisan migrate`

4. **Run the server**
`php artisan serve`


## 📂 API Details 
## Authentication

**Register user**
`Endpoint : /api/register`
`header : Accept:application/json`
`method : POST`
`body params : email,password,name`
`response` : 
{
    "success": true,
    "code": 200,
    "message": "User registered successfully.",
    "data": {
        "token": "", //token 
        "id": 1,
        "name": "Alex",
        "email": "alex@mailinator.com"
    }
}

**login user**
`Endpoint : /api/login`
`header : Accept:application/json`
`method : POST`
`body params : email,password`
`response` : 
{
    "success": true,
    "code": 200,
    "message": "Login successful.",
    "data": {
        "token": "", //valid token
        "id": 1,
        "name": "Alex",
        "email": "alex@mailinator.com"
    }
}

**Search Drug** 
## Public Search

`Endpoint : /api/drugs/search?drug_name=cymbalta`
`header : Accept:application/json`
`method : GET`
`response` : 
{
    "success": true,
    "code": 200,
    "message": "Success",
    "data": [
        {
            "rxcui": "596928",
            "drug_name": "duloxetine 20 MG Delayed Release Oral Capsule [Cymbalta]",
            "base_ingredients": [
                "duloxetine"
            ],
            "dose_forms": [
                "Oral Product",
                "Pill"
            ]
        }
    ],
    "query": "cymbalta"
}

**Add User Medication API (Authenicated)**

`Endpoint : /api/user/medications`
`header : Accept:application/json`
`method : POST`
`body params : rxcui`
`response` : 
{
    "success": true,
    "code": 200,
    "message": "User Medication added.",
    "data": []
}

**Delete User Medication API (Authenicated)**
`Endpoint : /api/user/medications/{rxcui}`
`header : Accept:application/json`
`method : DELETE`
`response` : 
{
    "success": true,
    "code": 200,
    "message": "User Medication deleted successfully.",
    "data": []
}

**Get User Medication API (Authenicated)**
`Endpoint : /api/user/medications`
`header : Accept:application/json`
`method : GET`
`response` : 
{
    "success": true,
    "code": 200,
    "message": "Success",
    "data": [
        {
            "rxcui": "596928",
            "drug_name": "duloxetine 20 MG Delayed Release Oral Capsule [Cymbalta]",
            "baseNames": [
                "duloxetine"
            ],
            "dose_forms_group": [
                "Oral Product",
                "Pill"
            ]
        }
    ]
}

**Logout (Authenicated)**
`Endpoint : /api/logout`
`header : Accept:application/json`
`method : POST`
`response` : 
{
    "success": true,
    "code": 200,
    "message": "Logout Successfully.",
    "data": []
}

## 📄 Postman Collection

Download and import the Postman collection to test the API:

[📥 Download medTracker.postman_collection.json](medTracker.postman_collection.json)
