Cypress.Commands.add("login", (login, password) =>
{
	cy.visit('/');
	cy.get('.auth-form-input__login').clear().type(login);
	cy.get('.auth-form-input__password').clear().type(password);
	cy.get('.auth-fill-btn.el-btn').click();
});

Cypress.Commands.add("logout", () =>
{
	cy.get('.sidebar__user-logout').invoke('show').click();
});

Cypress.Commands.add("resetPassword", (email, contain) =>
{
	cy.get('.auth-form-input__forgot-password').clear().type(email);
	cy.get('.auth-fill-btn.el-btn').click();
	cy.contains(contain);
});

Cypress.Commands.add("resetDB", (email, contain) =>
{
	cy.request(Cypress.env('apiUrl')+'dev.php?task=resetdb');
});

