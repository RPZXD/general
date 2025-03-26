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
$teacher_id = $userData['Teacher_id'];


require_once('header.php');


?>
<style>
    .form-check-input {
        transform: scale(2);
        margin-right: 30px;
    }
</style>
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
        <div class="modal-dialog modal-xl" role="document"> <!-- ใช้ modal-xl สำหรับขยาย modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addReportModalLabel">
                    ➕ บันทึกแจ้งซ่อมแซมทรัพย์สินประจำห้องเรียนและห้องปฏิบัติการชำรุด/เสียหาย ➕
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addReportForm" method="POST">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="text-center" for="AddDate">วันที่: <span class="text-danger">(เดือน/วัน/ปี ค.ศ.)</span></label>
                                    <input type="date" class="form-control text-center" id="AddDate" name="AddDate" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="text-center" for="AddLocation">ห้องเรียน: <span class="text-danger">(ชั้น ม / อาคาร / ชั้นที่) เช่น ห้องคอม(438) อาคาร 4</span></label>
                                    <input type="text" class="form-control text-center" id="AddLocation" name="AddLocation" placeholder="กรอกสถานที่ เช่น ห้องคอม(438) อาคาร 4" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <h5 class="font-weight-bold">==== มีทรัพย์สินชำรุด/เสียหาย ดังนี้ ====</h5>
                            
                            <br>
                            <h6 class="font-weight-bold">1. ครุภัณฑ์ภายในห้องเรียน/ห้องปฏิบัติการที่ชำรุด</h6>
                            <div id="topic1"></div>

                            <br>
                            <h6 class="font-weight-bold">2. ทัศนูปกรณ์ประจำห้องเรียน/ห้องปฏิบัติการที่ชำรุด</h6>
                            <div id="topic2"></div>

                            <br>
                            <h6 class="font-weight-bold">3. เครื่องใช้ไฟฟ้าประจำห้องเรียน/ห้องปฏิบัติการที่ชำรุด</h6>
                            <div id="topic3"></div>





                        </div>

                        <div class="modal-footer justify-content-between">
                            <input type="hidden" name="teach_id" value="<?php echo $userData['Teach_id'];?>">
                            <input type="hidden" name="term" value="<?php echo $term;?>">
                            <input type="hidden" name="pee" value="<?php echo $pee;?>">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document"> <!-- ใช้ modal-xl สำหรับขยาย modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">
                    รายละเอียด
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <!-- เนื้อหาจะถูกเติมที่นี่ผ่าน JavaScript -->
                </div>
                <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                        </div>
            </div>
        </div>
    </div>

