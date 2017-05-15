<?php
	$exportCsv			= "Export to CSV";
	$exportExc			= "Export to Excel";
	$copyClpbd			= "Copy to Clipboard";
	$tblCopied			= "Table Copied";
	$copyText			= "Copied";
	$rowText			= "row";
	$toClpBdText		= "to the clipboard.";
	$exportPdf			= "Export to PDF";
	$printViewText		= "Print View";
	$printViewQuip		= "Please use your browser's print function to print this table. Press the Escape (ESC) key when finished.";
?>
<script type="text/javascript">
	TableTools.BUTTONS = {
		"csv": $.extend( {}, TableTools.buttonBase, {
			"sAction": "flash_save",
			"sButtonClass": "DTTT_button_csv",
			"sToolTip": "<?php echo $exportCsv; ?>",
			"sButtonText": "<i class='fa fa-file-excel-o'></i>",
			"sFieldBoundary": '"',
			"sFieldSeperator": ",",
			"fnClick": function( nButton, oConfig, flash ) {
				this.fnSetText( flash, this.fnGetTableData(oConfig) );
			}
		} ),
		"copy": $.extend( {}, TableTools.buttonBase, {
			"sAction": "flash_copy",
			"sButtonClass": "DTTT_button_copy",
			"sToolTip": "<?php echo $copyClpbd; ?>",
			"sButtonText": "<i class='fa fa-copy'></i>",
			"fnClick": function( nButton, oConfig, flash ) {
				this.fnSetText( flash, this.fnGetTableData(oConfig) );
			},
			"fnComplete": function(nButton, oConfig, flash, text) {
				var lines = text.split('\n').length;
				if (oConfig.bHeader) lines--;
				if (this.s.dt.nTFoot !== null && oConfig.bFooter) lines--;
				var plural = (lines==1) ? "" : "s";
				this.fnInfo( '<h6><?php echo $tblCopied; ?></h6>'+
					'<p><?php echo $copyText; ?> '+lines+' <?php echo $rowText; ?>'+plural+' <?php echo $toClpBdText; ?></p>',
					1500
				);
			}
		} ),
		"pdf": $.extend( {}, TableTools.buttonBase, {
			"sAction": "flash_pdf",
			"sNewLine": "\n",
			"sFileName": "*.pdf",
			"sToolTip": "<?php echo $exportPdf; ?>",
			"sButtonClass": "DTTT_button_pdf",
			"sButtonText": "<i class='fa fa-file-pdf-o'></i>",
			"sPdfOrientation": "portrait",
			"sPdfSize": "A4",
			"sPdfMessage": "",
			"fnClick": function( nButton, oConfig, flash ) {
				this.fnSetText( flash,
					"title:"+ this.fnGetTitle(oConfig) +"\n"+
					"message:"+ oConfig.sPdfMessage +"\n"+
					"colWidth:"+ this.fnCalcColRatios(oConfig) +"\n"+
					"orientation:"+ oConfig.sPdfOrientation +"\n"+
					"size:"+ oConfig.sPdfSize +"\n"+
					"--/TableToolsOpts--\n" +
					this.fnGetTableData(oConfig)
				);
			}
		} ),
		"print": $.extend( {}, TableTools.buttonBase, {
			"sInfo": "<h6><?php echo $printViewText; ?></h6><p><?php echo $printViewQuip; ?></p>",
			"sMessage": null,
			"bShowAll": true,
			"sToolTip": "<?php echo $printViewText; ?>",
			"sButtonClass": "DTTT_button_print",
			"sButtonText": "<i class='fa fa-print'></i>",
			"fnClick": function ( nButton, oConfig ) {
				this.fnPrint( true, oConfig );
			}
		} )
	};
</script>