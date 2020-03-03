describe('is', ()=>
{
	before(()=>{cy.resetDB()});

	it('primary-field', ()=>
	{
		// go to table page
		cy.login('admin', 'adminpass');
		cy.wait(3000);
		cy.contains('Block Type').closest('a').click();
		cy.url().should('include', '/table/block_type/tview/16/page/1/');

		// add contains filter
		cy.contains('Filter').closest('li').click();
		cy.contains('Add filter').closest('button').click();
		cy.get('.select[data-test="filter-column"]:nth-child(2)').click();
		cy.contains(/^Is$/).closest('li').click();
		cy.get('[data-test="filter-value"] input').type('1');
		cy.wait(3000);
		cy.get('[data-test="table-row"]').should('have.length', 1).should('be.visible');
		cy.get('[data-test="filter-delete-icon"]').click();
		cy.wait(3000);
		cy.get('[data-test="table-row"]').should('have.length', 5).should('be.visible');
	});
});