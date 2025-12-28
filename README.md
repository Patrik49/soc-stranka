# Website Setup with XAMPP

This guide will walk you through setting up your PHP website with a MariaDB database using XAMPP.

## 1. Install XAMPP

- Download and install XAMPP from the [official website](https://www.apachefriends.org/index.html).
- During installation, you only need to select and install:
  - **Apache**
  - **PHP**
  - **phpMyAdmin**
  - **MySQL** (MariaDB will be installed as part of this)

## 2. Start Services

- Open the XAMPP Control Panel.
- Start the **Apache** and **MySQL** services.

## 3. Set Up the Database

1.  **Open phpMyAdmin:** In your web browser, go to `http://localhost/phpmyadmin`.
2.  **Create a database:**
    - Click on the **Databases** tab.
    - Under "Create database", enter `soc_stranka` and click **Create**.
3.  **Import tables:**
    - Select the `soc_stranka` database from the left-hand menu.
    - Click on the **Import** tab.
    - Click **Choose File** and select the `database.sql` file from this project.
    - Click **Go** at the bottom of the page to import the tables.

## 4. Run the Website

1.  **Move project files:** Copy the entire `soc-stranka` project folder into the `htdocs` directory inside your XAMPP installation folder (e.g., `C:\xampp\htdocs\soc-stranka`).
2.  **Access your site:** Open your web browser and navigate to `http://localhost/soc-stranka/`.

Your website should now be running and connected to the database.
