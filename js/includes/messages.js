$(document).ready( function () {
   $('#msg').dataTable({
		"paging": false,
		"order": [4, 'desc'],
		"pageLength": -1,
		"columnDefs": [
			// Hide the Full Date Column (order by)
            {
                "targets": [4],
                "visible": false,
                "searchable": false
            },
			{ "width": "2%", "targets": 0 },
			{ "width": "50%", "targets": 1 },
			{ "width": "20%", "targets": 3 }
        ]
	});

	$('#msg').addClass('pt-10 pb-10');

	$('.msgOptions, .msgHeading, .msgContent, .msgClear').hide();
	
	var viewMsgText		= $('#viewMsgText').val();
	var replyMsgText	= $('#replyMsgText').val();
	var markReadText	= $('#markReadText').val();
	var arcMsgText		= $('#arcMsgText').val();
	var delMsgText		= $('#delMsgText').val();

	$('.msgLink').click( function() {
		$('.msgQuip').hide();
		$('.msgHeading, .msgClear').show();

		var fromName = $(this).find('[name="fromName"]').val();
		var msgSubject = $(this).find('[name="msgSubject"]').val();
		var dateSent = $(this).find('[name="dateSent"]').val();
		var msgText = $(this).find('[name="msgText"]').val();
		var msgId = $(this).find('[name="msgId"]').val();
		var toRead = $(this).find('[name="toRead"]').val();

		$('.whoFrom').show().html(fromName);
		$('.theDate').show().html(dateSent);
		$('.msgTitle').show().html(msgSubject);
		$('.msgContent').show().html(msgText.replace(/\\r\\n/g, "<br />"));
		if (toRead === '0') {
			$('.msgOptions').show().html('<form action="" method="post"><a href="index.php?page=viewReceived&messageId='+msgId+'" class="btn btn-hover btn-default btn-xs"><i class="fa fa-external-link text-primary"></i><span>'+viewMsgText+'</span></a> <input name="messageId" value="'+msgId+'" type="hidden" /><a data-toggle="modal" href="#reply'+msgId+'" class="btn btn-hover btn-default btn-xs"><i class="fa fa-reply text-info"></i><span>'+replyMsgText+'</span></a> <input type="hidden" name="messageId" value="'+msgId+'" /><input type="hidden" name="messageTitle" value="'+msgSubject+'" /><button type="input" name="submit" value="markRead" class="btn btn-hover btn-default btn-xs"><i class="fa fa-check text-success"></i><span>'+markReadText+'</span></button> <a data-toggle="modal" href="#delete'+msgId+'" class="btn btn-hover btn-default btn-xs"><i class="fa fa-trash-o text-danger"></i><span>'+delMsgText+'</span></a></form>');
		} else {
			$('.msgOptions').show().html('<form action="" method="post"><a href="index.php?page=viewReceived&messageId='+msgId+'" class="btn btn-hover btn-default btn-xs"><i class="fa fa-external-link text-primary"></i><span>'+viewMsgText+'</span></a> <input name="messageId" value="'+msgId+'" type="hidden" /><a data-toggle="modal" href="#reply'+msgId+'" class="btn btn-hover btn-default btn-xs"><i class="fa fa-reply text-info"></i><span>'+replyMsgText+'</span></a> <input type="hidden" name="messageId" value="'+msgId+'" /><input type="hidden" name="messageTitle" value="'+msgSubject+'" /><button type="input" name="submit" value="archive" class="btn btn-hover btn-default btn-xs"><i class="fa fa-archive text-warning"></i><span>'+arcMsgText+'</span></button> <a data-toggle="modal" href="#delete'+msgId+'" class="btn btn-hover btn-default btn-xs"><i class="fa fa-trash-o text-danger"></i><span>'+delMsgText+'</span></a></form>');
			$('.veiwIt').hover( function() {
				$('.veiwIt').tooltip('show')
			});
			$('.replyIt').hover( function() {
				$('.replyIt').tooltip('show')
			});
			$('.archiveIt').hover( function() {
				$('.archiveIt').tooltip('show')
			});
			$('.deleteIt').hover( function() {
				$('.deleteIt').tooltip('show')
			});
		}
	});
});