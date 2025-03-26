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
    <div class="modal fade" id="addReportModal" tabindex="-1" role="dialog" aria-labelledby="addReportModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addReportModalLabel">..:: บันทึกการสอนประจำวัน ::..</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addReportForm" method="POST" enctype="multipart/form-data" class="p-2" novalidate>
                        <div class="form-group">
                            <label for="ck_date">วันที่ : <span class="text-danger">(เดือน/วัน/ปี ค.ศ.)</span></label>
                            <input type="date" class="form-control text-center" id="ck_date" name="ck_date" required>
                        </div>
                        <div class="form-group">
                            <label for="sub_no">วิชา : <span class="text-danger">(โปรดเลือกวิชา)</span></label>
                            <select class="form-control text-center" id="sub_no" name="sub_no" required>
                                <!-- Options will be populated here -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ck_level">ระดับชั้น :</label>
                            <select class="form-control text-center" id="ck_level" name="ck_level" required>
                                <option value="">-- โปรดเลือกระดับชั้น --</option>
                                <!-- Options will be populated here -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ck_room">ห้อง :</label>
                            <select class="form-control text-center" id="ck_room" name="ck_room" required>
                                <option value="">-- โปรดเลือกห้อง --</option>
                                <!-- Options will be populated here -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ck_pstart">คาบที่ : <span class="text-danger">(โปรดเลือกคาบที่สอน (เริ่ม))</span></label>
                            <select class="form-control text-center" id="ck_pstart" name="ck_pstart" required>
                                <option value="">-- เริ่ม --</option>
                                <!-- Options will be populated here -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ck_pend">ถึงคาบที่ : <span class="text-danger">(โปรดเลือกคาบที่สอน (สิ้นสุด))</span></label>
                            <select class="form-control text-center" id="ck_pend" name="ck_pend" required>
                                <option value="">-- เริ่ม --</option>
                                <!-- Options will be populated here -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ck_plan">แผนการจัดการเรียนรู้ที่ : <span class="text-danger">(โปรดเลือกแผนการจัดการเรียนรู้)</span></label>
                            <select class="form-control text-center" id="ck_plan" name="ck_plan" required>
                                <option value="">-- แผนที่ --</option>
                                <!-- Options will be populated here -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ck_title">แผนการจัดการเรียนรู้ / เรื่อง :</label>
                            <input type="text" class="form-control text-center" name="ck_title" id="ck_title" maxlength="250" placeholder="เช่น (แผนการจัดการเรียนรู้ที่ 1 เรื่อง พื้นที่รูปสามเหลี่ยม)" required>
                        </div>
                        <div class="form-group">
                            <label for="ck_event">กิจกรรม / เทคนิคการสอน :</label>
                            <textarea name="ck_event" id="ck_event" class="form-control text-center" rows="3" placeholder="กิจกรรม / เทคนิคการสอน"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="ck_attend">นักเรียนที่ไม่เข้าเรียน (ลาป่วย / ลากิจ / หนีเรียน)</label>
                            <textarea name="ck_attend" id="ck_attend" class="form-control text-center" rows="3" placeholder="เช่น 40,41,42"></textarea>
                        </div>
                        <div class="form-group">
                            <h5 class="text-center">บันทึกหลังแผนการจัดการเรียนรู้</h5>
                        </div>
                        <div class="form-group">
                            <label for="ck_rec_K">- ด้านการจัดการเรียนรู้ (K)</label>
                            <textarea name="ck_rec_K" id="ck_rec_K" class="form-control text-center" rows="3" placeholder="- ด้านการจัดการเรียนรู้"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="ck_rec_P">- ด้านทักษะกระบวนการ (P)</label>
                            <textarea name="ck_rec_P" id="ck_rec_P" class="form-control text-center" rows="3" placeholder="- ด้านทักษะกระบวนการ"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="ck_rec_A">- ด้านคุณลักษณะอันพึงประสงค์ (A)</label>
                            <textarea name="ck_rec_A" id="ck_rec_A" class="form-control text-center" rows="3" placeholder="- ด้านคุณลักษณะอันพึงประสงค์"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="ck_problem">ปัญหาและอุปสรรค์ในการสอน</label>
                            <textarea name="ck_problem" id="ck_problem" class="form-control text-center" rows="3" placeholder="ปัญหาที่พบ"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="ck_solution">ข้อเสนอแนะ/แนวทางแก้ไข</label>
                            <textarea name="ck_solution" id="ck_solution" class="form-control text-center" rows="3" placeholder="ข้อเสนอแนะ/แนวทางแก้ไข"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="img_name">รูปภาพประกอบการสอน <span class="text-danger">(หากมี อัพโหลดหรือไม่อัพโหลดก็ได้)</span> </label>
                            <input type="file" class="form-control text-center" name="img_name" id="img_name" accept=".jpg, .jpeg, .png">
                        </div>

                        <div class="modal-footer justify-content-between">
                            <input type="hidden" name="teach_id" value="<?php echo $userData['Teach_id'];?>">
                            <input type="hidden" name="term" value="<?php echo $term;?>">
                            <input type="hidden" name="pee" value="<?php echo $pee;?>">
                        </form>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                        </div>
                </div>
            </div>
        </div>
    </div>


        <!-- Modal แก้ไข -->
        <div class="modal fade" id="editReportModal" tabindex="-1" role="dialog" aria-labelledby="editReportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editReportModalLabel">..:: แก้ไขบันทึกการสอน ::..</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editReportForm" medthod="POST" enctype="multipart/form-data" class="p-2" novalidate>
                            <input type="hidden" id="editReportid" name="editReportid">
                        <div class="form-group">
                            <label for="Editck_date">วันที่ : <span class="text-danger">(เดือน/วัน/ปี ค.ศ.)</span></label>
                            <input type="date" class="form-control text-center" id="Editck_date" name="Editck_date" required>
                        </div>
                        <div class="form-group">
                            <label for="Editsub_no">วิชา : <span class="text-danger">(โปรดเลือกวิชา)</span></label>
                            <select class="form-control text-center" id="Editsub_no" name="Editsub_no" required>
                                <!-- Options will be populated here -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Editck_level">ระดับชั้น :</label>
                            <select class="form-control text-center" id="Editck_level" name="Editck_level" required>
                                <option value="">-- โปรดเลือกระดับชั้น --</option>
                                <!-- Options will be populated here -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Editck_room">ห้อง :</label>
                            <select class="form-control text-center" id="Editck_room" name="Editck_room" required>
                                <option value="">-- โปรดเลือกห้อง --</option>
                                <!-- Options will be populated here -->
                                <option value="1">ห้องที่ 1</option>
                                <option value="2">ห้องที่ 2</option>
                                <option value="3">ห้องที่ 3</option>
                                <option value="4">ห้องที่ 4</option>
                                <option value="5">ห้องที่ 5</option>
                                <option value="6">ห้องที่ 6</option>
                                <option value="7">ห้องที่ 7</option>
                                <option value="8">ห้องที่ 8</option>
                                <option value="9">ห้องที่ 9</option>
                                <option value="10">ห้องที่ 10</option>
                                <option value="11">ห้องที่ 11</option>
                                <option value="12">ห้องที่ 12</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Editck_pstart">คาบที่ : <span class="text-danger">(โปรดเลือกคาบที่สอน (เริ่ม))</span></label>
                            <select class="form-control text-center" id="Editck_pstart" name="Editck_pstart" required>
                                <option value="">-- เริ่ม --</option>
                                <!-- Options will be populated here -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Editck_pend">ถึงคาบที่ : <span class="text-danger">(โปรดเลือกคาบที่สอน (สิ้นสุด))</span></label>
                            <select class="form-control text-center" id="Editck_pend" name="Editck_pend" required>
                                <option value="">-- เริ่ม --</option>
                                <!-- Options will be populated here -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Editck_plan">แผนการจัดการเรียนรู้ที่ : <span class="text-danger">(โปรดเลือกแผนการจัดการเรียนรู้)</span></label>
                            <select class="form-control text-center" id="Editck_plan" name="Editck_plan" required>
                                <option value="">-- แผนที่ --</option>
                                <!-- Options will be populated here -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Editck_title">แผนการจัดการเรียนรู้ / เรื่อง :</label>
                            <input type="text" class="form-control text-center" name="Editck_title" id="Editck_title" maxlength="250" placeholder="เช่น (แผนการจัดการเรียนรู้ที่ 1 เรื่อง พื้นที่รูปสามเหลี่ยม)" required>
                        </div>
                        <div class="form-group">
                            <label for="Editck_event">กิจกรรม / เทคนิคการสอน :</label>
                            <textarea name="Editck_event" id="Editck_event" class="form-control text-center" rows="3" placeholder="กิจกรรม / เทคนิคการสอน"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="Editck_attend">นักเรียนที่ไม่เข้าเรียน (ลาป่วย / ลากิจ / หนีเรียน)</label>
                            <textarea name="Editck_attend" id="Editck_attend" class="form-control text-center" rows="3" placeholder="เช่น 40,41,42"></textarea>
                        </div>
                        <div class="form-group">
                            <h5 class="text-center">บันทึกหลังแผนการจัดการเรียนรู้</h5>
                        </div>
                        <div class="form-group">
                            <label for="Editck_rec_K">- ด้านการจัดการเรียนรู้ (K)</label>
                            <textarea name="Editck_rec_K" id="Editck_rec_K" class="form-control text-center" rows="3" placeholder="- ด้านการจัดการเรียนรู้"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="Editck_rec_P">- ด้านทักษะกระบวนการ (P)</label>
                            <textarea name="Editck_rec_P" id="Editck_rec_P" class="form-control text-center" rows="3" placeholder="- ด้านทักษะกระบวนการ"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="Editck_rec_A">- ด้านคุณลักษณะอันพึงประสงค์ (A)</label>
                            <textarea name="Editck_rec_A" id="Editck_rec_A" class="form-control text-center" rows="3" placeholder="- ด้านคุณลักษณะอันพึงประสงค์"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="Editck_problem">ปัญหาและอุปสรรค์ในการสอน</label>
                            <textarea name="Editck_problem" id="Editck_problem" class="form-control text-center" rows="3" placeholder="ปัญหาที่พบ"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="Editck_solution">ข้อเสนอแนะ/แนวทางแก้ไข</label>
                            <textarea name="Editck_solution" id="Editck_solution" class="form-control text-center" rows="3" placeholder="ข้อเสนอแนะ/แนวทางแก้ไข"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="Editimg_name">รูปภาพประกอบการสอน</label>
                            <input type="file" class="form-control text-center" name="Editimg_name" id="Editimg_name"  accept=".jpg, .jpeg, .png">
                        </div>
                            <input type="hidden" name="Editck_term" id="Editck_term" >
                            <input type="hidden" name="Editck_pee" id="Editck_pee" >
                        </form>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        <button type="button" id="submitEditForm" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                    </div>
                </div>
            </div>
        </div>
        </div>




    <section class="content">
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="callout callout-success text-center">
                    <h3 class="fw-bold bt-2">รายงานการสอน<br></h3>
                    <hr>
                    <div class="text-left">
                        <button class="btn-lg btn-success" data-toggle="modal" data-target="#addReportModal">บันทึกการสอนประจำวัน</button>
                        <div class="row justify-content-center my-3">
                            <div class="col-md-6">
                                <div class="input-group mb-3" id="term_selector">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="select_term">เทอม</label>
                                    </div>
                                    <select class="custom-select text-center" id="select_term">
                                        <option value="">โปรดเลือกเทอม...</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group mb-3" id="pee_selector">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="select_pee">ปีการศึกษา</label>
                                    </div>
                                    <select class="custom-select text-center" id="select_pee">
                                        <option value="">โปรดเลือกปีการศึกษา...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button id="filter" class="btn btn-sm btn-outline-info">
                                <i class="fa fa-search" aria-hidden="true"></i> ค้นหา
                            </button>
                            <button id="reset" class="btn btn-sm btn-outline-warning">
                                <i class="fa fa-trash" aria-hidden="true"></i> ล้างค่า
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-3 mb-3 mx-auto">
                            <div class="table-responsive mx-auto">
                                <table class="display table-bordered responsive" id="record_table" style="width:100%;">
                                    <thead class="table-dark text-white text-center">
                                        <tr>
                                            <th style="vertical-align: middle; text-align: center; width:5%;">ลำดับ</th>
                                            <th style="vertical-align: middle; text-align: center; width:5%;">วันที่</th>
                                            <th style="vertical-align: middle; text-align: center; width:10%;">วิชา</th>
                                            <th style="vertical-align: middle; text-align: center; width:5%;">แผนที่</th>
                                            <th style="vertical-align: middle; text-align: center; width:30%;">เรื่องที่สอน</th>
                                            <th style="vertical-align: middle; text-align: center; width:5%;">ระดับชั้น</th>
                                            <th style="vertical-align: middle; text-align: center; width:5%;">ปีการศึกษา</th>
                                            <th style="vertical-align: middle; text-align: center; width:20%;">การเช็คชื่อ</th>
                                            <th style="vertical-align: middle; text-align: center; width:5%;">หลังแผน</th>
                                            <th style="vertical-align: middle; text-align: center; width:10%;">จัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody> <!-- Data will be inserted here -->
                                </table>
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
            const teachId = <?php echo json_encode($userData['Teach_id']); ?>;

            // Fetch subjects and populate select element
            const apiUrl = `api/fet_subject.php?Teach_id=${teachId}`;

            // Populate level options
            const levels = [
                { value: 1, text: 'มัธยมศึกษาปีที่ 1' },
                { value: 2, text: 'มัธยมศึกษาปีที่ 2' },
                { value: 3, text: 'มัธยมศึกษาปีที่ 3' },
                { value: 4, text: 'มัธยมศึกษาปีที่ 4' },
                { value: 5, text: 'มัธยมศึกษาปีที่ 5' },
                { value: 6, text: 'มัธยมศึกษาปีที่ 6' }
            ];

            const ckLevelSelect = $('#ck_level');
            const ckLevelSelectEdit = $('#Editck_level');
            levels.forEach(level => {
                ckLevelSelect.append(`<option value="${level.value}">${level.text}</option>`);
                ckLevelSelectEdit.append(`<option value="${level.value}">${level.text}</option>`);
            });

            const selectPee = document.getElementById('select_pee');
            const currentYear = new Date().getFullYear() + 543;

            for (let i = 0; i <= 10; i++) {
                const option = document.createElement('option');
                option.value = currentYear - i;
                option.text = currentYear - i;
                selectPee.appendChild(option);
            }

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    const selectElement = $('#sub_no');
                    const selectElement2 = $('#Editsub_no');
                    selectElement.empty();
                    selectElement2.empty();
                    selectElement.append('<option value="">-- โปรดเลือกวิชา --</option>');
                    selectElement2.append('<option value="">-- โปรดเลือกวิชา --</option>');
                    data.forEach(item => {
                        selectElement.append(`<option value="${item.sub_no}">${item.sub_name}</option>`);
                    });
                    data.forEach(item => {
                        selectElement2.append(`<option value="${item.sub_no}">${item.sub_name}</option>`);
                    });
                })
                .catch(error => console.error('Error fetching subjects:', error));

            // Handle subject selection
            $('#sub_no').change(function() {
                const subNo = $(this).val();
                if (subNo) {
                    fetch(`api/get_subject.php?sub_no=${subNo}`)
                        .then(response => response.json())
                        .then(item => {
                            if (item) {
                                $('#ck_level').val(item.sub_level);
                                updateRoomOptions(item.sub_level);
                            } else {
                                $('#ck_level').val('');
                                updateRoomOptions(''); // Clear room options if no item found
                            }
                        })
                        .catch(error => console.error('Error fetching subject details:', error));
                } else {
                    $('#ck_level').val('');
                    updateRoomOptions(''); // Clear room options if no subject selected
                }
            });

            
            // Populate room options
            function updateRoomOptions(level) {
                const roomSelect = $('#ck_room');
                roomSelect.empty();
                roomSelect.append('<option value="">-- โปรดเลือกห้อง --</option>');

                let maxRooms = 12;
                if (level >= 4 && level <= 6) {
                    maxRooms = 7;
                }

                for (let i = 1; i <= maxRooms; i++) {
                    roomSelect.append(`<option value="${i}">ห้องที่ ${i}</option>`);
                }
            }
            

            // Populate period options
            const periods = [1, 2, 3, 4, 5, 6, 7, 8];

            function populatePeriods() {
                const startSelect = $('#ck_pstart');
                const startSelectedit = $('#Editck_pstart');
                const endSelect = $('#ck_pend');
                const endSelectedit = $('#Editck_pend');

                startSelect.empty();
                startSelectedit.empty();
                endSelect.empty();
                endSelectedit.empty();

                startSelect.append('<option value="">-- เริ่ม --</option>');
                startSelectedit.append('<option value="">-- เริ่ม --</option>');
                endSelect.append('<option value="">-- เริ่ม --</option>');
                endSelectedit.append('<option value="">-- เริ่ม --</option>');

                periods.forEach(period => {
                    startSelect.append(`<option value="${period}">คาบที่ ${period}</option>`);
                    endSelect.append(`<option value="${period}">คาบที่ ${period}</option>`);
                });

                periods.forEach(period => {
                    startSelectedit.append(`<option value="${period}">คาบที่ ${period}</option>`);
                    endSelectedit.append(`<option value="${period}">คาบที่ ${period}</option>`);
                });
            }

            // Function to update end period options based on start period
            function updateEndOptions() {
                const startPeriod = parseInt($('#ck_pstart').val());
                const endSelect = $('#ck_pend');

                endSelect.empty();
                endSelect.append('<option value="">-- ถึงคาบที่ --</option>');

                periods.forEach(period => {
                    if (period >= startPeriod) {
                        endSelect.append(`<option value="${period}">คาบที่ ${period}</option>`);
                    }
                });
            }
            function updateEndOptionsEdit() {
                const startPeriod = parseInt($('#Editck_pstart').val());
                const endSelect = $('#Editck_pend');

                endSelect.empty();
                endSelect.append('<option value="">-- ถึงคาบที่ --</option>');

                periods.forEach(period => {
                    if (period >= startPeriod) {
                        endSelect.append(`<option value="${period}">คาบที่ ${period}</option>`);
                    }
                });
            }

            populatePeriods();

            // Update end period options when start period changes
            $('#ck_pstart').change(updateEndOptions);
            $('#Editck_pstart').change(updateEndOptionsEdit);

            function populatePlansEdit() {
                const planSelectEdit = document.getElementById('Editck_plan');
                
                // Clear any existing options
                planSelectEdit.innerHTML = '<option value="">-- แผนที่ --</option>';
                
                // Loop through 1 to 40 and add options
                for (let i = 1; i <= 60; i++) {
                    const option = document.createElement('option');
                    option.value = i;
                    option.textContent = `แผนที่ ${i}`;
                    planSelectEdit.appendChild(option);
                }
            }
            function populatePlans() {
                const planSelect = document.getElementById('ck_plan');
                
                // Clear any existing options
                planSelect.innerHTML = '<option value="">-- แผนที่ --</option>';
                
                // Loop through 1 to 40 and add options
                for (let i = 1; i <= 60; i++) {
                    const option = document.createElement('option');
                    option.value = i;
                    option.textContent = `แผนที่ ${i}`;
                    planSelect.appendChild(option);
                }
            }

            // Call the function to populate the options
            populatePlans();
            populatePlansEdit();

             // Convert date to Thai format
            function convertToThaiDate(dateString) {
                const months = ["ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];
                const dateParts = dateString.split("-");
                const year = parseInt(dateParts[0]) + 543;
                const month = months[parseInt(dateParts[1]) - 1];
                const day = parseInt(dateParts[2]);
                return `${day} ${month} ${year}`;
            }

            // Load table data
            function loadTable(data) {
                const table = $('#record_table').DataTable();
                table.clear().destroy();
                $('#record_table tbody').empty();

                if (data.length === 0) {
                    $('#record_table tbody').append('<tr><td colspan="9" class="text-center">ไม่พบข้อมูล</td></tr>');
                } else {
                    data.forEach((item, index) => {
                        const thaiDate = convertToThaiDate(item.ck_date);
                        const row = `
                            <tr>
                                <td style="vertical-align: middle; text-align: center;">${index + 1}</td>
                                <td style="vertical-align: middle; text-align: center;">${thaiDate}</td>
                                <td style="vertical-align: middle; text-align: center;">${item.sub_name}</td>
                                <td style="vertical-align: middle; text-align: center;">${item.ck_plan}</td>
                                <td style="vertical-align: middle; text-align: center;">${item.ck_title}</td>
                                <td style="vertical-align: middle; text-align: center;"> ม.${item.ck_level}/${item.ck_room}</td>
                                <td style="vertical-align: middle; text-align: center;">${item.ck_term}/${item.ck_pee}</td>
                                <td style="vertical-align: middle; text-align: center;">${item.ck_attend}</td>
                                <td style="vertical-align: middle; text-align: center;">
                                    <button class="btn btn-warning btn-sm print-btn my-1" data-id="${item.id}">พิมพ์</button>
                                </td>
                                <td style="vertical-align: middle; text-align: center;">
                                   
                                    <button class="btn btn-primary btn-sm edit-btn my-1" data-id="${item.id}">แก้ไข</button>
                                    <button class="btn btn-danger btn-sm delete-btn my-1" data-id="${item.id}">ลบ</button>
                                </td>
                            </tr>`;
                        // เอาไว้้เพิ่มทีหลัง
                        //  <button class="btn btn-warning btn-sm print-btn my-1" data-id="${item.id}">พิมพ์</button>
                        $('#record_table tbody').append(row);
                    });
                }
     
                $('#record_table').DataTable({
                    "pageLength": 10,
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": true,
                    "responsive": true
                });
            }

            // Initial table load
            function initialLoad() {
                $.ajax({
                    url: 'api/fet_report.php',
                    method: 'GET',
                    data: { Teach_id: teachId },
                    dataType: 'json',
                    success: function(data) {
                        loadTable(data);
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการดึงข้อมูล', 'error');
                    }
                });
            }

            initialLoad();

            // Handle live search
            function handleLiveSearch() {
                const term = $('#select_term').val();
                const pee = $('#select_pee').val();

                if (term && pee) {
                    $.ajax({
                        url: 'api/fet_report_termpee.php',
                        method: 'GET',
                        data: { term: term, pee: pee, Teach_id: teachId },
                        dataType: 'json',
                        success: function(data) {
                            if (data.message === 'ไม่มีข้อมูล') {
                                Swal.fire('แจ้งเตือน', 'ไม่พบข้อมูลสำหรับเทอมและปีการศึกษาที่เลือก', 'info');
                            } else {
                                loadTable(data);
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการดึงข้อมูล', 'error');
                        }
                    });
                } else {
                    initialLoad();
                }
            }

            $('#select_term').change(handleLiveSearch);
            $('#select_pee').change(handleLiveSearch);
            $('#reset').click(function () {
                // Clear the value of #txt_stuid
                $('#select_term').val('');
                $('#select_pee').val('');
                initialLoad();
            });
                        
            // Handle Add Subject form submission
            $('#addReportForm').submit(function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch('api/insert_report.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => { throw new Error(text) });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire('สำเร็จ', 'เพิ่มรายงานการสอนเรียบร้อยแล้ว', 'success')
                        .then(() => {
                            // $('#addReportModal').modal('hide');
                            //$('#editReportModal').modal('hide');
                            // initialLoad();
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('ข้อผิดพลาด', 'ไม่สามารถเพิ่มรายงานการสอน: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการเพิ่มรายงานการสอน: ' + error.message, 'error');
                });
            });

            $(document).on('click', '.print-btn', function() {
                 const id = $(this).data('id');

                 window.open('print_report.php?id=' + id, '_blank');
            
            });
           // Handle edit button click event
           $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');

                $.ajax({
                    url: 'api/get_report.php',
                    method: 'GET',
                    data: { id: id },
                    dataType: 'json',
                    success: function(data) {
                        if (data) {
                            $('#Editck_date').val(data.ck_date);
                            $('#Editsub_no').val(data.sub_no);
                            $('#Editck_plan').val(data.ck_plan);
                            $('#Editck_term').val(data.ck_term);
                            $('#Editck_pee').val(data.ck_pee);
                            $('#Editck_pstart').val(data.ck_pstart);
                            $('#Editck_pend').val(data.ck_pend);
                            $('#Editck_title').val(data.ck_title);
                            $('#Editck_event').val(data.ck_event);
                            $('#Editck_rec_K').val(data.ck_rec_K);
                            $('#Editck_rec_P').val(data.ck_rec_P);
                            $('#Editck_rec_A').val(data.ck_rec_A);
                            $('#Editck_problem').val(data.ck_problem);
                            $('#Editck_solution').val(data.ck_solution);
                            $('#Editck_level').val(data.ck_level);
                            $('#Editck_room').val(data.ck_room);
                            $('#Editck_attend').val(data.ck_attend);
                            $('#editReportid').val(data.id);
                            $('#editReportModal').modal('show');
                        } else {
                            Swal.fire('ข้อผิดพลาด', 'ไม่พบข้อมูลที่ต้องการแก้ไข', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการดึงข้อมูล', 'error');
                    }
                });
            });

            // Handle save button click event in the edit modal
            document.getElementById('submitEditForm').addEventListener('click', function() {
                let form = document.getElementById('editReportForm');
                let formData = new FormData(form);

                Swal.fire({
                    title: 'กรุณารอสักครู่...',
                    text: 'กำลังบันทึกข้อมูล...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch('api/update_report.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();  // Read response as text first
                })
                .then(text => {
                    try {
                        let data = JSON.parse(text);  // Attempt to parse JSON
                        Swal.close();
                        if (data.success) {
                            Swal.fire({
                                title: 'สำเร็จ!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'ตกลง'
                            }).then(() => {
                                $('#editReportModal').modal('hide');
                                initialLoad();
                            });
                        } else {
                            Swal.fire({
                                title: 'เกิดข้อผิดพลาด!',
                                text: data.message,
                                icon: 'error',
                                confirmButtonText: 'ตกลง'
                            });
                        }
                    } catch (e) {
                        Swal.close();
                        Swal.fire({
                            title: 'เกิดข้อผิดพลาด!',
                            text: 'ข้อมูลที่ได้รับไม่ถูกต้อง: ' + text,
                            icon: 'error',
                            confirmButtonText: 'ตกลง'
                        });
                    }
                })
                .catch(error => {
                    Swal.close();
                    Swal.fire({
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'ไม่สามารถส่งข้อมูลได้: ' + error.message,
                        icon: 'error',
                        confirmButtonText: 'ตกลง'
                    });
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
                    Swal.fire({
                        title: 'กำลังลบ...',
                        text: 'กรุณารอสักครู่',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch('api/delete_report.php', {
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
                        Swal.close(); // Close the loading dialog
                        if (data.success) {
                            Swal.fire('ลบแล้ว!', 'ลบรายการเรียบร้อยแล้ว', 'success');
                            initialLoad(); // Reload table data
                        } else {
                            Swal.fire('ข้อผิดพลาด', 'ไม่สามารถลบรายการ: ' + data.message, 'error');
                            console.log(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('เกิดข้อผิดพลาดในการลบรายการ:', error);
                        Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการลบรายการ', 'error');
                    });
                }
            });
        });




            
    });
</script>







<?php require_once('scirpt.php');?>
</body>
</html>
