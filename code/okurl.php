<?
	/*********************************************************************************
	* ���� ������ �� ������ ��ȯ���� ȣ��Ǵ� �������̸� ���������� �����ؾ��ϴ� ȭ���Դϴ�.
	* ��ȣȭ ���� �ʼ� seed.php ������ �������� ������ ��ġ
	*********************************************************************************/

	include "inc/seed.php";		// ������ ��δ� ������ ������ ��ġ�� seed������ �����η� ���� �ʼ�
	
	/*****************************************************************
	* �Լ��� : cipher ��ȣȭ ����
	* ���� : cipher ("��ȣȭ�ҵ�����", "Ű")
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
	$Svcid      = $_POST["Svcid"     ]; //���񽺾��̵�
	$Mobilid    = $_POST["Mobilid"   ]; //������� �ŷ���ȣ
	$Signdate   = $_POST["Signdate"  ]; //��������
	$Tradeid    = $_POST["Tradeid"   ]; //������ �ŷ���ȣ
	$Name       = $_POST["Name"      ]; //�̸�
	$No         = $_POST["No"        ]; //�޴�����ȣ
	$Commid     = $_POST["Commid"    ]; //�̵���Ż�
	$Resultcd   = $_POST["Resultcd"  ]; //����ڵ�
	$Resultmsg  = $_POST["Resultmsg" ]; //����޽���
	$Cryptyn	= $_POST["Cryptyn"   ];	//��ȣȭ ��� ���� (default : Y)
	$Keygb		= $_POST["Keygb"     ];	//��ȣȭ Key ���� (0 : CI_SVCID 8�ڸ�, 1��2 : ������ ������ ��� �� ���)	
	$Socialno   = $_POST["Socialno"  ]; //�������
	$Sex        = $_POST["Sex"       ]; //���� (����:M, ����:F)
	$Foreigner  = $_POST["Foreigner" ]; //�ܱ��ο��� (�ܱ��� : Y)
	$Ci         = $_POST["Ci"        ]; //CI
	$Di         = $_POST["Di"        ]; //DI
	$CI_Mode    = $_POST["CI_Mode"   ]; //CI_Mode 41:LMS��������, 51:SMS��������, 61:SMS�߼�
	$DI_Code    = $_POST["DI_Code"   ]; //������Ʈ�ڵ�
	$Mac        = $_POST["Mac"       ]; //����Ű
	$MSTR       = $_POST["MSTR"      ]; //������ Ȯ�� ����
	
	/*********************************************************************************
	' Okurl ��ȣȭ ��� �� ��������� ��ȣ�� ����
	' ��) ����KEY ���� �� ���Ű : "������ ���� KEY + CI_SVCID �� 8�ڸ�" (16byte)    		// Keygb 1 or 2
	'			����KEY �� ���� �� ���Ű : "CI_SVCID �� 8�ڸ� + CI_SVCID �� 8�ڸ�" (16byte)	// keygb 0
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
	* Mac�� ������ ���� Ȯ�� SHA256(Signdate+Di+Ci+Mobilid+substr(Svcid,0, 8)+substr(Svcid,0, 8))
	* Mac���� SHA256���� ��ȯ�� ���� ���Ͽ� ���� ����ġ �� ��� ������ �������� 
	* �ʼ� ���� �ƴ�!
	**********************************************************************************/
	$key = $Signdate.$Di.$Ci.$Mobilid.substr($Svcid,0, 8).substr($Svcid,0, 8);
	$sha = hash('sha256', $key);
	$result = "���� ������";
	
	if( $Mac != $sha ){
		$result = "�����Ͱ� ���������Ǿ����ϴ�.";
	}
	
	/*********************************************************************************
	* �Ʒ��� ����� �ܼ��� ����ϴ� �����Դϴ�.
	* ������������ �θ�â ��ȯ�� ��ũ��Ʈ ó������ �Ͻø� �˴ϴ�.
	**********************************************************************************/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
<title>������ OKURL ������� �޴�������Ȯ��</title>
</head>
<body>
<h1>�������</h1>
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
