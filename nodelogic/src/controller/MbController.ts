import { Controller, Post, Get } from 'routing-controllers';
import { MbService } from '../service/MbService';
import { VerifyDto } from '../dto/VerifyDto';

@Controller('/mb')
export class MbController {
    constructor(private readonly mbService: MbService) { }

    @Get('/verify')
    public async verify(): Promise<string> {
        await this.mbService.verify();
        return 'test';
    }
}