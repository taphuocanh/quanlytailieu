var get_commnets = function(documentid) {
    $.ajax({
        url: 'comment.php', // gửi đến file upload.php 
        dataType: 'json',     
        data: {documentid: documentid},                 
        type: 'post',
        success: function(res){
            if (res.length == 0) {
                $('#comment-box').html('Không có bình luận nào.');
            } else {
                $('#comment-box').html('').html(draw_comments(res));
                //$(draw_comments(res)).appendTo('#comment-box');
            }
            
            $('#comment-content').val('');
        },
        error: function (jqXHR, exception) {
            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'Requested page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }
            console.log(msg);
        },
        statusCode: {
            404: function() {
              alert( "page not found" );
            }
        }
    });      
    
}

var draw_comments = function (comments) {
    //comments.sort(function(a, b){return a.createdate-b.createdate});
    content = `<ul class="media-list">`;
    comments.forEach(comment => {
        content += `<li class="media">
                        <div class="media-body">
                        <h5 class="media-heading">${comment.userid}</h5>
                        <p class="media-content">${comment.comment}</p>
                        <p style="margin-bottom: 0; margin-top: 5px;"><small>Vào ${moment.unix(comment.createdate).format('llll')}</small>
                        <div class"comment-link pull-right">
                            <button id="reply-commnet" comment-id="${comment.id}" onclick="reply_comment(this);">Trả lời</button>
                        </div>
                        </p>
                        </div>${(comment.child.length > 0) ? draw_comments(comment.child) : ''}
                    </li>`;
    });
    content +=`</ul>`;
    return content;
}

var reply_comment = function(element) {
    $('#comment-area').show();
    commentid = $(element).attr('comment-id');
    var elmnt = document.getElementById("comment-area");
    elmnt.scrollIntoView();
    return false;
};

$(document).ready(function() {
    $('#open-comment-area').on('click', function() {
        $('#comment-area').toggle();
        return false;
    });

    $('#post-comment').on('click', function() {
        if (typeof commentid === 'undefined') {
            commentid = null;
        }
        $.ajax({
            url: 'post_comment.php', // gửi đến file upload.php 
            dataType: 'json',     
            data: {documentid: data['id'], content: $('#comment-content').val(), commentid: commentid},                 
            type: 'post',
            success: function(comment){
                get_commnets(data['id']);
            },
            error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                console.log(msg);
            },
            statusCode: {
                404: function() {
                  alert( "page not found" );
                }
            }
        });   
        return false;
    });

    
});