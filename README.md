# LaraLance

This Laravel 10 API functions as a job hunting and application.

## How to test

1. Run `composer install` on your directory, and wait until autoload files are generated.

2. Copy *`.env.example`* and rename it to *`.env`*.

3. Create a MySQL database based from *`DB_DATABASE`* in the .env file. (Set by default: *`appdev2_finals_project_is3_harry_reyes`*)

4. Run `php artisan serve` on your directory to host a local server (*`http://127.0.0.1`*).

5. You need to use **Postman** to use these URL examples. Some routes require tokens that will be given to you upon registering.

    - **For USERS:**

        Total routes for users: 8

            POST
            1st User
            http://127.0.0.1:8000/api/users?username=harry&email=harry@example.com&password=Bruh_1234&password_confirmation=Bruh_1234

            2nd User
            http://127.0.0.1:8000/api/users?username=bruh&email=bruh@example.com&password=Harry_1234&password_confirmation=Harry_1234

            GET w/token
            http://127.0.0.1:8000/api/users

            http://127.0.0.1:8000/api/users/1

            POST w/token
            http://127.0.0.1:8000/api/users/search?q=har

            PUT|PATCH w/token
            http://127.0.0.1:8000/api/user?username=harr&email=harry@example.org

            POST w/token
            http://127.0.0.1:8000/api/logout

            http://127.0.0.1:8000/api/login?username=harry&password=Bruh_1234

            DELETE w/token
            http://127.0.0.1:8000/api/users/1?token=ijSLomBiyFv7Ic0Q811by7HK0QChc3ef

    ---

    - **For JOBS:**

        Total routes for jobs: 6

            GET
            http://127.0.0.1:8000/api/jobs

            http://127.0.0.1:8000/api/jobs/1

            POST
            http://127.0.0.1:8000/api/jobs/search?q=dev

            POST w/token
            http://127.0.0.1:8000/api/jobs?title=API Developer&company=LaraLance&site=https://laralance.example.com&desc=Laravel 10 API developer for our new web application for LaraLance.

            PUT|PATCH w/token
            http://127.0.0.1:8000/api/jobs/1?title=API Developer&company=LaraLance&site=https://laralance.example.com&desc=Laravel 10 API developer for our new web application for LaraLance.

            DELETE w/token
            http://127.0.0.1:8000/api/jobs/1

    ---

    - **For JOB APPLICATIONS:**

        Total routes for job applications: 8

            POST w/token
            http://127.0.0.1:8000/api/jobs/1/apply

            GET w/token
            http://127.0.0.1:8000/api/applications?token=ijSLomBiyFv7Ic0Q811by7HK0QChc3ef

            http://127.0.0.1:8000/api/applications/my

            http://127.0.0.1:8000/api/applications/applicants

            PATCH w/token
            http://127.0.0.1:8000/api/applications/1/accept

            http://127.0.0.1:8000/api/applications/1/decline

            http://127.0.0.1:8000/api/applications/1/undo?token=ijSLomBiyFv7Ic0Q811by7HK0QChc3ef

            DELETE w/token
            http://127.0.0.1:8000/api/applications/1
