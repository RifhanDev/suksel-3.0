var prev_id = 0;

$('.btn-file-view').on('click', function (e) {
    var uniqueParam = new Date().getTime();
    var file = $(this).data('url') + '?' + uniqueParam;
    $('#container').removeClass('container').addClass('container-fluid');
    $('#left-pane').addClass('col-md-7');
    $('#right-pane').addClass('col-md-5');
    $('#right-pane').show();
    PDFObject.embed(file, "#doc-view");
});

$('.btn-file-close').on('click', function (e) {
    $('#container').addClass('container').removeClass('container-fluid');
    $('#left-pane').removeClass('col-md-7');
    $('#right-pane').removeClass('col-md-5');
    $('#right-pane').hide();
});

$('.btn-circular-view').on('click', function (e) {
    if (prev_id!=0) {
        $('#circular-'+prev_id).removeClass('active');
    }
    var id = $(this).data('id');
    var uniqueParam = new Date().getTime();
    var file = $(this).data('url') + '?' + uniqueParam;
    $('#circular-'+id).addClass('active');
    prev_id=id;
    PDFObject.embed(file, "#doc-view");
});