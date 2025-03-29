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
$teacher_id = $userData['Teach_id'];


require_once('header.php');


?>

<body class="hold-transition sidebar-mini layout-fixed light-mode">
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
    <!-- Modal -->
    <div class="modal fade" id="addBookmarkModal" tabindex="-1" role="dialog" aria-labelledby="addBookmarkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document"> <!-- ใช้ modal-xl สำหรับขยาย modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBookmarkModalLabel">
                    ➕ บันทึกการจองห้องประชุม ➕
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addBookmarkForm" method="POST">
                        <div class="row">
                            
                            <div class="col-md-12">
                                <div class="form-group d-flex align-items-center">
                                    <div class="col-6 pr-2">
                                        <label class="text-center" for="BookmarkDate">วันที่จอง:</label>
                                        <input type="date" class="form-control text-center" id="BookmarkDate" name="BookmarkDate" readonly required>
                                    </div>

                                    <div class="col-6 pl-2">
                                        <label class="text-center" for="BookmarkName">ชื่อผู้จอง: </label>
                                        <input type="text" class="form-control text-center" id="BookmarkName" name="BookmarkName" value="<?=$userData['Teach_name']?>" readonly required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="text-center" for="BookmarkTel">เบอร์โทรศัพท์: </label>
                                    <input type="text" class="form-control text-center" id="BookmarkTel" name="BookmarkTel" value="<?=$userData['Teach_phone']?>"  required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="text-center" for="BookmarkLocation">ห้องที่จอง: </label>
                                    <select class="form-control text-center" id="BookmarkLocation" name="BookmarkLocation" required>
                                        <option value="">เลือกห้องประชุม</option>
                                        <option value="หอประชุมพิชัยดาบหัก">หอประชุมพิชัยดาบหัก</option>
                                        <option value="หอประชุมภักดิ์กมล">หอประชุมภักดิ์กมล</option>
                                        <option value="ห้องพิชยนุสรณ์">ห้องพิชยนุสรณ์</option>
                                        <option value="ห้องโสตทัศนศึกษา">ห้องโสตทัศนศึกษา</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group align-items-center">
                                <label class="text-center" for="timeSwitch">ช่วงเวลา:</label>
                                <div class="switch-container">
                                    <div class="switch-group">
                                        <label class="switch">
                                            <input type="radio" name="timeSwitch" id="morning" value="morning">
                                            <span class="slider"></span>
                                        </label>
                                        <span class="switch-label">เช้า (08:00 - 12:00)</span>
                                    </div>
                                    <div class="switch-group">
                                        <label class="switch">
                                            <input type="radio" name="timeSwitch" id="afternoon" value="afternoon">
                                            <span class="slider"></span>
                                        </label>
                                        <span class="switch-label">บ่าย (13:00 - 17:00)</span>
                                    </div>
                                    <div class="switch-group">
                                        <label class="switch">
                                            <input type="radio" name="timeSwitch" id="allDay" value="allDay">
                                            <span class="slider"></span>
                                        </label>
                                        <span class="switch-label">ทั้งวัน (08:00 - 17:00)</span>
                                    </div>
                                    <div class="switch-group">
                                        <label class="switch">
                                            <input type="radio" name="timeSwitch" id="custom" value="custom" checked>
                                            <span class="slider"></span>
                                        </label>
                                        <span class="switch-label">กำหนดเอง</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group d-flex align-items-center">
                                <div class="col-6 pr-2">
                                    <label class="text-center" for="BookmarkTimeStart">ตั้งเวลา:</label>
                                    <input type="time" class="form-control text-center" id="BookmarkTimeStart" name="BookmarkTimeStart" required>
                                </div>
                                <div class="col-6 pl-2">
                                    <label class="text-center" for="BookmarkTimeEnd">ถึงเวลา:</label>
                                    <input type="time" class="form-control text-center" id="BookmarkTimeEnd" name="BookmarkTimeEnd" required>
                                </div>
                            </div>
                        </div>




                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="text-center" for="BookmarkDetail">จุดประสงค์: </label>
                                <textarea name="BookmarkDetail" id="BookmarkDetail" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="text-center" for="BookmarkMedia">อุปกรณ์ที่จะใช้: </label>
                                <div class="d-flex flex-wrap">
                                    <div class="form-check mr-3">
                                        <input class="form-check-input" type="checkbox" name="BookmarkMedia[]" id="mic" value="ไมค์">
                                        <label class="form-check-label ml-2" for="mic">ไมค์</label>
                                    </div>
                                    <div class="form-check mr-3">
                                        <input class="form-check-input" type="checkbox" name="BookmarkMedia[]" id="projector" value="โปรเจคเตอร์">
                                        <label class="form-check-label ml-2" for="projector">โปรเจคเตอร์</label>
                                    </div>
                                    <div class="form-check mr-3">
                                        <input class="form-check-input" type="checkbox" name="BookmarkMedia[]" id="laptop" value="โน๊ตบุ๊ค">
                                        <label class="form-check-label ml-2" for="laptop">โน๊ตบุ๊ค</label>
                                    </div>
                                    <div class="form-check mr-3">
                                        <input class="form-check-input" type="checkbox" name="BookmarkMedia[]" id="air" value="แอร์">
                                        <label class="form-check-label ml-2" for="air">แอร์</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="otherMediaCheckbox" value="อื่นๆ">
                                        <label class="form-check-label ml-2" for="otherMediaCheckbox">อื่นๆ</label>
                                    </div>
                                </div>
                                <!-- Input field for "Other" -->
                                <div id="otherMediaInputContainer" style="display: none; margin-top: 10px;">
                                    <label for="otherMediaText">โปรดระบุอุปกรณ์เพิ่มเติม:</label>
                                    <input type="text" class="form-control" id="otherMediaText" name="BookmarkMedia[]" placeholder="ระบุอุปกรณ์ที่ต้องการ">
                                </div>
                            </div>
                        </div>





                        <div class="modal-footer justify-content-between">
                            <input type="hidden" name="teach_id" value="<?php echo $userData['Teach_id'];?>">
                            <input type="hidden" name="term" value="<?php echo $term;?>">
                            <input type="hidden" name="pee" value="<?php echo $pee;?>">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                            <button type="submit" class="btn btn-primary">บันทึกการจองห้องประชุม</button>
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

    <div class="modal fade" id="bookingsModal" tabindex="-1" role="dialog" aria-labelledby="bookingsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document"> <!-- ใช้ modal-xl สำหรับขยาย modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingsModalLabel">
                    รายการจองห้องประชุม
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="loader" class="text-center" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="bookingsTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">วันที่</th>
                                    <th class="text-center">ห้องที่จอง</th>
                                    <th class="text-center">เวลาเริ่ม</th>
                                    <th class="text-center">เวลาสิ้นสุด</th>
                                    <th class="text-center">จุดประสงค์</th>
                                    <th class="text-center">อุปกรณ์ที่ใช้</th>
                                    <th class="text-center">สถานะการจอง</th>
                                    <th class="text-center">การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- ข้อมูลจองจะถูกเติมที่นี่ผ่าน JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>


    <section class="content">
        <div class="container-fluid">
            <div class="col-12 mx-auto">
                <div class="callout callout-success text-center">
                    <h3 class="fw-bold bt-2">รายการบันทึกห้องประชุม <br></h3>
                    <hr>
                    <h5>
                        <p class="text-left text-bold text-danger">หมายเหตุ: คลิกที่วันที่เพื่อจองห้องประชุม </p>
                        <p class="text-left"><button id="showBookingsBtn" class="btn-lg btn-primary mb-3">แสดงรายการจองห้องประชุม</button></p>
                        
                    </h5>
                    <div class="form-group">
                        <label for="locationFilter">เลือกห้องประชุม:</label>
                        <select id="locationFilter" class="form-control text-center">
                            <option value="">ทั้งหมด</option>
                            <option value="หอประชุมพิชัยดาบหัก">หอประชุมพิชัยดาบหัก</option>
                            <option value="หอประชุมภักดิ์กมล">หอประชุมภักดิ์กมล</option>
                            <option value="ห้องพิชยนุสรณ์">ห้องพิชยนุสรณ์</option>
                            <option value="ห้องโสตทัศนศึกษา">ห้องโสตทัศนศึกษา</option>
                        </select>
                    </div>
                    
                    <div id="calendar"></div>


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

