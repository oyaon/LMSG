# Library Management System - API Documentation

This document provides comprehensive documentation for the Library Management System (LMS) API. The API allows programmatic access to the LMS functionality, enabling integration with other systems and development of custom clients.

## API Overview

- **Base URL**: `http://localhost/LMS/api`
- **Format**: All requests and responses use JSON format
- **Authentication**: API uses token-based authentication
- **Error Handling**: Standard HTTP status codes with descriptive error messages

## Authentication

### Get Authentication Token

Authenticate a user and receive an access token.

**Endpoint**: `POST /api/login`

**Request Body**:
```json
{
  "email": "user@example.com",
  "password": "securepassword"
}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "user": {
    "id": 123,
    "name": "John Doe",
    "email": "user@example.com",
    "role": "user"
  }
}
```

**Response (401 Unauthorized)**:
```json
{
  "success": false,
  "message": "Invalid credentials"
}
```

### Register New User

Register a new user account.

**Endpoint**: `POST /api/register`

**Request Body**:
```json
{
  "name": "John Doe",
  "email": "user@example.com",
  "password": "securepassword",
  "password_confirmation": "securepassword"
}
```

**Response (201 Created)**:
```json
{
  "success": true,
  "message": "User registered successfully",
  "user": {
    "id": 123,
    "name": "John Doe",
    "email": "user@example.com",
    "role": "user"
  }
}
```

**Response (422 Unprocessable Entity)**:
```json
{
  "success": false,
  "errors": {
    "email": ["The email has already been taken."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### Logout

Invalidate the current authentication token.

**Endpoint**: `POST /api/logout`

**Headers**:
```
Authorization: Bearer {token}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

## Books

### Get Books

Retrieve a paginated list of books.

**Endpoint**: `GET /api/books`

**Query Parameters**:
- `page` (optional): Page number (default: 1)
- `limit` (optional): Items per page (default: 10)
- `search` (optional): Search term for title, author, or ISBN
- `category` (optional): Filter by category ID
- `sort` (optional): Sort field (title, author, published_date)
- `order` (optional): Sort order (asc, desc)

**Headers**:
```
Authorization: Bearer {token}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "The Great Gatsby",
      "author": "F. Scott Fitzgerald",
      "isbn": "9780743273565",
      "published_date": "1925-04-10",
      "category": "Fiction",
      "available": true,
      "cover_image": "http://localhost/LMS/uploads/covers/gatsby.jpg"
    },
    {
      "id": 2,
      "title": "To Kill a Mockingbird",
      "author": "Harper Lee",
      "isbn": "9780061120084",
      "published_date": "1960-07-11",
      "category": "Fiction",
      "available": false,
      "cover_image": "http://localhost/LMS/uploads/covers/mockingbird.jpg"
    }
  ],
  "pagination": {
    "total": 42,
    "count": 2,
    "per_page": 2,
    "current_page": 1,
    "total_pages": 21,
    "links": {
      "next": "http://localhost/LMS/api/books?page=2&limit=2"
    }
  }
}
```

### Get Book by ID

Retrieve details of a specific book.

**Endpoint**: `GET /api/books/{id}`

**Headers**:
```
Authorization: Bearer {token}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "The Great Gatsby",
    "author": "F. Scott Fitzgerald",
    "isbn": "9780743273565",
    "published_date": "1925-04-10",
    "category": "Fiction",
    "description": "Set in the Jazz Age on Long Island...",
    "available": true,
    "cover_image": "http://localhost/LMS/uploads/covers/gatsby.jpg",
    "price": 12.99,
    "pages": 180,
    "publisher": "Scribner",
    "language": "English",
    "reviews": [
      {
        "user": "Jane Doe",
        "rating": 5,
        "comment": "A classic masterpiece!",
        "date": "2023-01-15"
      }
    ]
  }
}
```

**Response (404 Not Found)**:
```json
{
  "success": false,
  "message": "Book not found"
}
```

### Add New Book

Add a new book to the library (admin only).

