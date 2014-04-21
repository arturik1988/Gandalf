Gandalf
=======

A simple PHP authentication system. This is a massive work in progress to make this extremely efficient and simple to use. Pull requests welcome.

## Using the auth class
```
require_once 'classes/auth.php';
$auth = new Auth();
```

## Configuration
Located in app/config.php. The MySQL driver is only currently supported.

```
return array(
  'database' => array(
    'driver' => 'mysql',

    'host' => 'localhost',
    'db' => 'YOUR_DB_NAME',
    'username' => 'YOUR_DB_USERNAME',
    'password' => 'YOUR_DB_PASSWORD'
  ),
  'debug' => true
);
```

You can also set debug on or off. Always leave this off for a production envionment.

## build()
Builds the users table schema if it doesn't exist. You only need to run this once.

```
$auth->build();
```

## check()
Checks if a user is signed in.

```
if($auth->check()) {
  // signed in
} else {
  // not signed in
}
```

## userExists()
Checks if a user exists by username.

```
if($auth->userExists('billy')) {
  // the user 'billy' exists
}
```

There is no need to run this when creating new accounts. Auth will internally check this.

## create()
Creates a user account.

```
$create = $auth->create(array(
  'email' => $email,
  'username' => $username,
  'password' => $password
));

if($create) {
  // created
}
```

## signin()
Sign a user in.

```
$signin = $auth->signin(array(
  'username' => $username,
  'password' => $password
));

if($signin) {
  // signed in
}
```

There is no need to hash the plain text password, it'll be done automatically.

## signout()
Signs a user out.

```
$auth->signout();
```
