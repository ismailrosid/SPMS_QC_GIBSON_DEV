<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

ERROR - 2024-03-19 12:59:08 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  relation &quot;tm_model_color&quot; does not exist
LINE 7:    LEFT JOIN tm_model_color mc ON m.s_code = mc.s_model
                     ^ C:\xampp\htdocs\spmsg\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2024-03-19 12:59:08 --> Query error: ERROR:  relation "tm_model_color" does not exist
LINE 7:    LEFT JOIN tm_model_color mc ON m.s_code = mc.s_model
                     ^