**Endpoint**: `POST /api/books`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body**:
```
title: The Great Gatsby
author_id: 42
isbn: 9780743273565
published_date: 1925-04-10
category_id: 3
description: Set in the Jazz Age on Long Island...
price: 12.99
pages: 180
publisher: Scribner
language: English
cover_image: [file upload]
```

**Response (201 Created)**:
```json
{
  "success": true,
  "message": "Book added successfully",
  "data": {
    "id": 1,
    "title": "The Great Gatsby",
    "author": "F. Scott Fitzgerald",
    "isbn": "9780743273565",
    "published_date": "1925-04-10",
    "category": "Fiction",
    "available": true
  }
}
```

**Response (403 Forbidden)**:
```json
{
  "success": false,
  "message": "Unauthorized. Admin access required."
}
```

### Update Book

Update book details (admin only).

**Endpoint**: `PUT /api/books/{id}`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body**:
```
title: The Great Gatsby (Revised Edition)
price: 14.99
available: true
cover_image: [file upload]
```

**Response (200 OK)**:
```json
{
  "success": true,
  "message": "Book updated successfully",
  "data": {
    "id": 1,
    "title": "The Great Gatsby (Revised Edition)",
    "author": "F. Scott Fitzgerald",
    "isbn": "9780743273565",
    "published_date": "1925-04-10",
    "category": "Fiction",
    "available": true,
    "price": 14.99
  }
}
```

### Delete Book

Delete a book (admin only).

**Endpoint**: `DELETE /api/books/{id}`

**Headers**:
```
Authorization: Bearer {token}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "message": "Book deleted successfully"
}
```

## Borrowing

### Request to Borrow

Request to borrow a book.

**Endpoint**: `POST /api/borrow`

**Headers**:
```
Authorization: Bearer {token}
```

**Request Body**:
```json
{
  "book_id": 1,
  "due_date": "2023-06-30"
}
```

**Response (201 Created)**:
```json
{
  "success": true,
  "message": "Borrow request submitted successfully",
  "data": {
    "id": 42,
    "book_id": 1,
    "book_title": "The Great Gatsby",
    "borrow_date": "2023-06-01",
    "due_date": "2023-06-30",
    "status": "pending"
  }
}
```

**Response (400 Bad Request)**:
```json
{
  "success": false,
  "message": "Book is not available for borrowing"
}
```

### Get Borrowing History

Get borrowing history for the user.

**Endpoint**: `GET /api/borrow/history`

**Headers**:
```
Authorization: Bearer {token}
```

**Query Parameters**:
- `page` (optional): Page number (default: 1)
- `limit` (optional): Items per page (default: 10)
- `status` (optional): Filter by status (pending, approved, returned, overdue)

**Response (200 OK)**:
```json
{
  "success": true,
  "data": [
    {
      "id": 42,
      "book_id": 1,
      "book_title": "The Great Gatsby",
      "cover_image": "http://localhost/LMS/uploads/covers/gatsby.jpg",
      "borrow_date": "2023-06-01",
      "due_date": "2023-06-30",
      "return_date": null,
      "status": "approved",
      "fine": 0
    },
    {
      "id": 36,
      "book_id": 2,
      "book_title": "To Kill a Mockingbird",
      "cover_image": "http://localhost/LMS/uploads/covers/mockingbird.jpg",
      "borrow_date": "2023-05-01",
      "due_date": "2023-05-15",
      "return_date": "2023-05-10",
      "status": "returned",
      "fine": 0
    }
  ],
  "pagination": {
    "total": 12,
    "count": 2,
    "per_page": 2,
    "current_page": 1,
    "total_pages": 6,
    "links": {
      "next": "http://localhost/LMS/api/borrow/history?page=2&limit=2"
    }
  }
}
```

### Return Book

Mark a borrowed book as returned.

**Endpoint**: `PUT /api/borrow/{id}/return`

**Headers**:
```
Authorization: Bearer {token}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "message": "Book marked as returned",
  "data": {
    "id": 42,
    "book_id": 1,
    "book_title": "The Great Gatsby",
    "borrow_date": "2023-06-01",
    "due_date": "2023-06-30",
    "return_date": "2023-06-25",
    "status": "returned",
    "fine": 0
  }
}
```

