window.myNameSpace = window.myNameSpace || {};
var member;


function getVal($varname) {
    $temp = $varname;
    if ($('#' + $temp).val() != null) {
        return $('#' + $temp).val();
    } else {
        return null;
    }
}

function getText($varname) {
    $temp = $varname;
    if ($('#' + $temp).text() != null) {
        return $('#' + $temp).text();
    } else {
        return null;
    }
}

function getSelect($id) {
    var selects;
    if (document.getElementById($id) != null) {
        selects = document.getElementById($id);
        var selectedValue = selects.options[selects.selectedIndex].value;
        return selectedValue;
    } else {
        return null;
    }
}

function getCheckbox(id) {
    var checkbox = id;
    if ($('#' + checkbox).prop('checked')) {
        return 1;
    } else {
        return 0;
    }
}

function get(target_id, target_class, target_name) {
    var target = $((target_id != null ? '#' + target_id : "") + (target_class != null ? "." + target_class : "") + (target_name != null ? "[name=" + target_name + "]" : ""));
    var tag = target.prop('tagName');
    var result;
    switch (tag) {
    case 'DIV':
        result = target.text();
        break;
    case 'INPUT':
        var type = target.attr('type');
        switch (type) {
        case 'checkbox':
            if (target.prop('checked')) result = 1;
            else result = 0;
            break;
        default:
            result = target.val();
            break;
        }
        break;
    case 'SELECT':
    case 'TEXTAREA':
        result = target.val();
        break;
    default:
        result = target.val();
        break;
    }
    return result;
}

function set(value, target_id, target_class, target_name) {
    var target = $((target_id != null ? '#' + target_id : "") + (target_class != null ? "." + target_class : "") + (target_name != null ? "[name=" + target_name + "]" : ""));
    var tag = target.prop('tagName');
    //console.log("Value:"+value+" Target:"+target.attr('id') + "," +target.attr('class') + "," +target.attr('name')+" Tag:"+tag);
    switch (tag) {
    case 'DIV':
        target.text(value);
        break;
    case 'SELECT':
        target.val(value).change();
        break;
    case 'INPUT':
        var type = target.attr('type');
        switch (type) {
        case 'checkbox':
            if (value == '1') {
                target.prop('checked', true);
            } else {
                target.prop('checked', false);
            }
            break;
        case 'radio':
            var yes = $('#' + target_id + '1');
            var no = $('#' + target_id + '2');
            switch (value) {
            case '1':
                $radio1.attr('checked', 'checked');
                $radio2.removeAttr('checked');
                break;
            case '0':
                $radio2.attr('checked', 'checked');
                $radio1.removeAttr('checked');
                break;
            default:
                break;
            }
            break;
        default:
            target.val(value).change();
            break;
        }
        break;
    case 'TEXTAREA':
    default:
        target.val(value).change();
        break;
    }

}

function setCheckbox(value, id) {
    if (value == '1') {
        $('#' + id).prop('checked', true);
    } else {
        $('#' + id).prop('checked', false);
    }
}

function setText(value, id) {
    $target = $('#' + id);
    assertTrue($target != null);
    $target.text(value);
}

function setVal(value, id) {
    $target = $('#' + id);
    assertTrue($target != null);
    $target.val(value).change();
}

function setRadio(value, id) {
    //yes
    $radio1 = $('#' + id + '1');
    //no
    $radio2 = $('#' + id + '2');
    assertTrue($radio1 != null && $radio2 != null);
    switch (value) {
    case '1':
        $radio1.attr('checked', 'checked');
        $radio2.removeAttr('checked');
        break;
    case '0':
        $radio2.attr('checked', 'checked');
        $radio1.removeAttr('checked');
        break;
    default:
        break;
    }
}

function getRadio(id) {
    if ($("#" + id + "1").prop('checked')) {
        return 1;
    } else {
        return 0;
    }
}

