# Transactional Email Microservice

Simple scalable email sending microservice build with Laravel.  Emails are sent with a high degree of
certainty through external services, via auto fallback mechanism. Supported email types are plain text and html.

Currently, implemented services are [SendGrid](https://sendgrid.com/) & [MailJet](https://www.mailjet.com/), but including new ones is as simple as:
* Implementing the `App\Services\Email\EmailDriverContract` interface
* Adding a factory method inside the `App\Services\Email\EmailManager`
* Adding the service's config inside `config.services.email`

The microservice utilizes [Laravel Sail](https://laravel.com/docs/8.x/sail) to provide smooth experience for both development and deployment with its out of the box docker configuration. The project uses:
* redis - as queue driver
* mailhog - for testing emails locally
* mysql - for logging outbound emails through internal network

### Installation

* Installing sail & project dependencies: [Laravel's docs](https://laravel.com/docs/8.x/sail#installing-composer-dependencies-for-existing-projects)
* Executing database migrations:
  ```
  ./vendor/bin/sail artisan migrate
  ```
* Running tests:
  ```
  ./vendor/bin/sail artisan test
  ```

### Configuration

* Enter SendGrid API key in `.env` 
* Enter MailJet API key & secret in `.env`
* Enter global "from" email address in `MAIL_FROM_ADDRESS` in `.env`

### Sending emails

Sending emails through the services is possible with two methods:
* Interactive command line interface 
  ```
  ./vendor/bin/sail artisan email:send
  ```
* JSON Rest API (swagger documentation available at `/api/documentation`)
  ```
  POST /api/send-transactional-emails
  ```
* Displaying the email outbound log (contains both successful & failed messages)
  ```
  ./vendor/bin/sail artisan email:log
  ```
