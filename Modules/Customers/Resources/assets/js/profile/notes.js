var customer_id = $('#customer-main-tabs').attr('data-customer-id');

var $noteTemplate = $('[notes]').clone();
$('[notes]').html('');

var Customer = {
    notes: [],
    getNotes: function(reset = false){
        var $loadMoreButton = $('#notes-customer [data-action="show-more"]');
        var buttonText = $loadMoreButton.text();

        $loadMoreButton.attr('disabled', true);
        $loadMoreButton.html('<span class="fa fa-spinner"></span>');

        var page = $loadMoreButton.attr('data-current-page');

        if(reset || page == undefined){
            page = 1;
            Customer.notes = [];
        }else{
            page++;
        }

        $.get($("meta[name='BASE_URL']").attr("content")  + '/customers/' + customer_id + '/notes?page=' + page, function (response) {

            $('#notes-customer [data-action="show-more"]').attr('data-current-page', response.current_page);

            if(response.current_page == response.last_page){
                $('#notes-customer [data-action="show-more"]').hide();
            }

            $('[notes]').html('');
            var notes = '';

            $(response.data).each(function (index, note){
                if(!note.parent_id){
                    Customer.notes.push(note);
                }
            });

            $(Customer.notes).each(function(){
                notes += Customer.getNoteHtml(this);
            });

            $('[notes]').append(notes);
        })
        .always(function(){
            $loadMoreButton.attr('disabled', false);
            $loadMoreButton.html(buttonText);
        });
    },

    getNoteHtml: function(note){
        var $note = $noteTemplate.clone();

        $note.find('[data-source="id"]').attr('data-id', note.id);
        $note.find('[data-source="employee_personal_image_url"]').attr('src', note.created_by_user.personal_image_url);
        $note.find('[data-source="employee_name"]').html(note.created_by_user.name);
        $note.find('[data-source="content"]').html(note.content);
        $note.find('[data-source="created_at"]').html(note.created_at);

        $note.find('[data-source="comments-card-of-note"]').attr('data-note-id', note.id);

        var $commentTemplate = $note.find('[comments]').clone();
        $note.find('[comments]').html('');

        if(note.comments.length){
            var comments = '';

            $(note.comments).each(function (index2, comment){
                var $comment = $commentTemplate.clone();

                $comment.find('[data-source="comment_id"]').attr('data-id', comment.id);
                $comment.find('[data-source="comment_employee_personal_image_url"]').attr('src', comment.created_by_user.personal_image_url);
                $comment.find('[data-source="comment_employee_name"]').html(comment.created_by_user.name);
                $comment.find('[data-source="comment_content"]').html(comment.content);
                $comment.find('[data-source="comment_created_at"]').html(comment.created_at);

                comments += $comment.html();

                // var $replyTemplate = $comment.find('[replies]').clone();
                // $comment.find('[replies]').html('');

                // if(comment.comments.length){
                //     var replies = '';

                //     $(comment.comments).each(function (index3, reply){
                //         var $reply = $replyTemplate.clone();

                //         $reply.find('[data-source="reply_id"]').attr('data-id', reply.id);
                //         $reply.find('[data-source="reply_employee_personal_image_url"]').attr('src', reply.created_by_user.personal_image_url);
                //         $reply.find('[data-source="reply_employee_name"]').html(reply.created_by_user.name);
                //         $reply.find('[data-source="reply_content"]').html(reply.content);
                //         $reply.find('[data-source="reply_created_at"]').html(reply.created_at);

                //         replies += $reply.html();
                //     });

                //     $comment.find('[replies]').append(replies);
                // }
            });

            $note.find('[comments]').append(comments);
        }

        return $note.html();
    },
}

$(function() {

    Customer.getNotes();

    $('#notes-customer .form-store-note').on('submit', function(event){
        event.preventDefault();

        var $this = $(this);
        var buttonText = $this.find('button:submit').text();

        $this.find("button:submit").attr('disabled', true);
        $this.find("button:submit").html('<span class="fas fa-spinner" data-fa-transform="shrink-3"></span>');

        var posted_data = {
            _token: $("meta[name='csrf-token']").attr("content"),
            content: $this.find("textarea[name='content']").val(),
        }

        $.post($("meta[name='BASE_URL']").attr("content") + '/customers/' + customer_id + '/notes', posted_data, function(response){
            http.success({ 'message': response.message });
            $('#notes-customer .form-store-note').find("textarea[name='content']").val('');

            Customer.getNotes(reset = true);
        })
        .fail(function (response) {
            http.fail(response.responseJSON, true);
        })
        .always(function () {
            $this.find("button:submit").attr('disabled', false);
            $this.find("button:submit").html(buttonText);
        });
    });

    $('#notes-customer').on('submit', '.form-store-comment', function(event){
        event.preventDefault();

        var $this = $(this);
        var buttonText = $this.find('button:submit').text();

        $this.find("button:submit").attr('disabled', true);
        $this.find("button:submit").html('<span class="fas fa-spinner" data-fa-transform="shrink-3"></span>');

        var posted_data = {
            _token: $("meta[name='csrf-token']").attr("content"),
            content: $this.find("textarea[name='content']").val(),
            parent_id: $this.attr('data-note-id')
        }

        $.post($("meta[name='BASE_URL']").attr("content") + '/customers/' + customer_id + '/notes', posted_data, function(response){
            http.success({ 'message': response.message });
            $('#notes-customer .form-store-note').find("textarea[name='content']").val('');

            Customer.getNotes(reset = true);
        })
        .fail(function (response) {
            http.fail(response.responseJSON, true);
        })
        .always(function () {
            $this.find("button:submit").attr('disabled', false);
            $this.find("button:submit").html(buttonText);
        });

    });

    $("#notes-customer").on('click', '[data-action="show-more"]', function(){
        Customer.getNotes(reset = true);
    });
});