describe('isEmptyFilterTest', ()=>
{
	let fieldsData =
	{
		primary_key:
		{
			classForFilter: '.em-primary',
			correctResult: 0,
		},
		date:
		{
			classForFilter: '.em-date-wr',
			correctResult: 0,
		},
		file:
		{
			classForFilter: '.em-file-item-col',
			correctResult: 17,
		},
		flag:
		{
			classForFilter: '.em-check-wrapper',
			correctResult: 0,
		},
		node:
		{
			classForFilter: '.em-node',
			correctResult: 10,
		},
		text:
		{
			classForFilter: '.em-text.em-text__table',
			correctResult: 14,
		},
		list:
		{
			classForFilter: '.em-list',
			correctResult: 11,
		},
		string:
		{
			classForFilter: '.em-string',
			correctResult: 12
		},
	};

	function checkField(name, item)
	{

		cy.get('.filters-popup__wrapper').contains('Add filter').click();

		cy.get('.select__trigger').contains('primary_key').click();
		cy.get('.select__dropdown').contains(name).click();

		cy.get('.select__trigger').contains('Is Not Empty').click();
		cy.get('.select__dropdown').contains('Is Empty').click();

		cy.get(item.classForFilter).eq(item.correctResult).get('.filters-popup__delete-row-icon-wrapper').click({ multiple: true });

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

	it('checking file', ()=>
	{
		checkField('file', fieldsData.file);
	});

	it('checking flag', ()=>
	{
		checkField('flag', fieldsData.flag);
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