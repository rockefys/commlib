-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_page`(
 in _page int,/*目标页号*/
 in _size int,/*每页的记录数*/
 in _pagecur int,/*当前页号,用来进行查询优化，直接从当前页偏移到目标页*/
 in _maxid int,/*当前页的最大ID，用来进行查询优化，直接从下一个ID筛选数据*/
 in _table varchar(100),/*表*/
 in _join varchar(1000),/*关联列表*/
 in _column varchar(1000),/*显示字段*/
 in _where varchar(200),/*条件*/
 in _order varchar(200)/*排序*/
)
    COMMENT '分页存储过程'
BEGIN
 if _size<=1 then 
  set _size=20;
 end if;
 if _page < 1 then 
  set _page = 1; 
 end if;
 if(_page > _pagecur)then -- 向后翻页
    set @_minid=concat('(
        select min(id) from(
              select id from ',_table,' where id <',_maxid,' order by id desc limit ',(_page - _pagecur) * _size,',',_size,') ____t1_
        )');
    set @_idlist = concat(' inner join (SELECT id FROM ',_table,' where id between ',@_minid,' and ',_maxid,') ____t2_ using(id) ');     
 else
    set @_idlist = concat(' inner join (select id from ',_table,' where id >',_maxid
                    ,' order by id ASC limit ',(_pagecur - _page ) * _size,',',_size,') ____t1_ using(id) ');
     prepare stmtsql from @strsql; 
     execute stmtsql; 
     deallocate prepare stmtsql; 
 end if;

 set _pagecur=_page;
 
 set @strsql = concat('select ',_column,' from ',_table,' ',@_idlist,_join,_where,_order,' limit ',_size); 
 #set @strsql = concat(@strsql,';select _page');
 prepare stmtsql from @strsql; 
 execute stmtsql; 
 deallocate prepare stmtsql;
 
/*
  set @strsql0=concat('select SQL_BUFFER_RESULT count(id) into @rowscount from ',_table);
  prepare stmtsql from @strsql0; 
  execute stmtsql; 
  deallocate prepare stmtsql; 
  set @inter=round(@rowscount/4);
  set @strsql4=concat('select SQL_BUFFER_RESULT max(id) into @rows4 from ',_table,' where id >=(',@rowscount,')');
  set @strsql1=concat('select SQL_BUFFER_RESULT id into @rows1 from ',_table,
  ' order by id asc limit ',@inter,',1');
  set @strsql2=concat('select SQL_BUFFER_RESULT id into @rows2 from ',_table,
  ' where id >(',@strsql1,') order by id asc limit ',@inter,',1');
  set @strsql3=concat('select SQL_BUFFER_RESULT id into @rows3 from ',_table,
  ' where id between (',@strsql2,')  and (',@strsql4,') order by id asc limit ',@inter,',1');
  prepare stmtsql from @strsql3; 
  execute stmtsql; 
  deallocate prepare stmtsql; 
  select @rows1,@rows2,@rows3,@rows4,@rowscount;
  
    set @rownumb=_page * _size;
  
    if(@rownumb > @rows2) then
         if(@rownumb >@rows3)then 
            set @_imin=@rows3;
            set @_imax=@rows4;
        else
            set @_imin=@rows2;
            set @_imax=@rows3;
        end if;
  else
       if(@rownumb <@rows1)then 
            set @_imin=0;
            set @_imax=@rows1;
        else
            set @_imin=@rows1;
            set @_imax=@rows2;
        end if;
  end if;
  */

END