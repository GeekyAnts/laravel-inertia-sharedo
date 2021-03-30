### **Laravel Inertia Sharedo**

              Share Popup for Laravel Apps

![https://github.com/GeekyAnts/laravel-inertia-sharedo/raw/master/public/images/sharedo.gif](https://github.com/GeekyAnts/laravel-inertia-share-dialog/blob/fix/readme-gif/public/images/sharedo.gif)

## **Introduction**

**Sharedo** is a composer package for Laravel projects which allows other users to read or write your project's entities.

It helps you manage roles and permissions in an app by using Eloquent Models. You can assign read or write permissions to a user and remove permissions as required.

## **Prerequisites**

If you have [Tailwind](https://tailwindcss.com/) and [Bouncer](https://github.com/JosephSilber/bouncer) pre-installed, you can move on to the [Installation](###installation) section.

### 1) **Tailwind**

Install Tailwind as shown below:

```jsx
npm install -D tailwindcss@latest postcss@latest autoprefixer@latest
```

Next, generate your tailwind.config.js file:

```jsx
npx tailwindcss init
```

Add the following code to your tailwind.config.js file:

```jsx
module.exports = {
    purge: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {},
    },
    variants: {
        extend: {},
    },
    plugins: [],
};
```

### 2) **Bouncer**

Install Bouncer using composer:

```jsx
composer require silber/bouncer v1.0.0-rc.10
```

Add Bouncer's trait to your user model:

```jsx
use Silber\Bouncer\Database\HasRolesAndAbilities;

class User extends Model
{
    use HasRolesAndAbilities;
}
```

Run this command to publish the Bouncer's migrations to your app's migrations directory:

```jsx
php artisan vendor:publish --tag="bouncer.migrations"
```

---

## **Installation**

1. Install the sharedo package using composer:

    ```jsx
    composer require  geekyants/sharedo
    ```

2. Run this command to move the config file to your project's config folder:

    ```jsx
    php artisan vendor:publish  --tag="config"
    ```

3. Scaffold the view components present in the sharedo package as shown below:

    ```jsx
    php artisan ui sharedo
    ```

    A Sharedo folder containing Vue.js components will be created in your resources directory. You can now easily customise your Sharedo's Vuejs components 🚀

4. Execute this command to migrate Bouncer migrations and the new_users_share_dialog table:

    ```jsx
    php artisan migrate
    ```

5. To compile and minify the JavaScript files generated by sharedo, add this to your webpack.mix.js file:

    ```jsx
    .js("resources/js/sharedo.js", "public/js")
    .vue()
    ```

    Note: If your css is not compiled in your app.css file, you can change it in the sharedo.blade.php file.

6. Install the dependencies:

    ```jsx
    composer update
    npm install
    ```

7. Finally, build your assets as shown below:

    ```jsx
    npm run dev
    ```

## **Usage**

You must define a relation user for the entity model that you want to share. The relation should return the instance of the user who created it.

To share your entity with other users visit:

```jsx
{APP_URL}/sharedo/{entity_name}/{entity_id}
```

For example, to open a share dialog for your project model with id 123, run the following:

```
{APP_URL}/sharedo/projects/123;
```

Note: The entity_name should have the same name as that of the database migration corresponding to the model that you want to share.

**Sharedo sends error messages back to your application in the error props.**

If you invite a user who is not present in your database, sharedo automatically creates it in your users table. Also, a new entry is inserted into the new_users_sharedo table referencing the user's id as a foreign key and sets `has_ever_logged_in property` to false.

This can be useful if you want to differentiate between users created by sharedo and users created by the normal sign-up flow.

To restrict other users from accessing your entities, you have to explicitly use the [Bouncer methods here](https://github.com/JosephSilber/bouncer#cheat-sheet).

## **Customisation**

You can customise the functionality of sharedo easily by making changes in the sharedo.php file present in your config folder.

1. If your model files are present in some folder other than `"App\Models\\"`, you can set the `modelPath` to the path of that folder:

    ```jsx
    "modelPath" => "App\Models\\"
    ```

2. If you want to add your own custom middleware to the sharedo, append it to the middleware array. For example, if you want to add the `"admin"` middleware, then your middleware array will look like this:

    ```jsx
    "middleware" => ['web', 'auth','admin']
    ```

3. If you want only certain entities to be shareable, you can add them to the `restrict-entities` array. For instance, if your want only the `files` entity to be shareable, your array will look like this:

    ```jsx
    'restrict-entities' => ['files'],
    ```

4. You can also send email notifications to users when they are given access to an entity. Sharedo fires an `UserAbilityChanged` event when the user's access gets changed and attaches the `SendUserAbilityChangedNotification` listener to it. If you want to send an email notification, then add the following in EventServiceProvider:

    ```jsx
    use Geekyants\Sharedo\Events\UserAbilityChanged;
    use Geekyants\Sharedo\Listeners\SendUserAbilityChangedNotification;
    protected $listen = [
      ...
           UserAbilityChanged::class => [
             SendUserAbilityChangedNotification::class,
         ]

     ];
    ```

    Additionally, you can modify the email template by publishing the sharedo mail resources. After running this command, the mail notification template will be located in the

    `resources/vendor/sharedo/mail` directory:

    ```jsx
    :php artisan vendor:publish  --tag="mail"
    ```

    You can attach your own listeners to the event. For example, if you want to attach a `SendSlackNotification` listener to the event, you can add the following code:

    ```jsx
    use Geekyants\Sharedo\Events\UserAbilityChanged;

    ...

    protected $listen = [
            UserAbilityChanged::class => [
                SendSlackNotification::class,
            ]
        ];
    ```

5. If you want to provide an option to search users in the sharedo then you can create a class that implements **UserContactsInterface.php** from the package and define the **getUserContacts** function.

    The return type of the **getUserContacts** function should be a string containing the JSON representation of an array of objects where each object denoting a user must have an **"email"** attribute.

    ```php
    "[
      { email: '', ... },
      { email: '', ... },
      { email: '', ... }
    ]"
    ```

    For example, you can create a class as **SendUserContacts** which implements **UserContactsInterface** and perform an operation to get users in the **getUserContacts** function:

    ```php
    <?php

    namespace App\Repository;

    use App\Models\User;
    use Geekyants\Sharedo\Interfaces\UserContactsInerface;

    class SendUserContacts implements UserContactsInerface
    {

        public function getUserContacts($query)
        {
            $users = Some operation to get users
            $users = json_encode($users); //convert users array to json string
            return $users;
        }
    }
    ```

    In sharedo.config file set the "**typehead"** key to the **SendUserContacts** class path:

    ```php
    "typehead" => "App\Repository\SendUserContacts"
    ```

## **License**

Licensed under the MIT License. Please see the [License File](https://github.com/GeekyAnts/laravel-inertia-sharedo/blob/master/LICENSE.md) for more information.