<div class="modal fade" id="editStatusModal" tabindex="-1" role="dialog" aria-labelledby="editStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog"> <!-- ใช้ modal-xl สำหรับขยาย modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStatusModalLabel">
                    แก้ไขสถานะ
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" id="recordId">
                        <div class="mb-3">
                            <label for="currentStatus" class="form-label">สถานะ</label>
                            <select id="currentStatus" class="form-select form-control text-center">
                                <option value="0">⌛รอเจ้าหน้าที่ตรวจสอบ</option>
                                <option value="1">✔️รับเรื่องแล้วกำลังกำลังดำเนินการ</option>
                                <option value="2">⌛รอการสั่งซื้ออุปกรณ์/วัสดุ</option>
                                <option value="3">✅ดำเนินการแล้วเสร็จ✅</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                    <button type="button" class="btn btn-primary" onclick="saveStatus()">บันทึกการเปลี่ยนแปลง</button>
                </div>
            </div>
        </div>
    </div>




    <section class="content">
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="callout callout-success text-center">
                    <h3 class="fw-bold bt-2">รายการแจ้งซ่อมแซมทรัพย์สินประจำห้องเรียนและห้องปฏิบัติการชำรุด/เสียหาย<br></h3>
                    <hr>
                    <div class="text-left">
                        <button class="btn btn-danger" data-toggle="modal" data-target="#addReportModal">➕ บันทึกแจ้งซ่อมแซมทรัพย์สินประจำห้องเรียนและห้องปฏิบัติการชำรุด/เสียหาย ➕</button>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-3 mb-3 mx-auto">
                            <div class="table-responsive mx-auto">
                                <table class="display table-bordered responsive" id="record_table" style="width:100%;">
                                    <thead class="table-dark text-white text-center">
                                        <tr>
                                            <th style="vertical-align: middle; text-align: center; width:5%;">ลำดับ</th>
                                            <th style="vertical-align: middle; text-align: center; width:13%;">วันที่</th>
                                            <th style="vertical-align: middle; text-align: center;">สถานที่</th>
                                            <th style="vertical-align: middle; text-align: center; width:5%;">เทอม</th>
                                            <th style="vertical-align: middle; text-align: center; width:10%;">ปีการศึกษา</th>
                                            <th style="vertical-align: middle; text-align: center;">ผู้แจ้งซ่อม</th>
                                            <th style="vertical-align: middle; text-align: center;">เบอร์โทรผู้แจ้ง</th>
                                            <th style="vertical-align: middle; text-align: center;">สถานะงาน</th>
                                            <th style="vertical-align: middle; text-align: center; width:20%;">จัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
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
    // กำหนดวันที่ปัจจุบัน
    const today = new Date();
    const formattedDate = today.toISOString().split('T')[0]; // แปลงเป็นรูปแบบ YYYY-MM-DD
    document.getElementById('AddDate').value = formattedDate;



    const items = [
        { id: 'door', label: 'ประตู', detailsId: 'doorDetails' },
        { id: 'window', label: 'หน้าต่าง', detailsId: 'windowDetails' },
        { id: 'tablest', label: 'โต๊ะนักเรียน', detailsId: 'tablestDetails' },
        { id: 'chairst', label: 'เก้าอี้นักเรียน', detailsId: 'chairstDetails' },
        { id: 'tableta', label: 'โต๊ะครู', detailsId: 'tabletaDetails' },
        { id: 'chairta', label: 'เก้าอี้ครู', detailsId: 'chairtaDetails' },
        { id: 'other1', label: 'อื่นๆ', detailsId: 'other1Details' }
    ];


    const items2 = [
        { id: 'tv', label: 'โทรทัศน์', detailsId: 'tvDetails' },
        { id: 'audio', label: 'เครื่องเสียง', detailsId: 'audioDetails' },
        { id: 'hdmi', label: 'สาย HDMI', detailsId: 'hdmiDetails' },
        { id: 'projector', label: 'จอโปรเจคเตอร์', detailsId: 'projectorDetails' },
        { id: 'other2', label: 'อื่นๆ', detailsId: 'other2Details' }
    ];


    const items3 = [
        { id: 'fan', label: 'พัดลม', detailsId: 'fanDetails' },
        { id: 'light', label: 'หลอดไฟ', detailsId: 'lightDetails' },
        { id: 'air', label: 'เครื่องปรับอากาศ', detailsId: 'airDetails' },
        { id: 'sw', label: 'สวิตซ์ไฟ', detailsId: 'swDetails' },
        { id: 'swfan', label: 'สวิตซ์พัดลม', detailsId: 'swfanDetails' },
        { id: 'plug', label: 'ปลั๊กไฟ', detailsId: 'plugDetails' },
        { id: 'other3', label: 'อื่นๆ', detailsId: 'other3Details' }
    ];

    function createFormElement(item, topicId) {
    const topic = document.getElementById(topicId);
    const formCheckDiv = document.createElement('div');
    formCheckDiv.classList.add('form-check', 'mt-3');

    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.id = item.id;
    checkbox.classList.add('form-check-input');
    checkbox.onchange = () => toggleDetails(item.id);

    const label = document.createElement('label');
    label.setAttribute('for', item.id);
    label.classList.add('form-check-label');
    label.innerHTML = `&nbsp;&nbsp;&nbsp;${item.label}`;

    formCheckDiv.appendChild(checkbox);
    formCheckDiv.appendChild(label);

    const detailsDiv = document.createElement('div');
    detailsDiv.id = item.detailsId;
    detailsDiv.style.display = 'none';

    // Create the input field for "อื่นๆ" only if necessary
    if (item.id.includes('other')) {
        const otherInputDiv = document.createElement('div');
        otherInputDiv.style.display = 'none'; // Initially hidden

        const otherLabel = document.createElement('label');
        otherLabel.textContent = 'โปรดระบุ:';
        const otherInput = document.createElement('input');
        otherInput.type = 'text';
        otherInput.classList.add('form-control');
        otherInput.name = `${item.id}Details`;

        otherInputDiv.appendChild(otherLabel);
        otherInputDiv.appendChild(otherInput);
        detailsDiv.appendChild(otherInputDiv);

        // Add a property to the input div to indicate it's for "other"
        otherInputDiv.dataset.otherInput = 'true';
    }

    const row = document.createElement('div');
    row.classList.add('row', 'mt-2');

    const col1 = document.createElement('div');
    col1.classList.add('col-md-2');

    const label1 = document.createElement('label');
    label1.textContent = 'จำนวน:';
    const inputNumber = document.createElement('input');
    inputNumber.type = 'number';
    inputNumber.classList.add('form-control');
    inputNumber.name = `${item.id}Count`;
    inputNumber.min = 0;

    col1.appendChild(label1);
    col1.appendChild(inputNumber);

    const col2 = document.createElement('div');
    col2.classList.add('col-md-10');

    const label2 = document.createElement('label');
    label2.textContent = 'ระบุความเสียหาย:';
    const textarea = document.createElement('textarea');
    textarea.classList.add('form-control');
    textarea.name = `${item.id}Damage`;
    textarea.rows = 1;

    col2.appendChild(label2);
    col2.appendChild(textarea);

    row.appendChild(col1);
    row.appendChild(col2);
    detailsDiv.appendChild(row);
    topic.appendChild(formCheckDiv);
    topic.appendChild(detailsDiv);
}

