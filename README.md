# PrintMaster API (Lumen Backend)

Backend RESTful API untuk aplikasi PrintMaster menggunakan Lumen Framework.

## Requirements

- PHP >= 8.1
- Composer
- MySQL / MariaDB
- ext-pdo_mysql

## Installation

1. Clone atau navigate ke folder project:
```bash
cd percetakan-lumen
```

2. Install dependencies:
```bash
composer install
```

3. Copy file environment:
```bash
cp .env.example .env
```

4. Edit file `.env` dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=percetakan_db
DB_USERNAME=root
DB_PASSWORD=your_password

JWT_SECRET=your-super-secret-jwt-key
```

5. Buat database:
```sql
CREATE DATABASE percetakan_db;
```

6. Jalankan migration:
```bash
php artisan migrate
```

7. Jalankan seeder (optional, untuk data sample):
```bash
php artisan db:seed
```

8. Jalankan development server:
```bash
php -S localhost:8000 -t public
```

API akan berjalan di `http://localhost:8000`

## API Endpoints

### Public Endpoints (No Authentication)

#### Health Check
```
GET /health
```

#### Authentication
```
POST /api/v1/auth/register     - Register user baru
POST /api/v1/auth/login        - Login user/customer
POST /api/v1/auth/admin/login  - Login admin
```

#### Categories
```
GET /api/v1/categories              - List semua kategori
GET /api/v1/categories/{id}         - Detail kategori by ID
GET /api/v1/categories/slug/{slug}  - Detail kategori by slug
```

#### Products
```
GET /api/v1/products                        - List semua produk
GET /api/v1/products/search?q={keyword}     - Cari produk
GET /api/v1/products/{id}                   - Detail produk by ID
GET /api/v1/products/slug/{slug}            - Detail produk by slug
GET /api/v1/products/category/{categorySlug} - Produk by kategori
```

#### Promos
```
POST /api/v1/promos/validate - Validasi kode promo
```

### Authenticated Endpoints (Require Bearer Token)

#### Profile
```
GET  /api/v1/auth/me       - Get current user profile
PUT  /api/v1/auth/profile  - Update profile
PUT  /api/v1/auth/password - Change password
POST /api/v1/auth/logout   - Logout
```

#### Orders (Customer)
```
GET  /api/v1/my-orders              - List order saya
POST /api/v1/orders                 - Buat order baru
GET  /api/v1/orders/{id}            - Detail order
GET  /api/v1/orders/number/{number} - Detail order by number
```

#### Shipping Addresses
```
GET    /api/v1/addresses      - List alamat
POST   /api/v1/addresses      - Tambah alamat
PUT    /api/v1/addresses/{id} - Update alamat
DELETE /api/v1/addresses/{id} - Hapus alamat
```

### Admin Endpoints (Require Admin Role)

#### Dashboard
```
GET /api/v1/admin/dashboard/stats     - Statistik order
GET /api/v1/admin/dashboard/customers - Statistik customer
```

#### Categories Management
```
POST   /api/v1/admin/categories      - Tambah kategori
PUT    /api/v1/admin/categories/{id} - Update kategori
DELETE /api/v1/admin/categories/{id} - Hapus kategori
```

#### Products Management
```
POST   /api/v1/admin/products      - Tambah produk
PUT    /api/v1/admin/products/{id} - Update produk
DELETE /api/v1/admin/products/{id} - Hapus produk
```

#### Orders Management
```
GET /api/v1/admin/orders                     - List semua order
PUT /api/v1/admin/orders/{id}/status         - Update status order
PUT /api/v1/admin/orders/{id}/payment-status - Update status pembayaran
```

#### Customers Management
```
GET    /api/v1/admin/customers      - List customers
GET    /api/v1/admin/customers/{id} - Detail customer
PUT    /api/v1/admin/customers/{id} - Update customer
DELETE /api/v1/admin/customers/{id} - Hapus customer
```

#### Promos Management
```
GET    /api/v1/admin/promos      - List promos
GET    /api/v1/admin/promos/{id} - Detail promo
POST   /api/v1/admin/promos      - Tambah promo
PUT    /api/v1/admin/promos/{id} - Update promo
DELETE /api/v1/admin/promos/{id} - Hapus promo
```

## Authentication

API menggunakan JWT (JSON Web Token) untuk authentication.

### Login dan mendapatkan token:
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "admin@printmaster.id", "password": "admin123"}'
```

Response:
```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "user": { ... },
    "token": "eyJ0eXAiOiJKV1QiLC..."
  }
}
```

### Menggunakan token:
```bash
curl http://localhost:8000/api/v1/auth/me \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLC..."
```

## Default Credentials

Setelah menjalankan seeder:

**Super Admin:**
- Email: `admin@printmaster.id`
- Password: `admin123`

**Staff Admin:**
- Email: `staff@printmaster.id`
- Password: `staff123`

**Customer:**
- Email: `budi@email.com`
- Password: `user123`

## Response Format

### Success Response
```json
{
  "success": true,
  "message": "Success",
  "data": { ... }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": { ... }
}
```

### Paginated Response
```json
{
  "success": true,
  "message": "Success",
  "data": [ ... ],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 50,
    "last_page": 4
  }
}
```

## Query Parameters

### Filtering
- `?active=true` - Filter by active status
- `?category_id=xxx` - Filter product by category
- `?best_seller=true` - Filter best seller products
- `?promo=true` - Filter promo products

### Search
- `?search=keyword` - Search by name/description

### Sorting
- `?sort_by=name&sort_dir=asc` - Sort results

### Pagination
- `?per_page=15&page=1` - Paginate results

## Tech Stack

- **Framework:** Lumen 10.x
- **Database:** MySQL
- **Authentication:** JWT (firebase/php-jwt)
- **UUID:** Laravel HasUuids Trait

## Development

### Running Tests
```bash
vendor/bin/phpunit
```

### Clear Cache
```bash
php artisan cache:clear
```

## License

MIT License
