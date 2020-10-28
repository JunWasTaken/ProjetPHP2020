<?php
    $date = date(mktime(0,0,0, 7, 1, 2000));
    echo $date;
    $idate = idate('Y', $date);
    echo $idate;
?>