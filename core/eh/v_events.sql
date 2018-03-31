

ALTER VIEW `v_events` AS 
select 
        `events`.`id` AS `id`
    ,   `events`.`command` AS `command` 
    ,   SUBSTRING_INDEX(`events`.`coords`,':',3) AS `coords`
    ,   50 as prio
    ,   `events`.`time` as `start`
from `events` where (`events`.`time` <= unix_timestamp()) 
union all 
select 
        `flotten`.`id` AS `id`
    ,   'fleet_there' AS `command` 
    ,   SUBSTRING_INDEX(`flotten`.`toc`,':',3) AS `coords`
    ,   case typ
            when 'ag_p' then 90
            when 'ag' then 90
            when 'aks_lead' then 90
            when 'dest' then 91
            when 'recy' then 95
            else 100
        end as prio
    ,   `flotten`.`tthere` as `start`
from `flotten` where ((`flotten`.`tthere` > 0) and (`flotten`.`tthere` <= unix_timestamp())) 
union all 
select 
        `flotten`.`id` AS `id`
    ,   'fleet_back' AS `command` 
    ,   SUBSTRING_INDEX(`flotten`.`toc`,':',3) AS `coords`
    ,   80 as prio
    ,   `flotten`.`tback` as `start`
from `flotten` where ((`flotten`.`tthere` = 0) and (`flotten`.`tback` <= unix_timestamp())) 
union all 
select 
        `produktion`.`id` AS `id`
    ,   'v3prod' AS `command` 
    ,   SUBSTRING_INDEX(`produktion`.`coords`,':',3) AS `coords`
    ,   10 as prio
    ,   (`produktion`.`ptime` + `produktion`.`bauzeit`) as `start`
from `produktion` where ((`produktion`.`ptime` + `produktion`.`bauzeit`) <= unix_timestamp())
union all
select
        `coords` as `id`
    ,   'resRecalc' as `command`
    ,   `coords`
    ,   100 as `prio`
    ,   `boost_until` as `start`
from `rohstoffe`
where (boost_until > 0 AND boost_until < UNIX_TIMESTAMP())
ORDER BY start,prio ASC
;
