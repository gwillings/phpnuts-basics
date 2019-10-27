# PhpNuts Basics
The code within this repository was originally intended to support
content added to the PhpNuts YouTube channel:

http://youtube.com/c/phpnuts

You can experiment with the code in this repository to learn about PHP.
Or, try using it for your own code development.

## Installation
I recommend installing PhpNuts Basics through 
[Composer](http://getcomposer.org).

```bash
composer require phpnuts/basics
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

## BasicObject
In my first PhpNuts video we demonstrated how to create a 
useful base object from which we can build useful utility
classes. This was called BasicObject.

There's a video demonstrating how I made BasicObject 
and why it's important here:
https://youtu.be/keB1aQTqBMs

```php

$person = new BasicObject([
    'firstName' => '',
    'lastName' => ''
]);

// These are essentially all the same:
// a) set() method call
$person->set('firstName', 'Bob');

// b) __set() magic method call
$person->firstName = 'Bob';

// c) __call() magic method call
$person->setFirstName('Bob');

```

In most cases, where BasicObject has been extended it is best
to use the __call() method because this makes life much easier
in the long-term when it comes to refactoring your code. 

## More soon
Now that this small library is starting to grow, I hope to 
add more documentation outside of YouTube. 
