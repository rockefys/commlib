<?php

system:
module_table,module_refer,module_column,module,
user,role,authority,user_role,role_authority,user_authority,
menu,backup,parameters

update module_table
set type='system'
where name in(
'module_table','module_refer','module_column','module',
'user','role','authority','user_role','role_authority','user_authority',
'menu','backup','parameters'
);