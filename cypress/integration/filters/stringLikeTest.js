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
		cy.setCookie('user', '%7B%22name%22%3A%22Untitel%22%2C%22id%22%3A%221%22%2C%22email%22%3A%22admin%40email.com%22%2C%22avatar%22%3A%22https%3A%2F%2Fwww.gravatar.com%2Favatar%2F59235f35e4763abb0b547bd093562f6e%26s%3D40%22%2C%22language%22%3A%7B%22short%22%3A%22en%22%2C%22long%22%3A%22English%22%7D%7D');
	});

	it('add filter', ()=>
	{
		cy.wait(500).visit('localhost:8080/element/table/products/tview/20/page/1/');
		cy.get('.index__head-options-list').contains('Filter').click();
		cy.get('.el-gbtn').contains('Add filter').click();
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