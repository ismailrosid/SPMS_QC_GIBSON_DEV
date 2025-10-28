<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

ERROR - 2012-02-11 16:53:10 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  operator does not exist: character varying == unknown
LINE 1: ...'d_warehouse'  AND (s_phase != 'b22070' OR s_type == 'bolt')...
                                                             ^
HINT:  No operator matches the given name and argument type(s). You might need to add explicit type casts. D:\xampp171\htdocs\spmsg2\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2012-02-11 16:53:10 --> Query error: ERROR:  operator does not exist: character varying == unknown
LINE 1: ...'d_warehouse'  AND (s_phase != 'b22070' OR s_type == 'bolt')...
                                                             ^
HINT:  No operator matches the given name and argument type(s). You might need to add explicit type casts.
ERROR - 2012-02-11 17:34:25 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  syntax error at or near &quot;AS&quot;
LINE 1: ...n_order, n_line, s_field_process ORDER BY n_order AS, n_line...
                                                             ^ D:\xampp171\htdocs\spmsg2\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2012-02-11 17:34:25 --> Query error: ERROR:  syntax error at or near "AS"
LINE 1: ...n_order, n_line, s_field_process ORDER BY n_order AS, n_line...
                                                             ^
