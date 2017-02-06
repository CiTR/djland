//Created by Scott Pidzarko based off of work on membership.js, which was done by Evan Friday
window.myNameSpace = window.myNameSpace || { };

//PAGE CREATION
$(document).ready ( function() {
	$.when(constants_request).then( function () {
		add_submission_handlers();
	});
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
