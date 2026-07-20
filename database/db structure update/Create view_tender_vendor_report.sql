-- Indexing for improving generating speed temp_table 
create index vw_tndr_vdr_idx01 on etender.transactions(id, method, type, status, amount);
create index vw_tndr_vdr_idx02 on etender.tender_vendors(id, transaction_id, tender_id);
create index vw_tndr_vdr_idx03 on etender.tenders(id);

create or replace view view_tender_vendor_report  
as
SELECT 
    t2.id AS transaction_id, t2.method AS method, t2.type AS type, t2.status AS status, tv.amount AS amount,
    t.name AS name, t.ref_number AS ref_number, t2.gateway_message as gateway_message
from
transactions t2 left join tender_vendors tv on tv.transaction_id = t2.id 
left join tenders t on  tv.tender_id = t.id;

