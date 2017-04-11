//Created by Scott Pidzarko
window.myNameSpace = window.myNameSpace || {};

var genres, subgenres;
var activegenreid = 1;
var editingsubgenreid = 0;

//PAGE CREATION
$(document).ready(function() {
    $.when(constants_request).then(function() {
        //This function triggers getting subgenres and populating both tables
        initialGet();
        add_genremanager_listeners();
    });
});

/**
 * Adds all the listeners to elements in the genre manager page
 * called after each time you dynamically update the tables or reload tables
 * @returns {void}
 */
function add_genremanager_listeners() {
    $("#addgenre").off('click').on('click', function(e) {
        $("#addgenredialog").dialog("open");
    });
    $("#addsubgenre").off('click').on('click', function(e) {
        $("#addsubgenredialog").dialog("open");
    });
    $(".genrerow").off('click').on('click', function(e) {
        var activegenre = $(this).closest("tr").find("td:eq(0)").text();
        var string = "Subgenres for the " + activegenre + " Genre";
        $("#subgenretitle").text(string);
        activegenreid = $(this).attr('name');
        activegenreid = activegenreid.replace(/^\D+/g, ''); // replace all leading non-digits with nothing
        getSubGenres(activegenreid);
    });
    $('.genrerow input').off('click').on('click', function(e) {
        e.stopPropagation();
    });
    $('.subgenrerow input').off('click').on('click', function(e) {
        e.stopPropagation();
    });
    $(".genrerow").off('dblclick').on('dblclick', function(e) {
        var toedit = $(this).closest("tr").find("td:eq(0)").text();
        $("#editgenrebox").val(toedit);
        activegenreid = $(this).attr('name');
        activegenreid = activegenreid.replace(/^\D+/g, ''); // replace all leading non-digits with nothing
		$("#editgenredialog").dialog("open");
        getSubGenres(activegenreid);
    });
    $(".subgenrerow").off('dblclick').on('dblclick', function(e) {
        var toedit = $(this).closest("tr").find("td:eq(0)").text();
		editingsubgenreid = $(this).attr('name');
		editingsubgenreid = editingsubgenreid.replace(/^\D+/g, '');
        $("#editsubgenrebox").val(toedit);
        $("#editsubgenredialog").dialog("open");
    });
    $('.genrerow').off('change', '.delete_genre').on('change', '.delete_genre', function(e) {
        if ($(this).prop('checked') === true) {
            $(this.closest('tr')).addClass('delete');
        } else {
            $(this.closest('tr')).removeClass('delete');
        }
    });
    $('.subgenrerow').off('change', '.delete_subgenre').on('change', '.delete_subgenre', function(e) {
        if ($(this).prop('checked') === true) {
            $(this.closest('tr')).addClass('delete');
        } else {
            $(this.closest('tr')).removeClass('delete');
        }
    });
}

/**
 * Dialog box functions
 */
//Dialog box that appears when you click on a genre row
$(function() {
    $("#editgenredialog").dialog({
        autoOpen: false,
        modal: true,
        buttons: {
            "Apply": function() {
                updateGenre(parseInt(activegenreid), $('#editgenrebox').val(), parseInt($('#editgenrecrtc').val()), this);
            },
            Cancel: function() {
				//Reset the dialog
				$('#editgenrebox').val("");
				$('#editgenrecrtc').val("10").change();
                $(this).dialog("close");
            }
        }
    });
});
//Dialog box that appears when you click on a subgenre row
$(function() {
    $("#editsubgenredialog").dialog({
        autoOpen: false,
        modal: true,
        buttons: {
            "Apply": function() {
                updateSubgenre(editingsubgenreid, $('#editsubgenrebox').val(), activegenreid, this);
            },
            Cancel: function() {
				//Reset the dialog
				$("editsubgenrebox").val("");
                $(this).dialog("close");
            }
        }
    });
});
//Dialog box that appears when you click on the add genre button
$(function() {
    $("#addgenredialog").dialog({
        autoOpen: false,
        modal: true,
        buttons: {
            "Add": function() {
                addGenre($('#addgenrebox').val(), $('#addgenrecrtc').val(), this);
            },
            Cancel: function() {
				//Reset the dialog
				$('#addgenrebox').val("");
				$('#addgenrecrtc').val("10").change();
                $(this).dialog("close");
            }
        }
    });
});
//Dialog box that appears when you click on the add subgenre button
$(function() {
    $("#addsubgenredialog").dialog({
        autoOpen: false,
        modal: true,
        buttons: {
            "Add": function() {
                addSubgenre($('#addsubgenrebox').val(), activegenreid, this);
            },
            Cancel: function() {
				$("#addsubgenrebox").val("");
                $(this).dialog("close");
            }
        }
    });
});

