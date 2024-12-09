# Vending_Machine

## 📋 Features

- **Admin Panel**:
  - Manage products (CRUD operations).
  - Pagination and sorting for product listings.
- **User Panel**:
  - Browse and purchase products.
  - Pagination for product listings.
- **Authentication**:
  - Role-based access control for Admin and User.
  - JWT-based API authentication.
- **API Endpoints**:
  - Product listing and purchase functionality.
  - Secure token verification using JWT.
- **Error Handling**:
  - User-friendly error messages.
  - Server-side validation for secure inputs.
- **Test Environment**:
  - CLI support for testing critical purchase workflows.

---

## 🛠️ Project Setup
  - git clone https://github.com/myopaingthu/Vending_Machine.git
  - composer install
  - Update the config/database.php file with your database credentials
  - run database table migration query
  - php -S localhost:8000 -t public/

## Database Table Migration Query
```sql
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    quantity_available INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
```
---

## 🛠️ Demo
  - url - http://44.204.74.210/login
  - email - admin@gmail.com
  - password - password

---

### 📄 API Endpoints

#### **Authentication**
- `POST /api/login` - Authenticate a user and retrieve a JWT token.

#### **Products**
- `GET /api/products` - Retrieve all products.

#### **Purchases**
- `POST /api/products/{id}/purchase` - Purchase a product.

#### **Transactions**
- `POST /api/transactions` - Retrieve all transactions of a user.

