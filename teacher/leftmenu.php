

<?php
function createNavItem($href, $iconClass, $text) {
    return '
    <li class="nav-item">
        <a href="' . htmlspecialchars($href) . '" class="nav-link">
            <i class="nav-icon fas ' . htmlspecialchars($iconClass) . '"></i>
            <p>' . htmlspecialchars($text) . '</p>
        </a>
    </li>';
}

function createNavItemName($avatar, $text) {
    return '
    <li class="nav-item">
        <div class="nav-link text-center">
            <img src="' . $avatar .'" alt="User Avatar" class="user-avatar rounded-full w-28 h-28 mx-auto">
        </div>
        <div class="nav-link text-center">
            <p class="text-white font-bold">'. $text . '</p>
        </div>
        <div class="nav-link text-center">
            <p class="text-white font-bold">ตำแหน่ง : ครู</p>
        </div>
    </li>';
}

echo createNavItemName(htmlspecialchars($setting->getImgProfile().$userData['Teach_photo']), htmlspecialchars($userData['Teach_name']));

// echo "<hr style='border: 1px solid #ffffff;'>";
echo "<br>";

echo createNavItem('index.php', 'fas fa-home', 'หน้าหลัก');
echo createNavItem('report_repair.php', 'fas fa-clipboard', 'แจ้งซ่อม');
// echo createNavItem('index.php', 'fas fa-pen', 'แจ้มซ่อม(คอมพิวเตอร์)');
// echo createNavItem('#', 'fas fa-bookmark', 'จองห้องประชุม');
echo createNavItem('bookmark_room.php', 'fas fa-bookmark', 'จองห้องประชุม');
// echo createNavItem('index.php', 'fas fa-pen', 'จองรถยนต์');
echo createNavItem('../logout.php', 'fas fa-sign-out-alt', 'ออกจากระบบ');
?>