<!-- Include FullCalendar library -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/locales-all.min.js'></script>

<script>

document.addEventListener("DOMContentLoaded", () => {
    const morningStart = "08:00";
    const morningEnd = "12:00";
    const afternoonStart = "13:00";
    const afternoonEnd = "17:00";
    const allDayStart = "08:00";
    const allDayEnd = "17:00";

    const timeStart = document.getElementById("BookmarkTimeStart");
    const timeEnd = document.getElementById("BookmarkTimeEnd");
    const timeSwitches = document.querySelectorAll('input[name="timeSwitch"]');

    timeSwitches.forEach(switchButton => {
        switchButton.addEventListener("change", (event) => {
            switch (event.target.value) {
                case "morning":
                    timeStart.value = morningStart;
                    timeEnd.value = morningEnd;
                    break;
                case "afternoon":
                    timeStart.value = afternoonStart;
                    timeEnd.value = afternoonEnd;
                    break;
                case "allDay":
                    timeStart.value = allDayStart;
                    timeEnd.value = allDayEnd;
                    break;
                case "custom":
                    timeStart.value = "";
                    timeEnd.value = "";
                    break;
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const otherMediaCheckbox = document.getElementById("otherMediaCheckbox");
    const otherMediaInputContainer = document.getElementById("otherMediaInputContainer");

    otherMediaCheckbox.addEventListener("change", () => {
        if (otherMediaCheckbox.checked) {
            otherMediaInputContainer.style.display = "block";
        } else {
            otherMediaInputContainer.style.display = "none";
        }
    });
});



document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const locationFilter = document.getElementById('locationFilter');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'th',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: function(fetchInfo, successCallback, failureCallback) {
            const location = locationFilter.value;
            fetch(`api/fetch_calendar_booking.php?location=${location}`)
                .then(response => response.json())
                .then(data => successCallback(data))
                .catch(error => failureCallback(error));
        },
        eventDidMount: function (info) {
            // Assign colors based on location
            switch (info.event.extendedProps.location) {
                case 'หอประชุมพิชัยดาบหัก':
                    info.el.style.backgroundColor = '#ff5733'; // Red
                    info.el.style.color = '#FFFFFF'; // White text
                    break;
                case 'หอประชุมภักดิ์กมล':
                    info.el.style.backgroundColor = '#0687f1'; // Blue
                    info.el.style.color = '#FFFFFF'; // White text
                    break;
                case 'ห้องพิชยนุสรณ์':
                    info.el.style.backgroundColor = '#13c64c'; // Green
                    info.el.style.color = '#FFFFFF'; // White text
                    break;
                case 'ห้องโสตทัศนศึกษา':
                    info.el.style.backgroundColor = '#d83afe'; // Purple
                    info.el.style.color = '#FFFFFF'; // White text
                    break;
                default:
                    info.el.style.backgroundColor = '#ff0068'; // Gray
                    info.el.style.color = '#FFFFFF'; // White text
            }
        },
        dateClick: function (info) {
            const selectedDate = new Date(info.dateStr); // วันที่ที่เลือก
            const today = new Date(); // วันที่ปัจจุบัน
            today.setHours(0, 0, 0, 0); // ตั้งเวลาเป็น 00:00:00 เพื่อตรวจสอบเฉพาะวันที่

            // ตรวจสอบว่าวันที่ที่เลือกเป็นวันที่ปัจจุบันหรือในอนาคต
            if (selectedDate < today) {
                // แสดงการแจ้งเตือนด้วย SweetAlert2
                Swal.fire({
                    title: 'ไม่สามารถจองวันที่ย้อนหลังได้',
                    text: 'กรุณาเลือกวันที่ในปัจจุบันหรือในอนาคต',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                });
            } else {
                // เติมวันที่ใน input
                document.getElementById('BookmarkDate').value = info.dateStr;

                // เปิด Modal
                $('#addBookmarkModal').modal('show');
            }
        },
        eventClick: function (info) {
            const statusLabels = {
                0: '⌛รอเจ้าหน้าที่ตรวจสอบ',
                1: '✅จองเรียบร้อยแล้ว✅',
                2: '❌ ยกเลิกการจอง ❌'
            };

            const statusLabel = statusLabels[info.event.extendedProps.status] || 'Unknown';

            // Options for formatting in Thai
            const options = {
                weekday: 'long', // Full weekday name
                year: 'numeric', // Full year
                month: 'long',   // Full month name
                day: 'numeric',  // Day of the month
                hour: '2-digit', // Hour
                minute: '2-digit', // Minutes
                hourCycle: 'h24' // 24-hour format
            };

            // Format start and end times
            const startDateTime = info.event.start.toLocaleString('th-TH', options);
            const endDateTime = info.event.end ? info.event.end.toLocaleString('th-TH', options) : '-';

            Swal.fire({
                title: info.event.extendedProps.location,
                html: `
                    <p><strong>เริ่มเวลา:</strong> ${startDateTime} น.</p>
                    <p><strong>สิ้นสุดเวลา:</strong> ${endDateTime} น.</p>
                    <p><strong>สถานที่:</strong> ${info.event.extendedProps.location}</p>
                    <p><strong>จุดประสงค์:</strong> ${info.event.extendedProps.purpose}</p>
                    <p><strong>อุปกรณ์ที่ใช้:</strong> ${info.event.extendedProps.media}</p>
                    <p><strong>ชื่อผู้จอง:</strong> ${info.event.extendedProps.name}</p>
                    <p><strong>โทรศัพท์:</strong> ${info.event.extendedProps.phone}</p>
                    <p><strong>สถานะการจอง:</strong> ${statusLabel}</p>
                `,
                icon: 'info',
                width: '80%', // Set width to 80% of the viewport
                customClass: {
                    popup: 'swal-xl' // Apply a custom CSS class
                }
            });
        }

    });

    calendar.render();

    locationFilter.addEventListener('change', function() {
        calendar.refetchEvents();
    });

    document.getElementById('showBookingsBtn').addEventListener('click', function () {
    const loader = document.getElementById('loader');

    loader.style.display = 'block'; // Show loading
    fetch('api/fetch_bookings.php')
        .then(response => response.json())
        .then(data => {
            loader.style.display = 'none'; // Hide loading
            const tableBody = document.querySelector('#bookingsTable tbody');
            tableBody.innerHTML = '';

            data.forEach((booking, index) => { // Use index from forEach
                const row = document.createElement('tr');
                const statusLabels = {
                    0: '⌛ รอ',
                    1: '✅ ยืนยัน',
                    2: '❌ ยกเลิก'
                };
                const statusLabel = statusLabels[booking.status] || 'Unknown';
                row.innerHTML = `
                    <td class="text-center">${index + 1}</td> <!-- Row number -->
                    <td class="text-center">${booking.date}</td>
                    <td class="text-center">${booking.location}</td>
                    <td class="text-center">${booking.start_time}</td>
                    <td class="text-center">${booking.end_time}</td>
                    <td class="text-center">${booking.purpose}</td>
                    <td class="text-center">${booking.media}</td>
                    <td class="text-center">${statusLabel}</td> <!-- Fixed -->
                    <td class="text-center">
                        <button class="btn btn-warning btn-sm edit-booking" data-id="${booking.id}" data-status="${booking.status}">แก้ไขสถานะ</button>
                        <button class="btn btn-danger mx-2 mt-2 btn-sm delete-booking" data-id="${booking.id}">ลบ</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            $('#bookingsModal').modal('show');
        })
        .catch(error => {
            loader.style.display = 'none'; // Hide loading on error
            console.error('Error fetching bookings:', error);
            alert('Failed to load bookings.');
        });
});




    document.querySelector('#bookingsTable').addEventListener('click', function(e) {
        if (e.target.classList.contains('delete-booking')) {
            const bookingId = e.target.getAttribute('data-id');

            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: 'คุณต้องการลบการจองนี้หรือไม่? การดำเนินการนี้ไม่สามารถย้อนกลับได้!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`api/delete_booking.php?id=${bookingId}`, {
                        method: 'DELETE', // Ensure DELETE method is used
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ id: bookingId }) // Send the ID in the request body
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            e.target.closest('tr').remove();
                            Swal.fire('ลบสำเร็จ!', data.message, 'success');
                        } else {
                            Swal.fire('ข้อผิดพลาด', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('ข้อผิดพลาด', 'ไม่สามารถลบการจองได้', 'error');
                    });
                }
            });
        } else if (e.target.classList.contains('edit-booking')) {
            const bookingId = e.target.getAttribute('data-id');
            const currentStatus = e.target.getAttribute('data-status');

            Swal.fire({
                title: '<h3 style="text-align: center;">เปลี่ยนสถานะการจอง</h3>',
                input: 'select',
                inputOptions: {
                    0: '⌛รอเจ้าหน้าที่ตรวจสอบ',
                    1: '✅ ยืนยันการจอง ✅',
                    2: '❌ ยกเลิกการจอง ❌'
                },
                inputPlaceholder: 'เลือกสถานะ',
                inputValue: currentStatus,
                showCancelButton: true,
                confirmButtonText: 'บันทึก',
                cancelButtonText: 'ยกเลิก',
                inputValidator: (value) => {
                    return new Promise((resolve) => {
                        if (value === '') {
                            resolve('กรุณาเลือกสถานะ');
                        } else {
                            resolve();
                        }
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`api/update_booking_status.php`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ id: bookingId, status: result.value })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire('สำเร็จ!', data.message, 'success').then(() => {
                                location.reload(); // Reload the page to reflect changes
                            });
                        } else {
                            Swal.fire('ข้อผิดพลาด', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('ข้อผิดพลาด', 'ไม่สามารถเปลี่ยนสถานะการจองได้', 'error');
                    });
                }
            });
        }
    });
});


document.getElementById('addBookmarkForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('api/insert_booking.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Success', data.message, 'success').then(() => {
                $('#addBookmarkModal').modal('hide'); // Hide the modal
                document.getElementById('addBookmarkForm').reset(); // Reset the form
                location.reload(); // Reload calendar or page
            });
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'An error occurred while processing your request.', 'error');
    });
});

</script>



<?php require_once('script.php');?>
</body>
</html>
