# PMA Blog

The blog built on Day 3 of the PHP and Laravel workshop at the Philippine Military Academy.

It is a small Laravel 13 app that covers:

- CRUD on posts: list, create, edit, delete
- Forms with validation
- Login and logout, with the `auth` middleware on the routes that change posts
- The repository pattern: `PostController` depends on the `PostRepository` interface
- Automated tests for all of the above

## Requirements

- PHP 8.3 or newer, with the SQLite extension
- Composer

Node is not needed.

## Setup

```
git clone https://github.com/rdpascua/pma-blog.git
cd pma-blog
composer setup
php artisan serve
```

Then open http://127.0.0.1:8000/posts.

`composer setup` installs the packages, creates `.env` and the SQLite database, and fills the database with one user and two posts. Running it again resets the database.

## Login

| Email | Password |
|-|-|
| admin@example.com | secret123 |

This user is created by `database/seeders/DatabaseSeeder.php`. It is for practice only.

## Run the tests

```
php artisan test
```

The tests use a separate in-memory database. They do not touch your posts.

## Where the code is

| What | File |
|-|-|
| Routes | `routes/web.php` |
| Posts table | `database/migrations/2026_11_24_000000_create_posts_table.php` |
| Post model | `app/Models/Post.php` |
| Post pages | `app/Http/Controllers/PostController.php` |
| Login and logout | `app/Http/Controllers/LoginController.php` |
| Repository interface | `app/Repositories/PostRepository.php` |
| Repository class | `app/Repositories/EloquentPostRepository.php` |
| Repository binding | `app/Providers/AppServiceProvider.php` |
| Views | `resources/views/posts/` and `resources/views/login.blade.php` |
| Sample data | `database/seeders/DatabaseSeeder.php` |
| Tests | `tests/Feature/` |

## The routes

| Method | URL | Controller method | Login needed |
|-|-|-|-|
| GET | `/posts` | `PostController::index` | No |
| GET | `/posts/create` | `PostController::create` | Yes |
| POST | `/posts` | `PostController::store` | Yes |
| GET | `/posts/{post}/edit` | `PostController::edit` | Yes |
| PUT | `/posts/{post}` | `PostController::update` | Yes |
| DELETE | `/posts/{post}` | `PostController::destroy` | Yes |
| GET | `/login` | `LoginController::show` | No |
| POST | `/login` | `LoginController::login` | No |
| POST | `/logout` | `LoginController::logout` | No |

## The tests

| File | What it checks |
|-|-|
| `PostTest.php` | Each post page: the list, the forms, validation, guests being sent to the login page |
| `LoginTest.php` | Logging in, wrong credentials, logging out |
| `PostRepositoryTest.php` | The repository methods and the binding |
| `BlogFlowTest.php` | One full session: log in, create a post, edit it, delete it, log out |

## Things to try

1. In `PostController::store`, change the redirect to `/` and run the tests. Read which test fails and why. Change it back.
2. Remove `->middleware('auth')` from the group in `routes/web.php` and run the tests.
3. In `EloquentPostRepository::all`, add `->take(10)` before `->get()`. The controller does not change.
4. Add a `show` page for one post at `/posts/{post}`, and write a test for it first.
