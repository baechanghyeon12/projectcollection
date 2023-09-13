<?
session_start();
session_unset();
echo '<script>location.href="index.php"; alert("로그아웃 되었습니다.");</script>';
?>