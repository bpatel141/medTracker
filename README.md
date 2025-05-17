# Drug Search and Tracker API

A Laravel 12-based RESTful API that allows users to register, log in, search for drug information via the RxNorm API, and manage their personal medication list.

---

## 🚀 Features

- ✅ User Registration and Login (Authentication with Sanctum)
- 🔍 Public drug search using RxNorm API
- 🧬 View ingredients and dosage information
- 💊 Add/Delete drugs to/from a personal medication list
- 🧠 Rate limiting and caching for optimized performance
- ✅ Unit testing with over 90% coverage
- 🧪 Postman Collection for testing endpoints

---

## 📂 Installation

1. **Clone the repository**
```bash
git clone https://github.com/your-username/drug-tracker-api.git
cd drug-tracker-api
```

2. **Install dependencies**
```bash
composer install
```

4. **Set up the database (db name : medtracker_db)**
- Update `.env` with your database credentials.
- Run migrations:
```bash
php artisan migrate
```

5. **Run the development server**
```bash
php artisan serve
```

---

## 🔐 Authentication Endpoints

### Register User

**Endpoint:** `POST /api/register`  
**Headers:** `Accept: application/json`  
**Body:**
```form-data
email:alex@mailinator.com
password:alex@123
name:alex
```

**Response:**
```json
{
  "success": true,
  "code": 200,
  "message": "User registered successfully.",
  "data": {
    "token": "token_here",
    "id": 1,
    "name": "Alex",
    "email": "alex@mailinator.com"
  }
}
```

---

### Login User

**Endpoint:** `POST /api/login`  
**Headers:** `Accept: application/json`  
**Body:**
``form-data
email:alex@mailinator.com
password:password
```

**Response:**
```json
{
  "success": true,
  "code": 200,
  "message": "Login successful.",
  "data": {
    "token": "valid_token",
    "id": 1,
    "name": "Alex",
    "email": "alex@mailinator.com"
  }
}
```

---

## 🌐 Public Drug Search (Unauthenticated)

**Endpoint:** `GET /api/drugs/search?drug_name=cymbalta`  
**Headers:** `Accept: application/json`  

**Response:**
```json
{
  "success": true,
  "code": 200,
  "message": "Success",
  "data": [
    {
      "rxcui": "596928",
      "drug_name": "duloxetine 20 MG Delayed Release Oral Capsule [Cymbalta]",
      "base_ingredients": ["duloxetine"],
      "dose_forms": ["Oral Product", "Pill"]
    }
  ],
  "query": "cymbalta"
}
```

---

## 👤 Authenticated User Medication Endpoints

### Add User Medication

**Endpoint:** `POST /api/user/medications`  
**Headers:** `Accept: application/json`, `Authorization: Bearer <token>`  
**Body:**
```form-data
rxcui:596928
```

**Response:**
```json
{
  "success": true,
  "code": 200,
  "message": "User Medication added.",
  "data": []
}
```

---

### Delete User Medication

**Endpoint:** `DELETE /api/user/medications/{rxcui}`  
**Headers:** `Accept: application/json`, `Authorization: Bearer <token>`  

**Response:**
```json
{
  "success": true,
  "code": 200,
  "message": "User Medication deleted successfully.",
  "data": []
}
```

---

### Get User Medications

**Endpoint:** `GET /api/user/medications`  
**Headers:** `Accept: application/json`, `Authorization: Bearer <token>`  

**Response:**
```json
{
  "success": true,
  "code": 200,
  "message": "Success",
  "data": [
    {
      "rxcui": "596928",
      "drug_name": "duloxetine 20 MG Delayed Release Oral Capsule [Cymbalta]",
      "baseNames": ["duloxetine"],
      "dose_forms_group": ["Oral Product", "Pill"]
    }
  ]
}
```

---

### Logout

**Endpoint:** `POST /api/logout`  
**Headers:** `Accept: application/json`, `Authorization: Bearer <token>`  

**Response:**
```json
{
  "success": true,
  "code": 200,
  "message": "Logout Successfully.",
  "data": []
}
```


## 📄 Postman Collection

Download and import the Postman collection to test all the API endpoints:

[📥 Download medTracker.postman_collection.json](./medTracker.postman_collection.json)

---

## 📌 Notes

- Caching is enabled to reduce redundant calls to RxNorm API (1 day per search).
- Rate limiting is applied to the public drug search endpoint to avoid abuse.
- Authentication is handled securely using Laravel Sanctum.

---