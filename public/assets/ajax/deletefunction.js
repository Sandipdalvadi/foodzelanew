    $('#selectAll').click(function() {
        if ($(this).prop('checked')) {
            $('.case').prop('checked', true);
        } 
        else {
            $('.case').prop('checked', false);
        }
    });

    $('#selectcAll').click(function() {
        if ($(this).prop('checked')) {
            $('.ca').prop('checked', true);
        } 
        else {
            $('.ca').prop('checked', false);
        }
    });
    $('#selecttAll').click(function() {
        if ($(this).prop('checked')) {
            $('.cas').prop('checked', true);
        } 
        else {
            $('.cas').prop('checked', false);
        }
    });
	
	 function multipleDelete(urls){
	    if(confirm("Are you sure you want to delete this?")){ 
	    	var id = [];	
    	    $('input[name=case]:checked').each(function(i){
    	    	id[i] = $(this).val();
    	    });
		    if(id.length === 0){ 
		        alert("Please Select atleast one checkbox");
		    }
		    else{  
			    $.ajax({
				    type : 'POST',
				    // dataType : 'json',
				    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url :urls, 
				    data : {id:id},
				    success:function(responce)
				    {
				        for(var i=0; i<id.length; i++)
				        {
				            $('#'+id[i]).closest('tr').remove();
				            $('tr#'+id[i]).css('background-color', '#ccc');
				            $('tr#'+id[i]).fadeOut('slow');
				        }
				    }, 
				    error: function (e) {
		        		alert('error'); 
		        		e.preventDefault();
		    		} 
		     	}); 
			}
	    } 
	    else {
	    	return false;
	    }
	}