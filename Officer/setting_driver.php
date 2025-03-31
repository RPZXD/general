<?php 

session_start();

include_once("../config/Database.php");
include_once("../class/UserLogin.php");
include_once("../class/Utils.php");
require_once('../class/Setting.php');

// Initialize database connection
$connectDB = new Database("phichaia_student");
$db = $connectDB->getConnection();

$connectDBgeneral = new Database("phichaia_general");
$dbGeneral = $connectDBgeneral->getConnection();

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
$teacher_id = $_SESSION['Officer_login'];

$config = new Setting_Config($dbGeneral);
$locations = $config->fetchMeetingRooms();

require_once('header.php');

?>

<body class="hold-transition sidebar-mini layout-fixed light-mode bg-gray-100 text-gray-800">
<div class="wrapper">

    <?php require_once('wrapper.php');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

  <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"></h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

            <section class="content">
                <div class="container mx-auto">
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold text-gray-700">จัดการข้อมูลคนขับรถ</h3>
                            <button type="button" class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 shadow-md" data-toggle="modal" data-target="#addModal">
                                <i class="fas fa-plus mr-2"></i> เพิ่มคนขับรถ
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-3 mb-3">
                                <div class="table-responsive">
                                    <table class="display table-bordered responsive nowrap" id="record_table" style="width:100%;">
                                        <thead class="bg-blue-600 text-white">
                                            <tr>
                                                <th class="px-4 py-2 border-b text-center">#</th>
                                                <th class="px-4 py-2 border-b text-center">ชื่อ-นามสกุล</th>
                                                <th class="px-4 py-2 border-b text-center">เบอร์โทร</th>
                                                <th class="px-4 py-2 border-b text-center">ใบขับขี่</th>
                                                <th class="px-4 py-2 border-b text-center">วันหมดอายุ</th>
                                                <th class="px-4 py-2 border-b text-center">สถานะ</th>
                                                <th class="px-4 py-2 border-b text-center">การจัดการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Table data will be dynamically loaded -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

    <!-- /.content -->
     <!-- Toast แจ้งเตือน -->
    <div id="toast-container" class="fixed bottom-5 right-5 flex flex-col space-y-2 z-50"></div>
  </div>
    

  <!-- /.content-wrapper -->
    <?php require_once('../footer.php');?>
</div>
<!-- ./wrapper -->

<!-- Add Car Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="addDriverForm" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
                <div class="modal-header flex justify-between items-center border-b pb-4">
                            <h5 class="text-lg font-semibold" id="addModalLabel">เพิ่มข้อมูลคนขับรถ</h5>
                            <button type="button" class="text-gray-500 hover:text-gray-700 text-3xl" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>    
                <div class="modal-body space-y-4">
                <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700" for="Fullname">ชื่อ - นามสกุล</label>
                        <input type="text" class="form-control text-center mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="Fullname" name="FullName" placeholder="กรอกชื่อนามสกุล" required>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700" for="Phone">เบอร์โทรศัพท์</label>
                        <input type="text" class="form-control text-center mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="Phone" name="Phone" placeholder="กรอกหมายเลขโทรศัพท์" maxlength="10" required>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700" for="LicenseNo">ใบขับขี่</label>
                        <input type="text" class="form-control text-center mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="LicenseNo" name="LicenseNo" placeholder="กรอกเลขที่ใบขับขี่" required>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700" for="LicenseEx">วันหมดอายุ</label>
                        <input type="date" class="form-control text-center mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="LicenseEx" name="LicenseExpiry" placeholder="กรอกวันหมดอายุใบขับขี่" required>
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

<!-- Edit Car Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="editDriverForm" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
                <div class="modal-header flex justify-between items-center border-b pb-4">
                    <h5 class="text-lg font-semibold" id="editModalLabel">แก้ไขข้อมูลคนขับรถ</h5>
                    <button type="button" class="text-gray-500 hover:text-gray-700 text-3xl" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body space-y-4">
                    <input type="hidden" id="editDriverId" name="id">
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700" for="editFullname">ชื่อ - นามสกุล</label>
                        <input type="text" class="form-control text-center mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="editFullname" name="FullName" placeholder="กรอกชื่อนามสกุล" required>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700" for="editPhone">เบอร์โทรศัพท์</label>
                        <input type="text" class="form-control text-center mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="editPhone" name="Phone" placeholder="กรอกหมายเลขโทรศัพท์" maxlength="10" required>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700" for="editLicenseNo">ใบขับขี่</label>
                        <input type="text" class="form-control text-center mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="editLicenseNo" name="LicenseNo" placeholder="กรอกเลขที่ใบขับขี่" required>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700" for="editLicenseEx">วันหมดอายุ</label>
                        <input type="date" class="form-control text-center mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="editLicenseEx" name="LicenseExpiry" placeholder="กรอกวันหมดอายุใบขับขี่" required>
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




