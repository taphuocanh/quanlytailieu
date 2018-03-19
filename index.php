<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>DataTables example</title>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css"/>
    <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#example tfoot th').each( function () {
                console.log($(this).context.cellIndex);
                
                var title = $(this).text();
                if ($(this).context.cellIndex > -1 ) {
                    $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
                }
                
            } );
            var table = $('#example').DataTable( {
                "serverSide": true,
                "ajax" : "ajax.php",
                "columns": [
                    { "data": "id" },
                    { "data": "subj_code" },
                    { "data": "subj_name" },
                    { "data": "typed" },
                    { "data": "bookname" },
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
<div id="container">
    <h1>Datatables - individual search example</h1>
    <p>Source codes: <a href="https://github.com/n1crack/datatables-examples/tree/master/sqlite_examples/individualsearch">https://github.com/n1crack/datatables-examples/tree/master/sqlite_examples/individualsearch</a></p>
    <table border="0" cellpadding="11" cellspacing="0" class="display" id="example">
        <thead>
        <tr>
            <th>ID</th>
            <th>Mã môn học</th>
            <th>Tên môn học</th>
            <th>Giáo viên nhập</th>
            <th>Tên sách</th>
            <th>Tác giả</th>
            <th>Năm xuất bản</th>
            <th>Nơi xb/ Nhà xb</th>
            <th>Ghi chú tài liệu</th>
            <th>Trạng thái</th>
            <th>Nơi lưu trữ</th>
            <th>Tên sách đề nghị kiểm tra</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>loading...</td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <th>ID</th>
            <th>Mã môn học</th>
            <th>Tên môn học</th>
            <th>Giáo viên nhập</th>
            <th>Tên sách</th>
            <th>Tác giả</th>
            <th>Năm xuất bản</th>
            <th>Nơi xb/ Nhà xb</th>
            <th>Ghi chú tài liệu</th>
            <th>Trạng thái</th>
            <th>Nơi lưu trữ</th>
            <th>Tên sách đề nghị kiểm tra</th>
        </tr>
        </tfoot>

    </table>
</div>

</body>
</html>