describe( 'Run login', () => {
	beforeEach( () => {
		cy.visit("/wp-login.php");
		cy.wait( 1000 );
		cy.get( '#user_login' ).type( Cypress.env( "wpUser" ) );
		cy.get( '#user_pass' ).type( Cypress.env( "wpPassword" ) );
		cy.get( '#wp-submit' ).click();
	} );
	
	it( 'should take us to admin page', () => {
		cy.wait( 2000 );
		cy.url().should('eq', 'http://localhost:8888/wp-admin/');
	} );
} )