**Response (404 Not Found)**:
```json
{
  "success": false,
  "message": "Borrow record not found"
}
```

## Shopping Cart

### Get Cart

Get current user's shopping cart.

**Endpoint**: `GET /api/cart`

**Headers**:
```
Authorization: Bearer {token}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "data": {
    "items": [
      {
        "id": 1,
        "book_id": 1,
        "book_title": "The Great Gatsby",
        "cover_image": "http://localhost/LMS/uploads/covers/gatsby.jpg",
        "price": 12.99,
        "quantity": 1,
        "subtotal": 12.99
      },
      {
        "id": 2,
        "book_id": 3,
        "book_title": "1984",
        "cover_image": "http://localhost/LMS/uploads/covers/1984.jpg",
        "price": 10.99,
        "quantity": 2,
        "subtotal": 21.98
      }
    ],
    "total_items": 3,
    "total_price": 34.97
  }
}
```

### Add to Cart

Add a book to the cart.

**Endpoint**: `POST /api/cart/add`

**Headers**:
```
Authorization: Bearer {token}
```

**Request Body**:
```json
{
  "book_id": 1,
  "quantity": 1
}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "message": "Book added to cart",
  "data": {
    "items": [
      {
        "id": 1,
        "book_id": 1,
        "book_title": "The Great Gatsby",
        "cover_image": "http://localhost/LMS/uploads/covers/gatsby.jpg",
        "price": 12.99,
        "quantity": 1,
        "subtotal": 12.99
      }
    ],
    "total_items": 1,
    "total_price": 12.99
  }
}
```

### Remove from Cart

Remove a book from the cart.

**Endpoint**: `DELETE /api/cart/remove/{item_id}`

**Headers**:
```
Authorization: Bearer {token}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "message": "Item removed from cart",
  "data": {
    "items": [],
    "total_items": 0,
    "total_price": 0
  }
}
```

### Checkout

Complete the purchase.

**Endpoint**: `POST /api/cart/checkout`

**Headers**:
```
Authorization: Bearer {token}
```

**Request Body**:
```json
{
  "payment_method": "credit_card",
  "shipping_address": {
    "name": "John Doe",
    "address": "123 Main St",
    "city": "Anytown",
    "state": "CA",
    "zip": "12345",
    "country": "USA"
  },
  "payment_details": {
    "card_number": "4111111111111111",
    "expiry_month": "12",
    "expiry_year": "2025",
    "cvv": "123"
  }
}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "message": "Order placed successfully",
  "data": {
    "order_id": 12345,
    "order_date": "2023-06-01",
    "total_amount": 34.97,
    "payment_status": "completed",
    "shipping_address": {
      "name": "John Doe",
      "address": "123 Main St",
      "city": "Anytown",
      "state": "CA",
      "zip": "12345",
      "country": "USA"
    },
    "items": [
      {
        "book_id": 1,
        "book_title": "The Great Gatsby",
        "price": 12.99,
        "quantity": 1,
        "subtotal": 12.99
      },
      {
        "book_id": 3,
        "book_title": "1984",
        "price": 10.99,
        "quantity": 2,
        "subtotal": 21.98
      }
    ]
  }
}
```

## Admin Endpoints

### Get All Users

Get a list of all users (admin only).

**Endpoint**: `GET /api/admin/users`

**Headers**:
```
Authorization: Bearer {token}
```

**Query Parameters**:
- `page` (optional): Page number (default: 1)
- `limit` (optional): Items per page (default: 10)
- `search` (optional): Search term for name or email

