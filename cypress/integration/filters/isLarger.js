describe('isLargerFilterTest', ()=>
{
	let fieldsData =
	{
		date:
		{
			valueForFilter: '1',
			classForFilter: '.em-date-wr',
			correctResult: 10,
		},
	};

	function checkField(name, item)
	{
		cy.get('.filters-popup__wrapper').contains('Add filter').click();

		cy.get('.select__trigger').contains('primary_key').click();
		cy.get('.select__dropdown').contains(name).click();

		cy.get('.select__trigger').contains('Is Not Empty').click();
		cy.get('.select__dropdown').contains('Is Larger').click();
		checkDate({year: '2020', month: 'Jan', day: '10'});

		cy.get(item.classForFilter).eq(item.correctResult).get('.filters-popup__delete-row-icon-wrapper').click({ multiple: true });
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

	it('checking date', ()=>
	{
		checkField('date', fieldsData.date);
	});
});