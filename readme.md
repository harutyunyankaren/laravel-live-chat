### Running

- Create a free account in [Pusher](https://pusher.com/signup)
- Create an app in Pusher and get the keys
- In your `.env` setup the Pusher keys:
```
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=YOUR_APP_ID
PUSHER_APP_KEY=YOUR_APP_KEY
PUSHER_APP_SECRET=YOUR_APP_SECRET
PUSHER_CLUSTER=YOUR_PUSHER_CLUSTER
```
- In `resources/assets/js/bootstrap.js` line 47, replace the `PUSHER_APP_KEY` and `PUSHER_CLUSTER` to your to your respective Pusher values.


- Now, follow this steps:
```bash

# Install Laravel dependencies
composer install

# Install Node dependencies
npm install

# Configure your database and run the migrations
php artisan migrate

# Compile the JavaScript files using Laravel Mix
npm run dev

# Start the chat app
php artisan serve
```
