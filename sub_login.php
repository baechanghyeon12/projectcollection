<?php
        session_start();
define( "DB_CON",$_SERVER["DOCUMENT_ROOT"] );
define( "URL",DB_CON."/common/db_common.php" );
include_once(URL);



// if( $_POST['u_id'] != "" && $_POST['u_pw'] != "" )
// {
//   $u_id = $_POST['u_id'];
//   $u_pw = $_POST['u_pw'];
//   header('location: index.php');
// } else {
//   echo '에러입니다.';
// }
$s_method = $_SERVER['REQUEST_METHOD'];
if( $s_method == 'POST'){
  if( $_POST['u_id'] != "" && $_POST['u_pw'] != "" )
  {
    $u_id = $_POST['u_id'];
    $u_pw = $_POST['u_pw'];
    $u_info = get_u_id($u_id);
    var_dump($u_info);
    if(!empty($u_info)){
      if($u_info['u_pw'] == $u_pw){
        $_SESSION['u_id'] = $u_id;
        echo '<script>location.href="index.php"; alert("로그인 성공");</script>';
        // exit;
      } else {
        echo '<script>location.href="login.php"; alert("일치하지 않는 정보입니다.");</script>';
      }
    }else{
      echo '<script>alert("없는 정보입니다.")</script>';
    }
  } else {
    // header('location:login.php');
    echo '<script>location.href="login.php"; alert("정보를 입력해 주세요.");</script>';
  }
}
?>