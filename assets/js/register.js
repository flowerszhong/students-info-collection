$(function() {
	var DEPARTMENTS = [{
		"id": 1,
		"name": "环境工程与土木工程系",
		"major": [{
			"id": "101",
			"name": "环境监测与治理技术"
		}, {
			"id": "102",
			"name": "城市水净化技术"
		}, {
			"id": "103",
			"name": "给排水工程技术"
		}, {
			"id": "104",
			"name": "工程测量与监理"
		}, {
			"id": "105",
			"name": "建筑工程技术"
		}, {
			"id": "106",
			"name": "工程造价"
		}]
	}, {
		"id": 2,
		"name": "环境监测系",
		"major": [{
			"id": "201",
			"name": "环境监测与评价"
		}, {
			"id": "202",
			"name": "食品营养与检测"
		}, {
			"id": "203",
			"name": "室内检测与控制技术"
		}, {
			"id": "204",
			"name": "工业分析与检验"
		}, {
			"id": "205",
			"name": "商检技术专业"
		}]
	}, {
		"id": 3,
		"name": "环境科学系",
		"major": [{
			"id": "301",
			"name": "环境监测与评价"
		}, {
			"id": "302",
			"name": "工业环保与安全技术"
		}, {
			"id": "303",
			"name": "安全技术管理"
		}]
	},{
		"id": 4,
		"name": "循环经济与低碳经济系",
		"major": [{
			"id": "401",
			"name": "资源环境与城市管理"
		},{
			"id": "402",
			"name": "工业节能管理专业"
		}]
	},{
		"id": 5,
		"name": "机电工程系",
		"major": [{
			"id": "501",
			"name": "机电设备维修与管理"
		}, {
			"id": "502",
			"name": "模具设计与制造"
		}, {
			"id": "503",
			"name": "软件测试技术"
		}]
	},{
		"id": 6,
		"name": "生态环境系",
		"major": [{
			"id": "601",
			"name": "城市园林"
		}, {
			"id": "602",
			"name": "园艺技术"
		}]
	},{
		"id": 7,
		"name": "环境艺术与服务系",
		"major": [{
			"id": "701",
			"name": "环境艺术设计"
		}, {
			"id": "702",
			"name": "烹饪工艺与营养"
		}]
	}];

	var $department = $("#department");

	var options = "<option value=''>请选择系别</option>";
	for (var i = 0; i < DEPARTMENTS.length; i++) {
		var depa = DEPARTMENTS[i];
		options += ("<option name='department' value=" + depa.id + ">" + depa.name + "</option>");
	};

	$department.empty().append(options);


	$department.on('change',function () {
		var department_id = this.value;
		var depa = null;
		for (var i = 0; i < DEPARTMENTS.length; i++) {
			if (DEPARTMENTS[i].id == department_id) {
				depa = DEPARTMENTS[i];
				break;
			};
		};

		if(depa){
			var majors = depa['major'];
			var options = "<option value=''>请选择专业</option>";
			for (var i = 0; i < majors.length; i++) {
				var _m = majors[i];
				options += ("<option value=" + _m.id + ">" + _m.name + "</option>");
			};
			$("#major").empty().append(options);

		}
	});



	// $.validator.addMethod("username", function(value, element) {
	//     return this.optional(element) || /^[a-z0-9\_]+$/i.test(value);
	// }, "Username must contain only letters, numbers, or underscore.");

	// $("#regForm").validate();

});