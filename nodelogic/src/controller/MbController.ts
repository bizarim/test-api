import { Controller, Post, Get, Body } from 'routing-controllers';
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
    public async verify(@Body() dto: VerifyDto): Promise<string> {
        console.log('verify');
        await this.mbService.verify(dto);
        return 'test';
    }
}