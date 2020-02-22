describe('authTest', ()=>
{
	before(()=>{cy.resetDB()});

	it('empty data', ()=>
	{
		cy.visit('/');
		cy.get('[data-test="auth-login"]').click();
		cy.get('[data-test="input-login"]').should('have.class', 'el-inp--error');
		cy.get('[data-test="input-password"]').should('have.class', 'el-inp--error');
		cy.contains('Incorrect login');
		cy.contains('Incorrect password');
	});

	it('incorrect data', ()=>
	{
		cy.login('incorrectLogin', 'incorrectPassword');
		cy.get('[data-test="auth-login"]').click();
		cy.get('[data-test="error-text"]').should('contain', 'Incorrect login or password');
	});

	it('incorrect one of inputs', ()=>
	{
		cy.login('incorrectLogin', 'adminpass');
		cy.get('[data-test="error-text"]').should('contain', 'Incorrect login or password');

		cy.login('admin', 'incorrectPassword');
		cy.get('[data-test="error-text"]').should('contain', 'Incorrect login or password');

		cy.get('[data-test="input-login"]').clear();
		cy.get('[data-test="input-password"]').clear().type('incorrectPassword');
		cy.get('[data-test="auth-login"]').click();
		cy.get('[data-test="error-text"]').should('contain', 'Incorrect login');

		cy.get('[data-test="input-login"]').clear().type('admin');
		cy.get('[data-test="input-password"]').clear();
		cy.get('[data-test="auth-login"]').click();
		cy.get('[data-test="error-text"]').should('contain', 'Incorrect password');
	});

	it('log in with name', ()=>
	{
		cy.login('admin', 'adminpass');
		cy.url().should('include', '/settings');
		cy.logout();
	});

	it('log in with email', ()=>
	{
		cy.login('axel0726@gmail.com', 'adminpass');
		cy.wait(3000);
		cy.url().should('include', '/settings');
		cy.logout();
	});

	it('forgotPsw', ()=>
	{
		cy.get('[data-test="btn-transpar"]').click();

		cy.resetPassword('admin', 'Invalid email format');
		cy.resetPassword('admin@email', 'Invalid email format');
		cy.resetPassword('admin@email.', 'Invalid email format');
		cy.resetPassword('test@test.test', 'Incorrect email');

		cy.get('[data-test="btn-transpar"]').click();
		cy.get('[data-test="input-login"]');
		cy.get('[data-test="btn-transpar"]').click();
		cy.resetPassword('axel0726@gmail.com', 'New password was sended to your email.');
	});
});