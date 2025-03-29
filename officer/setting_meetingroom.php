<?php 

session_start();


include_once("../config/Database.php");
include_once("../class/UserLogin.php");
include_once("../class/Utils.php");

// Initialize database connection
$connectDB = new Database_User();
$db = $connectDB->getConnection();

// Initialize UserLogin class
$user = new UserLogin($db);

// Fetch terms and pee
$term = $user->getTerm();
$pee = $user->getPee();

if (isset($_SESSION['Officer_login'])) {
    $userid = $_SESSION['Officer_login'];
    $userData = $user->userData($userid);
} else {
    $sw2 = new SweetAlert2(
        'คุณยังไม่ได้เข้าสู่ระบบ',
        'error',
        '../login.php' // Redirect URL
    );
    $sw2->renderAlert();
    exit;
}

require_once('header.php');


?>
<body class="bg-gray-100 text-gray-800">
<div class="wrapper">

    <?php require_once('wrapper.php');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

  <div class="content-header">
      <div class="container mx-auto py-4">
        <div class="flex justify-between items-center">
          <h1 class="text-2xl font-bold">จัดการห้องประชุม</h1>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <section class="content">

        <div class="container mx-auto">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">ข้อมูลห้องประชุม</h3>
                    <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" data-toggle="modal" data-target="#addModal">
                        เพิ่มห้องประชุม
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-12 mt-3 mb-3 mx-auto">
                        <div class="table-responsive mx-auto">
                            <table class="display table-bordered responsive nowrap" id="record_table" style="width:100%;">
                                <thead class="bg-indigo-500 text-white ">
                                <tr >
                                    <th class="px-4 py-2 border-b text-center">#</th>
                                    <th class="px-4 py-2 border-b text-center">ชื่อห้องประชุม</th>
                                    <th class="px-4 py-2 border-b text-center">จำนวนคนที่บรรจุได้</th>
                                    <th class="px-4 py-2 border-b text-center">อุปกรณ์ที่มีอยู่</th>
                                    <th class="px-4 py-2 border-b text-center">จัดการ</th>
                                </tr>
                                </thead>       
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
    <!-- /.content -->

    <!-- Add Meeting Room Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="addMeetingRoomForm" class="bg-white rounded-lg shadow-md p-6">
                    <div class="modal-header flex justify-between items-center border-b pb-4">
                        <h5 class="text-lg font-semibold" id="addModalLabel">เพิ่มห้องประชุม</h5>
                        <button type="button" class="text-gray-500 hover:text-gray-700" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body space-y-4">
                        <div class="form-group">
                            <label for="roomName" class="block text-sm font-medium text-gray-700">ชื่อห้องประชุม:</label>
                            <input type="text" class="form-control mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="roomName" name="room_name" required>
                        </div>
                        <div class="form-group">
                            <label for="capacity" class="block text-sm font-medium text-gray-700">จำนวนคนที่สามารถบรรจุได้:</label>
                            <input type="number" class="form-control mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="capacity" name="capacity" required>
                        </div>
                        <div class="form-group">
                            <label for="equipment" class="block text-sm font-medium text-gray-700">อุปกรณ์ที่มีอยู่:</label>
                            <textarea class="form-control mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="equipment" name="equipment" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer flex justify-end space-x-2 border-t pt-4">
                        <button type="button" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-gray-600" data-dismiss="modal">ปิด</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Meeting Room Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editMeetingRoomForm" class="bg-white rounded-lg shadow-md p-6">
                    <div class="modal-header flex justify-between items-center border-b pb-4">
                        <h5 class="text-lg font-semibold" id="editModalLabel">แก้ไขห้องประชุม</h5>
                        <button type="button" class="text-gray-500 hover:text-gray-700" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body space-y-4">
                        <input type="hidden" id="editRoomId" name="id">
                        <div class="form-group">
                            <label for="editRoomName" class="block text-sm font-medium text-gray-700">ชื่อห้องประชุม:</label>
                            <input type="text" class="form-control mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="editRoomName" name="room_name" required>
                        </div>
                        <div class="form-group">
                            <label for="editCapacity" class="block text-sm font-medium text-gray-700">จำนวนคนที่สามารถบรรจุได้:</label>
                            <input type="number" class="form-control mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="editCapacity" name="capacity" required>
                        </div>
                        <div class="form-group">
                            <label for="editEquipment" class="block text-sm font-medium text-gray-700">อุปกรณ์ที่มีอยู่:</label>
                            <textarea class="form-control mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="editEquipment" name="equipment" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer flex justify-end space-x-2 border-t pt-4">
                        <button type="button" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-gray-600" data-dismiss="modal">ปิด</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

  </div>
  <!-- /.content-wrapper -->
    <?php require_once('../footer.php');?>
