describe('isSmallerFilterTest', ()=>
{
	let fieldsData =
	[
		{
			selectorForSearch: '.em-date-wr',
			selectorWithValue: '.em-date-wr__static-field-value',
			selectData:
			{
				select: dateSelect,
				day:   '8',
				month: 'Jan',
				year:  '2020'
			},
			compare: dateCompare,
			searchValue: '08 Jan 2020 ',
			name: 'date'
		},
	];

	function dateSelect()
	{
		cy.get('.filters-popup__operators-wrapper .em-date-filter-wr__static-field').click();
		cy.get('.filters-popup__operators-wrapper').within(()=>
		{
			cy.get('.day__month_btn.up').click();
			cy.get('.month__year_btn.up').click();
			cy.get('.cell.year').contains(this.year).click();
			cy.get('.cell.month').contains(this.month).click();
			cy.get('.cell.day').contains(new RegExp(this.day + '$')).click();
		});
		cy.get('.filters-popup__operators-wrapper .em-date-filter-wr__static-field').click();
	};

	function dateCompare(valueOfSearch)
	{
		return new Date(this.searchValue) > new Date(valueOfSearch);
	};

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
		cy.get('.select__dropdown').contains(/Is Smaller/).click();

		field.selectData.select();
	});

	Cypress.Commands.add('removeFilter', ()=>
	{
		cy.get('.filters-popup__delete-row-icon-wrapper').click({ multiple: true });
	});

	Cypress.Commands.add('checkField', field=>
	{
		let coincidence = 0,
			reg;

		cy.wait(4000).get(field.selectorForSearch).then(fields=>
		{
			let fieldData = Array.from(fields),
				notEmptyFieldData = fieldData.filter(element=>
				{
					if (!element.querySelector(field.selectorWithValue).innerHTML.replace(/<(.*?)$/, '').match(/empty/i))
						return element;
				});

			for(let fieldDom of notEmptyFieldData)
			{
				let innerHTML = fieldDom.querySelector(field.selectorWithValue).innerHTML.replace(/<(.*?)$/, '');
				if (field.compare(innerHTML))
					coincidence++;
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