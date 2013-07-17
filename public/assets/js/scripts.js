;(function($) {

    $(function() {

        var stock_template = $('#product_stock_template').html();

        $('#add_product').on('click', function(e) {
            e.preventDefault();

            $('.product_stock').last().after(stock_template);
        });

        //------------------------------------------------------------------------

        $(document).on('click', '.dummy_buttons', function() {
            var num = getNum($(this).attr('id'));

            $('#image_' + num).click();
        });

        //------------------------------------------------------------------------

        $(document).on('change', '.image_upload', function() {
            var num = getNum($(this).attr('id'));

            $('#dummy_' + num).val($(this).val());
        });

        //------------------------------------------------------------------------

        $('.image_preview').popover({
            trigger: 'hover',
            html: true,
            placement: 'top'
        });

        //------------------------------------------------------------------------

        $(document).on('click', '.dummy_delete_buttons', function() {
            var btn = $(this);
            var num = getNum(btn.attr('id'));
            var id = btn.data('image');
            var conf = confirm('Weet je zeker dat je dit product wilt verwijderen?');

            if( ! conf) return;

            $.ajax({
                type: 'DELETE',
                url: '/admin/images/' + id
            }).done(function(result) {

                if( ! result.status) {
                    $('.image_fieldset legend').after('<div class="alert alert-error"><a class="close" data-dismiss="alert" href="#">&times;</a>' + result.message + '</div>');
                }

                if(result.status) {
                    $('.image_fieldset legend').after('<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#">&times;</a>' + result.message + '</div>');

                    btn.removeClass('btn-danger dummy_delete_buttons')
                       .addClass('dummy_buttons')
                       .removeAttr('data-image')
                       .removeAttr('id')
                       .attr('id', 'dummy_button_' + num)
                       .html('Bladeren...');

                    btn.prev()
                       .removeClass('image_preview')
                       .val('')
                       .removeAttr('data-content data-original-title title');
                }
            });
        });

        //------------------------------------------------------------------------

        $('#new_term').on('click', function(e) {
            var btn = $(this);
            var url = btn.href;
            var csrf = btn.data('csrf');

            e.preventDefault();

            $('#new_term_modal').find('#new_term_action').text('Toevoegen').removeClass('edit').addClass('add');
            $('#new_term_modal').modal('show');
        });

        //------------------------------------------------------------------------

        $(document).on('click', '#new_term_action.add', function(e) {
            var modalbtn = $('#new_term');
            var url = modalbtn.attr('href');
            var csrf = modalbtn.data('csrf');

            var modal = $('#new_term_modal');
            var name = modal.find('input[name=name]').val();
            var desc = modal.find('textarea[name=description]').val();

            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: url,
                data: { name: name, description: desc, _token: csrf }
            }).done(function(result) {

                if( ! result.status) {
                    $('.modal-body label').first().before('<div class="alert alert-error"><a class="close" data-dismiss="alert" href="#">&times;</a>' + result.message + '</div>');
                }

                if(result.status) {
                    $('#new_term_modal').find('input, textarea').val('');

                    window.location.href = url;
                }

            });
        });

        //------------------------------------------------------------------------

        $('.edit_term').on('click', function(e) {
            var id = $(this).data('id');
            var taxonomy = $(this).data('taxonomy');
            var url = $(this).attr('href');
            var csrf = $(this).data('csrf');
            var id = $(this).data('id');

            e.preventDefault();

            $.getJSON(taxonomy + '/' + id, function(term) {
                $('#new_term_modal').find('input').val(term.name);
                $('#new_term_modal').find('textarea').val(term.description);

                $('#new_term_modal').find('#new_term_action')
                                    .text('Wijzigen')
                                    .removeClass('add')
                                    .addClass('edit')
                                    .data('url', url)
                                    .data('csrf', csrf)
                                    .data('id', id);

                $('#new_term_modal').modal('show');
            });
        });

        //------------------------------------------------------------------------

        $(document).on('click', '#new_term_action.edit', function(e) {
            var modalbtn = $(this);
            var url = modalbtn.data('url');
            var csrf = modalbtn.data('csrf');
            var id = modalbtn.data('id');

            var modal = $('#new_term_modal');
            var name = modal.find('input[name=name]').val();
            var desc = modal.find('textarea[name=description]').val();

            e.preventDefault();

            $.ajax({
                type: 'PUT',
                url: url,
                data: { name: name, description: desc, _token: csrf }
            }).done(function(result) {

                if( ! result.status) {
                    $('.modal-body label').first().before('<div class="alert alert-error"><a class="close" data-dismiss="alert" href="#">&times;</a>' + result.message + '</div>');
                }

                if(result.status) {
                    $('#new_term_modal').find('input, textarea').val('');

                    url = url.split('/');
                    url.pop();

                    window.location.href = url.join('/');
                }

            });
        });

        //------------------------------------------------------------------------

        function getNum(id) {
            return id.charAt(id.length - 1);
        }

    });

})(jQuery);