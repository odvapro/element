describe('isFilterTest', ()=>
{
	let fieldsData =
	[
		{
			selectorForSearch: '.em-primary',
			selectorWithValue: '.em-primary',
			searchText: '1',
			name: 'primary_key'
		},

		{
			selectorForSearch: '.em-text',
			searchText: '<p>Text',
			name: 'text'
		},

		{
			selectorForSearch: '.em-string',
			searchText: 'String',
			name: 'string'
		},
	];

	it('log in', ()=>
	{
		cy.visit('localhost:8080/element');
		cy.get('.auth-form-input__login').type('admin');
		cy.get('.auth-form-input__password').type('adminpass');
		cy.get('.auth-fill-btn.el-btn').click();
		cy.setCookie('user', '%7B%22name%22%3A%22%D0%9C%D0%B8%D1%85%D0%B0%D0%B8%D0%BB%22%2C%22id%22%3A%221%22%2C%22email%22%3A%22axel0726%40gmail.com%22%2C%22avatar%22%3A%22https%3A%2F%2Fwww.gravatar.com%2Favatar%2F28ff2f48655820e1aaf15687dc5e6be5%26s%3D40%22%2C%22language%22%3A%7B%22short%22%3A%22en%22%2C%22long%22%3A%22English%22%7D%7D');
		cy.visit('localhost:8080/element');

		cy.wait(1000).get('.sidebar-name-wrapper').contains('cypressTest').click({ multiple: true });
		cy.get('.index__menu-item').contains('Filter').click();
	});

	Cypress.Commands.add('addFilter', field=>
	{
		cy.contains('Add filter').closest('button').click();

		cy.get('.filters-popup__wrapper').contains('primary_key').closest('button').click();
		cy.get('.select__dropdown').contains(field.name).click();

		cy.get('.filters-popup__wrapper').contains('Is Not Empty').closest('button').click();
		cy.get('.select__dropdown').contains(/Start With/).click();

		if(!field.hasOwnProperty('selectData'))
			cy.get('.filters-popup__filter-input.el-inp').type(field.searchText);
		else
			field.selectData.select();
	});

	Cypress.Commands.add('removeFilter', ()=>
	{
		cy.get('.filters-popup__delete-row-icon-wrapper').click({ multiple: true });
	});

	Cypress.Commands.add('checkField', field=>
	{
		let coincidence = 0;
		cy.scrollTo('center');

		cy.wait(4000).get(field.selectorForSearch).then(fields=>
		{
			let fieldData = Array.from(fields);

			for(let fieldDom of fieldData)
			{
				if (fieldDom.innerHTML.match(/<input/))
				{

					if (fieldDom.querySelector('input').value.match(new RegExp('^' + field.searchText, 'i')))
						coincidence++;
				}
				else
				{
					let innerHTML;
						innerHTML = fieldDom.innerHTML.replace(/\t/, "").replace(/\n/, "").replace(/<!---->/, "");


					if (   innerHTML.match(new RegExp('^' + field.searchText))
						|| innerHTML.replace(/\s/, "").match(new RegExp('^' + field.searchText)))
						coincidence++;
				}
			};

			cy.addFilter(field);
			if (coincidence === 0)
				cy.get('.table-row').should('have.length', 2).should('be.visible');
			else
				cy.wait(5000).get(field.selectorForSearch).then(fieldsAfterFilter=>
				{
					if (fieldsAfterFilter.length !== coincidence)
						throw new Error(`Incorrect result for '${field.name}'. Expected ${coincidence} fields, have ${fieldsAfterFilter.length}.`);
				});

			cy.removeFilter();
		});
	});

	it('check all', ()=>
	{
		for(let field of fieldsData)
			cy.checkField(field);
	});
});