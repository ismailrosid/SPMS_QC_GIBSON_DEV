<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

ERROR - 2024-03-18 06:04:49 --> Severity: Warning  --> pg_pconnect() [<a href='function.pg-pconnect'>function.pg-pconnect</a>]: Unable to connect to PostgreSQL server: FATAL:  database &quot;try3&quot; does not exist C:\xampp\htdocs\spmsg\ci-core\database\drivers\postgre\postgre_driver.php 95
ERROR - 2024-03-18 06:04:49 --> Unable to connect to the database
ERROR - 2024-03-18 06:06:05 --> Severity: Notice  --> Undefined variable: sField C:\xampp\htdocs\spmsg\application\controllers\ag\reportlist.php 752
ERROR - 2024-03-18 06:06:05 --> Severity: Notice  --> Undefined variable: sField C:\xampp\htdocs\spmsg\application\controllers\ag\reportlist.php 752
ERROR - 2024-03-18 06:06:05 --> Severity: Warning  --> Cannot modify header information - headers already sent by (output started at C:\xampp\htdocs\spmsg\ci-core\libraries\Exceptions.php:164) C:\xampp\htdocs\spmsg\application\helpers\excel_helper.php 73
ERROR - 2024-03-18 06:06:05 --> Severity: Warning  --> Cannot modify header information - headers already sent by (output started at C:\xampp\htdocs\spmsg\ci-core\libraries\Exceptions.php:164) C:\xampp\htdocs\spmsg\application\helpers\excel_helper.php 74
ERROR - 2024-03-18 07:00:49 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  column &quot;s_status&quot; does not exist
LINE 3: WHERE s_status='Active' AND (s_division='AG' OR s_division I...
              ^ C:\xampp\htdocs\spmsg\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2024-03-18 07:00:49 --> Query error: ERROR:  column "s_status" does not exist
LINE 3: WHERE s_status='Active' AND (s_division='AG' OR s_division I...
              ^
ERROR - 2024-03-18 07:00:59 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  column &quot;s_status&quot; does not exist
LINE 3: WHERE s_status='Active' AND (s_division='AG' OR s_division I...
              ^ C:\xampp\htdocs\spmsg\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2024-03-18 07:00:59 --> Query error: ERROR:  column "s_status" does not exist
LINE 3: WHERE s_status='Active' AND (s_division='AG' OR s_division I...
              ^
ERROR - 2024-03-18 14:43:35 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  column &quot;s_status&quot; does not exist
LINE 3: WHERE s_status='Active' AND (s_division='AG' OR s_division I...
              ^ C:\xampp\htdocs\spmsg\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2024-03-18 14:43:35 --> Query error: ERROR:  column "s_status" does not exist
LINE 3: WHERE s_status='Active' AND (s_division='AG' OR s_division I...
              ^
ERROR - 2024-03-18 15:37:30 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  column &quot;s_status&quot; does not exist
LINE 3: WHERE s_status='Active' AND (s_division='AG' OR s_division I...
              ^ C:\xampp\htdocs\spmsg\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2024-03-18 15:37:30 --> Query error: ERROR:  column "s_status" does not exist
LINE 3: WHERE s_status='Active' AND (s_division='AG' OR s_division I...
              ^
ERROR - 2024-03-18 15:47:14 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  insert or update on table &quot;tt_prod_order&quot; violates foreign key constraint &quot;tt_prod_order_for_3&quot;
DETAIL:  Key (s_color)=(null) is not present in table &quot;tm_color&quot;. C:\xampp\htdocs\spmsg\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2024-03-18 15:47:14 --> Query error: ERROR:  insert or update on table "tt_prod_order" violates foreign key constraint "tt_prod_order_for_3"
DETAIL:  Key (s_color)=(null) is not present in table "tm_color".
