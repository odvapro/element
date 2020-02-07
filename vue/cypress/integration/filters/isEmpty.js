describe('isEmptyFilterTest', ()=>
{
	let fieldsData =
	[
		{
			cssClass: '.em-primary',
			name: 'primary_key'
		},
		{
			cssClass: '.em-date-wr',
			name: 'date'
		},
		{
			cssClass: '.em-file-item-col',
			name: 'file'
		},
		{
			cssClass: '.em-node',
			name: 'node'
		},
		{
			cssClass: '.em-text',
			name: 'text'
		},
		{
			cssClass: '.em-list',
			name: 'list'
		},
		{
			cssClass: '.em-string',
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

	Cypress.Commands.add('addFilter', fieldName=>
	{
		cy.contains('Add filter').closest('button').click();

		cy.get('.filters-popup__wrapper').contains('Is Not Empty').closest('button').click();
		cy.get('.select__dropdown').contains('Is Empty').click();

		cy.get('.filters-popup__wrapper').contains('primary_key').closest('button').click();
		cy.get('.select__dropdown').contains(fieldName).click();
	});

	Cypress.Commands.add('removeFilter', ()=>
	{
		cy.get('.filters-popup__delete-row-icon-wrapper').click({ multiple: true });
	});

	Cypress.Commands.add('checkField', field=>
	{
		let emptys = 0;
		cy.wait(4000).get(field.cssClass).then(fields=>
		{
			let fieldData = Array.from(fields);

			for(let field of fieldData)
			{
				if (field.innerHTML.match(/Empty/) && !field.innerHTML.match(/placeholder="Empty"/))
					emptys++;
				else
					if(field.querySelector('input'))
						if(!field.querySelector('input').value)
							emptys++;
			}
			cy.addFilter(field.name);

			if(emptys === 0)
				cy.get('.table-row').should('have.length', 2).should('be.visible');
			else
				cy.wait(5000).get(field.cssClass).then(fieldsAfterFilter=>
				{
					if (fieldsAfterFilter.length !== (emptys))
						throw new Error(`Incorrect result for '${field.name}'. Expected ${emptys} fields, have ${fieldsAfterFilter.length}.`);
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