/**
 * Updates the genre table
 * @param  {String} genres JSON list of genres
 * @return {void}
 */
function updateGenreListing(genres) {
    var newstring = "";

    if (genres.length === 0) {
        newstring = "<tr class=\"playitem\"><td colspan=4>No genres yet - add some for you to use in your library!</td></tr>";
    } else {
        for (var item in genres) {
            var genre = genres[item];
            var tempstring = "<tr name=\"genre" + genre.id + "\" class=\"playitem border genrerow\">" +
                "<td class=\"submission_row_element\">" + genre.genre + "</td>" +
                "<td class=\"submission_row_element\">" + genre.default_crtc_category + "</td>" +
                "<td class=\"submission_row_element\" name='names" + genre.created_by + "'>" + namesFromMemberId(genre.created_by) + "</td>" +
                "<td class=\"submission_row_element\" name='names" + genre.updated_by + "'>" + namesFromMemberId(genre.updated_by) + "</td>" +
                "<td class=\"submission_row_element\">" + genre.updated_at + "</td>" +
                "<td><input type=\"checkbox\" class=\"delete_genre\" id=\"delete_" + genre.id + "\"><div class=\"check hidden\">❏</div></td>" +
                "</tr>";
            newstring = newstring + tempstring;
        }
    }
    $("#genrelisting").html(newstring);

    add_genremanager_listeners();
}

/**
 * Updates the subgenre table on the genre manager page
 * @param  {String} genres JSON list of subgenres
 * @return {void}
 */
function updateSubGenreListing(subgenres) {
    //console.log(subgenres);
    var newstring = "";
    if (subgenres.length === 0) {
        newstring = "<tr class=\"playitem\"><td colspan=4>No subgenres for this genre yet!</td></tr>";
    } else {
        for (var item in subgenres) {
            var subgenre = subgenres[item];
            console.log(subgenre.created_by);
            console.log(namesFromMemberId(subgenre.created_by));
            var tempstring = "<tr name =\"subgenre" + subgenre.id + "\" class=\"playitem border subgenrerow\">" +
                "<td class=\"submission_row_element name\">" + subgenre.subgenre +
                "</td><td class=\"submission_row_element\" name='names" + subgenre.created_by + "'>" + namesFromMemberId(subgenre.created_by) + "</td>" +
                "<td class=\"submission_row_element\" name='names" + subgenre.updated_by + "'>" + namesFromMemberId(subgenre.updated_by) + "</td>" +
                "<td class=\"submission_row_element\">" + subgenre.updated_at + "</td>" +
                "<td><input type=\"checkbox\" class=\"delete_subgenre\" id=\"delete_" + subgenre.id + "\"><div class=\"check hidden\">❏</div></td></tr>";
            newstring = newstring + tempstring;
        }
    }
    $("#subgenrelisting").html(newstring);

    add_genremanager_listeners();
}

/**
 * Fetches the genres from the DJLand API on page load
 * @callback calls updateGenreListing(data) and getSubGenres(data['0'].id)
 * on sucesss
 * @return {void}
 */
function initialGet() {
    $.ajax({
        type: "GET",
        url: "api2/public/genres",
        dataType: 'json',
        async: true,
        success: function(data) {
            //console.log(data);
            updateGenreListing(data);
            activegenreid = (data['0'].id);
            getSubGenres(data['0'].id);
        }
    });
}

/**
 * Fetches genres from the DJLand API
 *
 * @callback calls updateGenreListing(data) on success
 * @return {void}
 */
function getGenres() {
    $.ajax({
        type: "GET",
        url: "api2/public/genres",
        dataType: 'json',
        async: true,
        success: function(data) {
            //console.log(data);
            updateGenreListing(data);
        }
    });
}

/**
 * Fetches subgenres for a genre from the api given a genre id
 *
 * @callback calles updateGenreListing(data) on success
 * @param {integer} genreid the id of the genre that we want to get the list
 * of subgenres for
 * @return {void}
 */
function getSubGenres(genreid) {
    $.ajax({
        type: "GET",
        url: "api2/public/genres/subgenres/" + genreid,
        dataType: 'json',
        async: true,
        success: function(data) {
            //console.log(data);
            updateSubGenreListing(data);
        }
    });
}

