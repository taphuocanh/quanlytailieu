<?php
$logged = false;
session_start();
if (array_key_exists("login_user",$_SESSION) && $_SESSION['login_user'] != '') {
    require 'configs.php';

    $conn2 = new mysqli($config2['host'], $config2['username'], $config2['password'], $config2['database']);
    mysqli_set_charset($conn2,"utf8");
    if ($conn2->connect_error) {
        die("Connection failed: " . $conn2->connect_error);
    } 
    
    $result = $conn2->query("SELECT * FROM nv_tu_dien_nhan_vien WHERE nv_tu_dien_nhan_vien_user = '" . $_SESSION['login_user'] . "'");

    if ($row = mysqli_fetch_assoc($result)) {
        $logged = true;
        $user = $row;
    } else {
        $logged = false;
        unset($_SESSION);
        session_destroy();
        session_write_close();
    }
}
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->

    <title>Quản lý tài liệu</title>
    <!-- <link rel="stylesheet" href="//cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css"/> -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/scroller/1.4.4/css/scroller.dataTables.min.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/fixedheader/3.1.3/css/fixedHeader.dataTables.min.css"/>
    <link rel="stylesheet" href="./assets/css/style.css"/>
    <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script type="text/javascript" language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/scroller/1.4.4/js/dataTables.scroller.min.js"></script>
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/fixedheader/3.1.3/js/dataTables.fixedHeader.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment-with-locales.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/locale/vi.js"></script>
    <?php if ($logged)  { ?>
        <script type="text/javascript" charset="utf-8">
            var logged = true;    
            var userfullname = '<?php echo $user['nv_tu_dien_nhan_vien_ho_lot_vn'] . " " . $user['nv_tu_dien_nhan_vien_ten_vn'] ; ?>';
        </script>
    <?php } ?>
    <script src="./assets/js/main.js"></script>
    <script type="text/javascript" charset="utf-8">


        function filterGlobal () {
            $('#mainTable').DataTable().search(
                $('#global_filter').val(),
                $('#global_regex').prop('checked'),
                $('#global_smart').prop('checked')
            ).draw();
        }
        
        function filterColumn ( i ) {
            $('#mainTable').DataTable().column( i ).search(
                $('#col'+i+'_filter').val(),
                $('#col'+i+'_regex').prop('checked'),
                $('#col'+i+'_smart').prop('checked')
            ).draw();
        }

        $(document).ready(function() {
            $('#mainTable tfoot th').each( function () {
                
                var title = $(this).text();
                if ($(this).context.cellIndex < -1 ) {
                    $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
                }
                
            } );
            var table = $('#mainTable').DataTable( {
                "language": {
                    "info": "<b>Tổng số:</b> _TOTAL_ tài liệu.",
                    "infoFiltered": " - Đã lọc từ _MAX_ tài liệu.",
                    "lengthMenu": "Hiển thị _MENU_ tài liệu.",
                },
                "dom": '<"top"iB>rt<"bottom"flp><"clear">',
                //dom: 'lBfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5'
                ],
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                initComplete : function() {
                    
                    $('#mainTable_filter').hide();
                    // $('#searchButton-area').html(`<button type="button" class="btn btn-info btn-md" data-toggle="modal" data-target="#searchModal">Tìm kiếm</button>`);
                    this.api().columns().every( function () {
                        if(this[0][0] > -1) {
                            var column = this;
                            var select = $('<br><select id="filter-column-' + this[0][0] + '" style="max-width:200px;"><option value=""></option></select>')
                                .appendTo( column.footer())
                                .on( 'change', function () {
                                    $('input.column_filter').each((k, element) => {
                                        $(element).val('');
                                    });
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    if (column[0][0] == 1) {
                                        $('#filter-select-box select').val( $(this).val() );
                                    }
            
                                    column
                                        .search( val ? '^'+val+'$' : '', true, false )
                                        .draw();
                                } );
            
                            column.data().unique().sort().each( function ( d, j ) {
                                if (d != null){
                                 select.append( '<option value="'+d+'">'+d+'</option>' )
                                }
                            } );

                            if (this[0][0] == 2) {
                                var column = this;
                                var select = $('<select class="form-control" style="max-width:200px;"><option value=""></option></select>')
                                    .appendTo( '#filter-select-box>div')
                                    .on( 'change', function () {
                                        $('input.column_filter').each((k, element) => {
                                            $(element).val('');
                                        });
                                        $('select#filter-column-1').val($(this).val());
                                        var val = $.fn.dataTable.util.escapeRegex(
                                            $(this).val()
                                        );
                                        
                                        column
                                            .search( val ? '^'+val+'$' : '', true, false )
                                            .draw();
                                    } );
                
                                column.data().unique().sort().each( function ( d, j ) {
                                    if (d != null){
                                        select.append( '<option value="'+d+'">'+d+'</option>' )
                                    }
                                } );
                                $('#filter-select-box').show();
                            }
                        }
                    } );
                },
                "ajax" : "ajax.php",
                fixedHeader: true,
                columnDefs: [ {
                    "max-width": "40%", "min-width": "25%"
                    , targets: 0
                } ],
                "autoWidth": false,
                order: [ 1, 'des' ],
                
                "columns" : [
                    { "data": "bookname" },
                    { "data": "subj_name" },
                    { "data": "tendonvi" },
                    { "data": "tenbomon" },
                    { "data": "document_note" },
                    { "data": "status" },
                    { "data": "storageat" }
                ]
            } );
            table.on( 'draw', function () {
                $('#mainTable tbody tr').each((k,e) => {
                    tensach = $($(e).find('td')[0]).text();
                    if (tensach.includes('Bài giảng') || tensach.includes('bài giảng') ) {
                        console.log($('#mainTable tbody tr')[k]);
                        $($('#mainTable tbody tr')[k]).css({"color": "rgb(0, 169, 21)", "text-decoration": "underline"});
                    }
                })
            } );
            // Apply the search
            table.columns().every( function () {
                var that = this;
                $( 'input', this.footer() ).on( 'keyup change', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw(false);
                    }
                } );
            } );


            $('input.global_filter').on( 'keyup click', function () {
                filterGlobal();
            } );
        
            $('input.column_filter').on( 'keyup click', function () {
                filterColumn( $(this).parents('tr').attr('data-column') );
                $('#mainTable tfoot th select').each((k,e) => {
                    $(e).val('');
                });
            } );


            $('#mainTable tbody').on( 'click', 'tr', function () {
                rowdata = table.row( this ).data();
                commentid = null;

                
                $('.upload-form').hide();
                // table.row( this ).data().foreach
                data = table.row( this ).data();
                for(key in data){
                    $('#txt-' + key).text(data[key]);
                }
                if (data['path'] != null) {
                    $('a#view-file-link').show().attr('href', './uploads/' + data["id"] + '.pdf' );
                    $('a#up-file-link').hide();
                } else {
                    $('a#view-file-link').hide();
                    $('a#up-file-link').show();
                }
                get_commnets(rowdata['id']);
                $('#DescModal').modal("show");
            } );

            $('#bottom-button button').on('click', function() {
                alert('click');
                console.log(this.attr())
            })
        } );

        
    </script>
    <style>
        tfoot input {
            width: 100%;
            padding: 3px;
            box-sizing: border-box;
        }
        #searchButton-area {
            position: fixed;
            top: 5px;
            right: 5px;
            z-index: 100000000000;
        }
        div.DTS div.dataTables_scrollBody {
            background: #FFF;
        }
        .text-wrap.width-200 {
            max-width: 500px;
            overflow-wrap: break-word;
            white-space: initial;
        }
        #mainTable tfoot th, #mainTable thead th {
            white-space: nowrap;
        }

        #comment-box ul.media-list {
            padding-left: 25px;
        }
        #comment-box>ul.media-list {
            padding-left: 0;
        }
        #comment-box ul.media-list li {
            margin-top: 0;
        }
        #comment-box .media-body {
            border: 1px solid #ececec;
            margin-bottom: 10px;
            display: block;
            width: 100%;
            padding: 10px;
            background: rgba(236, 236, 236, 0.6);
        }
        #comment-box .media-body p.media-content {
            background: #fff;
            padding: 10px;
        }
    </style>
