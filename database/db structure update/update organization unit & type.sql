use etender;

-- Script update struktur pangkalan data organization_types
alter table organization_types add column ori_id integer;
insert into organization_types (id, name ) values (5, 'Perbendaharaan Negeri Selangor');
insert into organization_types (id, name ) values (6, 'Jabatan Pengairan dan Saliran');
insert into organization_types (id, name ) values (7, 'Jabatan Kerja Raya Negeri Selangor');
update organization_types set ori_id = id;


set foreign_key_checks = 0;
update organization_types set id = 8  where ori_id = 4; -- jabatan lain jdi no.8 as temp
update organization_types set id = 4  where ori_id = 3; -- pbt jdi no.4
update organization_types set id = 3  where ori_id = 2; -- pejabat daerah jdi no.3
update organization_types set id = 2  where ori_id = 1; -- pejabat suk jdi no.2
update organization_types set id = 1  where ori_id = 5; -- pejabat Perbendaharaan jdi no.1
update organization_types set id = 5  where ori_id = 7; -- Jabatan Kerja Raya Negeri Selangor tukar jdi no.5
update organization_types set id = 7  where ori_id = 4; -- jabatan lain jdi no.7
set foreign_key_checks = 1;

commit;


-- Script update struktur pangkalan data organization_units
alter table organization_units add column ori_type_id integer;
update organization_units set ori_type_id = type_id;

set foreign_key_checks = 0;
update organization_units set type_id = 2 where ori_type_id = 1;
update organization_units set type_id = 3 where ori_type_id = 2;
update organization_units set type_id = 4 where ori_type_id = 3;
update organization_units set type_id = 1 where id=33 and short_name = 'Perbendaharaan Negeri';
update organization_units set type_id = 6 where parent_id = 24 or id = 24;
update organization_units set type_id = 2 where id = 30;
update organization_units set type_id = 2 where id in (35,36,49,62,71,73,77,80);
update organization_units set type_id = 7 where id in (31,32,34,79,72,64,63,61,65,81);
update organization_units set type_id = 5 where parent_id = 50 or id = 50;
set foreign_key_checks = 1;


/*
 * 	Script to reset to original organization id
 * 
 * 	SQL : update organization_types set id = ori_id;
 * 
 */

/*
 * 	Script to reset to original organization id
 * 
 * 	SQL : update organization_units set type_id = ori_type_id;
 * 
 */