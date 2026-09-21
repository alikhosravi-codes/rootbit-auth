# 🔐 RootBit Auth

یک سیستم احراز هویت امن با استفاده از **PHP و MariaDB**، با تمرکز بر توسعه Backend و امنیت برنامه‌های وب.

> **Learn → Build → Analyze → Secure**

---

## 📌 درباره پروژه

**RootBit Auth** یک سیستم احراز هویت واقعی است که از صفر با PHP ساخته شده است.

جریان اصلی پروژه:

```text
ثبت‌نام → ورود → Session → داشبورد → خروج
```

هدف پروژه فقط ساخت یک سیستم Login نیست؛ بلکه تلاش شده اصول مهم امنیت وب در فرآیند توسعه نیز رعایت و آزمایش شوند.

---

## ✨ قابلیت‌ها

* ثبت‌نام کاربر
* ورود کاربر
* احراز هویت مبتنی بر Session
* داشبورد محافظت‌شده
* خروج امن از حساب
* Hash کردن رمز عبور
* اعتبارسنجی سمت سرور
* جلوگیری از ثبت Email تکراری
* جلوگیری از ثبت Username تکراری
* محافظت در برابر CSRF
* محافظت در برابر XSS
* جلوگیری از SQL Injection
* محافظت در برابر Session Fixation
* تنظیمات امنیتی Session Cookie
* استفاده از PDO برای ارتباط با دیتابیس
* مدیریت اطلاعات دیتابیس با Environment Variables

---

## 🛡️ امنیت

امنیت در مراحل مختلف توسعه پروژه در نظر گرفته شده است.

### SQL Injection

برای Queryهای دیتابیس از **PDO Prepared Statements** استفاده شده و ورودی کاربر مستقیماً داخل SQL قرار نمی‌گیرد.

### Password Security

رمزهای عبور به صورت Plain Text ذخیره نمی‌شوند.

برای Hash کردن رمز:

```php
password_hash($password, PASSWORD_DEFAULT);
```

و برای بررسی رمز:

```php
password_verify($password, $user['password']);
```

استفاده شده است.

### XSS Protection

اطلاعاتی که از کاربر دریافت می‌شوند، هنگام نمایش با:

```php
htmlspecialchars()
```

Escape می‌شوند.

### CSRF Protection

فرم‌ها دارای CSRF Token هستند.

Token با استفاده از:

```php
random_bytes()
```

ساخته شده و با:

```php
hash_equals()
```

بررسی می‌شود.

### Session Security

پس از ورود موفق کاربر:

```php
session_regenerate_id(true);
```

اجرا می‌شود تا در برابر Session Fixation محافظت ایجاد شود.

Session Cookie نیز با ویژگی‌های زیر تنظیم شده است:

* `HttpOnly`
* `SameSite=Lax`

---

## 🧪 تست‌های امنیتی

پروژه به صورت دستی در برابر چند سناریوی امنیتی آزمایش شده است.

| تست                            | نتیجه        |
| ------------------------------ | ------------ |
| SQL Injection                  | ✅ محافظت شده |
| XSS                            | ✅ محافظت شده |
| CSRF                           | ✅ محافظت شده |
| Session Fixation               | ✅ محافظت شده |
| Password Storage               | ✅ Hash شده   |
| Server-Side Validation         | ✅ فعال       |
| Duplicate Email                | ✅ مسدود شده  |
| Duplicate Username             | ✅ مسدود شده  |
| دسترسی بدون Login به Dashboard | ✅ مسدود شده  |

---

## 🛠️ تکنولوژی‌ها

* PHP 8.5
* MariaDB 11.8
* Apache
* PDO
* Composer
* PHPDotenv
* HTML5
* CSS3
* Git
* GitHub

---

## 📁 ساختار پروژه

```text
rootbit-auth/
│
├── config/
│   └── database.php
│
├── includes/
│   ├── auth.php
│   └── csrf.php
│
├── public/
│   ├── assets/
│   │   └── style.css
│   │
│   ├── dashboard.php
│   ├── index.php
│   ├── login.php
│   ├── logout.php
│   └── register.php
│
├── .gitignore
├── composer.json
├── composer.lock
└── README.md
```

---

## ⚙️ نصب و اجرا

### 1. دریافت پروژه

```bash
git clone YOUR_REPOSITORY_URL
cd rootbit-auth
```

### 2. نصب Dependencies

```bash
composer install
```

### 3. ساخت Database

یک Database با نام زیر در MariaDB ایجاد کنید:

```text
rootbit_auth
```

سپس جدول `users` را ایجاد کنید:

```sql
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 4. تنظیم Environment Variables

در ریشه پروژه یک فایل `.env` بسازید:

```env
DB_HOST=localhost
DB_NAME=rootbit_auth
DB_USER=your_database_user
DB_PASSWORD=your_database_password
```

> ⚠️ فایل `.env` را هرگز در GitHub قرار ندهید.

### 5. تنظیم Apache

Document Root یا مسیر سرویس Apache را به پوشه `public/` پروژه تنظیم کنید.

سپس پروژه را از طریق آدرس زیر باز کنید:

```text
http://localhost/rootbit-auth/public/
```

---

## 🔐 نکات مربوط به Production

این پروژه برای اهداف آموزشی و Portfolio ساخته شده است.

برای استفاده در محیط Production، موارد بیشتری باید در نظر گرفته شوند:

* HTTPS
* فعال‌سازی `Secure` برای Session Cookie
* Login Rate Limiting
* محافظت در برابر Brute Force
* Security Headers
* ثبت و مدیریت خطاها
* Account Recovery
* Email Verification
* محدودیت‌های قوی‌تر برای ورودی‌ها
* Hardening سرور

---

## 👨‍💻 سازنده

**علی خسروی**

توسعه‌دهنده Backend • مهندسی نرم‌افزار • امنیت سایبری

GitHub:

`https://github.com/alikhosravi-codes`

---