function numbersonly(myfield, e, dec) {
    var key;
    var keychar;

    if (window.event)
        key = window.event.keyCode;
    else if (e)
        key = e.which;
    else
        return true;
    keychar = String.fromCharCode(key);

    // control keys
    if ((key == null) || (key == 0) || (key == 8) ||
        (key == 9) || (key == 13) || (key == 27))
        return true;

    // numbers
    else if ((("0123456789").indexOf(keychar) > -1))
        return true;

    // decimal point jump
    else if (dec && (keychar == ".")) {
        myfield.form.elements[dec].focus();
        return false;
    } else
        return false;
}


function decodeHTML(str) {
    str = str.replace(new RegExp('&quot;', 'gi'), '"');
    str = str.replace(new RegExp('&Atilde;', 'gi'), 'Ã');
    str = str.replace(new RegExp('&copy;', 'gi'), '©');
    return str.replace(/&#(\d+);/g, function (match, dec) {
        return String.fromCharCode(dec);
    });
}

function queryMembers(search_parameter, search_value, paid, membership_year, search_has_show, order_by) {
    console.log(search_parameter + " " + search_value + " " + paid + " " + membership_year + " " + search_has_show + " " + order_by);
    console.log('querying');
    return $.ajax({
        type: "GET",
        url: "api2/public/member/search",
        data: {
            'search_parameter': search_parameter,
            'search_value': search_value,
            'paid': paid,
            'membership_year': membership_year,
            'has_show': search_has_show,
            'order_by': order_by,
        },
        dataType: 'json',
        async: true
    });
}

//Returns all membership years present for member id
function queryMembershipYears(member_id) {
    return $.ajax({
        type: "GET",
        url: "api2/public/membershipyear",
        dataType: "json",
        async: true
    });
}

function loadYearSelect() {
    var years = queryMembershipYears();
    $.when(years).then(
        function (response) {
            $('.year_select').each(function (element) {
                for (var i = 0; i < response.length; i++) {
                    $(this).append("<option value=" + response[i]['membership_year'] + ">" + response[i]['membership_year'] + "</option>");
                }
                $(this).append("<option value='all'>All Years</option>");

            });
        },
        function (err) {
            console.log("failed to load years");
        });
    return years;
}

function loadMember(id) {
    $('#member_loading[name="view"]').show();
    $('#member_result').hide();
    var new_member = new Member(id);
    $.when(new_member.info_callback, new_member.interest_callback).then(function (info, interests) {
        member = new_member;
        $('#member_loading[name="view"]').hide();
        $('#member_result').show();
    }, function (err1, err2) {
        console.log("Failed to load member");
    });
}

function displayMemberList(search_by, value, paid, year, search_has_show, order_by) {
    $('.member_row').remove();
    $('#search_loading').show();
    if (year == null) {
        year = get('year_select', null, 'search');
    }
    $.when(queryMembers(search_by, value, paid, year, search_has_show, order_by)).then(function (data) {
        $('#search_loading').hide();
        var member_result_table = $('#membership_table[name="search"]');
        var member_result_header = $('#headerrow');
        member_result_header.show();
        console.log(data);
        for (var member in data) {
            var m = data[member];
            member_result_table.append("<tr id=row" + m.id + " class='member_row' name='" + m.id + "'></tr>");
            var row = $('#row' + m.id);
            row.append()
            for (var item in m) {
                if (item != 'id' && item != 'comments') row.append("<td class='member_row_element " + item + "'>" + (m[item] != null ? m[item] : "") + "</td>");
                else if (item == 'comments') row.append("<td><input class='staff_comment' id='comment" + m.id + "' value='" + (m[item] != null ? m[item] : "") + "'></input></td>");
            }
            if ($('#permission_level').text() >= permission_levels['administrator']['level']) {
                row.append("<td><input type='checkbox' class='delete_member' id='delete_" + member + "'></td>");
            }
            row.append("<div class='check hidden'>&#x274F;</div>");
        }
        if (data.length < 1) {
            member_result_header.hide();
            $('#membership_result[name="search"]').append("<div class='member_row'>No Results</div>");
        }
    });
}
//For saving comments from the search view
function saveComments() {
    var comments = {};

    $('.staff_comment.updated').each(function (element) {
        var id = ($(this).attr('id').toString().replace('comment', ''));
        var comment = ($(this).val());
        comments[id] = {
            'id': id,
            'comments': comment
        };
        $(this).removeClass('updated');
    });
    var requests = Array();
    for (var comment in comments) {
        requests.push(
            $.ajax({
                type: "POST",
                url: "api2/public/member/" + comment + "/comments",
                data: {
                    "comments": JSON.stringify(comments[comment]['comments'])
                },
                dataType: "json",
                async: true
            })
        );
    }

    $.when.apply($, requests).then(function () {
        console.log(arguments);

        alert("Successfully updated comments");
    }, function (err) {
        alert("Could not update comments");
    });

}

