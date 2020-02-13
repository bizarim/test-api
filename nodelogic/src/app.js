const util = require('util');
const express = require('express');
const router = express.Router();
const app = express();
const cookieParser = require('cookie-parser');
const cors = require('cors');
const config = require('../config/config2.js');

const models = require('../models');
const Sequelize = require('sequelize');
const request = require('request');
const Op = Sequelize.Op;


app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: false }));
app.use(cookieParser());
app.use(function(req, res, next) {
  console.log(req.url);
  console.log('Time: %d', Date.now());
  next();
});

app.use('/api/v2/public', require('../Routes/public'));
app.use('/api/v2', require('../Routes/protected'));

app.listen(10230, () => {
  console.log(
    `listening on port 10230`
  );
});
