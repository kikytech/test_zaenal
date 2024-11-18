# E-commerce Management System

## Description

An **E-commerce Management System** designed to handle customer management, order management, and user authentication for an online store. The system uses PHP 8.2, PostgreSQL for database management, Bootstrap for responsive design, JWT for secure user authentication, and JavaScript for client-side interactivity.

## Features

- **Customer Management**:
  - Add, update, delete, view customers
  - Search and paginate customer list
  
- **Order Management**:
  - Add, update, delete, view orders
  - Search and paginate orders
  
- **User Authentication**:
  - User login and registration using **JWT** for secure authentication
  - Role-based access control for different users
  
- **Security**:
  - All sensitive data is handled securely using **JWT** for authentication
  - Passwords are hashed using PHP's `password_hash()` before storing them in the database
  
- **Front-End**:
  - Responsive design using **Bootstrap 5**
  - Client-side form validation using **JavaScript**

## Technology Stack

- **PHP 8.2** native
- **PostgreSQL**: Database management
- **Bootstrap 5**: For responsive, mobile-first design
- **JWT (JSON Web Token)**: For secure user authentication and authorization
- **JavaScript**

## Prerequisites

- **PHP 8.2** or higher
- **PostgreSQL** database
- **Composer** for managing dependencies
- **Node.js** and **npm** (for managing JS packages if required)

## Installation

### Clone the repository

```bash
git clone https://github.com/your-username/your-repo-name.git
cd your-repo-name
