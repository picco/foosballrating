<?php

class foosballRating {
  /*
   * Arbitrary constant (50) used in Bonzini calculation.
   */
  private static $k = 50;

  /**
   * Calculates the new ratings for all players
   * based on the given the existing game and ratings data.
   *
   * @teams: array, with the following structure (winning team first!):
   *
   * $teams = array(
   *   array(array('Ivo', 1000), array('Andres', 500)),
   *   array(array('Mirko', 100), array('Lörinc', 50)),
   * );
   *
   */
  public static function calculatePostRatings($teams) {
    $preTeamRatings = array(0, 0);
    $playerRatings = array();

    foreach ($teams AS $i => $team) {
      foreach ($team AS $j => $player) {
        $preTeamRatings[$i] += $player[1];
      }

      $preTeamRatings[$i] = floor($preTeamRatings[$i] / 2);
    }

    foreach ($teams AS $i => $team) {
      $adjustment = floor(max(0,
        $preTeamRatings[$i] +
        self::$k * (
          ($i ? 0 : 1) -
          (self::calculateExpectance($preTeamRatings, $i) / 100)
        )
      ) - $preTeamRatings[$i]);

      foreach ($team AS $j => $player) {
        $playerRatings[$player[0]] = max(0, $player[1] + $adjustment);
      }
    }

    return $playerRatings;
  }

  /**
   * Calculates the winning expectance coeficent.
   */
  private static function calculateExpectance($teamRatings, $teamIndex) {
    $expectances = self::getExpectances();
    $diff = abs($teamRatings[0] - $teamRatings[1]);

    if ($teamRatings[0] >= $teamRatings[1]) {
      $isHigherRated = ($teamIndex == 0);
    }
    else {
      $isHigherRated = ($teamIndex == 1);
    }

    foreach ($expectances AS $ex) {
      if ($diff <= $ex[0]) {
        return $isHigherRated ? $ex[1] : $ex[2];
      }
    }

    return $isHigherRated ? 100 : 0;
  }

  /**
   * Defines the expectance values for different rating differences.
   */
  private static function getExpectances() {
    return array(
      array(0, 50, 50),
      array(25, 51, 49),
      array(50, 53, 47),
      array(75, 54, 46),
      array(100, 56, 44),
      array(150, 59, 41),
      array(200, 61, 39),
      array(250, 64, 36),
      array(300, 67, 33),
      array(350, 53, 47),
      array(400, 72, 28),
      array(450, 74, 26),
      array(500, 76, 24),
      array(600, 80, 20),
      array(700, 83, 17),
      array(800, 86, 14),
      array(900, 89, 11),
      array(1000, 91, 9),
      array(1100, 93, 7),
      array(1200, 94, 6),
      array(1300, 95, 5),
      array(1400, 96, 4),
      array(1500, 97, 3),
      array(1600, 98, 2),
      array(1800, 99, 1),
      array(2300, 100, 0),
    );
  }
}
