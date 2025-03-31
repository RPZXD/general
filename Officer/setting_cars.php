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
                            <h3 class="text-xl font-bold text-gray-700">จัดการข้อมูลรถยนต์</h3>
                            <button type="button" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 shadow-md" data-toggle="modal" data-target="#addModal">
                                <i class="fas fa-plus mr-2"></i> เพิ่มรถใหม่
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-3 mb-3">
                                <div class="table-responsive">
                                    <table class="display table-bordered responsive nowrap" id="record_table" style="width:100%;">
                                        <thead class="bg-blue-600 text-white">
                                            <tr>
                                                <th class="px-4 py-2 border-b text-center">#</th>
                                                <th class="px-4 py-2 border-b text-center">รูปภาพ</th>
                                                <th class="px-4 py-2 border-b text-center">ประเภทรถ</th>
                                                <th class="px-4 py-2 border-b text-center">หมายเลขทะเบียน</th>
                                                <th class="px-4 py-2 border-b text-center">เลขไมล์ล่าสุด</th>
                                                <th class="px-4 py-2 border-b text-center">ระดับน้ำมัน</th>
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
  </div>
  <!-- /.content-wrapper -->
    <?php require_once('../footer.php');?>
</div>
<!-- ./wrapper -->

<!-- Add Car Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="addCarForm" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
                <div class="modal-header flex justify-between items-center border-b pb-4">
                            <h5 class="text-lg font-semibold" id="addModalLabel">เพิ่มรถใหม่</h5>
                            <button type="button" class="text-gray-500 hover:text-gray-700 text-3xl" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>    
                <div class="modal-body space-y-4">
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700" for="vehicleType">ประเภทรถ</label>
                        <select class="form-control text-center  mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="vehicleType" name="vehicleType" required>
                            <option value="" disabled selected>กรุณาเลือกประเภทรถ</option>
                            <option value="รถเก๋ง">รถเก๋ง</option>
                            <option value="รถกระบะ">รถกระบะ</option>
                            <option value="รถตู้">รถตู้</option>
                            <option value="รถบรรทุก">รถบรรทุก</option>
                            <option value="รถจักรยานยนต์">รถจักรยานยนต์</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700" for="licensePlate">หมายเลขทะเบียน</label>
                        <input type="text" class="form-control text-center  mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="licensePlate" name="licensePlate" placeholder="กรอกหมายเลขทะเบียน" required>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700" for="latestMileage">เลขไมล์ล่าสุด</label>
                        <input type="number" class="form-control text-center  mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="latestMileage" name="latestMileage" placeholder="กรอกเลขไมล์ล่าสุด" required>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700" for="fuelLevel">ระดับน้ำมัน (%)</label>
                        <input type="number" class="form-control text-center  mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="fuelLevel" name="fuelLevel" placeholder="กรอกระดับน้ำมัน (%)" min="0" max="100" required>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700" for="carImage">รูปภาพรถ</label>
                        <input type="file" class="form-control text-center file" id="carImage" name="carImage" accept="image/*" required>
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
            <form id="editCarForm" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
                <div class="modal-header flex justify-between items-center border-b pb-4">
                    <h5 class="text-lg font-semibold" id="editModalLabel">แก้ไขข้อมูลรถ</h5>
                    <button type="button" class="text-gray-500 hover:text-gray-700 text-3xl" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body space-y-4">
                    <input type="hidden" id="editCarId" name="id">
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700" for="editVehicleType">ประเภทรถ</label>
                        <select class="form-control text-center mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="editVehicleType" name="vehicleType" required>
                            <option value="" disabled selected>กรุณาเลือกประเภทรถ</option>
                            <option value="รถเก๋ง">รถเก๋ง</option>
                            <option value="รถกระบะ">รถกระบะ</option>
                            <option value="รถตู้">รถตู้</option>
                            <option value="รถบรรทุก">รถบรรทุก</option>
                            <option value="รถจักรยานยนต์">รถจักรยานยนต์</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700" for="editLicensePlate">หมายเลขทะเบียน</label>
                        <input type="text" class="form-control text-center mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="editLicensePlate" name="licensePlate" placeholder="กรอกหมายเลขทะเบียน" required>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700" for="editLatestMileage">เลขไมล์ล่าสุด</label>
                        <input type="number" class="form-control text-center mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="editLatestMileage" name="latestMileage" placeholder="กรอกเลขไมล์ล่าสุด" required>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700" for="editFuelLevel">ระดับน้ำมัน (%)</label>
                        <input type="number" class="form-control text-center mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="editFuelLevel" name="fuelLevel" placeholder="กรอกระดับน้ำมัน (%)" min="0" max="100" required>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700" for="editCarImage">รูปภาพรถ</label>
                        <input type="file" class="form-control text-center file" id="editCarImage" name="carImage" accept="image/*">
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
    // Load table function
    function loadTable() {
        // Show loading toast before the table content loads
        const toast = showToast("กำลังโหลดข้อมูล...", "info");

        $.ajax({
            url: 'api/fetch_cars.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#record_table').DataTable().clear().destroy(); // Clear old data
                $('#record_table tbody').empty();

                if (response.length === 0) {
                    $('#record_table tbody').append('<tr><td colspan="8" class="text-center">ไม่พบข้อมูล</td></tr>');
                } else {
                    $.each(response, function(index, data) {
                        var mileageFormatted = Number(data.latest_mileage).toLocaleString(); // Format mileage
                        var fuelFormatted = `<i class="fas fa-gas-pump text-red-500 text-lg"></i> ${data.fuel_level}%`; // Fuel icon
                        
                        var statusChecked = data.status === "1" ? "checked" : "";
                        var statusToggle = `
                            <label class="relative inline-block w-10 h-6">
                                <input type="checkbox" ${statusChecked} onchange="updateStatus('${data.id}', this.checked)">
                                <span class="custom-slider"></span>
                            </label>`;

                        var row = `<tr>
                            <td class="text-center">${data.id}</td>
                            <td class="text-center flex justify-center items-center"> 
                                <img src="../${data.image_url}" class="w-[50px] h-[50px] rounded-lg" style="max-width: 50px; max-height: 50px;">
                            </td>
                            <td class="text-center">${data.vehicle_type}</td>
                            <td class="text-center">${data.license_plate}</td>
                            <td class="text-center">${mileageFormatted}</td>
                            <td class="text-center">${fuelFormatted}</td>
                            <td class="text-center">${statusToggle}</td>
                            <td class="text-center">
                                <button class="btn bg-yellow-500 text-white btn-sm editBtn" data-id="${data.id}"><i class="fas fa-pen text-lg"></i></button>
                                <button class="btn bg-red-500 text-white btn-sm ml-2 deleteBtn" data-id="${data.id}"><i class="fas fa-trash text-lg"></i></button>
                            </td>
                        </tr>`;
                        $('#record_table tbody').append(row);
                    });
                }

                // Initialize DataTable
                $('#record_table').DataTable({
                    "pageLength": 10,
                    "scrollY": "600px", // Vertical scroll area
                    "scrollCollapse": true, // Enable scroll collapse
                    "searching": true,
                    "ordering": true,
                    "paging": true,
                    "lengthChange": true,
                    "responsive": true,
                    "language": {
                        "lengthMenu": "แสดง _MENU_ แถว",
                        "zeroRecords": "ไม่พบข้อมูล",
                        "info": "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ แถว",
                        "infoEmpty": "ไม่มีข้อมูล",
                        "infoFiltered": "(กรองจากทั้งหมด _MAX_ แถว)",
                        "search": "ค้นหา:",
                        "paginate": {
                            "first": "หน้าแรก",
                            "last": "หน้าสุดท้าย",
                            "next": "ถัดไป",
                            "previous": "ก่อนหน้า"
                        }
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error("Load Table Error:", xhr.responseText);
                Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการดึงข้อมูล', 'error');
            }
        });
    }

    loadTable(); // Initial load of the table

    // Add car form submit
    $('#addCarForm').on('submit', function (e) {
        e.preventDefault();
    
        const formData = new FormData(this);
    
        $.ajax({
            url: 'api/add_car.php', // Replace with your API endpoint
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                Swal.fire('สำเร็จ', 'เพิ่มรถใหม่เรียบร้อยแล้ว', 'success');
                loadTable(); // Reload the table data
                $('#addCarModal').modal('hide'); // Close modal
            },
            error: function (xhr, status, error) {
                Swal.fire('ข้อผิดพลาด', 'ไม่สามารถเพิ่มรถได้', 'error');
            }
        });
    });

    // Edit car button click
    $(document).on('click', '.editBtn', function () {
        const id = $(this).data('id');
        fetch(`api/get_car.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                $('#editCarId').val(data.id);
                $('#editVehicleType').val(data.vehicle_type);
                $('#editLicensePlate').val(data.license_plate);
                $('#editLatestMileage').val(data.latest_mileage);
                $('#editFuelLevel').val(data.fuel_level);
                $('#editModal').modal('show');
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('ข้อผิดพลาด', 'ไม่สามารถโหลดข้อมูลได้', 'error');
            });
    });

    // Edit car form submit
    $('#editCarForm').on('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: 'api/update_car.php', // Replace with your API endpoint
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                Swal.fire('สำเร็จ', 'แก้ไขข้อมูลรถเรียบร้อยแล้ว', 'success');
                $('#editModal').modal('hide'); // Close modal
                loadTable(); // Reload the table data
            },
            error: function (xhr, status, error) {
                console.error("Update Car Error:", xhr.responseText);
                Swal.fire('ข้อผิดพลาด', 'ไม่สามารถแก้ไขข้อมูลรถได้', 'error');
            }
        });
    });

    // Delete car button click
    $(document).on('click', '.deleteBtn', function () {
        const carId = $(this).data('id');

        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: 'คุณต้องการลบข้อมูลรถนี้หรือไม่?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'api/delete_car.php', // Replace with your API endpoint
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ id: carId }),
                    success: function (response) {
                        Swal.fire('สำเร็จ', 'ลบข้อมูลรถเรียบร้อยแล้ว', 'success');
                        loadTable(); // Reload the table data
                    },
                    error: function (xhr, status, error) {
                        console.error("Delete Car Error:", xhr.responseText);
                        Swal.fire('ข้อผิดพลาด', 'ไม่สามารถลบข้อมูลรถได้', 'error');
                    }
                });
            }
        });
    });
});

// Update vehicle status function
function updateStatus(vehicleId, isChecked) {
    let newStatus = isChecked ? '1' : '0';

    $.ajax({
        url: 'api/update_vehicle_status.php',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ id: vehicleId, status: newStatus }),
        success: function(response) {
            Swal.fire('สำเร็จ', 'อัปเดตสถานะเรียบร้อย', 'success');
        },
        error: function(xhr, status, error) {
            console.error("Update Status Error:", xhr.responseText);
            Swal.fire('ข้อผิดพลาด', 'ไม่สามารถอัปเดตสถานะได้', 'error');
        }
    });
}


</script>



<?php require_once('script.php');?>
</body>
</html>
