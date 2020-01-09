## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.


## Using API

### Setup
    Clone this repository locally

    run: php artisan migrate --seed

### These are the list of available endpoints

    GET|HEAD  | api/users             

    POST      | api/users             

    POST      | api/users/list - Get users list

    POST      | api/users/search - Search user

    GET|HEAD  | api/users/{user}      

    PUT|PATCH | api/users/{user}      

    DELETE    | api/users/{user}      

    GET|HEAD  | api/users/{user}/edit

### Parameters of adding user

    name, email, password and password_confirmation

### Parameters of updating user - can be one of the following

    name, email, password and password_confirmation _method:PUT

### Parameters of deleting user

    _method:DELETE

### To perform search the following Parameters should be given
    length (pagination length), column (sort by this column) and search (key word)

### To get users list
    length (pagination length)



## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