/**
 * Add a genre to the genres table via the DJLand API
 * @callback getGenres() - to trigger table repopulation
 * @callback displayErrorList - display errors returned by the DJLand API
 * @param {String} genre     the genre to add to the table
 * @param {Object} dialogbox scope referring to the dialogbox. Passed usually
 * as "this"
 * @return {void}
 */
function addGenre(genre, default_crtc_category, dialogbox) {
    //console.log(genre);
    $.ajax({
        type: "POST",
        url: "api2/public/genres",
        data: {
            genre: genre,
            default_crtc_category: default_crtc_category
        },
        dataType: 'json',
        async: true,
        success: function(data) {
            //console.log(data);
            $(dialogbox).dialog("close");
            $('#addgenrecrtc').val("10").change();
            $("#addgenrebox").val("");

            getGenres();
        },
        error: function(err) {
			console.log(err);
            displayErrorList(err);
        }
    });
}

/**
 * Add a subgenre to the genres table via the DJLand API
 * @callback getSubGenres(parent_genre_id) - to trigger table repopulation
 * @param {String} subgenre		the subgenre to add to the table
 * @param {Object} dialogbox	scope referring to the dialogbox. Passed usually
 * as "this"
 * @return {void}
 */
function addSubgenre(subgenre, parent_genre_id, dialogbox) {
    console.log(subgenre, parent_genre_id);
    $.ajax({
        type: "POST",
        url: "api2/public/subgenres",
        data: {
            subgenre: subgenre,
            parent_genre_id: parent_genre_id
        },
        dataType: 'json',
        async: true,
        success: function(data) {
            //console.log(data);
            $(dialogbox).dialog("close");
            $("#addsubgenrebox").val("");
            getSubGenres(parent_genre_id);
        }
    });
}

/**
 * Update a genre's name via the DJLand API
 * @callback getGenres() - to trigger table repopulation
 * @param  {integer} id       id of the genre to change
 * @param  {String} newgenre  the string to update the genre to
 * @param  {integer} default_crtc_category the default_crtc_category for the genre
 * @param  {Object} dialogbox scope referring to the dialogbox that called this
 * function. Passed usually as "this"
 * @return {void}
 */
function updateGenre(id, newgenre, default_crtc_category, dialogbox) {
	console.log(id,newgenre,default_crtc_category, dialogbox);
    $.ajax({
        type: "PUT",
        url: "api2/public/genres",
        data: {
            id: id,
            genre: newgenre,
            default_crtc_category: default_crtc_category
        },
        dataType: 'json',
        async: true,
        success: function(data) {
            //console.log(data);
            $(dialogbox).dialog("close");
			$('#editgenrecrtc').val("10").change();
            $("#editgenrebox").val("");
            getGenres();
        }
    });
}

/**
 * Update a subgenre's name via the DJLand API
 * @callback getSubGenres() - to trigger table repopulation
 * @param  {integer} id					id of the subgenre to change
 * @param  {String} newsubgenre     	the string to update the subgenre to
 * @param  {integer} parent_genre_id 	the id of the parent genre of the
 * subgenre
 * @param  {Object} dialogbox       	scope referring to thej dialogbox that
 * called this function. Passed usually as "this"
 * @return {void}
 */
function updateSubgenre(id, newsubgenre, parent_genre_id, dialogbox) {
    $.ajax({
        type: "PUT",
        url: "api2/public/subgenres",
        data: {
            id: id,
            subgenre: newsubgenre,
            parent_genre_id: parent_genre_id
        },
        dataType: 'json',
        async: true,
        success: function(data) {
            //console.log(data);
            $(dialogbox).dialog("close");
            getSubGenres(parent_genre_id);
        }
    });
}

/**
 * Display in a series of alerts a list of errors
 * @param  {String[]} err JSON array of errors
 * @return {void}
 */
function displayErrorList(err) {
    for (var i = 0; i < err.responseJSON.length; i++) {
        alert(err.responseJSON[i]);
    }
    console.log(err);
}

function namesFromMemberId(id){
    console.log(id);
   var string = "";
   $.ajax({
       type:"GET",
       url: "api2/public/member/" + id + "/firstnamelastname",
       dataType:'json',
       async:true,
       success:function(response){
           var data = response[0];
           var identifier = "[name=\'names"+id+"\']";
           if(data != undefined){
               string = data['firstname'] + " " + data['lastname'];
               $(identifier).text(string);
           } else {
               $(identifier).text("Unknown");
               $(identifier).css("color","navy");
           }
        },
        error:function(err){
           //var json_response = err.responseJSON.msg;
           console.log("Bad format for AJAX Request with Member ID: " + id + ", the server said:");
           console.log(err);
       }
   });
}
