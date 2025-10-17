/**
 *= require bootstrap-transfer/js/bootstrap-transfer
 */

var xfer = $('#certificates').bootstrapTransfer();

$.getJSON('/certifications', function(results){
    xfer.populate(results)
});

$('#certificates').on('update', function(e, data){
    $('#certification-input').val(data.values)
});
