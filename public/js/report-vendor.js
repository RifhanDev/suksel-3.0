var date = new Date(String(year));

$('input[name="year_summary"]').datepicker({
    dateFormat: 'yyyy',
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
    
}).datepicker("setDate", date);