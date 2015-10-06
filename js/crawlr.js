
$(document).on('ready',function() {

    $('#search-submit').on('click', function(e) {
        e.preventDefault();

        var that = $(this);
        var url = that.parent().attr('action');
        var search = $('#url').val();
        var table = $('#result tbody');

        $('#result').show();
        table.empty();
        var spinner = '<tr><td class="text-center" colspan="3"><i class="fa fa-refresh fa-spin"></i></td></tr>';

        table.append(spinner);

        $.post(url,{ url: search })
            .done(function(data) {
                table.empty();
                $.each(data, function(index, value) {
                    var content = '<tr><td></<d><td>' + value + '</td><td></td></tr>';
                    table.append(content);
                });
            })
            .fail(function(data) {
                alert('An error occurred: ' + JSON.stringify(data));
            });
    });
});