import 'reflect-metadata';
import { Container } from 'typedi';
import { createExpressServer, useContainer as routingUseContainer } from 'routing-controllers';
import { MbController } from './controller/MbController';

routingUseContainer(Container);

const app = createExpressServer({
    cors: true,
    controllers: [MbController],
    middlewares: [],
    classTransformer: true,
    validation: true,
    development: true,
    defaultErrorHandler: true
});

app.listen(80, () => {
    console.log('env ' + process.env.NODE_ENV);
    console.log('User service listening on port ' + 80);
});