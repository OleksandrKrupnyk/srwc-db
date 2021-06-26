<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 18.11.17
 * Time: 21:22
 */
// обновление балов в таблице work
use zukr\base\Base;
use zukr\log\Log;
use zukr\work\Work;

$db = Base::$app->db;
$query = "
UPDATE  works SET
        works.balls = 
          (SELECT sumball FROM 
          (SELECT 
                  sum(
                      actual +  original +  methods +  theoretical +  practical +  literature +  selfcontained +  design +  publication +  government +  tendentious 
              ) AS sumball, id_w 
          FROM  reviews GROUP BY id_w
              ) AS tmp 
WHERE works.id = tmp.id_w );";
$db->rawQuery($query);
$log = Log::getInstance();
$log->logAction($_GET['action'], Work::getTableName(), 'ALL');
