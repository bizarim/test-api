import 'reflect-metadata';
import { Container } from 'typedi';
import {
    useExpressServer,
    createExpressServer,
    useContainer as routingUseContainer
} from 'routing-controllers';
import { MbController } from './controller/MbController';
import * as express from 'express';

const app = express();
app.use(express.json());
app.use(express.urlencoded({ extended: false }));

routingUseContainer(Container);


useExpressServer(app, {
    cors: true,
    controllers: [MbController],
    middlewares: [],
    classTransformer: true,
    validation: true,
    development: true,
    defaultErrorHandler: true
});


app.listen(10230, () => {
    console.log('env ' + process.env.NODE_ENV);
    console.log('User service listening on port ' + 10230);
});