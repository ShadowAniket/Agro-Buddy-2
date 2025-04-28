# Agro-Buddy 2

Agro-Buddy 2 is a web-based platform designed to help farmers and agricultural businesses manage products, access weather data, and connect with buyers. The application provides a user-friendly interface for product listing, weather integration, and secure transactions.

---

## Features

- **User Authentication:** Secure login and registration for buyers and sellers.
- **Product Management:** Add, edit, and remove agricultural products.
- **Weather Integration:** Real-time weather data using OpenWeather API.
- **Cart & Checkout:** Add products to cart and complete purchases.
- **Order Management:** Track orders and view order history.
- **Admin Dashboard:** Manage users, products, and orders.
- **Responsive Design:** Works on desktop and mobile devices.

---

## Technologies Used

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **APIs:** OpenWeather API

---

## Getting Started

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) or any PHP/MySQL server
- PHP 7.0+
- MySQL 5.7+
- Composer (if using PHP dependencies)
- OpenWeather API Key

### Installation Steps

1. **Clone the Repository**
   ```sh
   git clone https://github.com/ShadowAniket/agro-buddy-2.git
   cd agro-buddy-2
   ```

2. **Database Setup**
   - Import `database.sql` into your MySQL server:
     1. Open phpMyAdmin or use the MySQL CLI.
     2. Create a new database (e.g., `agrobuddy`).
     3. Import the provided `database.sql` file.

3. **Configuration**
   - Copy `config.php.example` to `config.php`:
     ```sh
     cp config.php.example config.php
     ```
   - Edit `config.php` and set your database credentials and OpenWeather API key.

4. **Run the Application**
   - Place the project folder in your XAMPP `htdocs` directory.
   - Start Apache and MySQL from the XAMPP control panel.
   - Visit [http://localhost/Agro-Buddy%202/](http://localhost/Agro-Buddy%202/) in your browser.

---

## File Structure

```
Agro-Buddy 2/
├── images/                # Static images
├── *.php                  # PHP backend files
├── *.js, *.css, *.html    # Frontend assets
├── database.sql           # Database schema
├── config.php.example     # Example config (no secrets)
├── .gitignore
├── README.md
├── LICENSE
└── ...
```

---

## Security Notes

- **Do not commit `config.php` or any file with secrets.**
- Use `.gitignore` to keep sensitive files out of version control.
- Always use strong passwords and secure your database.

---

## Contributing

Contributions are welcome!  
1. Fork the repository  
2. Create a new branch  
3. Make your changes  
4. Submit a pull request

---

## License

This project is licensed under the MIT License. See [LICENSE](LICENSE) for details.

---

## Contact

- GitHub: [ShadowAniket](https://github.com/ShadowAniket)
- Email: aniketasawale10@gmail.com
- Linkedin: https://www.linkedin.com/in/aniket-asawale-242612270/
