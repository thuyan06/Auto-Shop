# Online Shop

**Online Shop** is a web application that allows users to browse, purchase, and review automotive products. Built using PHP and MongoDB, this application includes features such as user login, a shopping cart, product reviews, and an admin area for managing products. Follow the instructions below to set up and run the application on both Windows and Linux systems.

## Features

- **User Login:** Secure login system for customers and admins.
- **Shopping Cart:** Manage and purchase products.
- **Product Reviews:** Customers can leave reviews on products.
- **Admin Panel:** Manage product listings and details.
- **Custom Product Attributes:** Products can have additional attributes like size and previous owners.

## Prerequisites

To run the Online Shop locally, you'll need the following:

- **XAMPP**: For running Apache and PHP.
- **PHP 7.4**: Required for the application.
- **Composer**: For managing PHP dependencies.
- **MongoDB**: NoSQL database for storing data.
- **MongoDB PHP Extension**: For PHP-MongoDB integration.

## Installation on Windows

1. **Clone the repository:**

    ```bash
    git clone https://github.com/PJR23/Online-Shop.git
    ```

2. **Navigate to the project directory:**

    ```bash
    cd online-shop
    ```

3. **Install XAMPP:**

    - Download and install [XAMPP](https://www.apachefriends.org/index.html).
    - During installation, ensure that Apache and PHP are selected.

4. **Move files to XAMPP's `htdocs` directory:**

    - Copy the contents of the `online-shop` directory to the `htdocs` folder in your XAMPP installation directory (usually `C:\xampp\htdocs`).

5. **Install Composer:**

    - Download and install [Composer](https://getcomposer.org/download/).
    - Follow the installation instructions to set up Composer globally.

6. **Install PHP dependencies:**

    - Open Command Prompt or PowerShell.
    - Navigate to the `htdocs` directory:

      ```bash
      cd C:\xampp\htdocs\online-shop
      ```

    - Install dependencies using Composer:

      ```bash
      composer install
      ```

7. **Install MongoDB PHP Extension:**

    - Download the MongoDB PHP extension for PHP 7.4 from [PECL](https://pecl.php.net/package/mongodb).
    - Follow the installation instructions provided on the PECL page.

8. **Install and configure MongoDB:**

    - Download and install [MongoDB](https://www.mongodb.com/try/download/community).
    - Ensure MongoDB is running. 
    - Open the MongoDB Shell (`mongosh`) and execute the database creation and inserts scripts provided in the `repository` folder.

9. **Start XAMPP:**

    - Open the XAMPP Control Panel.
    - Start the Apache server.

10. **Access the application:**

    - Open a web browser and navigate to `http://localhost` to use the Online Shop application.

## Installation on Linux

1. **Clone the repository:**

    ```bash
    git clone https://github.com/PJR23/Online-Shop.git
    ```

2. **Navigate to the project directory:**

    ```bash
    cd online-shop
    ```

3. **Install XAMPP:**

    - Download and install XAMPP for Linux from [Apache Friends](https://www.apachefriends.org/index.html).
    - Follow the instructions for installation.

4. **Move files to XAMPP's `htdocs` directory:**

    - Copy the contents of the `online-shop` directory to the `htdocs` folder in your XAMPP installation directory (usually `/opt/lampp/htdocs`).

5. **Install Composer:**

    - Download and install Composer:

      ```bash
      curl -sS https://getcomposer.org/installer | php
      mv composer.phar /usr/local/bin/composer
      ```

6. **Install PHP dependencies:**

    - Navigate to the `htdocs` directory:

      ```bash
      cd /opt/lampp/htdocs/online-shop
      ```

    - Install dependencies using Composer:

      ```bash
      composer install
      ```

7. **Install MongoDB PHP Extension:**

    - Install the MongoDB PHP extension:

      ```bash
      sudo pecl install mongodb
      ```

    - Add `extension=mongodb.so` to your `php.ini` file (usually located in `/opt/lampp/etc/php.ini`).

8. **Install and configure MongoDB:**

    - Download and install MongoDB from [MongoDB](https://www.mongodb.com/try/download/community).
    - Ensure MongoDB is running.
    - Open the MongoDB Shell (`mongosh`) and execute the database creation and inserts scripts provided in the `repository` folder.

9. **Start XAMPP:**

    - Start XAMPP using:

      ```bash
      sudo /opt/lampp/lampp start
      ```

10. **Access the application:**

    - Open a web browser and navigate to `http://localhost` to use the Online Shop application.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
