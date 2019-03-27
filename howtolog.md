# Logging development process milestones

```
composer create-project --prefer-dist laravel/laravel cms

cd cms

php artisan make:auth

php artisan migrate

(npm install --global gulp-cli)

npm install

gulp build

php artisan vendor:publish 
    9 - laravel-errors
    
php artisan make:migration ModifyUsersTable (is_super_admin)
php artisan migrate

php artisan make:command SuperadminCreate

```