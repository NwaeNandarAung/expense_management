Requirment
-PHP version >= 7.2.5
-node js and npm

Version
-PHP version => 7.2.5
-Laravel version => 7
-Vue.js version => 2
-vuex version => 3.6.2 

Project Setup Guideline for Laravel
-Install all the dependencies with the following command.
    composer install 
-Copy the example env file and make the required configuration changes in the .env file
    cp .env.example .env
-Generate a new application key
    php artisan key:generate
-Run the database migrations
    php artisan migrate
Start the local development server
    php artisan serve

Project Setup Guideline for Vue
-Install npm dependencies with the following command.
    npm install
-Run local server
    npm run dev

