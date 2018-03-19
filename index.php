<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DataTables example</title>
    <!-- <link rel="stylesheet" href="//cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css"/> -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css"/>
    <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" charset="utf-8">


        function filterGlobal () {
            $('#example').DataTable().search(
                $('#global_filter').val(),
                $('#global_regex').prop('checked'),
                $('#global_smart').prop('checked')
            ).draw();
        }
        
        function filterColumn ( i ) {
            $('#example').DataTable().column( i ).search(
                $('#col'+i+'_filter').val(),
                $('#col'+i+'_regex').prop('checked'),
                $('#col'+i+'_smart').prop('checked')
            ).draw();
        }

        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#example tfoot th').each( function () {
                console.log($(this).context.cellIndex);
                
                var title = $(this).text();
                if ($(this).context.cellIndex < -1 ) {
                    $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
                }
                
            } );
            var table = $('#example').DataTable( {
                "serverSide" : true,
                "ajax" : "ajax.php",
                "responsive" : {
                    details: {
                        type: 'column'
                    }
                }, 
                columnDefs: [ {
                    className: 'control',
                    orderable: false,
                    targets:   0
                } ],
                order: [ 2, 'asc' ],
                "columns" : [
                    { "data": "control" }, 
                    { "data": "id" },
                    { "data": "bookname" },
                    { "data": "subj_name" },
                    { "data": "subj_code" },
                    { "data": "author" },
                    { "data": "publishdate" },
                    { "data": "publisher" },
                    { "data": "document_note" },
                    { "data": "status" },
                    { "data": "storageat" },
                    { "data": "note" }
                ]
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
            } );
        } );
    </script>
    <style>
        tfoot input {
            width: 100%;
            padding: 3px;
            box-sizing: border-box;
        }
    </style>
</head>
<body id="dt_example">
<div id="container" class="container-fluid">
    <h1>Datatables - individual search example</h1>
    <p>Source codes: <a href="https://github.com/n1crack/datatables-examples/tree/master/sqlite_examples/individualsearch">https://github.com/n1crack/datatables-examples/tree/master/sqlite_examples/individualsearch</a></p>

<table style="width: 50%; margin: 0 auto 2em auto;" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Tên trường tìm kiếm</th>
                <th>Nội dung tìm kiếm</th>
            </tr>
        </thead>
        <tbody>
            <tr id="filter_global">
                <td>Tìm kiếm chung</td>
                <td align="center"><input type="text" class="global_filter" id="global_filter"></td>
            </tr>
            <tr id="filter_col2" data-column="2">
                <td>Tên sách</td>
                <td align="center"><input type="text" class="column_filter" id="col2_filter"></td>
            </tr>
            <tr id="filter_col3" data-column="3">
                <td>Tên môn học</td>
                <td align="center"><input type="text" class="column_filter" id="col3_filter"></td>
            </tr>
            <tr id="filter_col4" data-column="4">
                <td>Mã môn học</td>
                <td align="center"><input type="text" class="column_filter" id="col4_filter"></td>
            </tr>
            <tr id="filter_col6" data-column="6">
                <td>Tác giả</td>
                <td align="center"><input type="text" class="column_filter" id="col6_filter"></td>
            </tr>
            <tr id="filter_col7" data-column="7">
                <td>Năm xuất bản</td>
                <td align="center"><input type="text" class="column_filter" id="col7_filter"></td>
            </tr>
        </tbody>
    </table>

    <table id="example"  class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Hiển thị thêm</th>
            <th>ID</th>
            <th>Tên sách</th>
            <th>Tên môn học</th>
            <th>Mã môn học</th>
            <th>Tác giả</th>
            <th>Năm xuất bản</th>
            <th>Nơi xb/ Nhà xb</th>
            <th>Ghi chú tài liệu</th>
            <th>Trạng thái</th>
            <th>Nơi lưu trữ</th>
            <th>Ghi chú</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>loading...</td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <th>Hiển thị thêm</th>
            <th>ID</th>
            <th>Tên sách</th>
            <th>Tên môn học</th>
            <th>Mã môn học</th>
            <th>Tác giả</th>
            <th>Năm xuất bản</th>
            <th>Nơi xb/ Nhà xb</th>
            <th>Ghi chú tài liệu</th>
            <th>Trạng thái</th>
            <th>Nơi lưu trữ</th>
            <th>Ghi chú</th>
        </tr>
        </tfoot>

    </table>
</div>

</body>
</html>