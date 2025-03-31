<?php 

session_start();


include_once("../config/Database.php");
include_once("../class/UserLogin.php");
include_once("../class/Utils.php");

// Initialize database connection
$connectDB = new Database("phichaia_student");
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

$teacher_id = $_SESSION['Officer_login'];

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

    <section class="content">
      <div class="container-fluid">
              <div class="row">
                <div class="w-full">
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 text-center">
                            <h4 class="text-lg font-semibold">ยินดีต้อนรับเข้าสู่<?php echo $setting->getPageTitle() ?></h4>
                        </div>
                    </div>
              </div>
              <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="flex flex-wrap mt-4">
                        <div class="w-full md:w-1/3 px-2 mb-4">
                          <!-- small box -->  
                          <div class="bg-blue-500 text-white p-4 rounded-lg shadow">
                            <p class="mt-2">ระบบการแจ้งซ่อม</p>
                          </div>
                        </div>
                        <!-- ./col -->
                        <div class="w-full md:w-1/3 px-2 mb-4">
                          <!-- small box -->
                          <div class="bg-pink-500 text-white p-4 rounded-lg shadow">
                            <p class="mt-2">ระบบจองห้องประชุม</p>
                          </div>
                        </div>
                        <div class="w-full md:w-1/3 px-2 mb-4">
                          <!-- small box -->
                          <div class="bg-green-500 text-white p-4 rounded-lg shadow">
                            <p class="mt-2">ระบบจองรถ</p>
                          </div>
                        </div>

                    
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



<?php require_once('script.php');?>
</body>
</html>
