var date = new Date();

$('input[name="year"]').datepicker({
    dateFormat: 'yyyy',
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
    
}).datepicker("setDate", date);

function inputSelector(value) {
    var inputs = ['year','month','week'];

    inputs.forEach(function (val, i) {
        let a = '#'+val;
        if (val == value) {
            $(a).show();
        } else {
            $(a).hide();
        }
    });
}