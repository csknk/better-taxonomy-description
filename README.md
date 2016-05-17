# BetterTaxonomyDescription

> **WordPress plugin boilerplate.**

----


## License

BetterTaxonomyDescription is licensed under [MIT](http://opensource.org/licenses/MIT).

## Setup

1. Clone this repo
2. run `git remote rm origin`
2. Rename strings
3. Rename files

## Rename Commands
Change strings within file content using `sed`, a stream editor:

~~~
find . -type f -exec sed -i 's/BetterTaxonomyDescription/BetterTaxonomyDescription/g' '{}' \;
~~~

Change Filenames using `rename`:

~~~
find . -iname "*" -exec rename -v 's/BetterTaxonomyDescription/BetterTaxonomyDescription/g' '{}' \;
~~~
