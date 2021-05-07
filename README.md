Document Root Source

I. How to Clone a Specific Branch?
   Run this command:
    
        git clone --branch <branchname> <remote-repo-url>
        git clone --branch laravel_admin https://github.com/phelanhp/template_admin.git
       
   Or:
    
        git clone -b <branchname> <remote-repo-url>
        git clone -b laravel_admin https://github.com/phelanhp/template_admin.git
     
   Here -b is just an alias for --branch
    
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

VII. Add new Module                  
    Run this command:
    
        php artisan make:module {name}
   
   {name} is Module name            
