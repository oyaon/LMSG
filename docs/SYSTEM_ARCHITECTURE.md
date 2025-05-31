# Library Management System - System Architecture

## Overview

The Library Management System (LMS) is built using a multi-tier architecture that separates the application into distinct layers. This document provides a comprehensive overview of the system architecture, explaining how different components interact with each other.

## Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                        Presentation Layer                        │
│                                                                 │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────────────┐  │
│  │  User UI    │    │  Admin UI   │    │  API Endpoints      │  │
│  │ (HTML/CSS/JS)│    │(HTML/CSS/JS)│    │                     │  │
│  └─────────────┘    └─────────────┘    └─────────────────────┘  │
└───────────────────────────┬─────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│                        Application Layer                         │
│                                                                 │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────────────┐  │
│  │ Controllers │    │  Services   │    │  Utilities/Helpers  │  │
│  └─────────────┘    └─────────────┘    └─────────────────────┘  │
│                                                                 │
│  ┌─────────────────────────┐    ┌─────────────────────────────┐ │
│  │ Authentication/Security │    │ Business Logic Components   │ │
│  └─────────────────────────┘    └─────────────────────────────┘ │
└───────────────────────────┬─────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│                          Data Layer                              │
│                                                                 │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────────────┐  │
│  │   Models    │    │Data Access  │    │  Database Connection│  │
│  │             │    │  Objects    │    │                     │  │
│  └─────────────┘    └─────────────┘    └─────────────────────┘  │
└───────────────────────────┬─────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│                        Database Layer                            │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │                      MySQL Database                      │    │
│  └─────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────┘
```

## Component Descriptions

### 1. Presentation Layer

The presentation layer handles the user interface and user interactions.

#### User Interface (UI)
- **User UI**: Frontend interface for regular library users
  - Browse and search books
  - Manage borrowings
  - Shopping cart and checkout
  - User profile management
- **Admin UI**: Interface for library administrators
  - Book management
  - User management
  - Borrowing approval
  - Reports and statistics
- **API Endpoints**: RESTful API for programmatic access
  - Authentication endpoints
  - Book management endpoints
  - Borrowing endpoints
  - Cart and checkout endpoints

#### Technologies Used
- HTML5, CSS3, Bootstrap for responsive design
- JavaScript for client-side functionality
- AJAX for asynchronous data loading

### 2. Application Layer

The application layer contains the business logic and core functionality.

#### Controllers
- **UserController**: Handles user registration, authentication, and profile management
- **BookController**: Manages book-related operations
- **BorrowController**: Handles borrowing requests and returns
- **CartController**: Manages shopping cart operations
- **PaymentController**: Processes payments and orders
- **AdminController**: Handles administrative functions

#### Services
- **AuthService**: Authentication and authorization services
- **EmailService**: Email notification services
- **FileUploadService**: Handles file uploads (book covers, user avatars)
- **SearchService**: Advanced search functionality
- **ReportService**: Generates reports and statistics

#### Utilities/Helpers
- **Validation**: Input validation and sanitization
- **Logging**: Error and activity logging
- **Formatting**: Date, currency, and text formatting

#### Security Components
- **CSRF Protection**: Prevents cross-site request forgery
- **Password Hashing**: Secure password storage
- **Input Sanitization**: Prevents SQL injection and XSS attacks
- **Role-Based Access Control**: Restricts access based on user roles

### 3. Data Layer

The data layer manages data access and persistence.

#### Models
- **User**: User data and authentication
- **Book**: Book metadata and availability
- **Author**: Author information
- **Category**: Book categories
- **Borrow**: Borrowing records
- **Cart**: Shopping cart data
- **Order**: Purchase orders
- **Payment**: Payment records

#### Data Access Objects
- Database query builders
- Data retrieval and manipulation methods
- Transaction management

#### Database Connection
- Connection pooling
- Query execution
- Error handling

### 4. Database Layer

The database layer stores and manages all persistent data.

#### MySQL Database
- Relational database with normalized tables
- Foreign key constraints for data integrity
- Indexes for query optimization
- Stored procedures for complex operations

## Data Flow

### Book Borrowing Process
1. User searches for a book (Presentation Layer)
2. Search request is processed (Application Layer)
3. Book data is retrieved from database (Data Layer)
4. User initiates borrowing request (Presentation Layer)
5. Request is validated and processed (Application Layer)
6. Borrowing record is created in database (Data Layer)
7. Confirmation is displayed to user (Presentation Layer)

### Book Purchase Process
1. User adds book to cart (Presentation Layer)
2. Cart is updated (Application Layer)
3. User proceeds to checkout (Presentation Layer)
4. Payment information is collected and validated (Application Layer)
5. Payment is processed (Application Layer)
6. Order is created in database (Data Layer)
7. Confirmation is sent to user (Application Layer)
8. Order details are displayed (Presentation Layer)

## Integration Points

### External Systems
- **Payment Gateway**: Integration for processing online payments
- **Email Service**: For sending notifications and alerts
- **PDF Generation**: For creating receipts and reports

### APIs
- **RESTful API**: For mobile applications and third-party integrations
- **Authentication API**: OAuth2 for secure authentication

## Security Architecture

### Authentication
- Password hashing using bcrypt
- Session management with secure cookies
- CSRF token validation for form submissions

### Authorization
- Role-based access control (Admin, Librarian, User)
- Permission checks at controller level
- Row-level security for sensitive data

### Data Protection
- Input validation and sanitization
- Prepared statements for database queries
- Output encoding to prevent XSS

## Deployment Architecture

### Development Environment
- Local XAMPP stack
- Git for version control
- Composer for dependency management

### Production Environment
- Apache web server
- MySQL database server
- PHP 7.4+ runtime
- SSL/TLS encryption

## Scalability Considerations

### Horizontal Scaling
- Stateless application design
- Session storage in database
- Load balancing capability

### Performance Optimization
- Database query optimization
- Caching of frequently accessed data
- Asynchronous processing for non-critical operations

## Monitoring and Logging

### Error Logging
- PHP error logging
- Application-specific error handling
- Stack traces for debugging

### Activity Logging
- User authentication events
- Administrative actions
- System operations

## Conclusion

The Library Management System architecture is designed to be modular, secure, and maintainable. The separation of concerns between layers allows for easier development, testing, and future enhancements. The system can be extended with new features while maintaining backward compatibility with existing functionality.# Library Management System - System Architecture

## Overview

The Library Management System (LMS) is built using a multi-tier architecture that separates the application into distinct layers. This document provides a comprehensive overview of the system architecture, explaining how different components interact with each other.

## Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                        Presentation Layer                        │
│                                                                 │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────────────┐  │
│  │  User UI    │    │  Admin UI   │    │  API Endpoints      │  │
│  │ (HTML/CSS/JS)│    │(HTML/CSS/JS)│    │                     │  │
│  └─────────────┘    └─────────────┘    └─────────────────────┘  │
└───────────────────────────┬─────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│                        Application Layer                         │
│                                                                 │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────────────┐  │
│  │ Controllers │    │  Services   │    │  Utilities/Helpers  │  │
│  └─────────────┘    └─────────────┘    └─────────────────────┘  │
│                                                                 │
│  ┌─────────────────────────┐    ┌─────────────────────────────┐ │
│  │ Authentication/Security │    │ Business Logic Components   │ │
│  └─────────────────────────┘    └─────────────────────────────┘ │
└───────────────────────────┬─────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│                          Data Layer                              │
│                                                                 │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────────────┐  │
│  │   Models    │    │Data Access  │    │  Database Connection│  │
│  │             │    │  Objects    │    │                     │  │
│  └─────────────┘    └─────────────┘    └─────────────────────┘  │
└───────────────────────────┬─────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│                        Database Layer                            │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │                      MySQL Database                      │    │
│  └─────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────┘
```

