# Easier
*Express Alphanumeric Symbol Identification with Error Reduction*

Easier is a base-27 encoder/decoder designed to reduce human error when inputting encoded strings.

The goal of Easier is to simplify transmitting data between humans and computers by collapsing glyphs of similar appearance. For example, humans often have difficulty determining whether they’re looking at a lowercase ‘l’ or a capital ‘I’. Easier makes this ambiguity immaterial.

Easier enables confidently sharing strings even in media where print quality or font choice may render some glyphs difficult to decipher. Easier-encoded strings are particularly useful in shortened URLs destined to be shared in print, written by hand, or glimpsed in passing.

## Installation
If you use [Packagist](http://packagist.org), add `rfreebern/easier` to the "requirements" section of your `composer.json` and run `composer.phar update`.

## Usage
Include the library:
`use rfreebern\Easier as Easier;`

Encode a decimal number:
`$encoded = Easier::encode(8675309);`

Decode an Easier number:
`$number = Easier::decode($encoded);`

Easier can also be used in object context:

    $easier = new Easier(69105);
    $encoded = $easier->encode();
    $another = $easier->encode(1048576);

## Tests
Easier has a full PHPUnit test suite. Simply run `phpunit .` in the Easier root directory.

## Similar Projects
* [Douglas Crockford's Base 32](http://www.crockford.com/wrmg/base32.html)
* [Tantek Çelik's New Base 60](http://tantek.pbworks.com/w/page/19402946/NewBase60)
