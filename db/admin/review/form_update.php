<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 18.11.17
 * Time: 21:22
 */
// обновление балов в таблице work
$query = "UPDATE  works SET
          works.balls = 
          (SELECT sumball FROM 
          (SELECT 
                  sum(
                      actual +  original +  methods +  theoretical +  practical +  literature +  selfcontained +  design +  publication +  government +  tendentious 
              ) AS sumball, id_w 
          FROM  reviews GROUP BY id_w
              ) AS tmp 
          WHERE works.id = tmp.id_w )";
//$error_message = $query;
global $link;
mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
$result = mysqli_query($link, $query)
or die("Invalid query функція action.php?action=review_update: " . mysqli_error($link));
log_action($_GET['action'], "works", "ALL");
?>