**Response (200 OK)**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "admin",
      "created_at": "2023-01-01"
    },
    {
      "id": 2,
      "name": "Jane Smith",
      "email": "jane@example.com",
      "role": "user",
      "created_at": "2023-01-15"
    }
  ],
  "pagination": {
    "total": 42,
    "count": 2,
    "per_page": 2,
    "current_page": 1,
    "total_pages": 21,
    "links": {
      "next": "http://localhost/LMS/api/admin/users?page=2&limit=2"
    }
  }
}
```

### Get Borrowing Requests

Get a list of borrowing requests (admin only).

**Endpoint**: `GET /api/admin/borrow/requests`

**Headers**:
```
Authorization: Bearer {token}
```

**Query Parameters**:
- `page` (optional): Page number (default: 1)
- `limit` (optional): Items per page (default: 10)
- `status` (optional): Filter by status (pending, approved, returned, overdue)

**Response (200 OK)**:
```json
{
  "success": true,
  "data": [
    {
      "id": 42,
      "user_id": 2,
      "user_name": "Jane Smith",
      "book_id": 1,
      "book_title": "The Great Gatsby",
      "borrow_date": "2023-06-01",
      "due_date": "2023-06-30",
      "status": "pending"
    },
    {
      "id": 43,
      "user_id": 3,
      "user_name": "Bob Johnson",
      "book_id": 5,
      "book_title": "Pride and Prejudice",
      "borrow_date": "2023-06-02",
      "due_date": "2023-07-02",
      "status": "pending"
    }
  ],
  "pagination": {
    "total": 15,
    "count": 2,
    "per_page": 2,
    "current_page": 1,
    "total_pages": 8,
    "links": {
      "next": "http://localhost/LMS/api/admin/borrow/requests?page=2&limit=2"
    }
  }
}
```

### Approve Borrowing Request

Approve a borrowing request (admin only).

**Endpoint**: `PUT /api/admin/borrow/{id}/approve`

**Headers**:
```
Authorization: Bearer {token}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "message": "Borrowing request approved",
  "data": {
    "id": 42,
    "user_id": 2,
    "user_name": "Jane Smith",
    "book_id": 1,
    "book_title": "The Great Gatsby",
    "borrow_date": "2023-06-01",
    "due_date": "2023-06-30",
    "status": "approved"
  }
}
```

### Reject Borrowing Request

Reject a borrowing request (admin only).

**Endpoint**: `PUT /api/admin/borrow/{id}/reject`

**Headers**:
```
Authorization: Bearer {token}
```

**Request Body**:
```json
{
  "reason": "Book reserved for another user"
}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "message": "Borrowing request rejected",
  "data": {
    "id": 42,
    "user_id": 2,
    "user_name": "Jane Smith",
    "book_id": 1,
    "book_title": "The Great Gatsby",
    "borrow_date": "2023-06-01",
    "due_date": "2023-06-30",
    "status": "rejected",
    "rejection_reason": "Book reserved for another user"
  }
}
```

## Error Handling

The API uses standard HTTP status codes to indicate the success or failure of a request:

- **200 OK**: The request was successful
- **201 Created**: A new resource was created successfully
- **400 Bad Request**: The request was invalid or cannot be served
- **401 Unauthorized**: Authentication failed or user doesn't have permissions
- **403 Forbidden**: The user is authenticated but doesn't have permission
- **404 Not Found**: The requested resource does not exist
- **422 Unprocessable Entity**: Validation errors
- **500 Internal Server Error**: An error occurred on the server

All error responses follow this format:

```json
{
  "success": false,
  "message": "Error message describing what went wrong",
  "errors": {
    "field_name": ["Specific error for this field"],
    "another_field": ["Another specific error"]
  }
}
```

## Rate Limiting

The API implements rate limiting to prevent abuse. The current limits are:

- **Authentication endpoints**: 10 requests per minute
- **General endpoints**: 60 requests per minute
- **Admin endpoints**: 120 requests per minute

When a rate limit is exceeded, the API will return a 429 Too Many Requests response with a Retry-After header indicating when the client can try again.

## Versioning

The current API version is v1. The version is included in the URL path:

```
http://localhost/LMS/api/v1/books
```

Future versions will be accessible at:

```
http://localhost/LMS/api/v2/books
```

## Conclusion

This API documentation provides a comprehensive guide to integrating with the Library Management System. For additional support or to report issues, please contact the system administrator.# Library Management System - API Documentation

This document provides comprehensive documentation for the Library Management System (LMS) API. The API allows programmatic access to the LMS functionality, enabling integration with other systems and development of custom clients.

## API Overview

- **Base URL**: `http://localhost/LMS/api`
- **Format**: All requests and responses use JSON format
- **Authentication**: API uses token-based authentication
- **Error Handling**: Standard HTTP status codes with descriptive error messages