</div>
<!-- ./wrapper -->



<?php require_once('script.php');?>
<script>
    $(document).ready(function () {
      function loadTable() {
    $.ajax({
        url: 'api/fetch_meetingroom.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#record_table').DataTable().clear().destroy(); // Clear and destroy the existing table
            $('#record_table tbody').empty();

            if (response.length === 0) {
                $('#record_table tbody').append('<tr><td colspan="19" class="text-center">ไม่พบข้อมูล</td></tr>');
            } else {
                $.each(response, function(index, data) {
                    var row = '<tr>' +
                    '<td class="text-center">' + (index + 1) + '</td>' +
                    '<td class="text-center">' + data.room_name + '</td>' +
                    '<td class="text-center">' + data.capacity + '</td>' +
                    '<td class="text-center">' + data.equipment + '</td>' +
                    '<td class="text-center">' +
                    '<button class="btn bg-yellow-500 text-white btn-sm editBtn" data-id="' + data.id + '">Edit</button>' +
                    '<button class="btn bg-red-500 text-white btn-sm ml-2 deleteBtn" data-id="' + data.id + '">Delete</button>' +
                    '</td>' +
                    '</tr>';
                    $('#record_table tbody').append(row);
                });
            }

            // Reinitialize DataTable with responsive settings and export buttons
            $('#record_table').DataTable({
                "pageLength": 10,
                "paging": true,
                "lengthChange": true,
                "ordering": true,
                "responsive": true,
                "dom": 'Bfrtip'
            });
        },
        error: function(xhr, status, error) {
            console.error("Load Table Error:", xhr.responseText); // Debugging
            Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการดึงข้อมูล', 'error');
        }
    });
}

loadTable();

document.getElementById('addMeetingRoomForm').addEventListener('submit', function(event) {
    event.preventDefault(); // ป้องกันการส่งฟอร์มตามปกติ

    // เก็บข้อมูลจากฟอร์ม
    const formData = new FormData(this);
    const data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });

    // ส่งข้อมูลไปยังเซิร์ฟเวอร์โดยใช้ fetch
    fetch('api/insert_meetingroom.php', {   
        method: 'POST',
        body: new URLSearchParams(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            Swal.fire({
                title: 'สำเร็จ!',
                text: 'เพิ่มข้อมูลสำเร็จ!',
                icon: 'success',
                confirmButtonText: 'ตกลง'
            }).then(() => {
                // โหลดตารางข้อมูลใหม่หลังจากปิด modal
                window.location.reload();
            });
        } else {
            Swal.fire({
                title: 'ข้อผิดพลาด!',
                text: 'เกิดข้อผิดพลาด: ' + result.message,
                icon: 'error',
                confirmButtonText: 'ตกลง'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'ข้อผิดพลาด!',
            text: 'เกิดปัญหากับคำขอ.',
            icon: 'error',
            confirmButtonText: 'ตกลง'
        });
    });
});

    // Handle Edit Button Click
    $(document).on('click', '.editBtn', function () {
        const id = $(this).data('id');
        fetch(`api/get_meetingroom.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                $('#editRoomId').val(data.id);
                $('#editRoomName').val(data.room_name);
                $('#editCapacity').val(data.capacity);
                $('#editEquipment').val(data.equipment);
                $('#editModal').modal('show');
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('ข้อผิดพลาด', 'ไม่สามารถโหลดข้อมูลได้', 'error');
            });
    });

    // Handle Edit Form Submission
    $('#editMeetingRoomForm').on('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('api/update_meetingroom.php', {
            method: 'POST',
            body: new URLSearchParams(formData)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                Swal.fire('สำเร็จ!', 'แก้ไขข้อมูลสำเร็จ!', 'success').then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire('ข้อผิดพลาด!', result.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('ข้อผิดพลาด!', 'เกิดปัญหากับคำขอ.', 'error');
        });
    });

    // Handle Delete Button Click
    $(document).on('click', '.deleteBtn', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: 'คุณต้องการลบห้องประชุมนี้หรือไม่?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ลบ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`api/delete_meetingroom.php?id=${id}`, { method: 'DELETE' })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            Swal.fire('สำเร็จ!', 'ลบข้อมูลสำเร็จ!', 'success').then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('ข้อผิดพลาด!', result.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('ข้อผิดพลาด!', 'เกิดปัญหากับคำขอ.', 'error');
                    });
            }
        });
    });

});
</script>
</body>
</html>
