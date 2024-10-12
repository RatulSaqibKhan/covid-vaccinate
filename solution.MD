# Solution Approach

To solve this task, I will use Laravel + Octane for backend API, Next js for Frontend, MySQL for database, redis for cache and RabbitMQ for queue.

## Database structure
1. users

To store user details for registration.
```

id               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
nid              VARCHAR(20) UNIQUE       -- National ID, ensures users can't register twice
name             VARCHAR(255)             -- Full name of the user
email            VARCHAR(255) UNIQUE      -- Email for notification
phone            VARCHAR(13)              -- Optional, if needed
vaccine_center_id BIGINT UNSIGNED         -- Foreign key to vaccine_centers table
registration_date TIMESTAMP               -- The date and time when user registered
status           ENUM('Not scheduled', 'Scheduled', 'Vaccinated') 
scheduled_date   DATE                     -- Date the user is scheduled for vaccination
created_at       TIMESTAMP                -- Auto-generated timestamps
updated_at       TIMESTAMP
```
2. vaccine_centers

To store vaccine centers with their limits.
```
id               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
name             VARCHAR(255)             -- Name of the vaccine center
location         VARCHAR(255)             -- Location or address of the center
daily_capacity   INT                      -- Number of users this center can vaccinate per day
created_at       TIMESTAMP                -- Auto-generated timestamps
updated_at       TIMESTAMP
```
3. vaccine_schedules

To store the schedule details for the vaccination process.
```
id               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
vaccine_center_id BIGINT UNSIGNED         -- Foreign key to vaccine_centers table
scheduled_date   DATE                     -- Date when users are scheduled
slots_filled     INT                      -- Number of users already scheduled for this day
created_at       TIMESTAMP                -- Auto-generated timestamps
updated_at       TIMESTAMP
```
## Backend API
1. User Search API
2. User Registration API

## Backend Process
1. Queue Job for consuming user data and calculate the scheduled vaccination date
2. Cronjob for sending email notification to the user

## Frontend Pages
1. User Search Page
2. User Registration Page

### Approach
1. **User Search API**: 
    - Search By NID
    - path: <b>/api/v1/search/:nid</b> verb: <b>GET</b>
    - Search the user in Redis with key `covid_vaccinate:user:{nid}`
    - If found, 
      - Check id scheduled date passed,
      - If yes, then update the status of user as `Vaccinated` in users table
      - return the user details and set redis value
    - If not found, search in DB
    - If found, 
      - Check id scheduled date passed,
      - If yes, then update the status of user as `Vaccinated` in users table
      - return the user details and set redis value
    - If not found, return a 404 error

2. **User Registration API**:
    - path: <b>/api/v1/register</b> verb: <b>POST</b>
    - API payload: 
       ```
       {
          "nid": "string",
          "name": "string",
          "email": "srting",
          "phone": "srting",
          "vaccine_center_id": number
       }
      ```
    - Create a new user in DB:
        ```
        {
          "nid": "string",
          "name": "string",
          "email": "srting",
          "phone": "srting",
          "vaccine_center_id": number,
          "status": "Not Scheduled",
          "created_at": timestamp,
          "updated_at": timestamp
        }
        ```
    - Fire an event to enqueue the data in RabbitMQ.
3. **Queue Job**
    - Consume the user data in RabbitMQ
    - Calculate the probabale schedule date for the vaccination
    - Update the data in `users` table with status `Scheduled` and `vaccine_schedules` table
    - Set the redis value for the user with key `covid_vaccinate:user:{nid}`
4. **Cron Job**
    - Run every day at 21:00:00
    - Get all users with scheduled_date on the next day
    - Send an email notification to the user with the vaccine center details using queue job
