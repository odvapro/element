describe('create', () =>
{
	before(()=>cy.resetDB());
	it('new-group', ()=>
	{
		cy.login('admin', 'adminpass');
		cy.contains('Groups').click();
		cy.wait(1000);
		cy.contains('Create Group').closest('button').click();
		cy.wait(1000);
		cy.get('.settings-groups__group-wrapper').should('have.length', 3);
	});

	it('rename-group', ()=>
	{
		cy.get('.settings-groups__group-wrapper:last-child .settings-groups__group-name').clear().type('New Group Name');
		cy.get('.settings-groups__group-wrapper:last-child .settings-groups__group-name').should('contain', 'New Group Name');
	});

	it('add-member', ()=>
	{
		cy.get('.settings-groups__group-wrapper:last-child .settings-groups__add-member-btn').click();
		cy.wait(1000);
		cy.get('.select-popup__item-line:nth-child(3)').click();
		cy.get('.settings-groups__group-wrapper:last-child .settings-groups__member').should('have.length', 1);


		cy.get('.settings-groups__group-wrapper:last-child .settings-groups__add-member-btn').click();
		cy.wait(1000);
		cy.get('.select-popup__item-line:nth-child(4)').click();
		cy.get('.settings-groups__group-wrapper:last-child .settings-groups__member').should('have.length', 2);
	});
	it('remove-member', ()=>
	{
		cy.get('.settings-groups__group-wrapper:last-child .settings-groups__member:first-child .settings-groups__remove-btn').click();
		cy.wait(1000);
		cy.get('.settings-groups__group-wrapper:last-child').find('.settings-groups__member').should('have.length', 1);
	});
	it('delete-group', ()=>
	{
		cy.get('.settings-groups__group-wrapper:last-child .settings-groups__group-head .settings-groups__remove-btn').click();
		cy.wait(1000);
		cy.get('.settings-groups__group-wrapper').should('have.length', '2');
	});
});
