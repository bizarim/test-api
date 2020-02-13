import { Post, JsonController as Controller, Body, Get } from 'routing-controllers';
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

    @Post('/verify')
    public async verify(): Promise<string> {
        console.log('verify');
        await this.mbService.verify({name: 'a', no: 'a', mstr: ''});
        return 'test';
    }
}