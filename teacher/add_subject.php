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

if (isset($_SESSION['Teacher_login'])) {
    $userid = $_SESSION['Teacher_login'];
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
$teacher_id = $userData['Teacher_id'];


require_once('header.php');


?>
<body class="hold-transition sidebar-mini layout-fixed light-mode">
<div class="wrapper">

    <?php require_once('warpper.php');?>

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
    <!-- Modal -->
        <div class="modal fade" id="addSubjectModal" tabindex="-1" role="dialog" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSubjectModalLabel">เพิ่มรายวิชา</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="addSubjectForm"  method="POST">
                            <div class="form-group">
                                <label for="sub_name">ชื่อวิชา : <span class="text-danger">(โปรดกรอกชื่อวิชา)</span></label>
                                <input type="text" class="form-control text-center" id="sub_name" name="sub_name" maxlength="50" required>
                            </div>
                            <div class="form-group">
                                <label for="sub_id">รหัสวิชา : <span class="text-danger">(โปรดกรอกรหัสวิชา ไม่ต้องเว้นวรรค เช่น ง11101)</span></label>
                                <input type="text" class="form-control text-center" id="sub_id" name="sub_id"  maxlength="6" required>
                            </div>
                            <div class="form-group">
                                <label for="sub_level">ระดับชั้น : <span class="text-danger">(ระดับชั้นของวิชา)</span></label>
                                <select class="form-control text-center" id="sub_level" name="sub_level" required>
                                    <option value="">-- โปรดเลือกระดับชั้น --</option>
                                    <option value="1">มัธยมศึกษาปีที่ 1</option>
                                    <option value="2">มัธยมศึกษาปีที่ 2</option>
                                    <option value="3">มัธยมศึกษาปีที่ 3</option>
                                    <option value="4">มัธยมศึกษาปีที่ 4</option>
                                    <option value="5">มัธยมศึกษาปีที่ 5</option>
                                    <option value="6">มัธยมศึกษาปีที่ 6</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="sub_type">ประเภทของรายวิชา : <span class="text-danger">(โปรดเลือกประเภทของวิชา)</span></label>
                                <select class="form-control text-center" id="sub_type" name="sub_type" required>
                                    <option value="">-- โปรดเลือกประเภทวิชา --</option>
                                    <option value="1">พื้นฐาน</option>
                                    <option value="2">เพิ่มเติม</option>
                                    <option value="3">กิจกรรมพัฒนาผู้เรียน</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="sub_status">สถานะ : </label>
                                <select class="form-control text-center" id="sub_status" name="sub_status" required>
                                  <option value="1" checked>ใช้งาน</option>
                                  <option value="0">ไม่ใช้งาน</option>
                                </select>
                            </div>
                            <div class="modal-footer justify-content-between">
                                    <input type="hidden" name="teach_id" value="<?php echo $userData['Teach_id'];?>">
                                    <input type="hidden" name="department" value="<?php echo $userData['Teach_major'];?>">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                                    <button type="submit" class="btn btn-primary">บันทึก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal แก้ไข -->
        <div class="modal fade" id="editSubjectModal" tabindex="-1" role="dialog" aria-labelledby="editSubjectModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSubjectModalLabel">แก้ไขรายวิชา</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editSubjectForm">
                            <input type="hidden" id="editSubNo" name="editsub_no">
                            <div class="form-group">
                                <label for="editSubName">ชื่อรายวิชา</label>
                                <input type="text" class="form-control text-center" id="editSubName" name="editsub_name" maxlength="50" required>
                            </div>
                            <div class="form-group">
                                <label for="editsub_id">รหัสวิชา : <span class="text-danger">(โปรดกรอกรหัสวิชา ไม่ต้องเว้นวรรค เช่น ง11101)</span></label>
                                <input type="text" class="form-control text-center" id="editsub_id" name="editsub_id"  maxlength="6" required>
                            </div>
                            <div class="form-group">
                                <label for="editSubLevel">ระดับ</label>
                                <select class="form-control text-center" id="editSubLevel" name="editsub_level" required>
                                    <option value="">-- โปรดเลือกระดับชั้น --</option>
                                    <option value="1">มัธยมศึกษาปีที่ 1</option>
                                    <option value="2">มัธยมศึกษาปีที่ 2</option>
                                    <option value="3">มัธยมศึกษาปีที่ 3</option>
                                    <option value="4">มัธยมศึกษาปีที่ 4</option>
                                    <option value="5">มัธยมศึกษาปีที่ 5</option>
                                    <option value="6">มัธยมศึกษาปีที่ 6</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="editsub_type">ประเภทของรายวิชา</label>
                                <select class="form-control text-center" id="editsub_type" name="editsub_type" required>
                                    <option value="">-- โปรดเลือกประเภทวิชา --</option>
                                    <option value="1">พื้นฐาน</option>
                                    <option value="2">เพิ่มเติม</option>
                                    <option value="3">กิจกรรมพัฒนาผู้เรียน</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="editSubStatus">สถานะ</label>
                                <select class="form-control text-center" id="editSubStatus" name="editsub_status" required>
                                    <option value="1">ใช้งาน</option>
                                    <option value="0">ไม่ใช้งาน</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        <button type="button" id="submitEditForm" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                    </div>
                </div>
            </div>
        </div>




    <section class="content">

    <div class="container-fluid">
        <div class="col-md-12">
            <div class="callout callout-success text-center">

                <h3 class="fw-bold bt-2">จัดการรายวิชา<br>
                </h3>

                <hr>
                <div class="text-left">
                  <button class="btn-lg btn-success" data-toggle="modal" data-target="#addSubjectModal">เพิ่มรายวิชา</button>

                </div>
                <div class="row">
                    <div class="col-md-12 mt-3 mb-3 mx-auto">
                    <div class="table-responsive">
                    <table class="display table-bordered table-hover" id="record_table" style="width:100%;">
                        <thead class="table-dark text-white text-center">
                            <tr>
                              <th class="text-center" >ลำดับ</th>
                              <th class="text-center" >รหัสวิชา</th>
                              <th class="text-center" >ชื่อวิชา</th>
                              <th class="text-center" >ระดับชั้น</th>
                              <th class="text-center" >สถานะ</th>
                              <th class="text-center" >จัดการ</th>
                            </tr>
                          </thead>
                          <tbody></tbody> <!-- Data will be inserted here -->
                        </table>
                      </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div><!-- /.container-fluid -->
        
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
    <?php require_once('../footer.php');?>
</div>
<!-- ./wrapper -->
<script>
$(document).ready(function() {
    const teachId = <?php echo json_encode($userData['Teach_id']); ?>; // Encode Teach_id for safety

    function loadTable() {
        $.ajax({
            url: 'api/fet_subject.php',
            method: 'GET',
            data: { Teach_id: teachId },
            dataType: 'json',
            success: function(data) {
                $('#record_table').DataTable().clear().destroy(); // Clear and destroy the existing table

                $('#record_table tbody').empty();

                if (data.length === 0) {
                    $('#record_table tbody').append('<tr><td colspan="6" class="text-center">ไม่พบข้อมูล</td></tr>');
                } else {
                    $.each(data, function(index, item) {
                        var statusText = item.sub_status === '1' ? 'ใช้งาน' : 'ไม่ใช้งาน';

                        var row = '<tr>' +
                        '<td>' + (index + 1) + '</td>' +
                        '<td>' + item.sub_id + '</td>' +
                        '<td>' + item.sub_name + '</td>' +
                        '<td>' + item.sub_level + '</td>' +
                        '<td>' + statusText + '</td>' +
                        '<td>' +
                            '<button class="btn btn-primary btn-sm edit-btn" data-id="' + item.sub_no + '">แก้ไข</button> ' +
                            '<button class="btn btn-danger btn-sm delete-btn my-2" data-id="' + item.sub_no + '">ลบ</button>' +
                        '</td>' +
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

    loadTable();

    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');

        // Fetch data for the selected item
        $.ajax({
            url: 'api/get_subject.php',
            method: 'GET',
            data: { sub_no: id },
            dataType: 'json',
            success: function(data) {
                if (data) {
                    $('#editSubNo').val(data.sub_no);
                    $('#editSubName').val(data.sub_name);
                    $('#editsub_id').val(data.sub_id);
                    $('#editSubLevel').val(data.sub_level);
                    $('#editsub_type').val(data.tsub_id);
                    $('#editSubStatus').val(data.sub_status);
                    $('#editSubjectModal').modal('show');
                } else {
                    Swal.fire('ข้อผิดพลาด', 'ไม่พบข้อมูลที่ต้องการแก้ไข', 'error');
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการดึงข้อมูล', 'error');
            }
        });

        
    });

    // Handle Edit Subject form submission
    $('#submitEditForm').on('click', function(event) {
        event.preventDefault();

        const formData = new FormData($('#editSubjectForm')[0]);
        const params = new URLSearchParams(formData);

        fetch('api/update_subject.php', {
            method: 'POST',
            body: params,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('การตอบสนองของเครือข่ายไม่โอเค');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire('สำเร็จ', 'แก้ไขรายวิชาเรียบร้อยแล้ว', 'success')
                .then(() => {
                    $('#editSubjectModal').modal('hide'); // Close the modal
                    loadTable(); // Reload table data
                });
            } else {
                Swal.fire('ข้อผิดพลาด', 'ไม่สามารถแก้ไขรายวิชา: ' + data.message, 'error');
            }
        })
        .catch(error => {
            Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการแก้ไขรายวิชา', 'error');
        });
    });

    // Handle Delete button click
    $(document).on('click', '.delete-btn', function(event) {
        event.preventDefault();
        const itemId = $(this).data('id');

        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: 'คุณแน่ใจหรือว่าต้องการลบรายการนี้หรือไม่?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('api/delete_subject.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({ id: itemId })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('การตอบสนองของเครือข่ายไม่โอเค');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire('ลบแล้ว!', 'ลบรายการเรียบร้อยแล้ว', 'success');
                        loadTable(); // Reload table data
                    } else {
                        Swal.fire('ข้อผิดพลาด', 'ไม่สามารถลบรายการ: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('เกิดข้อผิดพลาดในการลบรายการ:', error);
                    Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการลบรายการ', 'error');
                });
            }
        });
    });

    // Handle Add Subject form submission
    $(document).on('submit', '#addSubjectForm', function(event) {
        event.preventDefault();

        const formData = new FormData(this);
        const params = new URLSearchParams(formData);

        fetch('api/insert_subject.php', {
            method: 'POST',
            body: params,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('การตอบสนองของเครือข่ายไม่โอเค');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire('สำเร็จ', 'เพิ่มรายวิชาเรียบร้อยแล้ว', 'success')
                .then(() => {
                    $('#addSubjectModal').modal('hide'); // Close the modal
                    loadTable(); // Reload table data
                });
            } else {
                Swal.fire('ข้อผิดพลาด', 'ไม่สามารถเพิ่มรายวิชา: ' + data.message, 'error');
            }
        })
        .catch(error => {
            Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการเพิ่มรายวิชา', 'error');
        });
    });
});


// Handle Add Report form submission
$('#addReportForm').on('submit', function(event) {
    event.preventDefault();

    // Create FormData object from the form
    const formData = new FormData(this);
    const params = new URLSearchParams(formData);

    // Send the request via fetch
    fetch('api/insert_report.php', {
        method: 'POST',
        body: params,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('การตอบสนองของเครือข่ายไม่โอเค');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire('สำเร็จ', 'เพิ่มข้อมูลเรียบร้อยแล้ว', 'success')
            .then(() => {
                $('#addReportModal').modal('hide'); // Close the modal
                location.reload(); // Reload the page
            });
        } else {
            Swal.fire('ข้อผิดพลาด', 'ไม่สามารถเพิ่มข้อมูล: ' + data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล', 'error');
    });
});


</script>





<?php require_once('scirpt.php');?>
</body>
</html>
