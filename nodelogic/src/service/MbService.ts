import { Service } from 'typedi';
import { VerifyDto } from '../dto/VerifyDto';
import axios from 'axios';

@Service()
export class MbService {

    public async test(): Promise<void> {
        console.log('test');
    }

    public async verify(dto: VerifyDto): Promise<void> {
        try {
            const { name, no, mstr } = dto;
            console.log('dto Name: ' + name);
            console.log('dto No: ' + no);
            console.log('dto MSTR: ' + mstr);
            const axiosRes = await axios.post<KGResponse>(`http://web:8080/okurl.php?Name=${name}&No=${no}`);
            console.log(axiosRes.status);
            console.log(axiosRes.statusText);
            const rt = axiosRes.data.Resultcd;
            console.log(rt);
            const a = axiosRes.data.Resultmsg;
            console.log(a);
            const ee = axiosRes.data.Name;
            const phoneHexa = axiosRes.data.No;
            console.log('name: ' + ee);
            console.log('phoneHexa: ' + phoneHexa);
        } catch (e) {
            console.log(JSON.stringify(e.message));
            console.log(JSON.stringify(e.stack));
        }
    }
}

interface KGResponse {
    Resultcd: string;
    Resultmsg: string;
    Mobilid: string;
    Cryptyn: string;
    Keygb: string;
    MSTR: string;
    Signdate: string;
    Svcid: string;
    Tradeid: string;
    Name: string;
    No: string;
    Commid: string;
    Socialno: string;
    Sex: string;
    Foreigner: string;
    Ci: string;
    Di: string;
    CI_Mode: string;
    DI_Code: string;
    Mac: string;
}

// 파라미터명	크기	설명
// Resultcd	4byte 고정	결과코드 (0000: 성공, 그 외: 실패)
// Resultmsg	100byte 이하	결과메세지
// Mobilid	15byte 고정	모빌리언스 거래번호
// Cryptyn	1byte 고정	암호화 사용 여부 “Y” 고정
// Keygb	1byte 고정	암호화key 지정 값
// MSTR	    2000byte 이하	가맹점 전달 콜백변수
// Signdate	14byte 이하	인증일자
// Svcid	12byte 고정	서비스ID
// Tradeid	40byte 이하	상점거래번호
// Name	    9byte 이하	사용자명 (암호화)
// No	    12byte 이하	사용자 전화번호 (암호화)
// Commid	3byte 고정	이동통신사 (암호화)
// Socialno	13byte 이하	생년월일 (19800101) (암호화)
// Sex	    1byte 고정	성별 (M:남성, F:여성) (암호화)
// Foreigner	1byte 고정	외국인여부 (Y:외국인 / N:내국인) (암호화)
// Ci	    88byte 고정	Ci값 (암호화)
// Di	    64byte 고정	Di값 (암호화)
// CI_Mode	2byte 고정	요청구분
// DI_Code	12byte 고정	웹사이트코드
// Mac	    64byte 고정	검증키 (암호화)
