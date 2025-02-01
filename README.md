# MailSender Application

This is a **MailSender** application that allows you to schedule and send emails using PHPMailer, powered by **InfinityFree**'s free hosting services. The application utilizes Gmail's SMTP server to send emails and schedules them using a cron job via **EasyCron**.

## Project Overview

This application is designed to send scheduled emails based on the database entries. It allows users to:

- **Register and Login**: Create accounts and authenticate users for sending emails.
###
![ScreenShot Tool -20250201114404](https://github.com/user-attachments/assets/9e00ba98-941a-4da0-b680-d5d221d2ac3b)
###
![ScreenShot Tool -20250201114427](https://github.com/user-attachments/assets/709d032a-da33-4b20-a561-981f63fce7cd)
###
![ScreenShot Tool -20250201114456](https://github.com/user-attachments/assets/731e2219-10c6-4c18-aed6-b71d1496540b)
###
- **Schedule Emails**: Schedule emails to be sent at a later time.
###
![ScreenShot Tool -20250201114520](https://github.com/user-attachments/assets/dbba7365-925e-47d8-8796-8fbf54f04463)
###
**Automating Email Scheduling with EasyCron**  

To ensure emails are sent automatically at the scheduled time, we use a **cron job** to periodically execute `send_scheduled_emails.php`. This script checks the database for emails marked as "pending" and sends them if their scheduled time has arrived.  

#### **Setting Up the Cron Job with EasyCron**  
Since free hosting services like **InfinityFree** do not provide built-in cron jobs, we use **EasyCron**, a third-party cron job scheduling service. Below is a breakdown of the EasyCron dashboard:  

- **Account:** `rk987828@gmail.com`  
- **Plan:** Individual Trial  
- **Cron Jobs:** 1 Active Job  
- **Schedule:** Runs every day at `13:01` (UTC)  
- **Target Script:** `phptestingtutedude.ct.ws/App/send_scheduled_emails.php`  

This means that every day, at the specified time, EasyCron triggers our script, which:  
1. Fetches unsent emails from the `email_schedule` table.  
2. Uses **PHPMailer** to send them via Gmail’s SMTP server.  
3. Updates the email status in the database.  

#### **How the Cron Job Works**  
- The **EasyCron** scheduler automatically **refreshes the script URL** daily, triggering the email-sending function.  
- Emails scheduled for that day are processed and sent using **PHPMailer**.  
- The status of each email is updated in the **email_schedule** table.  

###
![ScreenShot Tool -20250201115343](https://github.com/user-attachments/assets/d9b1ecd4-5bc7-49d9-90fd-ab9d9e6ca60d)
###
This setup ensures that scheduled emails are automatically sent without manual intervention, even on free hosting environments.

###

The application integrates a **SQL database** hosted by **InfinityFree**. The scheduled emails are tracked in the `email_schedule` table, where emails are marked as `pending` until they are sent, and their status is updated to `sent` once successfully delivered.

## Directory Structure
- This directory structure is the same as the one uploaded to GitHub & similar to the infinityfree.com File Manager.
```
📁 htdocs
   ├── 📁 App
   │   ├── 📁 src
   │   │   ├── PHPMailer.php
   │   │   ├── SMTP.php
   │   │   └── Exception.php
   │   ├── login.php
   │   ├── register.php
   │   ├── send_email.php
   │   ├── db_connect.php
   │   ├── schedule_email.php
   │   ├── schedule_email.html
   │   └── send_scheduled_emails.php
   ├── index.php      <-- Main landing page for your website
   ├── index.html     <-- Optional: If you prefer an HTML landing page
   ├── info.php       <-- Information page or testing script
   ├── test.php       <-- PHP Test Script
```

### File Details

- **`index.php`**: The dynamic landing page that welcomes users and ensures that PHP is working correctly. If you use **InfinityFree**, they require this file to confirm that your website is functioning.
  
  _Example of code in `index.php`_:
  
  ```php
  <?php
    echo "Welcome to our MailSender Application!";
  ?>
  ```

- **`index.html`**: An optional static landing page. If you prefer a basic HTML page, this is used as the default by InfinityFree to confirm the site is working.

  _Example of code in `index.html`_:
  
  ```html
  <!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Welcome to My Website</title>
  </head>
  <body>
      <h1>Welcome to My Website!</h1>
      <p>If you see this page, your website is working correctly!</p>
  </body>
  </html>
  ```

- **`info.php`**: A testing script to display PHP configuration details.
  
  _Example of code in `info.php`_:
  
  ```php
  <?php
  phpinfo();
  ?>
  ```

- **`test.php`**: A basic PHP test script to check if PHP is working.

  _Example of code in `test.php`_:
  
  ```php
  <?php
  echo "PHP is working!";
  ?>
  ```

- **`db_connect.php`**: This file establishes a connection to your SQL database hosted by InfinityFree. We used their free SQL database service to manage and schedule emails.

- **`schedule_email.php` and `schedule_email.html`**: These files handle scheduling the emails in the database. The `schedule_email.php` allows users to input email details (e.g., recipient, subject, message), and the status is marked as `pending`. You can manage your scheduled emails and mark them as `sent` after they are successfully delivered.

  The table `email_schedule` is created inside InfinityFree’s SQL management interface. Here’s an example of how the table structure looks:

  ```sql
  CREATE TABLE `email_schedule` (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `recipient_email` VARCHAR(255) COLLATE latin1_swedish_ci,
      `subject` VARCHAR(255) COLLATE latin1_swedish_ci,
      `message` TEXT COLLATE latin1_swedish_ci,
      `scheduled_time` DATETIME,
      `status` ENUM('pending', 'sent') COLLATE latin1_swedish_ci,
      PRIMARY KEY (`id`)
  );
  ```

  - **`recipient_email`**: Email address of the recipient.
  - **`subject`**: The subject of the email.
  - **`message`**: The body of the email.
  - **`scheduled_time`**: The time the email is scheduled to be sent.
  - **`status`**: Indicates whether the email is `pending` or `sent`.

- **`send_scheduled_emails.php`**: This script is triggered by a **cron job** (we used EasyCron) to check for emails scheduled in the database and send them at the scheduled time using **PHPMailer**.

  The `send_scheduled_emails.php` script connects to Gmail's SMTP server using an **App Password** for secure login. If you want to use Gmail's SMTP service, you need to create an App Password to allow your script to authenticate and send emails on behalf of your Gmail account.

  **Important**: You need to generate the App Password in Gmail, which will be used in the `send_scheduled_emails.php` file for sending emails.


## Steps to Set Up

### 1. **Setting Up the Database on InfinityFree**

- Log in to your **InfinityFree** account.
- Go to **Control Panel** > **MySQL Databases**.
- Create a new database and a user.
- Use the **phpMyAdmin** panel to import the SQL table structure for `email_schedule`.
- Connect the database to your app by configuring the `db_connect.php` file with the appropriate credentials.

### 2. **Creating Gmail App Password**

- Visit [Google's App Passwords page](https://myaccount.google.com/apppasswords).
- Select the app (e.g., "Mail") and device (e.g., "Other").
- Generate the app password and replace the password in the `send_scheduled_emails.php` file.

### 3. **Setting Up Cron Job with EasyCron**

We use EasyCron to automate the sending of scheduled emails. Here’s how to set up the cron job:

1. Sign up for an EasyCron account at [EasyCron](https://www.easycron.com/).
2. Once signed in, click "Create Cron Job".
3. Enter the URL of the PHP script you want to run (e.g., `https://yourdomain.com/App/send_scheduled_emails.php`).
4. Set the interval (e.g., every 5 minutes).
5. Save the cron job.


### **Database Structure**

For this application, we are using **two main tables** in the SQL database:

1. **users table**: Stores the user credentials (username, email, and hashed password).
   
   - The password is hashed using PHP’s `password_hash()` function to enhance security, ensuring that passwords are not stored in plain text.
   
   - **Relevant Code**:
     ```php
     // Hashing password before storing it in the database
     $hashed_password = password_hash($password, PASSWORD_DEFAULT);
     ```

2. **email_schedule table**: Stores information about scheduled emails, including their status (pending or sent). This table helps manage the scheduling of emails that need to be sent later.

   - **Relevant Code**:
     ```php
     // Query to insert a scheduled email into the email_schedule table
     $stmt = $conn->prepare("INSERT INTO email_schedule (email_subject, email_body, scheduled_time) VALUES (?, ?, ?)");
     $stmt->bind_param("sss", $email_subject, $email_body, $scheduled_time);
     ```

### **How to Create SQL Tables on InfinityFree**

To create these tables on InfinityFree’s SQL database:

1. Log in to your **InfinityFree** account and navigate to the **MySQL Database** section.
2. Create a new database (or use an existing one).
3. Open **phpMyAdmin**, which is available under the **Databases** section.
4. In phpMyAdmin, use the **SQL tab** to run SQL queries to create the tables. Here's an example for the `users` table:

   ```sql
   CREATE TABLE users (
       id INT AUTO_INCREMENT PRIMARY KEY,
       username VARCHAR(50) NOT NULL,
       email VARCHAR(100) NOT NULL,
       password VARCHAR(255) NOT NULL
   );
   ```

5. Similarly, create the `email_schedule` table with the following query:

   ```sql
   CREATE TABLE email_schedule (
       id INT AUTO_INCREMENT PRIMARY KEY,
       email_subject VARCHAR(100) NOT NULL,
       email_body TEXT NOT NULL,
       scheduled_time DATETIME NOT NULL,
       status ENUM('pending', 'sent') DEFAULT 'pending'
   );
   ```

Make sure to adjust the column types and sizes according to your needs.

### **PHPMailer Setup**

In this application, we use **PHPMailer** to send emails securely. PHPMailer is a popular library for sending email via SMTP with additional features like HTML email formatting, attachments, etc. You can download PHPMailer from GitHub, then upload the necessary files to your application directory.

#### **Steps to Download and Set Up PHPMailer**

1. **Download PHPMailer from GitHub**:
   - Go to the [PHPMailer GitHub repository](https://github.com/PHPMailer/PHPMailer).
   - Download the repository as a ZIP file by clicking on the green **Code** button and selecting **Download ZIP**.
   - Unzip the downloaded file to get the **`src`** folder.

2. **Upload PHPMailer to Your Application Directory**:
   - Upload the **`src`** folder to the **`App`** directory of your application. This should be structured as shown below:
````
   📁 **htdocs**  
   ├── 📁 **App**  
   │   ├── 📁 **src**  
   │   │   ├── **PHPMailer.php**  
   │   │   ├── **SMTP.php**  
   │   │   └── **Exception.php**  
   │   ├── **login.php**  
   │   ├── **register.php**  
   │   ├── **send_email.php**  
   │   ├── **db_connect.php**  
   │   ├── **schedule_email.php**  
   │   ├── **schedule_email.html**  
   │   └── **send_scheduled_emails.php**  
   ├── **index.php**  <-- Main landing page for your website  
   ├── **index.html** <-- Optional: If you prefer an HTML landing page  
   ├── **info.php**   <-- Information page or testing script  
   ├── **test.php**   <-- PHP Test Script  

```

3. **Include PHPMailer in Your Application**:
   - After uploading the `src` folder, you need to include the PHPMailer classes in your PHP files where you send emails. For example, in **`send_email.php`**, you should include PHPMailer as follows:
     ```php
     // Include PHPMailer files
     require 'App/src/PHPMailer.php';
     require 'App/src/SMTP.php';
     require 'App/src/Exception.php';
     ```

4. **Configure SMTP Settings**:
   - To send emails, configure your SMTP server settings in **`send_email.php`** or wherever email sending occurs:
     ```php
     $mail = new PHPMailer\PHPMailer\PHPMailer();
     $mail->isSMTP();
     $mail->Host = 'smtp.example.com'; // SMTP server address
     $mail->SMTPAuth = true;
     $mail->Username = 'your-email@example.com'; // SMTP username
     $mail->Password = 'your-email-password'; // SMTP password
     $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
     $mail->Port = 587; // SMTP port
     ```

#### **Why PHPMailer?**
PHPMailer is widely used for sending emails from PHP applications due to its versatility and support for SMTP authentication, HTML formatting, and email attachments. Using PHPMailer allows you to send emails securely while preventing common issues with PHP's `mail()` function, such as deliverability problems.

### **Storing Credentials Securely**

In my application, the email address and app password are exposed for the purpose of practicing and learning, but **this is not recommended for production environments**. For better security, we can store sensitive credentials in an environment file (`.env`). However, due to the limitations of the hosting service I'm using (infinityfree.com), I was unable to utilize this method in this particular setup.

That being said, here are the two files where you can find the credentials:
- **`send_email.php`**
- **`send_scheduled_emails.php`**

While it's not ideal to have credentials exposed in the code, I want to emphasize that in a production environment, **this approach should be avoided**. You can secure your credentials safely by using the **`phpdotenv`** library, which helps manage environment variables by loading them from a `.env` file.

#### **How to Use `.env` with PHPMailer**

1. **Install `phpdotenv` via Composer**:
   You can include **phpdotenv** in your project using **Composer**. If you don't have Composer installed, follow [these instructions](https://getcomposer.org/download/).

   Run the following command to install **phpdotenv**:
   ```bash
   composer require vlucas/phpdotenv
   ```

2. **Create a `.env` File**:
   In the root directory of your project, create a `.env` file that will store your sensitive data:
   ```
   EMAIL_ADDRESS=your-email@example.com
   APP_PASSWORD=your-email-password
   ```

3. **Load Environment Variables**:
   In your PHP code (e.g., `send_email.php`), use **phpdotenv** to load the environment variables:
   ```php
   require 'vendor/autoload.php'; // Include the Composer autoloader
   $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
   $dotenv->load();

   $email = $_ENV['EMAIL_ADDRESS'];
   $password = $_ENV['APP_PASSWORD'];
   ```

4. **Update Your PHPMailer Configuration**:
   Replace the hardcoded credentials with the variables loaded from the `.env` file:
   ```php
   $mail->Username = $email;
   $mail->Password = $password;
   ```

By using **phpdotenv**, your sensitive information is stored securely, and it's much safer than keeping it exposed in the source code.

---

### **Important Notes**

- **InfinityFree Hosting Limitations**:
  While **phpdotenv** is a great solution for handling sensitive data securely, **InfinityFree** (the free hosting service used in this project) offers limited memory and does not support advanced features like environment variable management or Composer. Due to these limitations, I did not implement this feature in my current setup.

- **Account Deletion**:
  Please note that **I will be deleting my InfinityFree account** after completing this project, so you won't be able to access my email credentials once that happens.

- **Important**:
  To **free up space** on InfinityFree, **do not delete the `index.php` file** from the root directory, as it is required for the website to work properly on InfinityFree.



This section makes sure users are aware of the security measures taken, while also providing a way to handle credentials securely in future projects, along with the context about limitations due to the hosting service.

## Final Thoughts

This application allows you to schedule and automatically send emails, ensuring that your emails are delivered at the right time. We utilized **InfinityFree** for the hosting and database, **Gmail's SMTP server** for email delivery, and **EasyCron** to automate the process.

We hope this guide helps you understand the setup and working of this MailSender application. If you have any questions, feel free to reach out!