<script>
$(document).ready(function () {
    function loadTable() {
        $.ajax({
            url: 'api/fetch_driver.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                let table = $('#record_table').DataTable();
                table.clear().draw();
                
                if (response.length === 0) {
                    table.row.add(['-', 'ไม่พบข้อมูล', '-', '-', '-', '-', '-']).draw();
                } else {
                    response.forEach(data => {
                        var statusToggle = `
                            <label class="relative inline-block w-10 h-6">
                                <input type="checkbox" ${data.status === "active" ? "checked" : ""} onchange="updateStatus('${data.id}', this.checked)">
                                <span class="custom-slider"></span>
                            </label>`;

                        table.row.add([
                            data.id,
                            data.full_name,
                            data.phone,
                            data.license_no,
                            data.license_expiry,
                            statusToggle,
                            `<button class="btn bg-yellow-500 text-white btn-sm editBtn" data-id="${data.id}"><i class="fas fa-pen"></i></button>
                             <button class="btn bg-red-500 text-white btn-sm ml-2 deleteBtn" data-id="${data.id}"><i class="fas fa-trash"></i></button>`
                        ]).draw();
                    });
                }
            },
            error: function(xhr) {
                console.error("Load Table Error:", xhr.responseText);
                showToast('danger', 'เกิดข้อผิดพลาดในการดึงข้อมูล');
            }
        });
    }

    loadTable();

    $('#addDriverForm').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: 'api/add_driver.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                $('#addModal').modal('hide');
                showToast('success', 'เพิ่มคนขับรถใหม่เรียบร้อยแล้ว');
                loadTable();
            },
            error: function (xhr) {
                showToast('danger', 'ไม่สามารถเพิ่มคนขับรถได้');
            }
        });
    });

    function updateStatus(driverId, isChecked) {
        let newStatus = isChecked ? 'active' : 'inactive';

        $.ajax({
            url: 'api/update_driver_status.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ id: driverId, status: newStatus }),
            success: function () {
                showToast('success', 'อัปเดตสถานะเรียบร้อย');
            },
            error: function (xhr) {
                showToast('danger', 'ไม่สามารถอัปเดตสถานะได้');
            }
        });
    }

    $(document).on('click', '.editBtn', function () {
        const id = $(this).data('id');
        $.get(`api/get_driver.php?id=${id}`, function (data) {
            $('#editDriverId').val(data.id);
            $('#editFullname').val(data.full_name);
            $('#editPhone').val(data.phone);
            $('#editLicenseNo').val(data.license_no);
            $('#editLicenseEx').val(data.license_expiry);
            $('#editModal').modal('show');
        }).fail(() => showToast('danger', 'ไม่สามารถโหลดข้อมูลได้'));
    });

    $('#editDriverForm').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: 'api/update_driver.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                $('#editModal').modal('hide');
                showToast('success', 'แก้ไขข้อมูลคนขับรถเรียบร้อยแล้ว');
                loadTable();
            },
            error: function () {
                showToast('danger', 'ไม่สามารถแก้ไขข้อมูลได้');
            }
        });
    });

    $(document).on('click', '.deleteBtn', function () {
        const driverId = $(this).data('id');

        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: 'คุณต้องการลบข้อมูลนี้หรือไม่?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ใช่, ลบเลย!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'api/delete_driver.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ id: driverId }),
                    success: function () {
                        showToast('success', 'ลบข้อมูลคนขับรถเรียบร้อยแล้ว');
                        loadTable();
                    },
                    error: function () {
                        showToast('danger', 'ไม่สามารถลบข้อมูลได้');
                    }
                });
            }
        });
    });

    

});
</script>



<?php require_once('script.php');?>
</body>
</html>
