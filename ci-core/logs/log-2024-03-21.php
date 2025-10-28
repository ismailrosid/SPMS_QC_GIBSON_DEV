<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

ERROR - 2024-03-21 12:38:16 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  relation &quot;tm_model_color&quot; does not exist
LINE 7:    LEFT JOIN tm_model_color mc ON m.s_code = mc.s_model
                     ^ C:\xampp\htdocs\spmsg\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2024-03-21 12:38:16 --> Query error: ERROR:  relation "tm_model_color" does not exist
LINE 7:    LEFT JOIN tm_model_color mc ON m.s_code = mc.s_model
                     ^
ERROR - 2024-03-21 12:39:16 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  column &quot;ttp.d_plan_date&quot; must appear in the GROUP BY clause or be used in an aggregate function
LINE 4:     ttp.d_plan_date, ttp.d_delivery_date,
            ^ C:\xampp\htdocs\spmsg\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2024-03-21 12:39:16 --> Query error: ERROR:  column "ttp.d_plan_date" must appear in the GROUP BY clause or be used in an aggregate function
LINE 4:     ttp.d_plan_date, ttp.d_delivery_date,
            ^
