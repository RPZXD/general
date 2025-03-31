
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

echo createNavItem('index.php', 'fas fa-home', 'หน้าหลัก');
// echo createNavItem('report.php', 'fas fa-clipboard', 'รายงานการสอน');
// echo createNavItem('faq.php', 'fas fa-question', 'วิธีการใช้งาน');
// echo createNavItem('add_subject.php', 'fas fa-pen', 'จัดการรายวิชา');
echo createNavItem('login.php', 'fas fa-sign-in-alt', 'ลงชื่อเข้าสู่ระบบ');
?>