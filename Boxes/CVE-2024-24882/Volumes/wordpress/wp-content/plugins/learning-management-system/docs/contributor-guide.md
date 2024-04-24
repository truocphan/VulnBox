# Contributor Guidelines
Welcome to Masteriyo Learning Management System. 

# How can I contribute?
To learn how to contribute to this project please see the sections below

# Setup
Before running this project you need to configure few things. Also make sure you have all these setup already.
* Node Js
* Local PHP Server
* Composer Installed
* WordPress Setup 

### Clone The Project
You need to clone/fork this project.

### Install Dependencies
```
yarn install
composer install
```
  
### Configure wp-config.php
Add this to your `wp-config.php`
```
define( 'SCRIPT_DEBUG', true );
define( 'MASTERIYO_DEVELOPMENT', true);
```
### Set Environment variables
You need to provide your WordPress URL.
Copy `.env.example` to `.env` and set your `WORDPRESS_URL`