## Component Descriptions

### 1. Presentation Layer

The presentation layer handles the user interface and user interactions.

#### User Interface (UI)
- **User UI**: Frontend interface for regular library users
  - Browse and search books
  - Manage borrowings
  - Shopping cart and checkout
  - User profile management
- **Admin UI**: Interface for library administrators
  - Book management
  - User management
  - Borrowing approval
  - Reports and statistics
- **API Endpoints**: RESTful API for programmatic access
  - Authentication endpoints
  - Book management endpoints
  - Borrowing endpoints
  - Cart and checkout endpoints

#### Technologies Used
- HTML5, CSS3, Bootstrap for responsive design
- JavaScript for client-side functionality
- AJAX for asynchronous data loading

### 2. Application Layer

The application layer contains the business logic and core functionality.

#### Controllers
- **UserController**: Handles user registration, authentication, and profile management
- **BookController**: Manages book-related operations
- **BorrowController**: Handles borrowing requests and returns
- **CartController**: Manages shopping cart operations
- **PaymentController**: Processes payments and orders
- **AdminController**: Handles administrative functions

#### Services
- **AuthService**: Authentication and authorization services
- **EmailService**: Email notification services
- **FileUploadService**: Handles file uploads (book covers, user avatars)
- **SearchService**: Advanced search functionality
- **ReportService**: Generates reports and statistics

