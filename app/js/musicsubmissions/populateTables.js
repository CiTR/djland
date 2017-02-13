function populateNewSubmissionsTable(){
    $(".reviewrow").remove();
    $(".reviewrowNotFound").remove();
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/unreviewed/cd",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			populateNewSubmissionsCd(data);
		}
	});
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/unreviewed/mp3",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			populateNewSubmissionsMP3(data);
		}
	});
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/unreviewed/other",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			populateNewSubmissionsOther(data);
		}
	});
}
function populateReviewedSubmissionsTable(){
    $(".reviewedrow").remove();
    $(".reviewedrowNotFound").remove();
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/reviewed/cd",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			populateReviewedSubmissionsCd(data);
		}
	});
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/reviewed/mp3",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			populateReviewedSubmissionsMP3(data);
		}
	});
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/reviewed/other",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			populateReviewedSubmissionsOther(data);
		}
	});
}
function populateApprovedSubmissionsTable(){
    $(".tagrow").remove();
    $(".tagrowNotFound").remove();
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/approved/cd",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			populateToTagSubmissionsCd(data);
		}
	});
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/approved/mp3",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			populateToTagSubmissionsMP3(data);
		}
	});
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/approved/other",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			populateToTagSubmissionsOther(data);
		}
	});
}
function populateTaggedSubmissionsTable(){
    $(".approverow").remove();
    $(".approverowNotFound").remove();
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/tagged/cd",
		dataType:'json',
		async:true,
		success:function(data){
			populateTaggedSubmissionsCd(data);
		}
	});
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/tagged/mp3",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			populateTaggedSubmissionsMP3(data);
		}
	});
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/tagged/other",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			populateTaggedSubmissionsOther(data);
		}
	});
}
function populateTrashedSubmissionsTable(){
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/trashed",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			populateTrashedSubmissions(data);
		}
	});
}
//
function populateNewSubmissionsCd(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border reviewrowNotFound\"><td></td><td></td><td></td><td></td><td>Nothing here ...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='newSubmissionCd']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border reviewrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "\"><div class=\"check hidden\">❏</div></td></tr>";
				//console.log(markup);
			$("tbody[name='newSubmissionCd']").append(markup);
		}
		add_submission_handlers();
	}
}
function populateNewSubmissionsMP3(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border reviewrowNotFound\"><td></td><td></td><td></td><td></td><td>Nothing here ...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='newSubmissionMP3']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border reviewrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='newSubmissionMP3']").append(markup);
		}
		add_submission_handlers();
	}
}
function populateNewSubmissionsOther(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border reviewrowNotFound\"><td></td><td></td><td></td><td></td><td>Nothing here ...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='newSubmissionOther']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border reviewrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='newSubmissionOther']").append(markup);
		}
		add_submission_handlers();
	}
}
//
function populateReviewedSubmissionsCd(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border reviewedrowNotFound\"><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td><td></td></tr>";
		$("tbody[name='reviewedSubmissionCd']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			if(item['approved'] == 1) var approvalYesNo = "Yes";
			else var approvalYesNo = "No";
			namesFromMemberId(item['reviewed']);
			var markup = "<tr class=\"playitem border reviewedrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td></td><td><span name=\"names" + item['reviewed'] + "\"></span></td><td>" + approvalYesNo + "</td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "\"></td></tr>";
			$("tbody[name='reviewedSubmissionCd']").append(markup);
		}
		var endrow = "<tr class=\'reviewedrowNotFound\'><td></td><td></td><td></td><td></td><td></td><td></td><td><button>Apply Approvals</button></td></tr>"
		$("tbody[name='reviewedSubmissionCd']").append(endrow);
		add_submission_handlers();
	}
}
function populateReviewedSubmissionsMP3(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border reviewrowNotFound\"><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td><td></td></tr>";
		$("tbody[name='reviewedSubmissionMP3']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			if(item['approved'] == 1) var approvalYesNo = "Yes";
			else var approvalYesNo = "No";
			namesFromMemberId(item['reviewed']);
			var markup = "<tr class=\"playitem border reviewedrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td></td><td><span name=\"names" + item['reviewed'] + "\"></span></td><td>" + approvalYesNo + "</td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "\"></td></tr>";
			$("tbody[name='reviewedSubmissionMP3']").append(markup);
		}
		var endrow = "<tr class=\'reviewedrowNotFound\'><td></td><td></td><td></td><td></td><td></td><td></td><td><button>Apply Approvals</button></td></tr>"
		$("tbody[name='reviewedSubmissionMP3']").append(endrow);
		add_submission_handlers();
	}
}
function populateReviewedSubmissionsOther(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border reviewedrowNotFound\"><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td><td></td></tr>";
		$("tbody[name='reviewedSubmissionOther']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			if(item['approved'] == 1) var approvalYesNo = "Yes";
			else var approvalYesNo = "No";
			namesFromMemberId(item['reviewed']);
			var markup = "<tr class=\"playitem border reviewedrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td></td><td><span name=\"names" + item['reviewed'] + "\"></span></td><td>" + approvalYesNo + "</td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "\"></td></tr>";
			$("tbody[name='reviewedSubmissionOther']").append(markup);
		}
		var endrow = "<tr class=\'reviewedrowNotFound\'><td></td><td></td><td></td><td></td><td></td><td></td><td><button>Apply Approvals</button></td></tr>"
		$("tbody[name='reviewedSubmissionOther']").append(endrow);
		add_submission_handlers();
	}
}
//
function populateToTagSubmissionsCd(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border tagrowNotFound\"><td></td><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='toTagSubmissionCd']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			//<tr class="playitem border tagrow" name="1272"><td class="submission_row_element name">Fuzzy P</td><td class="submission_row_element email">On A Lawn</td><td class="submission_row_element primary_phone">June 10th 2016</td><td class="submission_row_element submission_type">Indie Rock</td><td class="submission_row_element membership_year">June 26th 2016</td><td><input class="staff_comment" id="comment1272" value=""><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td></td><td><input type="checkbox" class="delete_submission" id="delete_5"></td><div class="check hidden">❏</div></tr>
			var markup = "<tr class=\"playitem border tagrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='toTagSubmissionCd']").append(markup);
		}
		add_submission_handlers();
	}
}
function populateToTagSubmissionsMP3(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border tagrowNotFound\"><td></td><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='toTagSubmissionMP3']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border tagrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='toTagSubmissionMP3']").append(markup);
		}
		add_submission_handlers();
	}
}
function populateToTagSubmissionsOther(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border tagrowNotFound\"><td></td><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='toTagSubmissionOther']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border tagrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='toTagSubmissionOther']").append(markup);
		}
		add_submission_handlers();
	}
}
//
function populateTaggedSubmissionsCd(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border approverowNotFound\"><td></td><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='taggedSubmissionCd']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			//<tr class="playitem border tagrow" name="1272"><td class="submission_row_element name">Fuzzy P</td><td class="submission_row_element email">On A Lawn</td><td class="submission_row_element primary_phone">June 10th 2016</td><td class="submission_row_element submission_type">Indie Rock</td><td class="submission_row_element membership_year">June 26th 2016</td><td><input class="staff_comment" id="comment1272" value=""><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td></td><td><input type="checkbox" class="delete_submission" id="delete_5"></td><div class="check hidden">❏</div></tr>
			var markup = "<tr class=\"playitem border approverow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='taggedSubmissionCd']").append(markup);
		}
		add_submission_handlers();
	}
}
function populateTaggedSubmissionsMP3(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border approverowNotFound\"><td></td><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='taggedSubmissionMP3']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border approverow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='taggedSubmissionMP3']").append(markup);
		}
		add_submission_handlers();
	}
}
function populateTaggedSubmissionsOther(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border approverowNotFound\"><td></td><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='taggedSubmissionOther']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border approverow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='taggedSubmissionOther']").append(markup);
		}
		add_submission_handlers();
	}
}
function populateTrashedSubmissions(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border reviewedrowNotFound\"><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td><td></td></tr>";
		$("tbody[name='trashedSubmissions']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			if(item['approved'] == 1) var approvalYesNo = "Yes";
			else var approvalYesNo = "No";
			var names = namesFromMemberId(item['reviewed']);
			var markup = "<tr class=\"playitem border reviewedrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td></td><td>" + item['reviewed']+ "</td><td>" + approvalYesNo + "</td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='trashedSubmissions']").append(markup);
		}
		add_submission_handlers();
	}
}
