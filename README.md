
# Phishing Attack Demonstration

## Overview

This repository contains a demonstration of a phishing attack aimed at educating users on the tactics used by attackers and how to recognize and avoid such threats. The demonstration mimics the look and feel of a popular social media platform to illustrate how attackers can deceive users into providing sensitive information.

**Demo Link:** [http://instagram.totalh.net/](http://instagram.totalh.net/)

## Disclaimer

**WARNING: This project is strictly for educational purposes. Do not use this code or any part of it for malicious activities. Unauthorized access to computer systems is illegal and unethical.**

## Table of Contents

- [Overview](#overview)
- [Disclaimer](#disclaimer)
- [How It Works](#how-it-works)
- [Installation](#installation)
- [Usage](#usage)
- [Prevention Tips](#prevention-tips)
- [License](#license)

## How It Works

The demonstration creates a fake login page that closely resembles the login page of Instagram. When users enter their credentials, the information is captured and stored in a database. This method is commonly used by attackers to steal sensitive information such as usernames, passwords, and other personal details.

## Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/Student408/phishing-demonstration-.git
   cd phishing-demonstration-
   ```

2. **Set up the environment**:
   - Ensure you have a web server (e.g., Apache, Nginx) running.
   - Place the files in your web server's root directory.

3. **Database setup**:
   - Create a database and import the `phishing_demo.sql` file to set up the necessary tables.
   - Update the database connection details in the `config.php` file.

## Usage

1. **Launch the demonstration**:
   - Open the demo link in a web browser: [http://instagram.totalh.net/](http://instagram.totalh.net/)

2. **Enter credentials**:
   - The demonstration will capture and log the entered credentials for educational review.

3. **Review logged credentials**:
   - Access the database to see how the captured credentials are stored.

## Prevention Tips

- **Verify URLs**: Always check the URL in the browser's address bar to ensure you're on the legitimate site.
- **Use 2FA**: Enable two-factor authentication on your accounts for an added layer of security.
- **Be cautious with links**: Avoid clicking on links from unknown or untrusted sources.
- **Educate yourself**: Stay informed about the latest phishing tactics and how to recognize them.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.
