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
                if ($(this).context.cellIndex <2 ) {
                    $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
                }
                
            } );
            var table = $('#example').DataTable( {
                "serverSide": true,
                "ajax" : "ajax.php",
                "columns": [
                    { "data": "uid" },
                    { "data": "uuid" },
                    { "data": "action" },
                    { "data": "method" }
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
    <table border="0" cellpadding="4" cellspacing="0" class="display" id="example">
        <thead>
        <tr>
            <th width="32%">Name</th>
            <th width="8%">ID</th>
            <th width="32%">Name</th>
            <th width="28%">Unit Price</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>loading...</td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <th>Name</th>
            <th>ID</th>
            <th>Name</th>
            <th>Unit Price</th>
        </tr>
        </tfoot>

    </table>
</div>

</body>
</html>