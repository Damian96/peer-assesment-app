<?php

/**
 * The configuration file for the mark calculation formula parameters
 * @see: http://webpaproject.com/webpa_wiki/index.php/The_Scoring_Algorithm
 */

return [
    'fudge' => env('FUDGE_FACTOR', 1.25),
    'group' => env('GROUP_WEIGHT', .5),
];
