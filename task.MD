# TASK
Develop a COVID vaccine registration system based on the following requirements using Laravel and your preferred choice of front-end (Blade, Vue, React or whatever you are comfortable with). No need to have a fancy design. The functionality, coding style and completion within time is way more important for this task. 

## Task specifications
- Create a registration page where users can register for vaccination (consider that only one doze of vaccine will be given).
- Users must select a Vaccine center at the time of registration.
- Every vaccine center has a limit of how many users they can serve in a single day (the limit can be varied from center to center).
- Distribute the registered people to vaccine centers and schedule their vaccination date based on ‘first come first serve’ strategy.
- Send a notification email to the users at 9 PM before the night of their scheduled vaccination date.
- Create a search page (authentication is not needed) where users can enter their NID and see the status. The statuses can be `Not registered`, `Not scheduled`, `Scheduled`, `Vaccinated`. Consider the following when showing the statuses after the search:
    - If the user is not registered yet, Show `Not registered` status along with a link to the ‘registration’ page.
    - If the users vaccination date is scheduled, show `Scheduled` status along with the scheduled date.
    - If the user is registered but not scheduled for vaccine yet, show `Not scheduled` status.
    - Assume that every user will take their vaccine on their scheduled date. Show the `Vaccinated` status if the scheduled date is passed.

## Note
- Users can’t register twice.
- Pre-populate the Database with a few (10 or 20) vaccine center. No need to create a CRUD for the vaccine centers.
- Schedule vaccination only for the weekdays (Sunday to Thursday).
- User registration and search should be fast. Try to optimise the performance as much as you can. (Or at least leave a note in the README, how you would optimise these if you had more time).
- Leave a note in the README, if an additional requirement of sending ‘SMS’ notification along with the email notification for vaccine schedule date is given in the future, what changes need to be make on your code.