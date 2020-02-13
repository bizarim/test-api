import { Post, Controller, Body, Get, QueryParam, QueryParams, Req, Res } from 'routing-controllers';
import { MbService } from '../service/MbService';
import { VerifyDto } from '../dto/VerifyDto';

@Controller('/mb')
export class MbController {
    constructor(private readonly mbService: MbService) { }

    @Get('/test')
    public async test(): Promise<string> {
        await this.mbService.test();
        return 'test';
    }

    @Get('/users')
    public async getAll(@Req() request: any, @Res() response: any) {

    }

    @Post('/verify')
    public async verify(@Req() request: any, @Res() response: any): Promise<string> {

        console.log('verify:' + JSON.stringify(request.body));
        // console.log(query);
        // await this.mbService.verify(dto);
        return response.send('<script>window.close();</script>');
    }


}