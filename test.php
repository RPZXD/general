<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User DataTable</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <table id="record_table" class="display">
        <thead>
            <tr>
                <th>#</th>
                <th>User Name</th>
                <th>User RefillMoney</th>
                <th>User ReRefer</th>
                <th>Create At</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be inserted here -->
        </tbody>
    </table>

    <script>
        function loadTable() {
            $.ajax({
                url: 'https://ran-donate.com/api/get_logTopup.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#record_table').DataTable().clear().destroy(); // Clear and destroy the existing table

                    $('#record_table tbody').empty();

                    if (data.length === 0) {
                        $('#record_table tbody').append('<tr><td colspan="4" class="text-center">ไม่พบข้อมูล</td></tr>');
                    } else {
                        $.each(data, function(index, item) {
                            var row = '<tr>' +
                                '<td>' + (index + 1) + '</td>' +
                                '<td>' + item.UserName + '</td>' +
                                '<td>' + item.RefillMoney + '</td>' +
                                '<td>' + item.ReRefer + '</td>' +
                                '<td>' + item.created_at + '</td>' +
                                '</tr>';
                            $('#record_table tbody').append(row);
                        });
                    }

                    // Reinitialize DataTable with responsive settings
                    $('#record_table').DataTable({
                        "pageLength": 10,
                        "paging": true,
                        "lengthChange": true,
                        "ordering": true,
                        "responsive": true // Enable responsive mode
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการดึงข้อมูล', 'error');
                }
            });
        }

        // Call the function to initialize the table
        $(document).ready(function() {
            loadTable();
        });
    </script>
</body>
</html>
