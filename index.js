var express = require('express');
var router = express.Router();

/* Plugins */
var multipart = require('connect-multiparty');

/* APLMHolder Helper */
var	utils = require('../models/Utils.js');

/* GET home page. */
router.get('/', function(req, res) {
	res.render('index', {
		title: 'Apkm Holder'
	});
});

/* ADD an APK info */
router.get('/push', function(req, res) {
	res.json(utils.pushRespInfo());
});

/* GET resp info */
router.get('/pull', function(req, res) {
	res.json(utils.pullRespInfo());
});

/* CLEAR resp info */
router.get('/clear', function(req, res) {
	res.json(utils.clearRespInfo());
});

/* DELETE resp info */
router.get('/delete', function(req, res) {
	var fname = req.query.fname;
	res.json(utils.deleteRespInfo(fname));
});

/* POST receive apk */
router.post('/upload', multipart(), function(req, res) {

	var filePath = req.files.apk.path,
		filename = req.files.apk.originalFilename;

	utils.upload(filePath, filename, function(data){
		res.json(data);
	});	
});

module.exports = router;