function toggleDetails(itemId) {
    const detailsDiv = document.getElementById(`${itemId}Details`);
    const checkbox = document.getElementById(itemId);

    // Show/hide the main details section
    if (checkbox.checked) {
        detailsDiv.style.display = 'block'; // Show when checked
    } else {
        detailsDiv.style.display = 'none'; // Hide when unchecked
    }

    // Special handling for "other" input
    if (itemId.includes('other')) {
        const otherInputDiv = detailsDiv.querySelector('[data-other-input="true"]');
        if (otherInputDiv) {
            otherInputDiv.style.display = checkbox.checked ? 'block' : 'none';
        }
    }
}

// Usage for creating elements for topic1, topic2, and topic3
items.forEach(item => createFormElement(item, 'topic1'));
items2.forEach(item => createFormElement(item, 'topic2'));
items3.forEach(item => createFormElement(item, 'topic3'));


document.getElementById('addReportForm').addEventListener('submit', function(event) {
    event.preventDefault(); // ป้องกันการส่งฟอร์มตามปกติ

    // เก็บข้อมูลจากฟอร์ม
    const formData = new FormData(this);
    const data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });

    // ส่งข้อมูลไปยังเซิร์ฟเวอร์โดยใช้ fetch
    fetch('api/insert_report_repair.php', {
        method: 'POST',
        body: new URLSearchParams(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            Swal.fire({
                title: 'สำเร็จ!',
                text: 'เพิ่มข้อมูลรายงานสำเร็จ!',
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

    const teachId = <?php echo json_encode($userData['Teach_id']); ?>; // Encode Teach_id for safety

    function formatThaiDate(dateString) {
        // สร้าง Date object จาก string
        const date = new Date(dateString);

        // ตรวจสอบว่า date ถูกต้อง
        if (isNaN(date)) {
            return 'วันที่ไม่ถูกต้อง';
        }

        const day = date.getDate(); // วันที่
        const month = date.toLocaleString('th-TH', { month: 'long' }); // เดือนภาษาไทย
        const year = date.getFullYear() + 543; // เพิ่ม 543 เพื่อแปลงเป็น พ.ศ.

        return `${day} ${month} ${year}`; // รูปแบบ "วันที่ เดือน ปี"
    }

    function loadTable() {
    $.ajax({
        url: 'api/fet_report_repair.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#record_table').DataTable().clear().destroy(); // Clear and destroy the existing table
            $('#record_table tbody').empty();

            if (response.length === 0) {
                $('#record_table tbody').append('<tr><td colspan="6" class="text-center">ไม่พบข้อมูล</td></tr>');
            } else {
                $.each(response, function(index, data) {
                    var item = data.report; // ใช้ข้อมูลในฟิลด์ 'report'
                    var user = data.user[0]; // ดึงข้อมูลผู้ใช้จาก 'user'

                    var status = Number(item.status);
                    var statusText;
                    switch (status) {
                        case 0:
                            statusText = '⌛รอเจ้าหน้าที่ตรวจสอบ';
                            break;
                        case 1:
                            statusText = '✔️รับเรื่องแล้วกำลังกำลังดำเนินการ';
                            break;
                        case 2:
                            statusText = '⌛รอการสั่งซื้ออุปกรณ์/วัสดุ';
                            break;
                        case 3:
                            statusText = '✅ดำเนินการแล้วเสร็จ✅';
                            break;
                        default:
                            statusText = '❌สถานะไม่ระบุ';
                            break;
                    }

                    var formattedDate = formatThaiDate(item.AddDate);

                    var row = '<tr class="text-center">' +
                        '<td>' + (index + 1) + '</td>' +
                        '<td>' + formattedDate + '</td>' +
                        '<td>' + item.AddLocation + '</td>' +
                        '<td>' + item.term + '</td>' +
                        '<td>' + item.pee + '</td>' +
                        '<td>' + user.Teach_name + '</td>' +
                        '<td>' + user.Teach_phone + '</td>' +
                        '<td>' + statusText + '</td>' +
                        '<td>' +
                            '<button class="btn btn-info btn-sm detail-btn my-2" onclick="showDetail(' + item.id + ')">แสดงรายละเอียด</button>' +
                            '<button class="btn btn-warning btn-sm edit-status-btn my-2 ml-1" onclick="openEditModal(' + item.id + ', ' + item.status + ')">แก้ไขสถานะ</button>' +
                            '<button class="btn btn-danger btn-sm delete-btn my-2 ml-1" data-id="' + item.id + '">ลบ</button>' +
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


function openEditModal(id, currentStatus) {
    $('#editStatusModal #recordId').val(id); // Set ID in hidden input
    $('#editStatusModal #currentStatus').val(currentStatus); // Set current status in dropdown
    $('#editStatusModal').modal('show');
}

function saveStatus() {
    var id = $('#editStatusModal #recordId').val();
    var newStatus = $('#editStatusModal #currentStatus').val();

    $.ajax({
        url: 'api/update_status_repair.php',
        method: 'POST',
        data: { id: id, status: newStatus },
        success: function(response) {
            if (response.success) {
                Swal.fire('สำเร็จ', 'แก้ไขสถานะสำเร็จ', 'success');
                $('#editStatusModal').modal('hide');
                loadTable(); // Reload table
            } else {
                Swal.fire('ข้อผิดพลาด', response.message, 'error');
            }
        },
        error: function(xhr, status, error) {
            Swal.fire('ข้อผิดพลาด', 'ไม่สามารถอัปเดตสถานะได้', 'error');
        }
    });
}

loadTable();


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
                fetch('api/del_report_repair.php', {
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

    function showDetail(id) {
        $.ajax({
            url: 'api/fetch_report_detail.php',
            method: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    const report = response.report;
                    var formattedDate = formatThaiDate(report.AddDate);
                    let detailContent = `
                        <p><strong>วันที่:</strong> ${formattedDate || '-'}</p>
                        <p><strong>สถานที่:</strong> ${report.AddLocation || '-'}</p>
                        <p><strong>ภาคเรียน:</strong> ${report.term || '-'} <strong>ปีการศึกษา:</strong> ${report.pee || '-'}</p>
                        <br>
                        <p><strong>มีทรัพย์สินชำรุด/เสียหาย ดังนี้</strong><p>
                    `;

                    // รายละเอียดกลุ่มอุปกรณ์
                    const groupedFields = [
                        { label: 'ประตู', countKey: 'doorCount', damageKey: 'doorDamage', unit: 'บาน' },
                        { label: 'หน้าต่าง', countKey: 'windowCount', damageKey: 'windowDamage', unit: 'บาน' },
                        { label: 'โต๊ะนักเรียน', countKey: 'tablestCount', damageKey: 'tablestDamage', unit: 'ตัว' },
                        { label: 'เก้าอี้นักเรียน', countKey: 'chairstCount', damageKey: 'chairstDamage', unit: 'ตัว' },
                        { label: 'โต๊ะอาจารย์', countKey: 'tabletaCount', damageKey: 'tabletaDamage', unit: 'ตัว' },
                        { label: 'เก้าอี้อาจารย์', countKey: 'chairtaCount', damageKey: 'chairtaDamage', unit: 'ตัว' },
                        { label: 'ทีวี', countKey: 'tvCount', damageKey: 'tvDamage', unit: 'เครื่อง' },
                        { label: 'อุปกรณ์เสียง', countKey: 'audioCount', damageKey: 'audioDamage', unit: 'ชุด' },
                        { label: 'HDMI', countKey: 'hdmiCount', damageKey: 'hdmiDamage', unit: 'เส้น' },
                        { label: 'โปรเจคเตอร์', countKey: 'projectorCount', damageKey: 'projectorDamage', unit: 'เครื่อง' },
                        { label: 'พัดลม', countKey: 'fanCount', damageKey: 'fanDamage', unit: 'ตัว' },
                        { label: 'ไฟ', countKey: 'lightCount', damageKey: 'lightDamage', unit: 'ดวง' },
                        { label: 'แอร์', countKey: 'airCount', damageKey: 'airDamage', unit: 'เครื่อง' },
                        { label: 'สวิตช์ไฟ', countKey: 'swCount', damageKey: 'swDamage', unit: 'ตัว' },
                        { label: 'สวิตช์พัดลม', countKey: 'swfanCount', damageKey: 'swfanDamage', unit: 'ตัว' },
                        { label: 'ปลั๊กไฟ', countKey: 'plugCount', damageKey: 'plugDamage', unit: 'จุด' }
                    ];

                    groupedFields.forEach(group => {
                        const count = report[group.countKey];
                        const damage = report[group.damageKey];

                        if ((count && count != 0) || (damage && damage != 0)) {
                            detailContent += `
                                <p><strong>${group.label}:</strong> ${count || 0} ${group.unit} 
                                ${damage ? `, ความเสียหาย: ${damage}` : ''}</p>
                            `;
                        }
                    });

                    // รายละเอียดอื่นๆ
                    const additionalFields = [
                        { label: 'รายละเอียดอื่นๆ 1', key: 'other1Details' },
                        { label: 'จำนวน (อื่นๆ 1)', key: 'other1Count' },
                        { label: 'ความเสียหาย (อื่นๆ 1)', key: 'other1Damage' },
                        { label: 'รายละเอียดอื่นๆ 2', key: 'other2Details' },
                        { label: 'จำนวน (อื่นๆ 2)', key: 'other2Count' },
                        { label: 'ความเสียหาย (อื่นๆ 2)', key: 'other2Damage' },
                        { label: 'รายละเอียดอื่นๆ 3', key: 'other3Details' },
                        { label: 'จำนวน (อื่นๆ 3)', key: 'other3Count' },
                        { label: 'ความเสียหาย (อื่นๆ 3)', key: 'other3Damage' }
                    ];

                    additionalFields.forEach(field => {
                        if (report[field.key] && report[field.key] != 0) {
                            detailContent += `<p><strong>${field.label}:</strong> ${report[field.key]}</p>`;
                        }
                    });

                    // แสดงข้อมูลใน Modal
                    $('#detailModal .modal-body').html(detailContent);
                    $('#detailModal').modal('show');
                } else {
                    Swal.fire('ข้อผิดพลาด', response.message, 'error');
                }
            },
            error: function (xhr, status, error) {
                Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการดึงข้อมูล', 'error');
            }
        });
    }


    


</script>







<?php require_once('scirpt.php');?>
</body>
</html>