#### Utilities/Helpers
- **Validation**: Input validation and sanitization
- **Logging**: Error and activity logging
- **Formatting**: Date, currency, and text formatting

#### Security Components
- **CSRF Protection**: Prevents cross-site request forgery
- **Password Hashing**: Secure password storage
- **Input Sanitization**: Prevents SQL injection and XSS attacks
- **Role-Based Access Control**: Restricts access based on user roles

### 3. Data Layer

The data layer manages data access and persistence.

#### Models
- **User**: User data and authentication
- **Book**: Book metadata and availability
- **Author**: Author information
- **Category**: Book categories
- **Borrow**: Borrowing records
- **Cart**: Shopping cart data
- **Order**: Purchase orders
- **Payment**: Payment records

#### Data Access Objects
- Database query builders
- Data retrieval and manipulation methods
- Transaction management

#### Database Connection
- Connection pooling
- Query execution
- Error handling

### 4. Database Layer

The database layer stores and manages all persistent data.

#### MySQL Database
- Relational database with normalized tables
- Foreign key constraints for data integrity
- Indexes for query optimization
- Stored procedures for complex operations

## Data Flow

### Book Borrowing Process
1. User searches for a book (Presentation Layer)
2. Search request is processed (Application Layer)
3. Book data is retrieved from database (Data Layer)
4. User initiates borrowing request (Presentation Layer)
5. Request is validated and processed (Application Layer)
6. Borrowing record is created in database (Data Layer)
7. Confirmation is displayed to user (Presentation Layer)

### Book Purchase Process
1. User adds book to cart (Presentation Layer)
2. Cart is updated (Application Layer)
3. User proceeds to checkout (Presentation Layer)
4. Payment information is collected and validated (Application Layer)
5. Payment is processed (Application Layer)
6. Order is created in database (Data Layer)
7. Confirmation is sent to user (Application Layer)
8. Order details are displayed (Presentation Layer)

## Integration Points

### External Systems
- **Payment Gateway**: Integration for processing online payments
- **Email Service**: For sending notifications and alerts
- **PDF Generation**: For creating receipts and reports

### APIs
- **RESTful API**: For mobile applications and third-party integrations
- **Authentication API**: OAuth2 for secure authentication

## Security Architecture

### Authentication
- Password hashing using bcrypt
- Session management with secure cookies
- CSRF token validation for form submissions

### Authorization
- Role-based access control (Admin, Librarian, User)
- Permission checks at controller level
- Row-level security for sensitive data

### Data Protection
- Input validation and sanitization
- Prepared statements for database queries
- Output encoding to prevent XSS

## Deployment Architecture

### Development Environment
- Local XAMPP stack
- Git for version control
- Composer for dependency management

### Production Environment
- Apache web server
- MySQL database server
- PHP 7.4+ runtime
- SSL/TLS encryption

## Scalability Considerations

### Horizontal Scaling
- Stateless application design
- Session storage in database
- Load balancing capability

### Performance Optimization
- Database query optimization
- Caching of frequently accessed data
- Asynchronous processing for non-critical operations

## Monitoring and Logging

### Error Logging
- PHP error logging
- Application-specific error handling
- Stack traces for debugging

### Activity Logging
- User authentication events
- Administrative actions
- System operations

## Conclusion

The Library Management System architecture is designed to be modular, secure, and maintainable. The separation of concerns between layers allows for easier development, testing, and future enhancements. The system can be extended with new features while maintaining backward compatibility with existing functionality.