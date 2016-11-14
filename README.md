System Requirements

1. PHP version must be higher than 5.3.5

   PHP will also need to have the following libraries installed:

   PHP intl
   PHP curl
   PHP mbstring
   PHPxml
   PHP openssl
   

2. Installed Cakephp version 3.3.8


3. MySql 
Database name:db_techtest

Admin
http://host/techtest/users/login
username:samir@otech.ne.jp
pass:12345

Front End
http://host/techtest/

Modified and created files list bellow
1. Database configure
config/app.php

2. Email Configuration
config/emailContact.php

3. Email layout file 
src/template/layout/Email/html/sendLayout.ctp
src/template/Email/html/sendLayout.ctp

4. Admin Login and Auth
/src/Controller/AppController.php
/src/Controller/UsersController.php
5. Contact Form
/src/Controller/ContactController.php


6. Validation Conatct form 
src\Model\Table/ContactTable.php

7. Validation User 
src\Model\Table/UsersTable.php

8. passowrd hash
src\Model\Entity/User.php
