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