</head>
<body id="dt_example">

<?php
    include 'header.php';
?>

<div id="container" class="container-fluid">
    <!-- Trigger the modal with a button -->
    <!-- Modal -->
    <div id="searchButton-area"></div>
    <div id="searchModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Tìm kiếm</h4>
        </div>
        <div class="modal-body">
        <table style="margin: 0 auto 2em auto;" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Tên trường tìm kiếm</th>
                <th>Nội dung tìm kiếm</th>
            </tr>
        </thead>
        <tbody>
            <tr id="filter_col0" data-column="0">
                <td>Tên sách</td>
                <td align="center"><input type="text" class="column_filter" id="col0_filter"></td>
            </tr>
            <tr id="filter_col2" data-column="1">
                <td>Tên môn học</td>
                <td align="center"><input type="text" class="column_filter" id="col2_filter"></td>
            </tr>
            <tr id="filter_col1" data-column="2">
                <td>Tên đơn vị</td>
                <td align="center"><input type="text" class="column_filter" id="col1_filter"></td>
            </tr>
            <tr id="filter_col5" data-column="3">
                <td>Tên bộ môn</td>
                <td align="center"><input type="text" class="column_filter" id="col5_filter"></td>
            </tr>
        </tbody>
    </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </div>

    </div>
    </div>
    <div class="">
    <table id="mainTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
        <tr>
            <th>Tên sách</th>
            <th>Tên môn học</th>
            <th>Tên đơn vị</th>
            <th>Tên bộ môn</th>
            <th>Ghi chú tài liệu</th>
            <th>Trạng thái</th>
            <th>Nơi lưu trữ</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>loading...</td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <th>Tên sách</th>
            <th>Tên môn học</th>
            <th>Tên đơn vị</th>
            <th>Tên bộ môn</th>
            <th>Ghi chú tài liệu</th>
            <th>Trạng thái</th>
            <th>Nơi lưu trữ</th>
        </tr>
        </tfoot>

    </table>
    </div>