## Authentication

### Get Authentication Token

Authenticate a user and receive an access token.

**Endpoint**: `POST /api/login`

**Request Body**:
```json
{
  "email": "user@example.com",
  "password": "securepassword"
}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "user": {
    "id": 123,
    "name": "John Doe",
    "email": "user@example.com",
    "role": "user"
  }
}
```

**Response (401 Unauthorized)**:
```json
{
  "success": false,
  "message": "Invalid credentials"
}
```

### Register New User

Register a new user account.

**Endpoint**: `POST /api/register`

**Request Body**:
```json
{
  "name": "John Doe",
  "email": "user@example.com",
  "password": "securepassword",
  "password_confirmation": "securepassword"
}
```

**Response (201 Created)**:
```json
{
  "success": true,
  "message": "User registered successfully",
  "user": {
    "id": 123,
    "name": "John Doe",
    "email": "user@example.com",
    "role": "user"
  }
}
```

**Response (422 Unprocessable Entity)**:
```json
{
  "success": false,
  "errors": {
    "email": ["The email has already been taken."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### Logout

Invalidate the current authentication token.

**Endpoint**: `POST /api/logout`

**Headers**:
```
Authorization: Bearer {token}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

## Books

### Get Books

Retrieve a paginated list of books.

**Endpoint**: `GET /api/books`

**Query Parameters**:
- `page` (optional): Page number (default: 1)
- `limit` (optional): Items per page (default: 10)
- `search` (optional): Search term for title, author, or ISBN
- `category` (optional): Filter by category ID
- `sort` (optional): Sort field (title, author, published_date)
- `order` (optional): Sort order (asc, desc)

**Headers**:
```
Authorization: Bearer {token}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "The Great Gatsby",
      "author": "F. Scott Fitzgerald",
      "isbn": "9780743273565",
      "published_date": "1925-04-10",
      "category": "Fiction",
      "available": true,
      "cover_image": "http://localhost/LMS/uploads/covers/gatsby.jpg"
    },
    {
      "id": 2,
      "title": "To Kill a Mockingbird",
      "author": "Harper Lee",
      "isbn": "9780061120084",
      "published_date": "1960-07-11",
      "category": "Fiction",
      "available": false,
      "cover_image": "http://localhost/LMS/uploads/covers/mockingbird.jpg"
    }
  ],
  "pagination": {
    "total": 42,
    "count": 2,
    "per_page": 2,
    "current_page": 1,
    "total_pages": 21,
    "links": {
      "next": "http://localhost/LMS/api/books?page=2&limit=2"
    }
  }
}
```

### Get Book by ID

Retrieve details of a specific book.

**Endpoint**: `GET /api/books/{id}`

**Headers**:
```
Authorization: Bearer {token}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "The Great Gatsby",
    "author": "F. Scott Fitzgerald",
    "isbn": "9780743273565",
    "published_date": "1925-04-10",
    "category": "Fiction",
    "description": "Set in the Jazz Age on Long Island...",
    "available": true,
    "cover_image": "http://localhost/LMS/uploads/covers/gatsby.jpg",
    "price": 12.99,
    "pages": 180,
    "publisher": "Scribner",
    "language": "English",
    "reviews": [
      {
        "user": "Jane Doe",
        "rating": 5,
        "comment": "A classic masterpiece!",
        "date": "2023-01-15"
      }
    ]
  }
}
```

**Response (404 Not Found)**:
```json
{
  "success": false,
  "message": "Book not found"
}
```

### Add New Book

Add a new book to the library (admin only).

