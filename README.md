# PhpNuts Basics
PhpNuts YouTube channel code for our basic objects.

The code within this project supports content added 
to the PhpNuts YouTube channel which you can find at:

https://www.youtube.com/channel/UCOFewEGc3r2Nddb04wf_N3A

In my first PhpNuts video we demonstrated how to create a 
useful base object from which we can build useful utility
classes. This was called BasicObject.

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