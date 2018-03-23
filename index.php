<?php
$logged = false;
session_start();
if (array_key_exists("login_user",$_SESSION) && $_SESSION['login_user'] != '') {
    require 'configs.php';

    $conn = new mysqli($config2['host'], $config2['username'], $config2['password'], $config2['database']);
    mysqli_set_charset($conn,"utf8");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    
    $result = $conn->query("SELECT * FROM nv_tu_dien_nhan_vien WHERE nv_tu_dien_nhan_vien_user = '" . $_SESSION['login_user'] . "'");

    if ($row = mysqli_fetch_assoc($result)) {
        $logged = true;
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DataTables example</title>
    <!-- <link rel="stylesheet" href="//cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css"/> -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/scroller/1.4.4/css/scroller.dataTables.min.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/fixedheader/3.1.3/css/fixedHeader.dataTables.min.css"/>
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
                dom: 'lBfrtip',
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

                            if (this[0][0] == 1) {
                                var column = this;
                                var select = $('<select class="form-control" style="max-width:200px;"><option value=""></option></select>')
                                    .appendTo( '#filter-select-box')
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
                    { "data": "tendonvi" },
                    { "data": "subj_name" },
                    { "data": "subj_code" },
                    { "data": "mabomon" },
                    { "data": "tenbomon" },
                    { "data": "author" },
                    { "data": "publishdate" },
                    { "data": "publisher" },
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
                
                $('.upload-form').hide();
                table.row( this ).data().foreach
                let data = table.row( this ).data();
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
    </style>
</head>
<body id="dt_example">

<?php
    include 'header.php';
?>

<div id="container" class="container-fluid">
    <center><h1>Quản lý tài liệu</h1></centeR>
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
            <tr id="filter_col1" data-column="1">
                <td>Tên đơn vị</td>
                <td align="center"><input type="text" class="column_filter" id="col1_filter"></td>
            </tr>
            <tr id="filter_col2" data-column="2">
                <td>Tên môn học</td>
                <td align="center"><input type="text" class="column_filter" id="col2_filter"></td>
            </tr>
            <tr id="filter_col3" data-column="3">
                <td>Mã môn học</td>
                <td align="center"><input type="text" class="column_filter" id="col3_filter"></td>
            </tr>
            <tr id="filter_col4" data-column="4">
                <td>Mã bộ môn</td>
                <td align="center"><input type="text" class="column_filter" id="col4_filter"></td>
            </tr>
            <tr id="filter_col5" data-column="5">
                <td>Tên bộ môn</td>
                <td align="center"><input type="text" class="column_filter" id="col5_filter"></td>
            </tr>
            <tr id="filter_col6" data-column="6">
                <td>Tác giả</td>
                <td align="center"><input type="text" class="column_filter" id="col6_filter"></td>
            </tr>
            <tr id="filter_col7" data-column="7">
                <td>Năm xuất bản</td>
                <td align="center"><input type="text" class="column_filter" id="col7_filter"></td>
            </tr>
            <tr id="filter_col8" data-column="8">
                <td>Nơi xb/ Nhà xb</td>
                <td align="center"><input type="text" class="column_filter" id="col8_filter"></td>
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
    <?php if (!$logged) { ?> 
    <div id="filter-select-box" class="form-group">
        Lọc theo đơn vị: 
    </div>
    <br>
    <?php } ?>
    <div class="">
    <table id="mainTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
        <tr>
            <th>Tên sách</th>
            <th>Tên đơn vị</th>
            <th>Tên môn học</th>
            <th>Mã môn học</th>
            <th>Mã bộ môn</th>
            <th>Tên bộ môn</th>
            <th>Tác giả</th>
            <th>Năm xuất bản</th>
            <th>Nơi xb/ Nhà xb</th>
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
            <th>Tên đơn vị</th>
            <th>Tên môn học</th>
            <th>Mã môn học</th>
            <th>Mã bộ môn</th>
            <th>Tên bộ môn</th>
            <th>Tác giả</th>
            <th>Năm xuất bản</th>
            <th>Nơi xb/ Nhà xb</th>
            <th>Ghi chú tài liệu</th>
            <th>Trạng thái</th>
            <th>Nơi lưu trữ</th>
        </tr>
        </tfoot>

    </table>
    </div>
</div>

<?php
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