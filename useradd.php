<?php
session_start();
require("headers/security_header.php");
require("headers/menu_header.php");

printf("<html><head><meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">");
printf("<link rel=stylesheet href=citr.css type=text/css>");
printf("<title>DJ LAND | users</title></head><body>");

print_menu();

if(isset($_GET['action']) && $_GET['action'] == 'add') {

	if(isset($_POST['edit']) && $_POST['edit']) {
		if(is_member("administrator")) {
			$result = mysqli_query($db,"SELECT * FROM user WHERE username='".fas($_POST['edit'])."'");
		}
		else {
			$result = mysqli_query($db,"SELECT * FROM user WHERE username='".fas($_POST['edit'])."' AND username='".fas($_SESSION['sv_username'])."'");
		}
		$ed = mysqli_num_rows($result);
	}
	else {
		$ed = 0;
	}


	if($ed || is_member("adduser")) {
		//make sure they exist
		if(test_cgi('username') && ($ed || (test_cgi('password') && test_cgi('password2')))) {
			if($_POST['password'] != $_POST['password2']) {
				printf("<H2>Passwords do not match.</H2><BR><BR>\n");
				printf("<A href=\"javascript:history.go(-1)\">go back</A>");
			}
			else {
				//edit it...
				if($ed){
					//Password...
					if(test_cgi('password')) {
						//Make sure it's not an operator...
						if(!mysqli_num_rows(mysqli_query($db, "SELECT * FROM group_members WHERE username='".fas($_POST['edit'])."' AND groupname='operator'")) || $_POST['edit'] == $_SESSION['sv_username']) {
							$result = mysqli_query($db, "UPDATE user SET password='".md5($_POST['password'])."' WHERE username='".fas($_POST['edit'])."'");
						}
					}
					//set fields for edit name field...
					$result = mysqli_query($db, "UPDATE user SET edit_name='".fas($_SESSION['sv_username'])."', login_fails='0' WHERE username='".fas($_POST['edit'])."'");
				}
				//create it...
				else {
					$result = mysqli_query($db, "SELECT * FROM user WHERE username = '".fas($_POST['username'])."'");
					if(mysqli_num_rows($result)) {
						printf("<H2>User already exists.</H2><BR><BR>\n");
						printf("<A href=\"javascript:history.go(-1)\">go back</A>");
					}
					else {
						$result = mysqli_query($db,"INSERT INTO user (username, password, status, login_fails, create_date, create_name) VALUES ('".fas($_POST['username'])."','".md5($_POST['password'])."','Enabled','0','".date('Y-m-d H:i:s')."','".fas($_SESSION['sv_username'])."')");
					}
				}
		
				//Do general stuff...
				//

				//Do groups, characters, etc...
				if(is_member("administrator")) {

					//Update 
					$result = mysqli_query($db, "UPDATE user SET status='".fas($_POST['status'])."' WHERE username='".fas($_POST['username'])."'");

					//Add to groups
					$result = mysqli_query($db, "DELETE FROM group_members WHERE username='".fas($_POST['username'])."' AND groupname !='operator'");
					$result = mysqli_query($db,"SELECT * FROM groups WHERE name != 'operator'");
					$num_rows = mysqli_num_rows($result);
					$count = 0;
					while ($count < $num_rows) { 
						$cgi_group = "group_" . mysqli_result_dep($result,$count,"name");
						if(test_cgi($cgi_group)) {
							$result2 = mysqli_query($db, "INSERT INTO group_members (username, groupname) VALUES ('".fas($_POST['username'])."','".fas(mysqli_result_dep($result,$count,"name"))."')");
						}
						$count++;
					}

				}	
				else if(!$ed) {
					$result = mysqli_query($db,"INSERT INTO group_members (username, groupname) VALUES ('".fas($_POST['username'])."','member')");
				}
				printf("<H2>User %s: %s</H2><BR><BR>\n", $ed ? "edited" : "added", $_POST['username']);
			}
		}
		else {
			printf("<H2>Some required data is missing...</H2><BR><BR>\n");
			printf("<A href=\"javascript:history.go(-1)\">go back</A>");
		}
	}
}
else if(isset($_GET['action']) && $_GET['action'] == 'list') {

	if(is_member("adduser")) {
		printf("<br><table class=menu border=0 align=center><tr>");
		printf("<td class=menu><a href=useradd.php>&nbsp;Add New User&nbsp;</a></td></tr></table>");
	}	

	if(is_member("adduser")) {
		$result = mysqli_query($db,"SELECT * FROM user ORDER BY username");
	}
	else {
		$result = mysqli_query($db,"SELECT * FROM user WHERE username='".fas($_SESSION['sv_username'])."' ORDER BY username");
	}
	$num_rows = mysqli_num_rows($result);
	$count = 0;

	printf("<CENTER><FORM METHOD=\"GET\" ACTION=\"useradd.php\" name=\"the_form\">\n");
	printf("<SELECT NAME=\"edit\" SIZE=15>\n");

	while($count < $num_rows) {
		$user_name = mysqli_result_dep($result,$count,"username");
		printf("<OPTION>%s\n", $user_name);
		$count++;
	}

	printf("</SELECT><BR><INPUT TYPE=submit VALUE=\" Edit \">\n");
	printf("</FORM></CENTER>\n");

}
else {

	if(isset($_GET['edit']) && $_GET['edit']) {
		if(is_member("administrator")) {
			$result = mysqli_query($db,"SELECT * FROM user WHERE username='".fas($_GET['edit'])."'");
		}
		else {
			$result = mysqli_query($db,"SELECT * FROM user WHERE username='".fas($_GET['edit'])."' AND username='".fas($_SESSION['sv_username'])."'");
		}
		$ed = mysqli_num_rows($result);
	}
	else {
		$ed = 0;
	}

	if($ed || is_member("adduser")) {
	
		printf("<br><br><br><br><br><table width=600 class=box align=center>");
		printf("<tr><td colspan=2 align=center><br><h1>%s User</h1></td></tr>", ($ed ? "Edit" : "Add New"));

		printf("<FORM METHOD=post ACTION=\"useradd.php?action=add\" name=\"the_form\" OnSubmit=\"javascript:if(document.the_form.password.value == document.the_form.password2.value) return true; else {alert('Passwords do not match'); return false;}\">");

		$user_name = $ed ? mysqli_result_dep($result, 0, "username") : "";
		$user_status = $ed ? mysqli_result_dep($result, 0, "status") : "";
		$user_login_fails = $ed ? mysqli_result_dep($result, 0, "login_fails") : 0;
		$is_in_group = 0;

		if($user_login_fails) {
			printf("<tr><td colspan=2>WARNING!!! User has had %s consecutive login failures. User might be a hacker if this number is large<BR><br></td></tr>\n", $user_login_fails);
		}

		if($ed) {
			printf("<tr><td align=right>Username:</td><td><INPUT TYPE=hidden NAME=username VALUE=\"%s\">%s</td></tr>", $user_name, $user_name);
		}
		else {
			printf("<tr><td align=right>Username:</td><td><INPUT TYPE=text SIZE=20 MAXLENGTH=20 NAME=username VALUE=\"%s\"></td></tr>\n", $user_name);
		}

		printf("<tr><td align=right>Password:</td><td><INPUT TYPE=password SIZE=20 MAXLENGTH=20 NAME=password VALUE=\"\"></td></tr>\n");
		printf("<tr><td align=right>Password Confirm:</td><td><INPUT TYPE=password SIZE=20 MAXLENGTH=20 NAME=password2 VALUE=\"\">\n");
		if($ed) {
			printf("(Leave these blank to keep current password)\n");
		}
		printf("</td></tr>\n");

		if((is_member("administrator"))) {

			printf("<tr><td align=right>Status:</td><td><select name=status size=1>\n");
			if($ed) {
				printf("<option>%s</option>\n", $user_status);
			}
			$result = mysqli_query($db,"SELECT * FROM login_status WHERE name!='".fas($user_status)."' ORDER BY name DESC");
			$count = 0;
			$num_rows = mysqli_num_rows($result);
			while ($count < $num_rows) { 
			//	printf("<option>%s</option>\n", mysqli_result_dep($result,$count,"name"));
				$count++;
			}
            if ($user_status == 'Disabled'){
            printf("<option>Enabled</option>");
            } else if ($user_status == 'Enabled'){
            printf("<option>Disabled</option>");
            } else {
                printf("<option>Enabled</option>");
                printf("<option>Disabled</option>");

            }
			printf("</select></td></tr>\n");

			printf("<tr><td align=right valign=top>Groups:</td><td>\n");
			$result = mysqli_query($db,"SELECT * FROM groups WHERE name != 'operator' ORDER BY name DESC");
			$count = 0;
			$num_rows = mysqli_num_rows($result);
			while ($count < $num_rows) {
				$group_name = mysqli_result_dep($result,$count,"name");
				if($ed) {
					$is_in_group = mysqli_num_rows(mysqli_query($db,"SELECT * FROM group_members WHERE username='".fas($_GET['edit'])."' AND groupname='".fas($group_name)."'"));
				}
				printf("<input type=checkbox name=\"group_%s\"%s> %s - %s<br>", $group_name, (($group_name == "member" && !$ed) || ($ed && $is_in_group)) ? " checked" : "", $group_name, mysqli_result_dep($result,$count,"description"));
				$count++;
			}
			printf("</td></tr>");
		}

		printf("<tr><td colspan=2 align=center><BR><INPUT TYPE=submit NAME=submitname VALUE=\"%s\">", $ed ? "Edit User" : "Create User");
		if($ed) {
			printf("<INPUT TYPE=hidden NAME=edit VALUE=\"%s\">", $user_name);
		}
		printf("<INPUT TYPE=reset NAME=resetname VALUE=Clear></FORM>");
		printf("</td></tr></table>");
	}
}

printf("</body></html>");

?>
