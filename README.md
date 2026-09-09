# Orcullo Custom Controllers

A web application for exploring, customizing, and purchasing arcade sticks, leverless controllers, and custom fightsticks.

---

##  Features

- **Storefront & Catalog**: Browse pre-built controllers, adapters, and arcade parts with real-time stock indicators.
- **Controller Customizer**: Choose controller layouts (Arcade Stick, Leverless, Gamepad), pushbutton colors, and upload custom top-panel artwork.
- **Shopping Cart & Checkout**: Seamless order placement with transactional order recording.
- **User Accounts & Authentication**: Secure registration and login with session-based authentication and role authorization.
- **Customer Dashboard**: View order history, tracking statuses, and manage account credentials.
- **Admin Control Panel**: Manage product catalog, adjust stock levels, update customer roles, and modify order statuses.

---

## Built With

- **Backend**: Pure PHP (No client-side JavaScript frameworks)
- **Database**: MySQL / MariaDB via PDO
- **Frontend**: Semantic HTML5, CSS3 (Glassmorphism & Responsive Design)
- **Icons & Fonts**: FontAwesome 6, Space Grotesk, Inter

---

##  Getting Started

1. Place the project folder into your XAMPP/LAMPP web directory:
   ```bash
   /opt/lampp/htdocs/my-project/
   ```
2. Start Apache and MySQL services in your LAMPP control panel:
   ```bash
   sudo /opt/lampp/lampp start
   ```
3. Import the database schema into MySQL:
   - Database name: `e_commerce` (or your configured DB name in `database/db.php`).
4. Access the site in your browser:
   ```text
   http://localhost/my-project/
   ```

---

## Project Structure

```text
my-project/
├── about/          # About us page
├── Assets/         # Images, banners, and product photos
├── auth/           # Login, registration, and session scripts
├── cart/           # Shopping cart handling
├── checkout/       # Checkout flow & order confirmation
├── css/            # Modular stylesheets for all sections
├── customize/      # Interactive customizer configuration
├── dashboard/      # User & Administrator control panels
├── database/       # DB connection & CRUD functions
├── global/         # Global header and footer components
└── index.php       # Homepage
```

---