$("#reason").change(function () {
    	if(this.value == 'Other') {
    		$("#pecify").val('');
            $('#specified').show();

        } else {
        	$("#specify").val('');
            $('#specified').hide();

        }

    });