**Endpoint**: `POST /api/books`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body**:
```
title: The Great Gatsby
author_id: 42
isbn: 9780743273565
published_date: 1925-04-10
category_id: 3
description: Set in the Jazz Age on Long Island...
price: 12.99
pages: 180
publisher: Scribner
language: English
cover_image: [file upload]
```

**Response (201 Created)**:
```json
{
  "success": true,
  "message": "Book added successfully",
  "data": {
    "id": 1,
    "title": "The Great Gatsby",
    "author": "F. Scott Fitzgerald",
    "isbn": "9780743273565",
    "published_date": "1925-04-10",
    "category": "Fiction",
    "available": true
  }
}
```

**Response (403 Forbidden)**:
```json
{
  "success": false,
  "message": "Unauthorized. Admin access required."
}
```

### Update Book

Update book details (admin only).

**Endpoint**: `PUT /api/books/{id}`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body**:
```
title: The Great Gatsby (Revised Edition)
price: 14.99
available: true
cover_image: [file upload]
```

**Response (200 OK)**:
```json
{
  "success": true,
  "message": "Book updated successfully",
  "data": {
    "id": 1,
    "title": "The Great Gatsby (Revised Edition)",
    "author": "F. Scott Fitzgerald",
    "isbn": "9780743273565",
    "published_date": "1925-04-10",
    "category": "Fiction",
    "available": true,
    "price": 14.99
  }
}
```

### Delete Book

Delete a book (admin only).

**Endpoint**: `DELETE /api/books/{id}`

**Headers**:
```
Authorization: Bearer {token}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "message": "Book deleted successfully"
}
```

## Borrowing

### Request to Borrow

Request to borrow a book.

**Endpoint**: `POST /api/borrow`

**Headers**:
```
Authorization: Bearer {token}
```

**Request Body**:
```json
{
  "book_id": 1,
  "due_date": "2023-06-30"
}
```

**Response (201 Created)**:
```json
{
  "success": true,
  "message": "Borrow request submitted successfully",
  "data": {
    "id": 42,
    "book_id": 1,
    "book_title": "The Great Gatsby",
    "borrow_date": "2023-06-01",
    "due_date": "2023-06-30",
    "status": "pending"
  }
}
```

**Response (400 Bad Request)**:
```json
{
  "success": false,
  "message": "Book is not available for borrowing"
}
```

### Get Borrowing History

Get borrowing history for the user.

**Endpoint**: `GET /api/borrow/history`

**Headers**:
```
Authorization: Bearer {token}
```

**Query Parameters**:
- `page` (optional): Page number (default: 1)
- `limit` (optional): Items per page (default: 10)
- `status` (optional): Filter by status (pending, approved, returned, overdue)

**Response (200 OK)**:
```json
{
  "success": true,
  "data": [
    {
      "id": 42,
      "book_id": 1,
      "book_title": "The Great Gatsby",
      "cover_image": "http://localhost/LMS/uploads/covers/gatsby.jpg",
      "borrow_date": "2023-06-01",
      "due_date": "2023-06-30",
      "return_date": null,
      "status": "approved",
      "fine": 0
    },
    {
      "id": 36,
      "book_id": 2,
      "book_title": "To Kill a Mockingbird",
      "cover_image": "http://localhost/LMS/uploads/covers/mockingbird.jpg",
      "borrow_date": "2023-05-01",
      "due_date": "2023-05-15",
      "return_date": "2023-05-10",
      "status": "returned",
      "fine": 0
    }
  ],
  "pagination": {
    "total": 12,
    "count": 2,
    "per_page": 2,
    "current_page": 1,
    "total_pages": 6,
    "links": {
      "next": "http://localhost/LMS/api/borrow/history?page=2&limit=2"
    }
  }
}
```

### Return Book

Mark a borrowed book as returned.

**Endpoint**: `PUT /api/borrow/{id}/return`

**Headers**:
```
Authorization: Bearer {token}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "message": "Book marked as returned",
  "data": {
    "id": 42,
    "book_id": 1,
    "book_title": "The Great Gatsby",
    "borrow_date": "2023-06-01",
    "due_date": "2023-06-30",
    "return_date": "2023-06-25",
    "status": "returned",
    "fine": 0
  }
}
```

