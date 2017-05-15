/**
 * Translations
 * Only translate lines 69 through 73.
 * ie. user_name.html("Name: &nbsp; "+obj[0].userFirst+" "+obj[0].userLast);
 * Translate only the text "Name" and no other code.
 *
 * If you have any questions at all about this file, please contact me through my Support Center:
 * http://jennperrin.com/support/
 **/
$(document).ready( function () {
	$(".userInfo, .admin, .superuser, .typeMessage").hide();	// Hide on page load

	var msgText		= $("#msgText").find('span');
	var errorOne	= $("#errorOne").val();

	$('input[type="checkbox"]').click(function() {
		// Show a message if an Admin has not been loaded
		if ($("#theId").val() == '' && $("#usrName3").val() == '') {
			var msgText	= $("#msgText").find('span');
			msgText.html('<div class="alertMsg warning"><div class="msgIcon pull-left"><i class="fa fa-warning"></i></div>'+errorOne+'</div>');
			msgText.show();
		}
	});

	// Load an Admin
	$("#loadUser").click(function(e) {
		e.preventDefault();

		$("#loadUser").addClass('disabled');

		// Set some variables
		var usersId			= $("#selectUser").val();
		var usersName		= $('#selectUser option:selected').text();
		var user_name		= $("#user_name").find('span');
		var user_email		= $("#user_email").find('span');
		var user_position	= $("#user_position").find('span');
		var is_admin		= $("#is_admin").find('span');
		var is_superuser	= $("#is_superuser").find('span');

		// Get started!
		if (usersId !== '...') {
			// Make the ajax call
			post_data = {'usersId':usersId};
			$.post('pages/userAuths_f.php', post_data, function(datares) {
				if (datares.indexOf("userEmail") > 0) {
					// User found, load the data
					var obj = $.parseJSON(datares);

					var yesOpt	= $("#yesOpt").val();
					var noOpt	= $("#noOpt").val();

					if (obj[0].isAdmin == '1') {
						var admin = yesOpt;
					} else {
						var admin = noOpt;
					}
					if (obj[0].superUser == '1') {
						var superUser = yesOpt;
					} else {
						var superUser = noOpt;
					}
					if (obj[0].userPosition == null) {
						var userPosition = '';
					} else {
						var userPosition = obj[0].userPosition;
					}

					// Display the Admins Name & Email for reference
					user_name.html("Name: &nbsp; "+obj[0].userFirst+" "+obj[0].userLast);
					user_email.html("Email: &nbsp; "+obj[0].userEmail);
					user_position.html("Position: &nbsp; "+userPosition);
					is_admin.html("Administrator/Manager: &nbsp; "+admin);
					is_superuser.html("Superuser Access: &nbsp; "+superUser);

					// Populate the hidden Admin's ID & Name Field
					$("#usrId1").val(obj[0].userId);
					$("#usrName1").val(obj[0].userFirst+" "+obj[0].userLast);
					$("#usrId2").val(obj[0].userId);
					$("#usrName2").val(obj[0].userFirst+" "+obj[0].userLast);
					$("#theId").val(obj[0].userId);
					$("#usrName3").val(obj[0].userFirst+" "+obj[0].userLast);
					$("#isadminStatus").val(isAdmin);
					$("#isSuperuserStatus").val(superUser);

					// Set the Admin Status Select Option
					//var admStat	= $("#isadminStatus").val();
					$("select#isAdmin option").each(function() { this.selected = (this.text == admin); });

					// Set the Superuser Status Select Option
					//var admStat	= $("#isSuperuserStatus").val();
					$("select#isSuperuser option").each(function() { this.selected = (this.text == superUser); });

					// Loop through the Auth Flags for the Admin
					$.each($.parseJSON(datares), function(idx, obj) {
						// Set the appropriate checkboxes as checked
						$('#'+obj.authFlag+'').prop('checked', true);
					});

					// Show the data
					$(".userInfo, .admin, .superuser, .typeMessage").show();
					msgText.hide();
				}
			});
		} else {
			// Show an error
			msgText.html('<div class="alertMsg warning"><div class="msgIcon pull-left"><i class="fa fa-warning"></i></div>'+errorOne+'</div>');
			msgText.show();
			$(".userInfo, .admin, .superuser, .typeMessage").hide();
			$("#loadUser").removeClass('disabled');
		}
	});

	// Reset the Form Data
	$(".resetForm").click(function(e) {
		e.preventDefault();
		$("#selectUser option:first").prop("selected", "selected");
		$('.chosen-single span').html('Select User');
		$('input[type="checkbox"]').removeAttr('checked');
		$(".userInfo, .admin, .superuser, .typeMessage").hide();
		$("#loadUser").removeClass('disabled');
		$("#theId, #usrName3").val('');
		msgText.hide();
	});
});