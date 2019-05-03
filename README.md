Quickstart Repo for Coding Challenge: Let's roll
================================================

This is part of the [Codeception talk][1] where there is a small coding
challenge at the end. It's a variant of a dice game called [Greed][2]
or [Farkle][3].

The rules are as follows:
  - you have 6 dice and roll each of those once
  - you pick combinations out of those to get the highest possible
    score but make sure to count each die only once
  - the score is calculated like this:
    - a single one: 100 points
    - a single five: 50 points
    - three of a kind: 100 x rolled number
      - except for three ones: 1000 points
      - for each additional of that kind: double the score
    - three pairs: 800 points
    - straight: 1200 points

You will find a class for the dice and a class for the game with some
method stubs. Now you should install codeception, bootstrap it and
start writing some unit tests and implement the logic like described
above.

[1]: https://github.com/burned42/codeception-talk
[2]: http://codingdojo.org/kata/Greed/
[3]: https://en.wikipedia.org/wiki/Farkle
