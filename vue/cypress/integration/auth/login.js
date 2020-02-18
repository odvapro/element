describe('authTest', ()=>
{
	before(()=>{cy.resetDB()});

	it('empty data', ()=>
	{
		cy.visit('/');
		cy.get('.auth-fill-btn.el-btn').click();
		cy.get('.auth-form-input__login').should('have.class', 'el-inp--error');
		cy.get('.auth-form-input__password').should('have.class', 'el-inp--error');
		cy.contains('Incorrect login');
		cy.contains('Incorrect password');
	});

	it('incorrect data', ()=>
	{
		cy.login('incorrectLogin', 'incorrectPassword');
		cy.get('.auth-fill-btn.el-btn').click();
		cy.get('.auth__error-text').should('contain', 'Incorrect login or password');
	});

	it('incorrect one of inputs', ()=>
	{
		cy.login('incorrectLogin', 'adminpass');
		cy.get('.auth__error-text').should('contain', 'Incorrect login or password');

		cy.login('admin', 'incorrectPassword');
		cy.get('.auth__error-text').should('contain', 'Incorrect login or password');

		cy.get('.auth-form-input__login').clear();
		cy.get('.auth-form-input__password').clear().type('incorrectPassword');
		cy.get('.auth-fill-btn.el-btn').click();
		cy.get('.auth__error-text').should('contain', 'Incorrect login');

		cy.get('.auth-form-input__login').clear().type('admin');
		cy.get('.auth-form-input__password').clear();
		cy.get('.auth-fill-btn.el-btn').click();
		cy.get('.auth__error-text').should('contain', 'Incorrect password');
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
		cy.get('.auth-transpar-btn').click();

		cy.resetPassword('admin', 'Invalid email format');
		cy.resetPassword('admin@email', 'Invalid email format');
		cy.resetPassword('admin@email.', 'Invalid email format');
		cy.resetPassword('test@test.test', 'Incorrect email');

		cy.get('.auth-transpar-btn').click();
		cy.get('.auth-form-input__login');
		cy.get('.auth-transpar-btn').click();
		cy.resetPassword('axel0726@gmail.com', 'New password was sended to your email.');
	});
});