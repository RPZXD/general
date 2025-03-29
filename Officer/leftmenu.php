

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
            <p class="text-white font-bold">ตำแหน่ง : เจ้าหน้าที่</p>
        </div>
    </li>';
}

function createNavSubMenu($iconClass, $text, $subItems) {
    $subMenu = '
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="nav-icon fas ' . htmlspecialchars($iconClass) . '"></i>
            <p>
                ' . htmlspecialchars($text) . '
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">';
    foreach ($subItems as $item) {
        $subMenu .= createNavItem($item['href'], $item['iconClass'], $item['text']);
    }
    $subMenu .= '</ul></li>';
    return $subMenu;
}

$subItems_Setting = [
    ['href' => 'setting_meetingroom.php', 'iconClass' => 'fa-user-plus', 'text' => 'ตั้งค่าห้องประชุม'],
    ['href' => 'setting_cars.php', 'iconClass' => 'fa-user-plus', 'text' => 'ตั้งค่ารถยนต์'],
    ['href' => 'setting_drivers.php', 'iconClass' => 'fa-user-plus', 'text' => 'ตั้งค่าคนขับรถ']
];

echo createNavItemName(htmlspecialchars($setting->getImgProfile().$userData['Teach_photo']), htmlspecialchars($userData['Teach_name']));

// echo "<hr style='border: 1px solid #ffffff;'>";
echo "<br>";

echo createNavItem('index.php', 'fas fa-home', 'หน้าหลัก');
echo createNavItem('report_repair.php', 'fas fa-clipboard', 'รายงานการแจ้งซ่อม');
// echo createNavItem('save_report.php', 'fas fa-pen-square', 'บันทึกการสอน');
// echo createNavItem('index.php', 'fas fa-pen', 'แจ้มซ่อม(คอมพิวเตอร์)');
// echo createNavItem('index.php', 'fas fa-pen', 'จองห้องประชุม');
echo createNavItem('bookmark_room.php', 'fas fa-home', 'รายการจองห้อง');
echo createNavSubMenu('fa-pen', 'ตั้งค่า', $subItems_Setting);
// echo createNavItem('index.php', 'fas fa-pen', 'จองรถยนต์');
echo createNavItem('../logout.php', 'fas fa-sign-out-alt', 'ออกจากระบบ');
?>