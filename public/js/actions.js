function matchCustom(params, data) {
	// If there are no search terms, return all of the data
  if ($.trim(params.term) === '') {
    return data;
  }

  /*** Custom Search ***/
	var searchData = data.text.toLowerCase().trim(),
	searchTerm = params.term.toLowerCase().trim(), 	//typed by user
	nameArray = searchData.split(', '),
	firstName = nameArray[1],
	lastName = nameArray[0];

	firstName = (typeof firstName ===  'undefined')? '':firstName.replace('(cm)', '').trim();
	lastName = (typeof lastName ===  'undefined')? '':lastName.trim();
	var fullName = firstName +' '+ lastName;
	var fullNameRev = lastName +' '+ firstName;

// 	console.log(firstName);
// 	console.log(lastName);
// 	console.log(searchData);
// 	console.log(searchTerm);

	if(firstName.indexOf(searchTerm)>-1) return data;
	if(lastName.indexOf(searchTerm)>-1) return data;
	if(fullName.indexOf(searchTerm)>-1) return data;
	if(fullNameRev.indexOf(searchTerm)>-1) return data;

  // Do not display the item if there is no 'text' property
  if (typeof data.text === 'undefined') {
    return null;
  }

  // `params.term` should be the term that is used for searching
  // `data.text` is the text that is displayed for the data object
  if (data.text.indexOf(params.term) > -1) {
    var modifiedData = $.extend({}, data, true);
    modifiedData.text += ' (matched)';

    // You can return modified objects from here
    // This includes matching the `children` how you want in nested data sets
    return modifiedData;
  }

  // Return `null` if the term should not be displayed
  return null;
}

function editRow(row) {
	$('td.editable', row).each(function() {
		$(this).html('<input type="text" name="'+ $(this).data('name') +'" value="' + $(this).html() + '" style="width: 100%;" />');
	});

	/* Responsive */
	if(!$(row).hasClass('parent')) {
		$('td.dtr-control', row).trigger('click');
	}
	if($(row).hasClass('parent')) {
		$(row).next('tr.child').find('li.editable span.dtr-data').each(function() {
			var colIndex = $(this).closest('li.editable').data('dt-column');
			var parentCol = $(row).find('td').eq(colIndex);
			var name = parentCol.data('name');
			$(this).html('<input type="text" name="'+name +'" value="' + $(this).text() + '" style="width: 100%;" />');
		});
	}
}
function cancelEdit(row) {
	$('td.editable', row).each(function() {
		if($(this).find('input').hasClass('error')) {
			$(this).html($(this).find('input').attr('value'));
		}
		else {
			$(this).html($(this).find('input').val());
		}
	});

	/* Responsive */
	if($(row).hasClass('parent')) {
		$('td.dtr-control', row).trigger('click');
		$('#communityTable').DataTable().ajax.reload();
	}
	
	$('#validation-errors').html('').removeClass();
}
function submit(id, row, updateURL) {
	var inputData = {};
	$('td.editable input', row).each(function() {
		inputData[$(this).attr('name')] = $(this).val();
	});
	inputData['id'] = id;

	/* Responsive */
	if($(row).hasClass('parent')) {
		$(row).next('tr.child').find('li.editable span.dtr-data input').each(function() {
			inputData[$(this).attr('name')] = $(this).val();			
		});		
	}

	$.ajax({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		type: 'post',
		url: updateURL,
		data: inputData,
		dataType: 'json',
		success: function() {
			cancelEdit(row);
			$(row).find('div.submitButtons').hide();
			$(row).find('div.submitButtons').siblings('span.saved').show();
			$('#validation-errors').html('').removeClass();
		},
		error: function(xhr) {
			$('#validation-errors').html('');
			$('#validation-errors').addClass('alert alert-danger');
			$.each(xhr.responseJSON.errors, function(key, value) {
				$(row).find('input[name='+ key +']').addClass('error border border-danger');
				$('#validation-errors').append('<div>'+value+'</div>');
			}); 
		},
	});
}
function destroy(id, row, delURL) {
	$.ajax({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		type: 'post',
		url: delURL,
		data: {
			id: id,
		},
		success: function() {
			$(row).remove();
		}
	});
}
