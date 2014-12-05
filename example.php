<?php

require('./foosballRating.class.php');

$teams = array(
  array(array('Ivo', 2070), array('Andres', 1940)),
  array(array('Mirko', 1495), array('Lörinc', 1315)),
);

print_r(foosballRating::calculatePostRatings($teams));
