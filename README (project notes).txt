NOTE:

I have removed all vendor packages from all projects
before uploading them here.

for site1: 
1. Please run "composer update" after extracting the project
2. Navigate inside the directory and you can run it using
php -S localhost:8000 -t .\public
3. the database used is not included here, modify the env file for the correct database connection
4. create the database, table and columns in your respective database engine manually since this example does not have migrations.

for laravelAuthorsApi:
1. Please run "composer update" after extracting the project
2. Navigate inside the directory and you can run it using
php -S localhost:8001 -t .\public
3. the sqlite database used is included here

for laravelBooksApi:
1. Please run "composer update" after extracting the project
2. Navigate inside the directory and you can run it using
php -S localhost:8002 -t .\public
3. the sqlite database used is included here

for laravelAuthorsApi:
1. Please run "composer update" after extracting the project
2. Navigate inside the directory and you can run it using
php -S localhost:8003 -t .\public
3. the sqlite database used is included here

for Paypaldemo:
1. Please run "composer update" after extracting the project
2. login to your paypal developer account, go to API and Credentials, get Client_ID and Secret
3. set those values in .env
4. Navigate inside the directory and you can run it using
php -S localhost:8004 -t .\public
5. no database used in this project
6. for sandbox transactions, you have dummy accounts in your paypal developer accounts (in Testing Menu)
7. if encountering "sizeof()" error, navigate to
vendor/paypal/lib/paypal/common/paypalmodel.php

edit this line (usually around line 178):
[OLD]
} else if (sizeof($v) <= 0 && is_array($v)) {

[NEW]
} else if (is_array($v) && sizeof($v) <= 0) {

8. restart server and test again



