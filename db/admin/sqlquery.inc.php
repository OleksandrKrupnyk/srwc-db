<?php
define('SUPERSQL3', "select univerfull,t4.allworks,t6.students,t1.first,t2.second,t3.third,t7.count_invitation
FROM autors
LEFT JOIN
  (select works.id_u, count(works.title) as allworks  from works
    group by works.id_u) as t4  ON autors.id_u = t4.id_u
LEFT JOIN (select id_u, count(place) as first from autors where place = 'I' group by id_u) as t1 ON autors.id_u = t1.id_u
LEFT JOIN (select id_u, count(place) as second from autors where place = 'II' group by id_u) as t2 ON autors.id_u = t2.id_u
LEFT JOIN (select id_u, count(place) as third from autors where place = 'III' group by id_u) as t3 ON autors.id_u = t3.id_u
LEFT JOIN (select autors.id_u, count(autors.place) as students  from autors
    left join wa on wa.id_a = autors.id
    left JOIN works on wa.id_w = works.id
  group by id_u)as t6
    ON autors.id_u = t6.id_u
LEFT JOIN
  (select autors.id_u, count(autors.place) as count_invitation
   from autors
     left join wa on wa.id_a = autors.id
     left JOIN works on wa.id_w = works.id
   where works.invitation = '1' group by id_u)as t7
    ON autors.id_u = t7.id_u
    
LEFT JOIN univers ON univers.id=autors.id_u
GROUP BY autors.id_u ORDER BY univer");