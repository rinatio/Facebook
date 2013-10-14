Facebook Test User API wrapper
========

This is a wrapper for Facebook [Test User API](https://developers.facebook.com/docs/test_users).
It allows you to create, read, update and delete Facebook test users in OOP style.

##Installation

You can install it through [composer](http://getcomposer.org/) with this command:

    $ php composer.phar require rinatio/Facebook:dev-master

After installing, you need to require Composer's autoloader:

    require 'vendor/autoload.php';

##Usage

Load namespaced classes like this:

    use rinatio\Facebook\Test\User as TestUser;
    use rinatio\Facebook\Facebook;

Define your Facebook [application](http://developers.facebook.com/docs/web/) credentials:

    Facebook::setAppId('fbAppId');
    Facebook::setAppSecret('fbAppSecret');

Now you're ready to go. Here are some examples

Create a new users:

    $user1 = TestUser::create(array(
        'name' => 'Butch Cassidy'
    ));

Fetch user's profile

    $user2 = TestUser::create(array(
        'name' => 'Etta Place'
    ));
    $user2->fetchProfile();
    echo $user2->first_name; // outputs 'Etta'

Get all test users created for application:

    $users = TestUser::all();

Change user's name or password:

    $user2->update(array(
        'name' => 'Sundance Kid'
    ))

Add friends connection:

    $user1->addFriend($user2);

Delete user:

    $user1->delete();

Or delete all users:

    TestUser::deleteAll()

