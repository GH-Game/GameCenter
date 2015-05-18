var path = require('path'),
	fs = require('fs'),
	exec = require('child_process').exec;

var FILEDIR = './public/data/',
	RESP_PATH = FILEDIR + 'resp.json';

var Utils = function() {};

Utils.prototype = {
	pullRespInfo: function() {
		return JSON.parse(fs.readFileSync(RESP_PATH));
	},
	pushRespInfo: function() {
		var data = {
			"type": 1,
			"time": new Date().toLocaleString(),
			"list": [{
				"package_name": "com.example.helloandroid",
				"file_name": "helloandroid.apk"
			}]
		};

		fs.writeFileSync(RESP_PATH, JSON.stringify(data));

		return JSON.parse(fs.readFileSync(RESP_PATH));
	},
	clearRespInfo: function() {
		var data = JSON.parse(fs.readFileSync(RESP_PATH));

		data.type = 0;
		data.time = new Date().toLocaleString();
		data.list = [];

		fs.writeFileSync(RESP_PATH, JSON.stringify(data));

		return data;
	},
	deleteRespInfo: function(filename) {
		console.log(filename);

		var data = JSON.parse(fs.readFileSync(RESP_PATH));

		data.type = 1;
		data.time = new Date().toLocaleString();

		var list = [];

		for (var i = 0, len = data.list.length; i < len; i++) {
			if (data.list[i]['file_name'] != filename) {
				list.push(data.list[i]);
			}
		}
		data.list = list;
		if (data.list.length == 0) {
			data.type = 0;
		}

		fs.writeFileSync(RESP_PATH, JSON.stringify(data));

		return data;
	},
	upload: function(filePath, filename, callback) {
		var newPath = FILEDIR + filename,
			_this = this;

		fs.readFile(filePath, function(err, data) {
			if (err) {
				callback({
					ret: "201"
				});
			}

			fs.writeFile(newPath, data, function(err) {
				if (!err) {
					// GET package_name
					_this.getPackageName(filename, function(pname) {
						var data = JSON.parse(fs.readFileSync(RESP_PATH));

						data.type = 1;
						data.time = new Date().toLocaleString();
						data.list.push({
							package_name: pname,
							file_name: filename
						});

						fs.writeFileSync(RESP_PATH, JSON.stringify(data));
					});

					callback({
						ret: "1"
					});
				} else {
					callback({
						ret: "202"
					});
				}
			});
		});
	},
	getPackageName: function(filename, callback) {
		var cmd = 'aapt dump badging ' + __dirname.substring(0, __dirname.lastIndexOf('/')) + '/public/data/' + filename,
			last = exec(cmd);

		last.stdout.on('data', function(data) {

			var package_name = '',
				reg = /name='(.*?)'\s*v/g;

			callback(data.match(reg)[0].split('\'')[1]);
		});
	}
};

module.exports = new Utils();