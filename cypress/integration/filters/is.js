describe('isFilterTest', ()=>
{
	let fieldsData =
	[
		// {
		// 	selectorForSearch: '.em-primary',
		// 	selectorWithValue: '.em-primary',
		// 	searchText: '6',
		// 	name: 'primary_key'
		// },

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
			searchText: '08 Jan 2020',
			name: 'date'
		},

		// {
		// 	selectorForSearch: '.em-node',
		// 	selectorWithValue: '.list-option span',
		// 	searchText: 'val1',
		// 	selectData:
		// 	{
		// 		select: listSelect,
		// 		value: 'val1',
		// 	},
		// 	name: 'node'
		// },

		// {
		// 	selectorForSearch: '.em-text',
		// 	selectorWithValue: '.em-text.em-text__table input',
		// 	searchText: '<p>Text</p>',
		// 	name: 'text'
		// },

		// {
		// 	selectorForSearch: '.em-list',
		// 	selectorWithValue: '.list-option span',
		// 	searchText: 'val2',
		// 	selectData:
		// 	{
		// 		select: listSelect,
		// 		value: 'val2',
		// 	},
		// 	name: 'list'
		// },

		// {
		// 	selectorForSearch: '.em-string',
		// 	selectorWithValue: 'input',
		// 	searchText: 'String',
		// 	name: 'string'
		// },
	];


	function listSelect()
	{
		cy.get('.filters-popup__operators-wrapper .list span').click();
		cy.wait(3000).get('.filters-popup__operators-wrapper .list-option span').contains(this.value).click();
	};

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
		cy.get('.select__dropdown').contains(/Is$/).click();

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
		let coincidence = 0,
			reg;

		cy.wait(4000).get(field.selectorForSearch).then(fields=>
		{
			let fieldData = Array.from(fields);
			console.log(fieldData);
			for(let fieldDom of fieldData)
			{
				if (fieldDom.innerHTML.match(/<input/))
				{
					reg = new RegExp('^' + field.searchText + '$', 'i');

					if (fieldDom.querySelector('input').value.match(reg))
						coincidence++;
				}
				else
				{
					// let innerHTML = fieldDom.innerHTML.replace(/\s/, "").replace(/\n/, "").replace(/\t/, "");
					// reg = new RegExp('<span>' + field.searchText);
					// console.log('"' + fieldDom.innerHTML + '"', '"' + innerHTML + '"');
					// if (   innerHTML.match(reg)
					// 	|| innerHTML.match(new RegExp('^' + field.searchText + '$')))
					// 	coincidence++;
				}
			};

			console.log(coincidence);
			// cy.addFilter(field);
			// if (coincidence === 0)
			// 	cy.get('.table-row').should('have.length', 2).should('be.visible');
			// else
			// 	cy.wait(5000).get(field.selectorForSearch).then(fieldsAfterFilter=>
			// 	{
			// 		if (fieldsAfterFilter.length !== coincidence)
			// 			throw new Error(`Incorrect result for '${field.name}'. Expected ${coincidence} fields, have ${fieldsAfterFilter.length}.`);
			// 	});

			// cy.removeFilter();
		});








		// let coincidence = 0,
		// 	reg = new RegExp(field.searchText, 'i');

		// cy.wait(4000).get(field.selectorForSearch).then(fields=>
		// {
		// 	let fieldData = Array.from(fields);

		// 	for(let fieldDom of fieldData)
		// 	{
		// 		if (fieldDom.innerHTML.match(/<input/))
		// 		{

		// 			if (fieldDom.querySelector('input').value.match(reg))
		// 				coincidence++;
		// 		}
		// 		else
		// 		{
		// 			if (fieldDom.innerHTML.match(reg))
		// 				coincidence++;
		// 		}
		// 	};
		// 	cy.addFilter(field);

		// 	if (coincidence === 0)
		// 		cy.get('.table-row').should('have.length', 2).should('be.visible');
		// 	else
		// 		cy.wait(5000).get(field.selectorForSearch).then(fieldsAfterFilter=>
		// 		{
		// 			if (fieldsAfterFilter.length !== coincidence)
		// 				throw new Error(`Incorrect result for '${field.name}'. Expected ${coincidence} fields, have ${fieldsAfterFilter.length}.`);
		// 		});

		// 	cy.removeFilter();
		// });
	});

	it('check all', ()=>
	{
		for(let field of fieldsData)
			cy.checkField(field);
	});
});