#Testing

##Unit tests
Create feature test classes in the Feature directory by running:

    php artisan make:test MyTest
    
Create unit test classes with:

    php artisan make:test MyTest --unit
    
Run unit tests with

    phpunit
    
##Browser tests
Create browser test classes by running:

    php artisan dusk:make MyTest
    
Run browser tests with

    php artisan dusk