# How to Setup Project

Hi! This document explains how to setup **Findemailaddress.co** dashboard.

## Clone Directory from Gitlab  
git clone https://gitlab.com/aneeqiftikhar/emailfinder.git  
This command will create a directory emailfinder.


## Create .env file    
### Extra keys   
APP_HOME_ADDRESS=http://localhost/emailfinder/public/login    
PYTHON_SERVER_IP=http://3.13.100.227:5000/  


MAIL_DRIVER=smtp  
MAIL_HOST=smtp.gmail.com  
MAIL_PORT=587  
MAIL_USERNAME=jacob@findemailaddress.co  
MAIL_PASSWORD=[Ask Admin]
MAIL_ENCRYPTION=tls  
MAIL_FROM_ADDRESS=no-reply@findemailaddress.co  
MAIL_FROM_NAME=FindEmailAddress.co  
MAIL_FROM_ADDRESS_SUPPORT=support@findemailaddress.co


SES_MAIL_DRIVER=smtp  
SES_MAIL_HOST=email-smtp.us-east-1.amazonaws.com  
SES_MAIL_PORT=587  
SES_MAIL_USERNAME=[Ask Admin]  
SES_MAIL_PASSWORD=[Ask Admin]
SES_MAIL_ENCRYPTION=tls  
SES_MAIL_FROM_ADDRESS=amsal@findmailaddress.com  
SES_MAIL_FROM_NAME=FindEmailAddress.co


FASTSPRING_USERNAME=[Ask Admin]  
FASTSPRING_PASSWORD=[Ask Admin]  
FASTSPRING_BASE_URI=[Ask Admin]
FASTSPRING_WEBHOOK_HMAC_SECRET=[Ask Admin] 
  
  
FAILED_RESPONSE_EMAIL=[Ask Admin]  
  
AUTOMIZY_TOKEN=[Ask Admin]


## Run composer Install

## Run php artisan migrate

## Replace [this] folder (in vendor) 
