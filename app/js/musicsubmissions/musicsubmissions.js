//Created by Scott Pidzarko based off of work on membership.js, which was done by Evan Friday
window.myNameSpace = window.myNameSpace || { };

//PAGE CREATION
$(document).ready ( function() {
	$.when(constants_request).then( function () {
		add_submission_handlers();
	});
	populateTables();
});
/********************
 ***** Handlers *****
 ********************/
function add_submission_handlers(){
	//Listener for adding 'updated' to allow only updated comments to be submitted for saving
	//TODO
	$('#membership_table').off('keyup','.staff_comment').on('keyup','.staff_comment',function(element){
		$(this).addClass('updated');
	});

	//Listener for saving comments
	//TODO
	$('#search').off('click','#save_comments').on('click','#save_comments',function(element){
		saveComments();
	});
	//Listener for closing stuff with ESC
	$(document).keyup(function(e) {
	    if (e.keyCode == 27) { // escape key maps to keycode `27`
	        $('#submissionspopup').hide();
			$('#submissionsapprovalpopup').hide();
			$('#view_submissions').hide();
	    	$('#reviewed_submissions_view').hide();
		}
	});
	//Listener for viewing the tagging sidebar from clicking on their row
    $(".tagrow").click(function(e){
		$('#submissionspopup').show();
    });
	$("#submissionscloser").click(function(e){
		$('#submissionspopup').hide();
    });
	$("#tagcancel").click(function(e){
		$('#submissionspopup').hide();
    });
	$(".approverow").click(function(e){
		$('#submissionsapprovalpopup').show();
    });
	$("#submissionsapprovalcloser").click(function(e){
		$('#submissionsapprovalpopup').hide();
    });
	$("#approvecancel").click(function(e){
		$('#submissionsapprovalpopup').hide();
    });
	//Listener for viewing the review from clicking on their row
	$(".reviewrow").click(function(e){
		$('#view_submissions').show();
    });
	$("#view_submissions_closer").click(function(e){
		$('#view_submissions').hide();
	});
	$(".reviewedrow").click(function(e){
		$('#reviewed_submissions_view').show();
    });
	$("#reviewed_submissions_closer").click(function(e){
		$('#reviewed_submissions_view').hide();
	});
	//CHANGING TABS
	$('#tab-nav').off('click','.submission_action').on('click','.submission_action', function(e){
		$('.submission_action').attr('class','nodrop inactive-tab submission_action');
		$(this).attr('class','nodrop active-tab submission_action');
		$('.submission').hide();
		if($(this).attr('name') == 'search'){
			var search_value;
			$('.search_value').each(function(e){
				if($(this).css('display') != 'none'){
					search_value = $(this).val();
				}
			});
			displayMemberList( getVal('search_by'), search_value || "", getVal('paid_status'), $('.year_select[name="search"]').val(), getVal('order_by'));
		}
		$('.submission#'+$(this).attr('name')).show();

	});
	//Listener for viewing an unreviewed submission from it's row
	//TODO
    $('#search').off('click','.member_row_element').on('click','.member_row_element',function(e){
        $('.submission_action').attr('class','nodrop inactive-tab submission_action');
		$(".submission_action[name='view']").attr('class','nodrop active-tab submission_action');
		loadMember($(this.closest('tr')).attr('id').toString().replace('row',''));
		$('.membership').hide();
		$('.membership#view').show();
    });
	//Toggling red bar for showing members you are going to delete
	//TODO
	$('.membership').off('change','.delete_member').on('change','.delete_member',function(e) {
		$(this.closest('tr')).toggleClass('delete');

	});
	//Listener for blue highlighting for the submisisons result tables
	//TODO: Handler for blue highlighting on

}
function populateTables(){
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
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/tagged/cd",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
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
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td></td><td>Nothing here ...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='newSubmissionCd']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border reviewrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='newSubmissionCd']").append(markup);
		}
		add_submission_handlers();
	}
}
function populateNewSubmissionsMP3(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td></td><td>Nothing here ...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='newSubmissionMP3']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border reviewrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='newSubmissionMP3']").append(markup);
		}
		add_submission_handlers();
	}
}
function populateNewSubmissionsOther(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td></td><td>Nothing here ...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='newSubmissionOther']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border reviewrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='newSubmissionOther']").append(markup);
		}
		add_submission_handlers();
	}
}
//
function populateReviewedSubmissionsCd(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td><td></td></tr>";
		$("tbody[name='reviewedSubmissionCd']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border reviewedrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td></td><td>" + item['reviewed']+ "</td><td>" + item['approved'] + "</td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "></td><div class=\"check hidden\">❏</div><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='reviewedSubmissionCd']").append(markup);
		}
		add_submission_handlers();
	}
}
function populateReviewedSubmissionsMP3(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td><td></td></tr>";
		$("tbody[name='reviewedSubmissionMP3']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border reviewedrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td></td><td>" + item['reviewed']+ "</td><td>" + item['approved'] + "</td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "></td><div class=\"check hidden\">❏</div><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='reviewedSubmissionMP3']").append(markup);
		}
		add_submission_handlers();
	}
}
function populateReviewedSubmissionsOther(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td><td></td></tr>";
		$("tbody[name='reviewedSubmissionOther']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border reviewedrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td></td><td>" + item['reviewed']+ "</td><td>" + item['approved'] + "</td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "></td><div class=\"check hidden\">❏</div><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='reviewedSubmissionOther']").append(markup);
		}
		add_submission_handlers();
	}
}
//
function populateToTagSubmissionsCd(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='toTagSubmissionCd']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			//<tr class="playitem border tagrow" name="1272"><td class="submission_row_element name">Fuzzy P</td><td class="submission_row_element email">On A Lawn</td><td class="submission_row_element primary_phone">June 10th 2016</td><td class="submission_row_element submission_type">Indie Rock</td><td class="submission_row_element membership_year">June 26th 2016</td><td><input class="staff_comment" id="comment1272" value=""><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td></td><td><input type="checkbox" class="delete_submission" id="delete_5"></td><div class="check hidden">❏</div></tr>
			var markup = "<tr class=\"playitem border tagrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='toTagSubmissionCd']").append(markup);
		}
		add_submission_handlers();
	}
}
function populateToTagSubmissionsMP3(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='toTagSubmissionMP3']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border tagrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='toTagSubmissionMP3']").append(markup);
		}
		add_submission_handlers();
	}
}
function populateToTagSubmissionsOther(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='toTagSubmissionOther']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border tagrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='toTagSubmissionOther']").append(markup);
		}
		add_submission_handlers();
	}
}
//
function populateTaggedSubmissionsCd(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='taggedSubmissionCd']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			//<tr class="playitem border tagrow" name="1272"><td class="submission_row_element name">Fuzzy P</td><td class="submission_row_element email">On A Lawn</td><td class="submission_row_element primary_phone">June 10th 2016</td><td class="submission_row_element submission_type">Indie Rock</td><td class="submission_row_element membership_year">June 26th 2016</td><td><input class="staff_comment" id="comment1272" value=""><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td></td><td><input type="checkbox" class="delete_submission" id="delete_5"></td><div class="check hidden">❏</div></tr>
			var markup = "<tr class=\"playitem border approverow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='taggedSubmissionCd']").append(markup);
		}
		add_submission_handlers();
	}
}
function populateTaggedSubmissionsMP3(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='taggedSubmissionMP3']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border approverow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='taggedSubmissionMP3']").append(markup);
		}
		add_submission_handlers();
	}
}
function populateTaggedSubmissionsOther(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='taggedSubmissionOther']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border approverow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='taggedSubmissionOther']").append(markup);
		}
		add_submission_handlers();
	}
}
function populateTrashedSubmissions(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td><td></td></tr>";
		$("tbody[name='trashedSubmissions']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border reviewedrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td></td><td>" + item['reviewed']+ "</td><td>" + item['approved'] + "</td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "></td><div class=\"check hidden\">❏</div><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='trashedSubmissions']").append(markup);
		}
		add_submission_handlers();
	}
}
