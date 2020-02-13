<?
	/*********************************************************************************
	* 인증 성공시 웹 페이지 전환으로 호출되는 페이지이며 가맹점에서 구현해야하는 화면입니다.
	* 복호화 사용시 필수 seed.php 파일을 가맹점측 서버에 설치
	*********************************************************************************/

	include "inc/seed.php";		// 좌측의 경로는 가맹점 서버에 설치한 seed파일의 절대경로로 수정 필수
	
	/*****************************************************************
	* 함수명 : cipher 암호화 실행
	* 사용법 : cipher ("복호화할데이터", "키")
	*****************************************************************/
	
	function cipher($seedStr, $seedKey) {
		if( $seedStr == "" ) return "";
		return decodeString($seedStr, getKey($seedKey));
	}
	
	function getKey( $value ){
		$padding = "123456789123456789";
		$tmpKey = $value;
		$keyLength = strlen( $value );
		if( $keyLength < 16 ) $tmpKey = $tmpKey.substr($padding, 0, 16-$keyLength);
		else  $tmpKey = substr( $tmpKey, strlen( $tmpKey )-16,  strlen( $tmpKey ) );
		for($i = 0 ; $i < 16; $i++) {
			$result = $result.chr(ord( substr( $tmpKey, $i, 1 ))^($i+1));
		}
		return $result;
	}
?>
<?
	$Svcid      = $_POST["Svcid"     ]; //서비스아이디
	$Mobilid    = $_POST["Mobilid"   ]; //모빌리언스 거래번호
	$Signdate   = $_POST["Signdate"  ]; //결제일자
	$Tradeid    = $_POST["Tradeid"   ]; //가맹점 거래번호
	$Name       = $_POST["Name"      ]; //이름
	$No         = $_POST["No"        ]; //휴대폰번호
	$Commid     = $_POST["Commid"    ]; //이동통신사
	$Resultcd   = $_POST["Resultcd"  ]; //결과코드
	$Resultmsg  = $_POST["Resultmsg" ]; //결과메시지
	$Cryptyn	= $_POST["Cryptyn"   ];	//암호화 사용 여부 (default : Y)
	$Keygb		= $_POST["Keygb"     ];	//암호화 Key 구분 (0 : CI_SVCID 8자리, 1·2 : 가맹점 관리자 등록 후 사용)	
	$Socialno   = $_POST["Socialno"  ]; //생년월일
	$Sex        = $_POST["Sex"       ]; //성별 (남성:M, 여성:F)
	$Foreigner  = $_POST["Foreigner" ]; //외국인여부 (외국인 : Y)
	$Ci         = $_POST["Ci"        ]; //CI
	$Di         = $_POST["Di"        ]; //DI
	$CI_Mode    = $_POST["CI_Mode"   ]; //CI_Mode 41:LMS문구설정, 51:SMS문구설정, 61:SMS발송
	$DI_Code    = $_POST["DI_Code"   ]; //웹사이트코드
	$Mac        = $_POST["Mac"       ]; //검증키
	$MSTR       = $_POST["MSTR"      ]; //가맹점 확장 변수
	
	/*********************************************************************************
	' Okurl 암호화 사용 시 사용자정보 암호문 전달
	' 주) 고유KEY 설정 시 비밀키 : "가맹점 고유 KEY + CI_SVCID 앞 8자리" (16byte)    		// Keygb 1 or 2
	'			고유KEY 미 설정 시 비밀키 : "CI_SVCID 앞 8자리 + CI_SVCID 앞 8자리" (16byte)	// keygb 0
	*********************************************************************************/
	if($Cryptyn == "Y") {
		$cipherKey = substr($Svcid, 0, 8).substr($Svcid, 0, 8);
		$Name		= cipher($Name, $cipherKey);
		$No			= cipher($No, $cipherKey);
		$Commid		= cipher($Commid, $cipherKey);
		$Socialno	= cipher($Socialno, $cipherKey);
		$Sex		= cipher($Sex, $cipherKey);
		$Foreigner	= cipher($Foreigner, $cipherKey);
		$Ci			= cipher($Ci, $cipherKey);
		$Di			= cipher($Di, $cipherKey);
	}
	/*********************************************************************************
	* Mac값 위변조 여부 확인 SHA256(Signdate+Di+Ci+Mobilid+substr(Svcid,0, 8)+substr(Svcid,0, 8))
	* Mac값과 SHA256으로 변환한 값을 비교하여 값이 불일치 할 경우 데이터 위·변조 
	* 필수 사항 아님!
	**********************************************************************************/
	$key = $Signdate.$Di.$Ci.$Mobilid.substr($Svcid,0, 8).substr($Svcid,0, 8);
	$sha = hash('sha256', $key);
	$result = "정상 데이터";
	
	if( $Mac != $sha ){
		$result = "데이터가 위·변조되었습니다.";
	}
	
	/*********************************************************************************
	* 아래는 결과를 단순히 출력하는 샘플입니다.
	* 가맹점에서는 부모창 전환등 스크립트 처리등을 하시면 됩니다.
	**********************************************************************************/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
<title>가맹점 OKURL 모빌리언스 휴대폰본인확인</title>
</head>
<body>
<h1>인증결과</h1>
<table border="1" width="100%">
	<tr>
		<td><strong style="color:#ff0000">result</strong></td>
		<td><strong style="color:#ff0000"><? echo $result; ?></strong></td>
	</tr>
	<tr>
		<td>Cryptyn</td>
		<td><? echo $Cryptyn; ?></td>
	</tr>
	<tr>
		<td>Keygb</td>
		<td><? echo $Keygb; ?></td>
	</tr>
	<tr>
		<td>Svcid</td>
		<td><? echo $Svcid; ?></td>
	</tr>
	<tr>
		<td>Mobilid</td>
		<td><? echo $Mobilid; ?></td>
	</tr>
	<tr>
		<td>Signdate</td>
		<td><? echo $Signdate; ?></td>
	</tr>
	<tr>
		<td>Tradeid</td>
		<td><? echo $Tradeid; ?></td>
	</tr>
	<tr>
		<td>Name</td>
		<td><? echo $Name; ?></td>
	</tr>
	<tr>
		<td>No</td>
		<td><? echo $No; ?></td>
	</tr>
	<tr>
		<td>Commid</td>
		<td><? echo $Commid; ?></td>
	</tr>
	<tr>
		<td>Resultcd</td>
		<td><? echo $Resultcd; ?></td>
	</tr>
	<tr>
		<td>Resultmsg</td>
		<td><? echo $Resultmsg; ?></td>
	</tr>
	<tr>
		<td>Socialno</td>
		<td><? echo $Socialno; ?></td>
	</tr>
	<tr>
		<td>Sex</td>
		<td><? echo $Sex; ?></td>
	</tr>
	<tr>
		<td>Foreigner</td>
		<td><? echo $Foreigner; ?></td>
	</tr>
	<tr>
		<td>Ci</td>
		<td><? echo $Ci; ?></td>
	</tr>
	<tr>
		<td>Di</td>
		<td><? echo $Di; ?></td>
	</tr>
	<tr>
		<td>CI_Mode</td>
		<td><? echo $CI_Mode; ?></td>
	</tr>
	<tr>
		<td>DI_Code</td>
		<td><? echo $DI_Code; ?></td>
	</tr>
	<tr>
		<td>Mac</td>
		<td><? echo $Mac; ?></td>
	</tr>
	<tr>
		<td>sha</td>
		<td><? echo $sha; ?></td>
	</tr>
	<tr>
		<td>MSTR</td>
		<td><? echo $MSTR; ?></td>
	</tr>
</table>
</body>
</html>