//Get the yearly report from the API and insert into the DOM elements for the yearly report page
function yearlyReport(year_callback) {
    $.when(year_callback).then(function () {
        console.log(year_callback);
        var year = $('.year_select[name="report"]').val();
        console.log(year);
        var query_url = "api2/public/member/report/" + year.substring(0, 4) + "/" + year.substring(5, 9); //year is in format "2016/2017"
        console.log(query_url);
        var ajax = $.ajax({
            type: "GET",
            url: query_url,
            data: {},
            dataType: "json",
            async: true
        }).success(function (data) {
            //for(var item in data[0]){
            //    console.log(data[0][item]);
            //	set(data[item],item);
            //}
            //console.log(data);
            ins = data[0];
            console.log(ins);
            //insert the values into DOM
            var report_total = $('#report_total');
            report_total.html(ins.count);
            var report_paid = $('#report_paid');
            report_paid.html(ins.paid);
            var report_unpaid = $('#report_unpaid');
            report_unpaid.html(ins.count - ins.paid);
            var report_student = $('#report_student');
            report_student.html(ins.Student);
            var report_community = $('#report_community');
            report_community.html(ins.Community);
            var report_lifetime = $('#report_lifetime');
            report_lifetime.html(ins.Lifetime);
            var report_staff = $('#report_staff');
            report_staff.html(ins.Staff);

            console.log(interests);
            for (var interest in interests) {
                var value = interests[interest];
                $('#report_' + value).html(ins[value]);
            }

        });

    });
}

function emailList() {
    var email_value;
    $('.email_select_value').each(function (e) {
        if ($(this).is(':visible')) {
            email_value = $(this).val();
        }
    });

    var request = $.ajax({
        type: "GET",
        url: "api2/public/member/email_list",
        data: {
            "type": get('email_select'),
            'value': email_value,
            "year": get(null, 'year_select', 'email'),
            "from": get('from'),
            "to": get('to')
        },
        dataType: "json",
        async: true
    });

    $.when(request).then(
        function (reply) {
            var email_list = $('#email_list');
            email_list.val("");
            var email_list_out = "";

            var length = reply.length;

            if (length == 0) {
                email_list_out += "No results returned";
                email_list.val(email_list_out);
                return;
            }

            for (var email in reply) {
                email_list_out += reply[email].email;

                if (email != length - 1 && length != 1) {
                    email_list_out += ", ";
                }
            }
            email_list.val(email_list_out);
        },
        function (error) {
            console.log(error[0]);
        });

    console.log(email_value);
}

/**
 * Get the URL parameters
 * source: https://css-tricks.com/snippets/javascript/get-url-variables/
 * @param  {String} url The URL
 * @return {Object}     The URL parameters
 */
function getParams(url) {
    var params = {};
    var parser = document.createElement('a');
    parser.href = url;
    var query = parser.search.substring(1);
    var vars = query.split('&');
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split('=');
        params[pair[0]] = decodeURIComponent(pair[1]);
    }
    return params;
};

/**
 * Convert a string to boolean, unless the arg isn't a string and 
 * then it'll just vanilla JS Boolean compare
 * source: https://stackoverflow.com/a/1414175
 * @param  {String} string The string
 * @return {Boolean}       The parsed string turned to Boolean
 */
function stringToBoolean(string){
    switch(string.toLowerCase().trim()){
        case "true": case "yes": case "1": return true;
        case "false": case "no": case "0": case null: return false;
        default: return Boolean(string);
    }
}