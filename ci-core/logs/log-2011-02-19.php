<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

ERROR - 2011-02-19 11:33:54 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  column &quot;s_devision&quot; does not exist
LINE 1: ...* FROM tt_production WHERE s_serial_no =  $1  AND s_devision...
                                                             ^
QUERY:  SELECT * FROM tt_production WHERE s_serial_no =  $1  AND s_devision = Vs_devision
CONTEXT:  PL/pgSQL function &quot;tt_prod_order_aiud&quot; line 201 at SQL statement D:\xampp\htdocs\spmsg2\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2011-02-19 11:33:54 --> Query error: ERROR:  column "s_devision" does not exist
LINE 1: ...* FROM tt_production WHERE s_serial_no =  $1  AND s_devision...
                                                             ^
QUERY:  SELECT * FROM tt_production WHERE s_serial_no =  $1  AND s_devision = Vs_devision
CONTEXT:  PL/pgSQL function "tt_prod_order_aiud" line 201 at SQL statement
ERROR - 2011-02-19 11:35:00 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  column &quot;vs_division&quot; does not exist
LINE 1: ...duction WHERE s_serial_no =  $1  AND s_division = Vs_divisio...
                                                             ^
QUERY:  SELECT * FROM tt_production WHERE s_serial_no =  $1  AND s_division = Vs_division
CONTEXT:  PL/pgSQL function &quot;tt_prod_order_aiud&quot; line 201 at SQL statement D:\xampp\htdocs\spmsg2\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2011-02-19 11:35:00 --> Query error: ERROR:  column "vs_division" does not exist
LINE 1: ...duction WHERE s_serial_no =  $1  AND s_division = Vs_divisio...
                                                             ^
QUERY:  SELECT * FROM tt_production WHERE s_serial_no =  $1  AND s_division = Vs_division
CONTEXT:  PL/pgSQL function "tt_prod_order_aiud" line 201 at SQL statement
