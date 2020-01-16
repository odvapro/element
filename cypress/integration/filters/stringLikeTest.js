describe('stringLikeTest', ()=>
{
	it('visit a page', ()=>
	{
		cy.visit('localhost:8080/element');
	});
	it('log in', ()=>
	{
		cy.get('.auth-form-input__login').type('admin');
		cy.get('.auth-form-input__password').type('adminpass');
		cy.get('.auth-fill-btn.el-btn').click();
		cy.url().should('include', '/settings');
	});

	it('to filter', ()=>
	{
		cy.get('.sidebar-tables-list').within(($testList)=>
		{
			cy.contains('test1').click();
			cy.url().should('include', 'table/test1');
		})
	});

	// it('is', ()=>
	// {

	// });

	// it('is', ()=>
	// {

	// });

	// it('is not', ()=>
	// {

	// });

	// it('is empty', ()=>
	// {

	// });

	// it('is not empty', ()=>
	// {

	// });

	// it('contains', ()=>
	// {

	// });

	// it('does not contain', ()=>
	// {

	// });

	// it('start with', ()=>
	// {

	// });

	// it('end with', ()=>
	// {

	// });
});