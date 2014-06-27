$(function() {
	var DEPARTMENTS = [{
		"id": "department_1",
		"name": "d_aaa",
		"major": [{
			"id": "major_1",
			"name": "m_aaa01"
		}, {
			"id": "major_2",
			"name": "m_aaa02"
		}, {
			"id": "major_3",
			"name": "m_aaa03"
		}, {
			"id": "major_4",
			"name": "m_aaa04"
		}]
	}, {
		"id": "department_2",
		"name": "d_bbb",
		"major": [{
			"id": "major_1",
			"name": "m_bbb01"
		}, {
			"id": "major_2",
			"name": "m_bbb02"
		}, {
			"id": "major_3",
			"name": "m_bbb03"
		}, {
			"id": "major_4",
			"name": "m_bbb04"
		}]
	}, {
		"id": "department_3",
		"name": "d_ccc",
		"major": [{
			"id": "major_1",
			"name": "m_ccc01"
		}, {
			"id": "major_2",
			"name": "m_ccc02"
		}, {
			"id": "major_3",
			"name": "m_ccc03"
		}, {
			"id": "major_4",
			"name": "m_ccc04"
		}]
	}];

	var $department = $("#department");

	var options = "<option value=''>请选择系别</option>";
	for (var i = 0; i < DEPARTMENTS.length; i++) {
		var depa = DEPARTMENTS[i];
		options += ("<option value=" + depa.id + ">" + depa.name + "</option>");
	};

	$department.html(options);


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
			$("#major").html(options);

		}
	});



	// $.validator.addMethod("username", function(value, element) {
	//     return this.optional(element) || /^[a-z0-9\_]+$/i.test(value);
	// }, "Username must contain only letters, numbers, or underscore.");

	// $("#regForm").validate();

});