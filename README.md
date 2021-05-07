Document Root Source

II. Set .env file
    Clone .env.example to .env and set database info.
    
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=database_name
    DB_USERNAME=root
    DB_PASSWORD=
    
III. Install laravel in root folder project
    Run this command:
    
        composer install
        
IV. Migrate base table
    Run this command:
                       
        php artisan migrate
        
V. Run permission
    Run this command:
     
        php artisan permissions:update

VI. Generate Application Key:

        php artisan key:generate

