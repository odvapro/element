describe('isFilterTest', ()=>
{
	function checkField(name, item)
	{
		cy.wait(500).get('.filters-popup__wrapper').contains('Add filter').click();

		cy.get('.select__trigger').contains('primary_key').click();
		cy.get('.select__dropdown').contains(name).click();

		cy.get('.select__trigger').contains('Is Not Empty').click();
		cy.get('.select__dropdown').contains(/Is$/).click();

		item.check(item.valueForFilter);

		cy.wait(500).get(item.classForFilter).eq(item.correctResult).get('.filters-popup__delete-row-icon-wrapper').click({ multiple: true });
	};

	function checkStrLike(valueForFilter)
	{
		console.log(valueForFilter);
		cy.get('.filters-popup__filter-input').type(valueForFilter);
	};

	function checkDate(date)
	{
		cy.get('.em-date-filter-wr').click();
		cy.get('.day__month_btn.up').click();
		cy.get('.month__year_btn.up').click();

		cy.get('.cell.year').contains(date.year).click();
		cy.get('.cell.month').contains(date.month).click();
		cy.get('.cell.day').contains(date.day).click();
	};

	function checkListLike(valueForFilter)
	{
		cy.get('.filters-popup__wrapper .list').click();
		if (typeof valueForFilter === 'array')
		{
			cy.wait(500).contains(valueForFilter[0]).click();
			cy.get('.index__menu-item').contains('Filter').click();
		}
		else
			cy.wait(500).contains(valueForFilter).click();

	};

	let fieldsData =
	{
		primary_key:
		{
			valueForFilter: '6',
			correctResult: 1,
			classForFilter: '.em-primary',
			check: checkStrLike,
		},
		date:
		{
			valueForFilter: {year: '2020', month: 'Jan', day: '10'},
			correctResult: 1,
			classForFilter: '.em-date-wr',
			check: checkDate,
		},
		node:
		{
			valueForFilter: ['val3'],
			correctResult: 1,
			classForFilter: '.em-node',
			check: checkListLike,
		},
		text:
		{
			valueForFilter: "<p>Text</p>\n",
			correctResult: 1,
			classForFilter: '.em-text',
			check: checkStrLike,
		},
		list:
		{
			valueForFilter: 'val2',
			correctResult: 3,
			classForFilter: '.em-list',
			check: checkListLike,
		},
		string:
		{
			valueForFilter: 'String',
			correctResult: 1,
			classForFilter: 'em-string',
			check: checkStrLike,
		},
	};

	it('visit a page', ()=>
	{
		cy.visit('localhost:8080/element');
	});

	it('log in', ()=>
	{
		cy.get('.auth-form-input__login').type('admin');
		cy.get('.auth-form-input__password').type('adminpass');
		cy.get('.auth-fill-btn.el-btn').click();
		cy.setCookie('user', '%7B%22name%22%3A%22%D0%9C%D0%B8%D1%85%D0%B0%D0%B8%D0%BB%22%2C%22id%22%3A%221%22%2C%22email%22%3A%22axel0726%40gmail.com%22%2C%22avatar%22%3A%22https%3A%2F%2Fwww.gravatar.com%2Favatar%2F28ff2f48655820e1aaf15687dc5e6be5%26s%3D40%22%2C%22language%22%3A%7B%22short%22%3A%22en%22%2C%22long%22%3A%22English%22%7D%7D');
		cy.visit('localhost:8080/element');

		cy.wait(1000).get('.sidebar-name-wrapper').contains('cypressTest').click();
		cy.get('.index__menu-item').contains('Filter').click();
	});

	it('checking primary_key', ()=>
	{
		checkField('primary_key', fieldsData.primary_key);
	});

	it('checking date', ()=>
	{
		checkField('date', fieldsData.date);
	});

	it('checking node', ()=>
	{
		checkField('node', fieldsData.node);
	});

	it('checking text', ()=>
	{
		checkField('text', fieldsData.text);
	});

	it('checking list', ()=>
	{
		checkField('list', fieldsData.list);
	});

	it('checking string', ()=>
	{
		checkField('string', fieldsData.string);
	});
});