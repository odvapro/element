describe('authTest', ()=>
{

	function logIn(login, psw)
	{
		cy.get('.auth-form-input__login').clear().type(login);
		cy.get('.auth-form-input__password').clear().type(psw);
		cy.get('.auth-fill-btn.el-btn').click();
	};
	function logOut()
	{
		cy.get('.sidebar__user-logout').invoke('show').click();
	};
	function resetPswWithEmail(email, contain)
	{
		cy.get('.auth-form-input__forgot-password').clear().type(email);
		cy.get('.auth-fill-btn.el-btn').click();
		cy.contains(contain);
	};

	it('visit a page', () =>
	{
		cy.visit('localhost:8080/element');
	});

	it('empty data', ()=>
	{
		cy.get('.auth-fill-btn.el-btn').click();
		cy.get('.auth-form-input__login').should('have.class', 'el-inp--error');
		cy.get('.auth-form-input__password').should('have.class', 'el-inp--error');
		cy.contains('Incorrect login');
		cy.contains('Incorrect password');
	});

	it('incorrect data', ()=>
	{
		logIn('incorrectLogin', 'incorrectPassword');
		cy.get('.auth-fill-btn.el-btn').click();
		cy.get('.auth__error-text').should('contain', 'Incorrect login or password');
	});

	it('incorrect one of inputs', ()=>
	{
		logIn('incorrectLogin', 'adminpass');
		cy.get('.auth__error-text').should('contain', 'Incorrect login or password');

		logIn('admin', 'incorrectPassword');
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
		logIn('admin', 'adminpass');
		cy.url().should('include', '/settings');
		logOut();
	});

	it('log in with email', ()=>
	{
		logIn('axel0726@gmail.com', 'adminpass');
		cy.wait(3000);
		cy.url().should('include', '/settings');
		logOut();
	});

	it('forgotPsw', ()=>
	{
		// TODO need cleanup
		return true;
		cy.get('.auth-transpar-btn').click();

		resetPswWithEmail('admin', 'Invalid email format');
		resetPswWithEmail('admin@email', 'Invalid email format');
		resetPswWithEmail('admin@email.', 'Invalid email format');
		resetPswWithEmail('test@test.test', 'Incorrect email');

		cy.get('.auth-transpar-btn').click();
		cy.get('.auth-form-input__login');
		cy.get('.auth-transpar-btn').click();
		resetPswWithEmail('axel0726@gmail.com', 'New password was sended to your email.');
	});
});