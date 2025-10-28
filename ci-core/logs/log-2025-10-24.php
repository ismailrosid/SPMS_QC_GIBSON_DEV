<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

ERROR - 2025-10-24 00:02:28 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  column &quot;file_name&quot; of relation &quot;tt_checker_gibson&quot; does not exist
LINE 1: ...ser_scan&quot;, &quot;location&quot;, &quot;judgment&quot;, &quot;uploaded_by&quot;, &quot;file_name...
                                                             ^ C:\xampp\htdocs\spmsg\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2025-10-24 00:02:28 --> Query error: ERROR:  column "file_name" of relation "tt_checker_gibson" does not exist
LINE 1: ...ser_scan", "location", "judgment", "uploaded_by", "file_name...
                                                             ^
ERROR - 2025-10-24 08:11:32 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  duplicate key value violates unique constraint &quot;tt_checker_gibson_serial_no_key&quot;
DETAIL:  Key (serial_no)=(27012300001) already exists. C:\xampp\htdocs\spmsg\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2025-10-24 08:11:32 --> Query error: ERROR:  duplicate key value violates unique constraint "tt_checker_gibson_serial_no_key"
DETAIL:  Key (serial_no)=(27012300001) already exists.
