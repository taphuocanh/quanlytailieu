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
                            ${(logged && comment.userid == userfullname)? `
                                <button id="edit-commnet" comment-id="${comment.id}" onclick="edit_comment(this);">Sữa</button>
                                <button id="delete-commnet" comment-id="${comment.id}" onclick="delete_comment(this);">Xóa</button>
                            `:''}
                        </div>
                        </p>
                        </div>${(comment.child.length > 0) ? draw_comments(comment.child) : ''}
                    </li>`;
    });
    content +=`</ul>`;
    return content;
}

var reply_comment = function(element) {
    if (typeof id === 'undefined') {
        id = null;
    } else {
        id = null;
    }

    $('#comment-area').show();
    commentid = $(element).attr('comment-id');
    var elmnt = document.getElementById("comment-area");

    elmnt.scrollIntoView();
    document.getElementById("comment-content").focus();
    return false;
};



var edit_comment = function(element) {
    $('#comment-area').show();
    id = $(element).attr('comment-id');
    text = $(element).parent().parent().find('p.media-content').text();

    document.getElementById("comment-content").value = text;
    console.log(text);
    var elmnt = document.getElementById("comment-area");
    elmnt.scrollIntoView();
    document.getElementById("comment-content").focus();
    return false;
};
var delete_comment = function(element) {

    id = $(element).attr('comment-id');
    $.ajax({
        url: 'post_comment.php', // gửi đến file upload.php 
        dataType: 'json',     
        data: {id: id},                 
        type: 'post',
        success: function(){
            get_commnets(data['id']);
            commentid = null;
            id = null;
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
        if (typeof id === 'undefined') {
            id = null;
        }
        if ($('#comment-content').val() != '' ) {
            $.ajax({
                url: 'post_comment.php', // gửi đến file upload.php 
                dataType: 'json',     
                data: {documentid: data['id'], content: $('#comment-content').val(), commentid: commentid, id: id},                 
                type: 'post',
                success: function(comment){
                    get_commnets(data['id']);
                    commentid = null;
                    id = null;
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
        return false;
    });

    
});