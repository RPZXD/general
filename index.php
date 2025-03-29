<?php 
require_once('config/Database.php');
require_once('class/Report_repair.php');
require_once('class/Booking.php');

$connectDB = new Database_General();
$db = $connectDB->getConnection();

// Initialize UserLogin class
$report = new Report_repair($db);
                
// Initialize UserLogin class
$booking = new Booking($db);


require_once('header.php');
require_once('config/Setting.php');
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
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <section class="content">

    <div class="container-fluid">
        <h3 class="text-dark">รายการแจ้งซ่อมแซมทรัพย์สินประจำห้องเรียนและห้องปฏิบัติการชำรุด/เสียหาย</h3>
            <div class="row">
                <?php
                function createSmallBox($number, $description, $bgClass, $iconClass) {
                    return "
                    <div class=\"col-lg-2 col-sm-6 col-md-6\">
                        <!-- small box -->
                        <div class=\"small-box $bgClass\">
                            <div class=\"inner\">
                                <h3>$number</h3>
                                <p>$description</p>
                            </div>
                            <div class=\"icon\">
                                <i class=\"$iconClass\"></i>
                            </div>
                        </div>
                    </div>";
                }




                $reportCountAll = $report->getReport();
                $report_wait = $report->countReports(0);
                $report_processing = $report->countReports(1);
                $report_waiting_materials = $report->countReports(2);
                $report_completed = $report->countReports(3);
                
                // Example usage of createSmallBox function
                echo createSmallBox($reportCountAll, 'รายการแจ้งซ่อมทั้งหมด', 'bg-info', 'fas fa-clipboard');
                echo createSmallBox($report_wait, 'รอเจ้าหน้าที่ตรวจสอบ', 'bg-warning', 'fas fa-exclamation-circle');
                echo createSmallBox($report_processing, 'กำลังดำเนินการ', 'bg-primary', 'fas fa-cogs');
                echo createSmallBox($report_waiting_materials, 'รอการสั่งซื้ออุปกรณ์/วัสดุ', 'bg-danger', 'fas fa-shopping-cart');
                echo createSmallBox($report_completed, 'ดำเนินการแล้วเสร็จ', 'bg-success', 'fas fa-check-circle');
                ?>
            </div>
        <h3 class="text-dark">รายการแจ้งขอใช้ห้องประชุม</h3>
            <div class="row">
                <?php


                $bookingCountAll = count($booking->getbooking());
                $booking_Pending = $booking->countbookings(0);
                $booking_Confirmed = $booking->countbookings(1);
                $booking_Cancelled	= $booking->countbookings(2);
                
                // Example usage of createSmallBox function
                echo createSmallBox($bookingCountAll, 'รายการแจ้งขอใช้ห้องประชุมทั้งหมด', 'bg-info', 'fas fa-clipboard');
                echo createSmallBox($booking_Pending, 'รอการยืนยัน', 'bg-warning', 'fas fa-exclamation-circle');
                echo createSmallBox($booking_Confirmed, 'ยืนยันแล้ว', 'bg-primary', 'fas fa-check-circle');
                echo createSmallBox($booking_Cancelled, 'ยกเลิกแล้ว', 'bg-danger', 'fas fa-times-circle');
                ?>
            </div>

            <!-- Donut Chart for Report Repair -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">รายการแจ้งซ่อมแซมทรัพย์สินประจำห้องเรียนและห้องปฏิบัติการชำรุด/เสียหาย</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">สถานะการจองห้องประชุม</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="donutChartBooking" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

    </div><!-- /.container-fluid -->
        
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
    <?php require_once('footer.php');?>
</div>
<!-- ./wrapper -->


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('donutChart').getContext('2d');
    var donutChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['รอเจ้าหน้าที่ตรวจสอบ', 'กำลังดำเนินการ', 'รอการสั่งซื้ออุปกรณ์/วัสดุ', 'ดำเนินการแล้วเสร็จ'],
            datasets: [{
                data: [<?php echo $report_wait; ?>, <?php echo $report_processing; ?>, <?php echo $report_waiting_materials; ?>, <?php echo $report_completed; ?>],
                backgroundColor: ['#f39c12', '#007bff', '#dc3545', '#28a745'],
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
        }
    });

    var ctxBooking = document.getElementById('donutChartBooking').getContext('2d');
    var donutChartBooking = new Chart(ctxBooking, {
        type: 'doughnut',
        data: {
            labels: ['รอการยืนยัน', 'ยืนยันแล้ว', 'ยกเลิกแล้ว'],
            datasets: [{
                data: [<?php echo $booking_Pending; ?>, <?php echo $booking_Confirmed; ?>, <?php echo $booking_Cancelled; ?>],
                backgroundColor: ['#f39c12', '#007bff', '#dc3545'],
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
        }
    });
  });
</script>

<?php require_once('script.php');?>
</body>
</html>