**Response (404 Not Found)**:
```json
{
  "success": false,
  "message": "Borrow record not found"
}
```

## Shopping Cart

### Get Cart

Get current user's shopping cart.

**Endpoint**: `GET /api/cart`

**Headers**:
```
Authorization: Bearer {token}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "data": {
    "items": [
      {
        "id": 1,
        "book_id": 1,
        "book_title": "The Great Gatsby",
        "cover_image": "http://localhost/LMS/uploads/covers/gatsby.jpg",
        "price": 12.99,
        "quantity": 1,
        "subtotal": 12.99
      },
      {
        "id": 2,
        "book_id": 3,
        "book_title": "1984",
        "cover_image": "http://localhost/LMS/uploads/covers/1984.jpg",
        "price": 10.99,
        "quantity": 2,
        "subtotal": 21.98
      }
    ],
    "total_items": 3,
    "total_price": 34.97
  }
}
```

### Add to Cart

Add a book to the cart.

**Endpoint**: `POST /api/cart/add`

**Headers**:
```
Authorization: Bearer {token}
```

**Request Body**:
```json
{
  "book_id": 1,
  "quantity": 1
}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "message": "Book added to cart",
  "data": {
    "items": [
      {
        "id": 1,
        "book_id": 1,
        "book_title": "The Great Gatsby",
        "cover_image": "http://localhost/LMS/uploads/covers/gatsby.jpg",
        "price": 12.99,
        "quantity": 1,
        "subtotal": 12.99
      }
    ],
    "total_items": 1,
    "total_price": 12.99
  }
}
```

### Remove from Cart

Remove a book from the cart.

**Endpoint**: `DELETE /api/cart/remove/{item_id}`

**Headers**:
```
Authorization: Bearer {token}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "message": "Item removed from cart",
  "data": {
    "items": [],
    "total_items": 0,
    "total_price": 0
  }
}
```

### Checkout

Complete the purchase.

**Endpoint**: `POST /api/cart/checkout`

**Headers**:
```
Authorization: Bearer {token}
```

**Request Body**:
```json
{
  "payment_method": "credit_card",
  "shipping_address": {
    "name": "John Doe",
    "address": "123 Main St",
    "city": "Anytown",
    "state": "CA",
    "zip": "12345",
    "country": "USA"
  },
  "payment_details": {
    "card_number": "4111111111111111",
    "expiry_month": "12",
    "expiry_year": "2025",
    "cvv": "123"
  }
}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "message": "Order placed successfully",
  "data": {
    "order_id": 12345,
    "order_date": "2023-06-01",
    "total_amount": 34.97,
    "payment_status": "completed",
    "shipping_address": {
      "name": "John Doe",
      "address": "123 Main St",
      "city": "Anytown",
      "state": "CA",
      "zip": "12345",
      "country": "USA"
    },
    "items": [
      {
        "book_id": 1,
        "book_title": "The Great Gatsby",
        "price": 12.99,
        "quantity": 1,
        "subtotal": 12.99
      },
      {
        "book_id": 3,
        "book_title": "1984",
        "price": 10.99,
        "quantity": 2,
        "subtotal": 21.98
      }
    ]
  }
}
```

## Admin Endpoints

### Get All Users

Get a list of all users (admin only).

**Endpoint**: `GET /api/admin/users`

**Headers**:
```
Authorization: Bearer {token}
```

**Query Parameters**:
- `page` (optional): Page number (default: 1)
- `limit` (optional): Items per page (default: 10)
- `search` (optional): Search term for name or email