</div>

<?php
    include 'footer.php';
    include 'document_details.php';
    include 'loginModal.php';
?>
<script>
$("#logout-link").on('click', function() {
    var r = confirm("Bạn thực sự muốn đăng xuất?");
    if (r == true) {
        
        $.ajax({
            url : "logout.php",
            type : "post",
            dataType:"json",
            success : function (result){
                if (result.status && result.status == 'logout') {
                    location.reload();
                }
            }
        });
    }

});
$(function() {
  $('input[type=file]').change(function(){
    var t = $(this).val();
    var labelText = 'File : ' + t.substr(12, t.length);
    $(this).prev('label').text(labelText);
  })

    $('a#up-file-link').on('click', function() {
        $('.upload-form').show();
        return false;
    });

    $('#up-file-btn').on('click', function() {
        var file_data = $('#file').prop('files')[0];
        //lấy ra kiểu file
        var type = file_data.type;
        //Xét kiểu file được upload
        var match= ["application/pdf"];
        //kiểm tra kiểu file
        if(type == match[0])
        {
            //khởi tạo đối tượng form data
            var form_data = new FormData();
            //thêm files vào trong form data
            file_data.id = rowdata.id;
            form_data.append('id', rowdata.id);
            form_data.append('file', file_data);
            console.log(form_data);
            
            //sử dụng ajax post
            $.ajax({
                url: 'upload.php', // gửi đến file upload.php 
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                       
                type: 'post',
                success: function(res){
                    $('.status').html(res);
                    $('#file').val('');
                    var labelText = 'Choose a file ';
                    $('input[type=file]').prev('label').text(labelText);
                }
            });           
        } else{
            $('.status').text('Chỉ được upload file pdf trong khi file của bạn là "' + file_data.type + '"');
                $('#file').val('');
        }
        return false;
    });

});

</script>

</body>
</html>