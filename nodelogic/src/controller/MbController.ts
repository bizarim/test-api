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


    // @Post('/verify')
    // public async verify(@Body() dto: any, @QueryParams() query: any): Promise<string> {
    //     console.log('verify:' + JSON.stringify(dto));
    //     console.log(query);
    //     await this.mbService.verify(dto);
    //     return '<script>window.close();</script>';
    // }

    @Post('/verify')
    public async verify(@Req() request: any, @Res() response: any) {
        const dto = request.body as VerifyDto;
        console.log('verify:' + JSON.stringify(dto));

        await this.mbService.verify(dto);

        return response.send('<script>window.close();</script>');
    }

    @Get('/close')
    public async close(@Req() request: any, @Res() response: any) {
        return response.send('<script>callback()</script>');
    }

}