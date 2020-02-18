describe('is-empty', ()=>
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
		cy.get('.select.filters-popup__select:nth-child(2)').click();
		cy.contains(/^Is Empty$/).closest('li').click();
		cy.wait(3000);
		cy.get('.table-row').should('have.length', 2).should('be.visible');
		cy.get('.filters-popup__delete-row-icon').click();
		cy.wait(3000);
		cy.get('.table-row').should('have.length', 6).should('be.visible');
	});
});