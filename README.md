# Project Name

## Getting Started

### Clone the Project
First, clone the repository to your local machine:
```sh
git clone "<repository_address>"
cd <project_name>
```

### Set up env
- Copy `.env.example` into `.env`.

### Run the Application with Docker
The application is containerized using Docker. To start the services, simply run:
```sh
docker-compose up --build
```
Using `entrypoing.sh` and docker, this command will:
- Start the necessary containers.
- Handle database migrations automatically.
- Start the queue worker to process background jobs.

## Project Overview

### API Routes
API routes are defined in the api.php

### JWT Middleware
hardcoded JWT middleware named: `ApiTokenAuth`, that is set over the whole api's in kernel.

### Job Processing
- Used Laravel `Job-Queue` and `Redis` to handle the "Asynchronous Processing"
- As soon as a record is being created, a job is passed to queue (`FetchPageData`)
- The handler will crawl the url and save the title and description tags of that page in database by updating the record.
- Starting the queue worker is handled inside docker/php/`entrypoint.sh`

### Data Storage
- The application uses a postgres for storing data.
- Queue jobs are stored in the Redis.

### Soft Deletes
- Instead of permanently deleting records, the application implements soft deletes.
- A `deleted_at` timestamp is used to mark records as deleted without removing them from the database.

## Additional Notes
- Ensure you have Docker installed before running the project.
- Copy `.env.example` into `.env`.
- Endpoints are defined in a postman collection in project root: `bookmarker.postman_collection.json`


## Possible Problems:
- if it said entrypoint.sh is not executable, make it executable: `sudo chmod +x entrypoint.sh`

- if it said storage permission denied cant log, do these: `sudo chmod -R 775 storage`, `sudo chown -R $USER:www-data storage`.
