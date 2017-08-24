# Nasa Neo API

The api is used to fetch and persist the Near Earth Objects's data for the last 3 days in the database. The following endpoints are available to use:

  - `neo/hazardous`
  - `neo/fastest?hazardous=(1|0)`
  - `neo/best-year?hazardous=(1|0)`
  - `neo/best-month?hazardous=(1|0)`

# Implementation

  ## Setup Laravel
  
As the first step after cloning the repo, copy the `.env.example` to `.env` and supply your actual database settings in the file. 

After database setup, run the following commands to generate encryption key and create the database schema.

```
php artisan key:generate 
```

```
php artisan migrate
```


  ## Console Command
```
php artisan nasa:get_neos
```
  - This command is used to fetch a list of all NEOs from Nasa API within a range of 3 days starting from today to 3 days ago.
  - The command then parses the API response and stores the NEOs in database table in the specified format.
  - The command will show an error in case it's unable to fetch due to network issues or wrong request.

  ## API Routes

The API routes are registered in the file at `routes/web.php`.

To test the API routes after running the above console command, run the following console command first. This command will start a simple PHP development server in the root folder.

```
php artisan serve
```

  ### Endpoints

```
GET /neo/hazardous
```
   - This endpoint returns a list of all hazardous NEOs from the database.

```
GET neo/fastest?hazardous=(1|0)
```
   - This endpoint filters the available NEOs based on the hazardous parameter and returns the fastest NEO.

```
GET neo/best-year?hazardous=(1|0)
```
   - This endpoing has not been implemented yet. Please read the docblock in the controller for more info.

```
GET neo/best-month?hazardous=(1|0)
```
   - This endpoing has not been implemented yet. Please read the docblock in the controller for more info.

  ## Database
- I chose MySQL as the database since I have been using it for a long time and it's vastly supported in the community.

 ## Framework
 - I chose Laravel(5.4) for creating this project since I have a good amount of experience with it and also because it's very simple for prototyping. It borrows a lot of components from Symfony like console, debugger, http, dumper etc. 

# Testing
  
Create a `testing` database schema and to run all the tests, run `vendor/bin/phpunit`.

# Security

I've not protected the endpoint for keeping things simple, but I'd highly recommend securing the API from unauthorized access either using OAUTH or JWT in production scenarios.

### Note: 
I was running short on time and so I decided to not dockerize the application. I have a very rudimentary knowledge about Dockers and how to work with them. But, I use a service called `Nanobox` which does all under the hood Docker heavy lifting for me when I'm working on my dev environment. It's proven to be very useful to me.

