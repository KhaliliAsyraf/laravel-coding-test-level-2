# Getting Started

Application Setup (DO THIS FIRST)

1. Run composer install. Added Spatie package for ACL purposes.
2. Run php artisan migrate:fresh --seed. Must run seeder in order to process.
3. Add .env.testing for unit testing database purposes.
4. Run php artisan serve

### Project Overview
This project is about a backend of project management application.

#### Flow:

1. I already added ADMIN and PRODUCT OWNER credentials inside seeder for API request. Please refer as per below. Or you can also just import postman collection that I've also added both credentials.
   1. ADMIN
      1. email: admin@test.my
      2. password: password123
   2. PRODUCT OWNER
      1. email: owner@test.my
      2. password: password123
2. Get API token for each respective ADMIN and PRODUCT OWNER by calling (Login (Admin) and Login (Product Owner) request).
3. Please add both variables below on postman environment. Or you can also just import postman collection that I've also added both variables.
   1. {{token}} = API token 
   2. {{url}} = Project URL

Kindly ask/address to me for any enquiries and problem.
