function getEditButtons(id) {
	var html='\
		<div class="editButtons">\
				<button type="button" class="btn btn-light btn-sm editButton">Edit</button>\
				<button type="button" class="btn btn-danger btn-sm deleteButton">Delete</button>\
				<img src="/images/check.svg" class="saved" style="width: 35px; display: none;">\
			</div>\
			<div class="delButtons" style="display: none;">\
					<button type="button" class="btn btn-danger btn-sm confirmDelete" data-id="'+ id +'">Confirm</button>\
					<button type="button" class="btn btn-light btn-sm cancelDelete">Cancel</button>\
				</div>\
		';
		return html;
}
