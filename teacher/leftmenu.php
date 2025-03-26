
<?php
function createNavItem($href, $iconClass, $text) {
    return '
    <li class="nav-item">
        <a href="' . htmlspecialchars($href) . '" class="nav-link ">
            <i class="nav-icon fas ' . htmlspecialchars($iconClass) . '"></i>
            <p>' . htmlspecialchars($text) . '</p>
        </a>
    </li>';
}

function createNavItemName($avatar, $text) {
    return '
    <li class="nav-item">
        <div  class="nav-link text-center">
            <img src="https://std.phichai.ac.th/teacher/uploads/phototeach/' . $avatar .'" alt="User Avatar" class="user-avatar img-circle elevation-2" style="width: 40px; height: 40px;">
        </div>
        <div  class="nav-link text-center">
            <p class="text-light text-wold">'. $text . '</p>
        </div>
        <div  class="nav-link text-center">
            <p class="text-light text-wold">ตำแหน่ง : ครู</p>
        </div>

    </li>';
}


echo createNavItemName(htmlspecialchars($userData['Teach_photo']), htmlspecialchars($userData['Teach_name']));

echo "<hr>";

echo createNavItem('index.php', 'fas fa-home', 'หน้าหลัก');
echo createNavItem('report_repair.php', 'fas fa-clipboard', 'แจ้งซ่อม');
// echo createNavItem('index.php', 'fas fa-pen', 'แจ้มซ่อม(คอมพิวเตอร์)');
// echo createNavItem('#', 'fas fa-bookmark', 'จองห้องประชุม');
echo createNavItem('bookmark_room.php', 'fas fa-bookmark', 'จองห้องประชุม');
// echo createNavItem('index.php', 'fas fa-pen', 'จองรถยนต์');
echo createNavItem('../logout.php', 'fas fa-sign-out-alt', 'ออกจากระบบ');
?>