const express = require('express');
const cors = require('cors');
const app = express();

// Allow requests from all origins
app.use(cors());

// Your other Express middleware and routes
