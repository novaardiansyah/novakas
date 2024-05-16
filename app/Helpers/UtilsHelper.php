<?php

namespace App\Helpers\UtilsHelper;

function formatNumberToShortString($number, $withThousands = true, $prefix = 'Rp. ', $thousandsDelimiter = 'k', $millionsDelimiter = 'jt', $desimal = 0, $desimal_saparator = ',', $thousand_saparator = '.')
{
  if ($withThousands == false && $number < 100000) {
    return $prefix . number_format($number, $desimal, $desimal_saparator, $thousand_saparator);
  }

  if ($number >= 1000000) {
    if ($number % 1000000 > 0) $desimal = 1;
    return $prefix . number_format($number / 1000000, $desimal, $desimal_saparator, $thousand_saparator) . $millionsDelimiter;
  } elseif ($number >= 1000) {
    if ($number % 1000 > 0) $desimal = 1;
    return $prefix . number_format($number / 1000, $desimal, $desimal_saparator, $thousand_saparator) . $thousandsDelimiter;
  } else {
    return $prefix . $number;
  }
}