**Response (200 OK)**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "admin",
      "created_at": "2023-01-01"
    },
    {
      "id": 2,
      "name": "Jane Smith",
      "email": "jane@example.com",
      "role": "user",
      "created_at": "2023-01-15"
    }
  ],
  "pagination": {
    "total": 42,
    "count": 2,
    "per_page": 2,
    "current_page": 1,
    "total_pages": 21,
    "links": {
      "next": "http://localhost/LMS/api/admin/users?page=2&limit=2"
    }
  }
}
```

### Get Borrowing Requests

Get a list of borrowing requests (admin only).

**Endpoint**: `GET /api/admin/borrow/requests`

**Headers**:
```
Authorization: Bearer {token}
```

**Query Parameters**:
- `page` (optional): Page number (default: 1)
- `limit` (optional): Items per page (default: 10)
- `status` (optional): Filter by status (pending, approved, returned, overdue)

**Response (200 OK)**:
```json
{
  "success": true,
  "data": [
    {
      "id": 42,
      "user_id": 2,
      "user_name": "Jane Smith",
      "book_id": 1,
      "book_title": "The Great Gatsby",
      "borrow_date": "2023-06-01",
      "due_date": "2023-06-30",
      "status": "pending"
    },
    {
      "id": 43,
      "user_id": 3,
      "user_name": "Bob Johnson",
      "book_id": 5,
      "book_title": "Pride and Prejudice",
      "borrow_date": "2023-06-02",
      "due_date": "2023-07-02",
      "status": "pending"
    }
  ],
  "pagination": {
    "total": 15,
    "count": 2,
    "per_page": 2,
    "current_page": 1,
    "total_pages": 8,
    "links": {
      "next": "http://localhost/LMS/api/admin/borrow/requests?page=2&limit=2"
    }
  }
}
```

### Approve Borrowing Request

Approve a borrowing request (admin only).

**Endpoint**: `PUT /api/admin/borrow/{id}/approve`

**Headers**:
```
Authorization: Bearer {token}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "message": "Borrowing request approved",
  "data": {
    "id": 42,
    "user_id": 2,
    "user_name": "Jane Smith",
    "book_id": 1,
    "book_title": "The Great Gatsby",
    "borrow_date": "2023-06-01",
    "due_date": "2023-06-30",
    "status": "approved"
  }
}
```

### Reject Borrowing Request

Reject a borrowing request (admin only).

**Endpoint**: `PUT /api/admin/borrow/{id}/reject`

**Headers**:
```
Authorization: Bearer {token}
```

**Request Body**:
```json
{
  "reason": "Book reserved for another user"
}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "message": "Borrowing request rejected",
  "data": {
    "id": 42,
    "user_id": 2,
    "user_name": "Jane Smith",
    "book_id": 1,
    "book_title": "The Great Gatsby",
    "borrow_date": "2023-06-01",
    "due_date": "2023-06-30",
    "status": "rejected",
    "rejection_reason": "Book reserved for another user"
  }
}
```

## Error Handling

The API uses standard HTTP status codes to indicate the success or failure of a request:

- **200 OK**: The request was successful
- **201 Created**: A new resource was created successfully
- **400 Bad Request**: The request was invalid or cannot be served
- **401 Unauthorized**: Authentication failed or user doesn't have permissions
- **403 Forbidden**: The user is authenticated but doesn't have permission
- **404 Not Found**: The requested resource does not exist
- **422 Unprocessable Entity**: Validation errors
- **500 Internal Server Error**: An error occurred on the server

All error responses follow this format:

```json
{
  "success": false,
  "message": "Error message describing what went wrong",
  "errors": {
    "field_name": ["Specific error for this field"],
    "another_field": ["Another specific error"]
  }
}
```

## Rate Limiting

The API implements rate limiting to prevent abuse. The current limits are:

- **Authentication endpoints**: 10 requests per minute
- **General endpoints**: 60 requests per minute
- **Admin endpoints**: 120 requests per minute

When a rate limit is exceeded, the API will return a 429 Too Many Requests response with a Retry-After header indicating when the client can try again.

## Versioning

The current API version is v1. The version is included in the URL path:

```
http://localhost/LMS/api/v1/books
```

Future versions will be accessible at:

```
http://localhost/LMS/api/v2/books
```

## Conclusion

This API documentation provides a comprehensive guide to integrating with the Library Management System. For additional support or to report issues, please contact the system administrator.