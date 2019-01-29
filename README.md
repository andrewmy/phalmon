 # Phalmon
 
 ![Travis CI build status](https://travis-ci.com/andrewmy/phalmon.svg?branch=master)
 
 This is a sample JSON REST API project on Phalcon PHP framework with MongoDB.
 
 ## Running
 
 1. `docker-compose up` — containers with all the dev dependencies.
 	- Altenatively, if you'd like to run a leaner image with no dev dependencies:
 	  `docker-compose -f docker-compose.prod.yml up`
 2. Visit `http://127.0.0.1:1080`
 
 ## Preparing data from CLI
 
 Can be run in both dev and prod containers.
 - A single user:
   
   `docker-compose exec php php app/cli.php seed user <username> <password>`
 
 - Ten users, 20 messages each:
   
   `docker-compose exec php php app/cli.php seed everything`
 
 ## API
 
 - Available to anonymous users:
   - Available endpoint list: `OPTIONS /api`
   - Login:
   	 ```
     POST /api/login
     {"username": "someuser", "password": "somepassword"}
     ```
     Response: a JWT.
 - Available only to users with a `Bearer` authorization:
   - A list of the current user's messages with an access counter:
     `GET /api/messages`
   - Creating new message:
     ```
     POST /api/messages 
     {"content": "Hello"}
     ``` 
     As the action is async, the response somewhat breaks compatibility with JSON-API,
     because it has no ID or entity fields at all for that matter.
 
 ## Testing
 
Can be run in dev containers only.
`docker-compose exec php_app codecept run`

If you're launching tests locally, change the `DBNAME` environment variable in `php_app` and `php_worker` containers before launching docker-compose, or change the env var on the fly and restart `supervisord` on `php_worker`, or accept the cleared DB.
 