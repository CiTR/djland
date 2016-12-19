//Created by Scott Pidzarko based off of work on membership.js, which was done by Evan Friday
window.myNameSpace = window.myNameSpace || { };

//PAGE CREATION
$(document).ready ( function() {
	$.when(constants_request).then( function () {
		add_submission_handlers();
	});
});
function add_submission_handlers(){
	//This makes page printer/user friendly and toggles on the button trigger
	$('#print_friendly').on('click',function(element){
		if(!$(this).hasClass('print_friendly')){
			$(this).text('Normal View');
			$('#admin-nav, #nav, #tab-nav, #headerrow, #submission_header').hide();

			$('body').removeClass('wallpaper');
			$('.submission').removeClass('grey');
			//make printer friendly
			$('.staff_comment, .delete_member').each(function(element){
				$(this).hide();
			});

			$('.check').each(function(element){
				$(this).removeClass('hidden');
			});

			//$('#search').addClass('inline_block');
			$('#submission_result').removeClass('overflow_auto').removeClass('height_cap').addClass('overflow_visible');
		}else{
			//return to normal
			$(this).text('Print View');
			$('#admin-nav, #nav, #tab-nav, #headerrow, #submission_header').show();

			$('body').addClass('wallpaper');
			$('.submission').addClass('grey');

			$('.staff_comment, .delete_member').each(function(element){
				$(this).show();
			});
			$('.check').each(function(element){
				$(this).addClass('hidden');
			});
			//$('#search').removeClass('inline_block');
			$('#submission_result').removeClass('overflow_visible').addClass('height_cap').addClass('overflow_auto');
		}
		$(this).toggleClass('print_friendly');
	});
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
	$('#tab-nav').off('click','.member_action').on('click','.member_action', function(e){
		$('.member_action').attr('class','nodrop inactive-tab member_action');
		$(this).attr('class','nodrop active-tab member_action');
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
        $('.member_action').attr('class','nodrop inactive-tab member_action');
		$(".member_action[name='view']").attr('class','nodrop active-tab member_action');
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
