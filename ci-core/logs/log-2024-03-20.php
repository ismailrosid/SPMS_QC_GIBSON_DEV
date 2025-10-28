<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

ERROR - 2024-03-20 11:00:40 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  column &quot;s_status&quot; does not exist
LINE 3: WHERE s_status='Active' AND (s_division='AG' OR s_division I...
              ^ C:\xampp\htdocs\spmsg\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2024-03-20 11:00:40 --> Query error: ERROR:  column "s_status" does not exist
LINE 3: WHERE s_status='Active' AND (s_division='AG' OR s_division I...
              ^
ERROR - 2024-03-20 11:09:33 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  column &quot;ttp.d_production_date&quot; must appear in the GROUP BY clause or be used in an aggregate function
LINE 3:     EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRA...
                              ^ C:\xampp\htdocs\spmsg\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2024-03-20 11:09:33 --> Query error: ERROR:  column "ttp.d_production_date" must appear in the GROUP BY clause or be used in an aggregate function
LINE 3:     EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRA...
                              ^
ERROR - 2024-03-20 11:15:16 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  column &quot;ttp.d_production_date&quot; must appear in the GROUP BY clause or be used in an aggregate function
LINE 3:     EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRA...
                              ^ C:\xampp\htdocs\spmsg\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2024-03-20 11:15:16 --> Query error: ERROR:  column "ttp.d_production_date" must appear in the GROUP BY clause or be used in an aggregate function
LINE 3:     EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRA...
                              ^
ERROR - 2024-03-20 11:15:47 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  column &quot;ttp.d_production_date&quot; must appear in the GROUP BY clause or be used in an aggregate function
LINE 2:   SELECT  ttp.s_po_no, ttp.s_po,  d_production_date, 
                                          ^ C:\xampp\htdocs\spmsg\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2024-03-20 11:15:47 --> Query error: ERROR:  column "ttp.d_production_date" must appear in the GROUP BY clause or be used in an aggregate function
LINE 2:   SELECT  ttp.s_po_no, ttp.s_po,  d_production_date, 
                                          ^
ERROR - 2024-03-20 11:18:22 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  column &quot;ttp.d_production_date&quot; must appear in the GROUP BY clause or be used in an aggregate function
LINE 3:     d_production_date, 
            ^ C:\xampp\htdocs\spmsg\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2024-03-20 11:18:22 --> Query error: ERROR:  column "ttp.d_production_date" must appear in the GROUP BY clause or be used in an aggregate function
LINE 3:     d_production_date, 
            ^
ERROR - 2024-03-20 11:19:04 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  column &quot;ttp.d_production_date&quot; must appear in the GROUP BY clause or be used in an aggregate function
LINE 24:    ORDER BY d_production_date ASC   
                     ^ C:\xampp\htdocs\spmsg\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2024-03-20 11:19:04 --> Query error: ERROR:  column "ttp.d_production_date" must appear in the GROUP BY clause or be used in an aggregate function
LINE 24:    ORDER BY d_production_date ASC   
                     ^
ERROR - 2024-03-20 11:21:54 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  column &quot;ttp.d_plan_date&quot; must appear in the GROUP BY clause or be used in an aggregate function
LINE 4:     ttp.d_plan_date, ttp.d_delivery_date,
            ^ C:\xampp\htdocs\spmsg\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2024-03-20 11:21:54 --> Query error: ERROR:  column "ttp.d_plan_date" must appear in the GROUP BY clause or be used in an aggregate function
LINE 4:     ttp.d_plan_date, ttp.d_delivery_date,
            ^
ERROR - 2024-03-20 11:22:12 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  column &quot;ttp.d_plan_date&quot; must appear in the GROUP BY clause or be used in an aggregate function
LINE 4:     ttp.d_plan_date, ttp.d_delivery_date,
            ^ C:\xampp\htdocs\spmsg\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2024-03-20 11:22:12 --> Query error: ERROR:  column "ttp.d_plan_date" must appear in the GROUP BY clause or be used in an aggregate function
LINE 4:     ttp.d_plan_date, ttp.d_delivery_date,
            ^
ERROR - 2024-03-20 11:31:10 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  column &quot;ttp.s_po&quot; must appear in the GROUP BY clause or be used in an aggregate function
LINE 2:   SELECT  ttp.s_po_no, ttp.s_po, 
                               ^ C:\xampp\htdocs\spmsg\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2024-03-20 11:31:10 --> Query error: ERROR:  column "ttp.s_po" must appear in the GROUP BY clause or be used in an aggregate function
LINE 2:   SELECT  ttp.s_po_no, ttp.s_po, 
                               ^
ERROR - 2024-03-20 11:39:50 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  column &quot;ttp.s_po_no&quot; must appear in the GROUP BY clause or be used in an aggregate function
LINE 2:    SELECT  ttp.s_po_no, ttp.s_po, 
                   ^ C:\xampp\htdocs\spmsg\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2024-03-20 11:39:50 --> Query error: ERROR:  column "ttp.s_po_no" must appear in the GROUP BY clause or be used in an aggregate function
LINE 2:    SELECT  ttp.s_po_no, ttp.s_po, 
                   ^
ERROR - 2024-03-20 11:50:01 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  column &quot;s_status&quot; does not exist
LINE 3: WHERE s_status='Active' AND (s_division='AG' OR s_division I...
              ^ C:\xampp\htdocs\spmsg\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2024-03-20 11:50:01 --> Query error: ERROR:  column "s_status" does not exist
LINE 3: WHERE s_status='Active' AND (s_division='AG' OR s_division I...
              ^
ERROR - 2024-03-20 12:09:21 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  column &quot;s_status&quot; does not exist
LINE 3: WHERE s_status='Active' AND (s_division='AG' OR s_division I...
              ^ C:\xampp\htdocs\spmsg\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2024-03-20 12:09:21 --> Query error: ERROR:  column "s_status" does not exist
LINE 3: WHERE s_status='Active' AND (s_division='AG' OR s_division I...
              ^
ERROR - 2024-03-20 12:59:09 --> Severity: Warning  --> pg_query() [<a href='function.pg-query'>function.pg-query</a>]: Query failed: ERROR:  column &quot;s_status&quot; does not exist
LINE 3:    REPLACE(s_status, ' ', '--') AS s_status_2
                   ^ C:\xampp\htdocs\spmsg\ci-core\database\drivers\postgre\postgre_driver.php 153
ERROR - 2024-03-20 12:59:09 --> Query error: ERROR:  column "s_status" does not exist
LINE 3:    REPLACE(s_status, ' ', '--') AS s_status_2
                   ^
