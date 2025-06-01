# MineFood Project

MineFood is an online food ordering and management system with both admin and user interfaces. Below is a clear, page-wise breakdown of the main features and structure of the project.

## Admin Side (Back End)

- **Dashboard (`admin/index.php`)**
  - Overview of platform statistics and quick actions.

- **User Management (`admin/user.php`)**
  - Add, edit, or delete users.
  - View user details and activity.

- **Dish Management (`admin/dish.php`, `admin/dishDetails.php`)**
  - Add, edit, or remove dishes.
  - Manage dish categories and details.

- **Banner Management (`admin/banner.php`)**
  - Add or update promotional banners.

- **Category Management (`admin/category.php`)**
  - Manage food categories for better organization.

- **Coupon Code Management (`admin/coupon-code.php`)**
  - Create and manage discount codes for promotions.

- **Delivery Boy Management (`admin/delivery-boy.php`)**
  - Add and manage delivery personnel.

- **Profile & Settings (`admin/profile.php`, `admin/settings.php`)**
  - Update admin profile and platform settings.
  - SEO and social media settings for each page.

- **Event Calendar (`admin/event-calendar.php`)**
  - Manage and view upcoming events or offers.

- **Authentication**
  - `admin/login.php`, `admin/logout.php`: Secure admin login/logout.

## Common Features

- **Responsive Design**: Works on desktop and mobile devices.
- **SEO & Social Media Integration**: Admin can set SEO meta tags and social links for each page.
- **Database**: MySQL database structure included in `MySQL_FILE/`.
- **Assets**: Organized CSS, JS, and image files for both admin and user interfaces.

## Getting Started

- Clone the repository and set up your web server (e.g., WAMP, XAMPP).
- Import the database from `MySQL_FILE/online_food.sql`.
- Update database credentials in `admin/config/database.php`.
- Access the user site via `user/index.php` and the admin panel via `admin/index.php`.

---

For more details, see the code comments and follow the links and instructions provided in this README.
