SELECT
        e1.id           
    ,   e2.command      
FROM 
(
    SELECT 
        SUBSTRING_INDEX(
            MIN(
                    CONCAT(LPAD(`sub`.`prio`,5,'0'),'%',LPAD(`sub`.`start`,20,'0'),LPAD(CONCAT('%',`sub`.`id`),20,'0'))
                )
        ,'%',-1) as id, sub.coords as coords FROM v_events sub GROUP BY sub.coords
) as e1 
LEFT JOIN v_events e2 
       ON e1.id = e2.id 
      AND e1.coords = e2.coords