<?php
    //---------------------------------
    //함수명    : db_conn
    //기능      : php를 db에 연결
    //파라미터  : array(속성)   $param_conn(파라미터 명)
    //---------------------------------

    function db_conn( &$param_conn )
    {
        $host = "localhost";
        $user = "bae";
        $pass = "qockdgus12@";
        $charset = "utf8mb4";
        $db_name = "minitwo";
        $dns = "mysql:host=".$host.";dbname=".$db_name.";charset=".$charset;
        $pdo_option =
            array(
                PDO::ATTR_EMULATE_PREPARES      => false
                ,PDO::ATTR_ERRMODE              => PDO::ERRMODE_EXCEPTION
                ,PDO::ATTR_DEFAULT_FETCH_MODE   => PDO::FETCH_ASSOC
            );

        try
        {
            $param_conn = new PDO( $dns, $user, $pass, $pdo_option );
        }
        catch (Exception $e)
        {
            $param_conn = null;
            throw new Exception( $e->getMessage() );
        }
    }

    // ---------------------------------
    // 함수명	: delete_list
    // 기능		: DB delete
    // 파라미터	: Array       &$param_no
    // 리턴값	: 없음
    // ---------------------------------
    function delete_list(&$param_no)
    {
        $sql =
            " DELETE FROM "
            ." to_do_list_info "
            ." WHERE "
            ." list_no = :list_no"
            ;
        $arr_prepare =
                array(
                    ":list_no" => $param_no["list_no"]
                );
        $conn=null;

        try
        {
            db_conn($conn);
            $conn->beginTransaction();
            $stmt = $conn->prepare($sql);
            $stmt->execute($arr_prepare);
            $conn->commit();
        }
        catch (Exception $e)
        {
            $conn->rollback();
            return $e->getMessage();
        }
        finally
        {
            $conn = null;
        }
    }
    
    // ---------------------------------
    // 함수명	: select_list_no
    // 기능		: list_no에 해당하는 값들 출력
    // 파라미터	: Array       &$param_no
    // 리턴값	: Array/String  $result[0]/ERRMSG
    // ---------------------------------
    function get_u_id( &$param_no ) // 0419 edit 함수명
    {
        $sql =
            " SELECT "
            ." * " // 0420 edit 값 전체 받아오게 변경
            ." FROM "
            ."  user_info "
            ." WHERE "
            ."  u_id = :u_id "
            ;

        $arr_prepare =
            array(
                ":u_id"   => $param_no
            );

        $conn = null;

        try
        {
            db_conn( $conn );
            $stmt = $conn->prepare( $sql );
            $stmt->execute( $arr_prepare );
            $result = $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
        finally
        {
            $conn = null;
        }
        return $result[0];
    }

    // ---------------------------------
    // 함수명	: update_list
    // 기능		: DB update
    // 파라미터	: Array     &$param_arr
    // 리턴값	: STRING    ERRMSG
    // ---------------------------------
    
    function update_list( &$param_arr )
    {
        $sql = 
            " UPDATE "
            ." to_do_list_info "
            ." SET "
            ." list_title = :list_title "
            ." ,list_memo = :list_memo "
            ." ,list_comp_flg = :list_comp_flg "
            ." ,list_start_time = :list_start_time "
            ." ,list_start_minute = :list_start_minute "
            ." ,list_end_time = :list_end_time "
            ." ,list_end_minute = :list_end_minute "
            ." WHERE "
            ." list_no = :list_no "
            ;

        $arr_prepare = 
            array(
                ":list_title"           => $param_arr["list_title"]
                ,":list_memo"           => $param_arr["list_memo"]
                ,":list_comp_flg"       => $param_arr["list_comp_flg"]
                ,":list_start_time"     => $param_arr["list_start_time"]
                ,":list_start_minute"   => $param_arr["list_start_minute"]
                ,":list_end_time"       => $param_arr["list_end_time"]
                ,":list_end_minute"     => $param_arr["list_end_minute"]
                ,":list_no"             => $param_arr["list_no"]
            );

        $conn = null;
        try
        {
            db_conn( $conn );
            $conn->beginTransaction();
            $stmt = $conn->prepare( $sql );
            $stmt->execute( $arr_prepare );
            $conn->commit();
        }
        catch( Exception $e )
        {
            $conn->rollback();
            return $e->getMessage();
        }
        finally
        {
            $conn = null;
        }
    }

// ---------------------------------
    // 함수명	: select_list_info
    // 기능		: 페이지에 표시할 리스트 불러옴
    // 파라미터	: 없음
    // 리턴값	: Array/String  $result/ERRMSG
    // ---------------------------------

    function select_list_info(&$param_arr)
    {
        $sql =
		" SELECT "
		." 	list_no "
		." 	,list_title "
		." 	,list_comp_flg "
		." 	,list_start_time "
		." 	,list_start_minute "
		." 	,list_end_time "
		." 	,list_end_minute "
		." FROM "
		." 	to_do_list_info "
        ." ORDER by list_comp_flg, list_no DESC"
        ." LIMIT :limit_num OFFSET :offset "
		;
    
        $arr_prepare =
		array(
			":limit_num"	=> $param_arr["limit_num"]
			,":offset"		=> $param_arr["offset"]
		);

	$conn = null;
	try
	{
		db_conn( $conn );
		$stmt = $conn->prepare( $sql );
		$stmt->execute($arr_prepare);
		$result = $stmt->fetchAll();
	}
	catch( Exception $e )
	{
		return $e->getMessage();
	}
	finally
	{
		$conn = null;
	}
	return $result;
}
    // ---------------------------------
    // 함수명	: select_goal_info
    // 기능		: 페이지에 표시할 목표 불러옴
    // 파라미터	: 없음
    // 리턴값	: array/STRING      $result/ERRMSG
    // ---------------------------------

    function select_goal_info()
    {
            $sql =
            " SELECT "
            ." 	goal_title "
            ." 	,goal_date "
            ." FROM "
            ." 	goal_info "
            ;

        $conn = null;
        try
        {
            db_conn( $conn );
            $stmt = $conn->prepare( $sql );
            $stmt->execute();
            $result = $stmt->fetchAll();
        }
        catch( Exception $e )
        {
            return $e->getMessage();
        }
        finally
        {
            $conn = null;
        }

        if ( empty($result) )
        {
            $result = "";
        }
        else {
            $result = $result[0];
        }
        return $result;
    }

        
    //------------------------------------
    // 함수명       : insert_to_do_list
    // 기능         : 리스트의 값 추가
    // 파라미터     : array       $param_no
    // 리턴값       : INT/STRING    $result_cnt/ERRMSG
    //------------------------------------
    function insert_to_do_list_info( &$param_arr )
    {
        $sql =
            " INSERT INTO "
            ." to_do_list_info( "
            ." list_title "
            ." , list_memo "
            ." , list_start_time "
            ." , list_start_minute "
            ." , list_end_time "
            ." , list_end_minute "
            ." ) "
            ." VALUES( "
            ." :list_title "
            ." , :list_memo "
            ." , :list_start_time "
            ." , :list_start_minute "
            ." , :list_end_time "
            ." , :list_end_minute "
            ." ) "
            ;

        $arr_prepare =
        array(
            ":list_title" => $param_arr["list_title"]
            , ":list_memo" => $param_arr["list_memo"]
            , ":list_start_time" => $param_arr["list_start_time"]
            , ":list_start_minute" => $param_arr["list_start_minute"]
            , ":list_end_time" => $param_arr["list_end_time"]
            , ":list_end_minute" => $param_arr["list_end_minute"]
        );

        $conn = null;
        try
        {
            db_conn( $conn );
            $conn->beginTransaction();
            $stmt = $conn->prepare( $sql );
            $stmt->execute( $arr_prepare );
            $result_cnt = $stmt->rowCount();
            if($result_cnt !== 1)
            {
                throw new Exception("카운트 이상");
            }
            $conn->commit();
        }
        catch ( Exception $e)
        {
            $conn->rollback();
            return $e->getMessage();
        }
        finally
        {
            $conn = null;
        }
        return $result_cnt;
    }

    //------------------------------------
    // 함수명       : select_to_do_list_limit
    // 기능         : 리스트의 값중 상단 1개만 불러오기 위해 사용
    // 파라미터     : 없음
    // 리턴값       : array
    //------------------------------------
    function select_to_do_list_limit ( )
    {
        $sql = 
            " SELECT "
            ." list_no "
            ." from "
            ." to_do_list_info "
            ." order by "
            ." list_no "
            ." desc "
            ." limit "
            ." 1 "
            ;

        $conn = null;
        try
        {
            db_conn( $conn );
            $stmt = $conn->prepare( $sql );
            $stmt->execute();
            $result = $stmt->fetchAll();
        }
        catch( Exception $e )
        {
            return $e->getMessage();
        }
        finally
        {
            $conn = null;
        }
        return $result[0];
    }

    //--------------------------------------
    // 함수명   : select_list_all_cnt
    // 기능     : 모든 리스트의 갯수를 센다
    // 파라미터	: 없음
    // 리턴값	: array/STRING      $result[0]["cnt"]/ERRMSG
    //--------------------------------------
    function select_list_all_cnt()
    {
        $sql =
            " SELECT "
            ."  COUNT(*) cnt "
            ." FROM "
            ."  to_do_list_info "
            ;
        
        $conn = null;

        try
        {
            db_conn( $conn );
            $stmt = $conn->query( $sql );
            $result = $stmt->fetchAll();
        }
        catch( Exception $e )
        {
            return $e->getMessage();
        }
        finally
        {
            $conn = null;
        }

        return $result[0]["cnt"];
    }

    // //--------------------------------------
    // // 함수명   : select_list_comp_cnt
    // // 기능     : 수행완료한 리스트의 갯수를 센다
    // // 파라미터	: 없음
    // // 리턴값	: array/STRING      $result[0]["cnt"]/ERRMSG
    // //--------------------------------------
    // function select_list_comp_cnt()
    // {
    //     $sql =
    //         " SELECT "
    //         ."  COUNT(*) cnt "
    //         ." FROM "
    //         ."  to_do_list_info "
    //         ." WHERE "
    //         ."  list_comp_flg = '1' "
    //         ;
        
    //     $conn = null;

    //     try
    //     {
    //         db_conn( $conn );
    //         $stmt = $conn->query( $sql );
    //         $result = $stmt->fetchAll();
    //     }
    //     catch( Exception $e )
    //     {
    //         return $e->getMessage();
    //     }
    //     finally
    //     {
    //         $conn = null;
    //     }

    //     return $result[0]["cnt"];
    // }

    //  0427 del

    // // ---------------------------------
    // // 함수명	: comp_percent
    // // 기능		: select_list_comp_cnt함수와 select_list_all_cnt 함수를 이용하여 리스트 달성률 계산하는 함수
    // // 파라미터	: Array     &$param_arr
    // // 리턴값	: INT       $result
    // // ---------------------------------
    // function comp_percent()
    // {
    //     $result = select_list_comp_cnt() / select_list_all_cnt() * 100 ;
    //     $result = intval($result);
    //     return $result;
    // }

    //  0427 del

    // ---------------------------------
    // 함수명	: comp_percent
    // 기능		: 달성도 계산하는 함수
    // 파라미터	: 없음
    // 리턴값	: INT/STRING       $result/ERRMSG
    // ---------------------------------
    function comp_percent()
    {
        $sql =
            " SELECT "
            ."  FLOOR( SUM( CASE WHEN list_comp_flg = 1 THEN 1 ELSE 0 END ) / COUNT(*) * 100 ) comp_per "
            ." FROM "
            ."  to_do_list_info ";

        $conn = null;

        try
        {
            db_conn( $conn );
            $stmt = $conn->query( $sql );
            $result = $stmt->fetchAll();
        }
        catch( Exception $e )
        {
            return $e->getMessage();
        }
        finally
        {
            $conn = null;
        }

        return $result[0]["comp_per"];
    }

    // ---------------------------------
    // 함수명	: comp_check
    // 기능		: select_list_comp_cnt함수와 select_list_all_cnt 함수를 이용하여 리스트 달성률 계산하는 함수
    // 파라미터	: Array     &$param_arr
    // 리턴값	: INT       $result
    // ---------------------------------
    // function comp_check( &$param_num )
    // {
    //     $sql =
    //         " SELECT "
    //         ."  COUNT(*) cnt "
    //         ." FROM "
    //         ."  to_do_list_info "
    //         ." WHERE "
    //         ."  list_comp_flg = :list_comp_flg "
    //         ;
    //     $arr_prepare =
    //         array(
    //             "list_comp_flg"     => $param_num
    //         );
    //     $conn = null;

    //         try
    //         {
    //             db_conn( $conn );
    //             $stmt = $conn->prepare( $sql );
    //             $stmt->execute( $arr_prepare );
    //             $result = $stmt->fetchAll();
    //         }
    //         catch( Exception $e )
    //         {
    //             return $e->getMessage();
    //         }
    //         finally
    //         {
    //             $conn = null;
    //         }
    
    //         return $result[0]["cnt"];
    // }

    // ---------------------------------
    // 함수명	: update_goal
    // 기능		: DB update
    // 파라미터	: Array     &$param_arr
    // 리턴값	: 없음
    // ---------------------------------
    
    function update_goal( &$param_arr )
    {
        $sql = 
            " UPDATE "
            ." goal_info "
            ." SET "
            ." goal_title = :goal_title "
            ." ,goal_date = :goal_date "
            ;

        $arr_prepare = 
            array(
                ":goal_title" => $param_arr["goal_title"]
                ,":goal_date" => $param_arr["goal_date"]
            );

        $conn = null;
        try
        {
            db_conn( $conn );
            $conn->beginTransaction();
            $stmt = $conn->prepare( $sql );
            $stmt->execute( $arr_prepare );
            $conn->commit();
        }
        catch( Exception $e )
        {
            $conn->rollback();
            return $e->getMessage();
        }
        finally
        {
            $conn = null;
        }
    }

// ---------------------------------
// 함수명	: select_list_info_paging
// 기능		: list 페이징하는 함수
// 파라미터	: Array		&$param_arr
// 리턴값	: Array		$result
// ---------------------------------
function select_list_info_paging( &$param_arr )
{
	$sql =
		" SELECT "
		." 	board_no "
		." 	,board_title "
		." 	,board_write_date "
		." FROM "
		." 	board_info "
		." WHERE "
		." 	board_del_flg = '0' "
		." ORDER BY "
		." 	board_no DESC "
		." LIMIT :limit_num OFFSET :offset "
		;
	
	$arr_prepare =
		array(
			":limit_num"	=> $param_arr["limit_num"]
			,":offset"		=> $param_arr["offset"]
		);

	$conn = null;
	try
	{
		db_conn( $conn );
		$stmt = $conn->prepare( $sql );
		$stmt->execute( $arr_prepare );
		$result = $stmt->fetchAll();
	}
	catch( Exception $e )
	{
		return $e->getMessage();
	}
	finally
	{
		$conn = null;
	}

	return $result;
}

    // ---------------------------------
    // 함수명	: select_profile_info
    // 기능		: 프로필 정보 불러오는 함수
    // 파라미터	: 없음
    // 리턴값	: Array/STRING		$result[0]/ERRMSG
    // ---------------------------------
    function select_profile_info()
    {
        $sql =
            " SELECT "
            ."  * "
            ." FROM "
            ."  profile_info "
            ;

            try
            {
                db_conn( $conn );
                $stmt = $conn->query( $sql );
                $result = $stmt->fetchAll();
            }
            catch( Exception $e )
            {
                return $e->getMessage();
            }
            finally
            {
                $conn = null;
            }
    
            return $result[0];
    }

    // ---------------------------------
    // 함수명	: update_profile_info
    // 기능		: 프로필 정보 업데이트하는 함수
    // 파라미터	: Array             $param_arr
    // 리턴값	: 없음
    // ---------------------------------
    function update_profile_info( &$param_arr )
    {
        $sql =
            " UPDATE "
            ."  profile_info "
            ." SET "
            ."  profile_name = :profile_name "
            ."  ,profile_img = :profile_img "
            ;
        
            $arr_prepare =
            array(
                ":profile_name"	=> $param_arr["profile_name"]
                ,":profile_img" => $param_arr["profile_img"]
            );
    
        $conn = null;
        try
        {
            db_conn( $conn );
            $conn->beginTransaction();
            $stmt = $conn->prepare( $sql );
            $stmt->execute( $arr_prepare );
            $conn->commit();
        }
        catch( Exception $e )
        {
            $conn->rollback();
            return $e->getMessage();
        }
        finally
        {
            $conn = null;
        }
    }

    // ---------------------------------
    // 함수명	: update_comp_flg
    // 기능		: 상세 페이지에서 리스트 수행 완료 / 취소 하는 함수
    // 파라미터	: Array             $param_arr
    // 리턴값	: 없음
    // ---------------------------------
    function update_comp_flg( &$param_arr ){
        $sql =
            " UPDATE "
            ."  to_do_list_info  "
            ." SET "
            ."  list_comp_flg = :list_comp_flg"
            ." WHERE "
            ."  list_no = :list_no "
            ;
        $arr_prepare =
            array(
                ":list_comp_flg"    => $param_arr["list_comp_flg"]
                ,":list_no"         => $param_arr["list_no"]
            );

            $conn = null;
            try
            {
                db_conn( $conn );
                $conn->beginTransaction();
                $stmt = $conn->prepare( $sql );
                $stmt->execute( $arr_prepare );
                $conn->commit();
            }
            catch( Exception $e )
            {
                $conn->rollback();
                return $e->getMessage();
            }
            finally
            {
                $conn = null;
            }
    }
?>