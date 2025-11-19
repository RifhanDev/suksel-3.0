-- Indexing for improving generating speed temp_table 
create index vw_vdr_tran_idx01 on etender.vendors(id, name);
create index vw_vdr_tran_idx02 on etender.transactions(id, created_at, organization_unit_id, vendor_id, number, type, gateway_reference);



drop table IF EXISTS view_vendor_transaction_report;
create table view_vendor_transaction_report as 
(
    SELECT year(t.created_at) AS year, month(t.created_at) AS month, yearweek(t.created_at,7) AS week, 
    t.organization_unit_id AS organization_unit_id, t.id AS transaction_id, t.vendor_id AS vendor_id, 
    v.name AS name, t.number AS number, 
    (case when (t.type = 'purchase') then '73105' else '71399' end) AS hasil_code, 
    t.gateway_reference AS gateway_reference, t.created_at AS created_at 
    FROM (transactions t left join vendors v on((t.vendor_id = v.id)))
);

-- Indexing for improving retrieval speed for temp_table data
create index report_vendor_trans_idx01 on etender.view_vendor_transaction_report(year, organization_unit_id, created_at);
create index report_vendor_trans_idx02 on etender.view_vendor_transaction_report(year, month, organization_unit_id, created_at);
create index report_vendor_trans_idx03 on etender.view_vendor_transaction_report(year, week, organization_unit_id, created_at);


-- Create trigger to make sure data always updated to temp_table if new transaction is created
DELIMITER $$

CREATE TRIGGER upd_report_vendor_trans
AFTER INSERT
ON transactions FOR EACH ROW
BEGIN
    
    INSERT INTO view_vendor_transaction_report(year, month, week, organization_unit_id, transaction_id, vendor_id, name, number, hasil_code,gateway_reference,created_at)
    SELECT year(t.created_at) AS year, month(t.created_at) AS month, yearweek(t.created_at,7) AS week, 
    t.organization_unit_id AS organization_unit_id, t.id AS transaction_id, t.vendor_id AS vendor_id, 
    v.name AS name, t.number AS number, 
    (case when (t.type = 'purchase') then '73105' else '71399' end) AS hasil_code, 
    t.gateway_reference AS gateway_reference, t.created_at AS created_at 
    FROM (transactions t left join vendors v on((t.vendor_id = v.id)))
    where t.id = new.id;
    
END$$

DELIMITER ;